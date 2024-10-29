<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 3/12/2019
 * Time: 2:55 PM
 */

namespace App\Http\Traits;


use App\Mail\UserRegisterVerify;
use Illuminate\Support\Facades\Mail;

trait VerifyUserTrait
{
    public function sendVerifyToken($token, $email, $userType='Member'){

        $data = [];
        $data['url'] = $url = route('verify.registration', $token);
        $data['app_name'] = $app_name = human_words(config('app.name'));
        $data['userType'] = $userType;
//        Mail::send('common.verifyTokenMail', ['url' => $url], function ($message) use($email, $userType, $app_name){
//
//            $message->to($email);
//            $message->subject( $app_name." ".$userType.' Registration Confirmation');
//            $message->from('info@rcreation.com', $app_name);
//        });

        Mail::to($email)->send( new UserRegisterVerify($data));


        return true;
    }
}
