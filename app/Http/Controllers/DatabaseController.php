<?php

namespace App\Http\Controllers;

use App\Http\Services\PaymentMethodIntegrate;
use App\Http\Traits\SmsTrait;
use App\Mail\SystemCreatedAdminMail;
use App\Mail\SystemRegisterConfirm;
use App\Models\Setting;
use App\Models\User;
use App\Models\Company;
use App\Services\CompanyFeature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant;
use xmlapi;
use WebReinvent\CPanel\CPanel;

class DatabaseController extends Controller
{
    use SmsTrait;

    private $adminEmail = "wwwrcreationbd@gmail.com";
    private $adminPhone = "8801813316786";

    public function  create()
    {
        $features = new CompanyFeature();
        $data['features'] = $features->options();

        return view('user-db.create_db', $data);
    }

    public function  show($id)
    {
        return view('user-db.domain-info');
    }

    public function  checkDomain(Request $request)
    {
        $name = strtolower(removeSpecialChar($request->name));

        $item = DB::connection("landlord")->table('tenants')
                ->where('database',$name)->first();


        if(!$item)
        {
            $data['status'] = 'success';
            $data['domain'] = $name;
        } else{
            $data['status'] = 'failure';
//            $data['domain'] = $name;
        }

        header('Content-Type: application/json');

        echo json_encode($data);
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'full_name' => 'required',
            'phone' => 'required',
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        $cpanel_domain = config('cpanel.CPANEL_DOMAIN');
        $cpanel_api_token = config('cpanel.CPANEL_API_TOKEN');
        $cpanel_username =config('cpanel.CPANEL_USERNAME');
        $port = config('cpanel.CPANEL_PORT');
        $protocol='https';

        $name = strtolower(removeSpecialChar($request->name));
        $hasDomain = DB::connection("landlord")->table('tenants')
                        ->where('database', $cpanel_username."_".$name)
                        ->first();

        
        $cpanel = new CPanel($cpanel_domain, $cpanel_api_token, $cpanel_username, $protocol, $port);
        $response = $cpanel->checkDatabase($cpanel_username."_".$name);
        
        $db_name = $name;
        if($hasDomain || $response['status'] == "success")
        {
            $db_name = $name.rand(1,100);
        }


        if(!config('cpanel.CPANEL_USE'))
        {
            $appUrl = "localhost/hisabe";
        }else{

            $appUrl = "hisebi.rcreation-bd.com";
        }

        $database_user = config('database.connections.landlord.username');
        $database_password = config('database.connections.landlord.password');

        $data = [];
        $data['database'] = $database_name  = $cpanel_username.'_'.$db_name;
        $data['domain'] = $domain = $db_name.".".$appUrl;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = $request->password ?? Str::random(6);
        $data['phone'] = $request->phone;
        $data['full_name'] = $request->full_name;
        $data['company_type_id'] = $request->company_type_id;
        $data['num_of_emp'] = $request->num_of_emp;
        $data['features'] = json_encode($request->features);
        $data['created_at'] = Carbon::today();

        $id = DB::connection("landlord")->table('tenants')->insertGetId($data);


        if(!config('cpanel.CPANEL_USE'))
        {
             $cot = "CHARACTER SET utf8 COLLATE utf8_general_ci";

             DB::connection('mysql')->select('CREATE DATABASE '. $database_name .' '.$cot);
         }else{

            $response = $cpanel->createDatabase($database_name);

//          $response = $cpanel->createDatabaseUser($username, $password);
            $response = $cpanel->setAllPrivilegesOnDatabase($database_user, $database_name);
         }



        $data['id'] = $id;
        Session::put($data);

        Session::flash('type', 'success');
        Session::flash('message', 'Your System Create Successfully');

        return redirect()->route('system.show', $id);
    }


    private function xmlBasedSave($opts)
    {
        $xmlapi = new xmlapi("your cpanel domain");
        $xmlapi->set_port( 2083 );
        $xmlapi->password_auth($opts['user'], $opts['pass']);
        $xmlapi->set_debug(0);//output actions in the error log 1 for true and 0 false

        $cpaneluser=$opts['user'];
        $databasename="something";
        $databaseuser="else";
        $databasepass=$opts['pass'];

        //create database
        $createdb = $xmlapi->api1_query($cpaneluser, "Mysql", "adddb", array($databasename));

        //create user
        $usr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduser", array($databaseuser, $databasepass));

        //add user
        $addusr = $xmlapi->api1_query($cpaneluser, "Mysql", "adduserdb", array("".$cpaneluser."_".$databasename."", "".$cpaneluser."_".$databaseuser."", 'all'));

    }


    public function index($id){

        // define('STDIN',fopen("php://stdin","r"));
        Artisan::call('migrate', array('--force' => true));
        Artisan::call('db:seed', array('--force' => true));

        $tenants = DB::connection("landlord")->table('tenants')->find($id);


        $data = [];
        $data['database'] = $tenants->database;
        $data['domain'] = $tenants->domain;
        $data['name']  = $tenants->name;
        $data['id']  = $tenants->id;
        $data['email'] = $email = $tenants->email;
        $data['phone'] = $phone_number  = $tenants->phone;
        $data['full_name'] = $full_name  = $tenants->full_name;
        $data['password'] = $pass = $tenants->password;
        $data['company_type_id'] = $tenants->company_type_id;
        $data['num_of_emp'] = $tenants->num_of_emp;
        Session::put($data);


        User::find(1)->update(['email'=>$tenants->email, 'password'=>Hash::make($tenants->password)]);
        Company::find(1)->update(['company_name'=>$tenants->name, 'phone'=>$phone_number, 'email'=>$email, 'quote_ref' => get_quote_ref($tenants->name)]);

        $features = json_decode($tenants->features);

        foreach ($features as $value)
        {
            $data2 = [];
            $data2['company_id'] = 1;
            $data2['key'] = $value;
            $data2['value'] = true;

            Setting::create($data2);

            if($value == "pos")
            {
                $posPayment = new PaymentMethodIntegrate();
                $posPayment->posPaymentMethodSave();
            }
        }

//        DB::connection("landlord")->table('tenants')->where('id', $id)
//            ->update(['password'=>Hash::make($tenants->password)]);

        if(config('cpanel.CPANEL_USE')) {
            Mail::to($tenants->email)->send(new SystemRegisterConfirm($data));
            Mail::to($this->adminEmail)->send(new SystemCreatedAdminMail($data));
        }



        $msg = "Thanks for your registration in Hisebi. \n\n Login Url: ". route('login')."\n  Email: ".$email."\n password: ".$pass."\n Helpline: +8801813-316786";

        $adminMsg = "Hisebi New Account. \n\n Login Url: ". route('login')."\n  Email: ".$email."\n password: ".$pass;

        if(!empty($data['phone']))
            if(config('cpanel.CPANEL_USE')) { $this->sendSMS($phone_number, $msg); }
        else
            $phone_number = $email;

        if(config('cpanel.CPANEL_USE')) {
            $this->sendAdminSMS($tenants->full_name, $phone_number);
            $this->sendSMS($this->adminPhone, $adminMsg);
        }

        Session::flash('db', 'yes');
        Session::flash('type', 'success');
        Session::flash('message', 'Process Complete Successfully !! ');


        return redirect()->route('system.show', $id);
    }
}
