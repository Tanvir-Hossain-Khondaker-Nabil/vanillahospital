<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/8/2019
 * Time: 3:15 PM
 */

namespace App\Http\Traits;


use Illuminate\Support\Facades\Auth;

trait CompanyInfoTrait
{

    public function company($data=[]){

        if(Auth::user()->company_id != null)
        {
            $data['company_logo']= !empty(Auth::user()->company->logo) ? Auth::user()->company->company_logo_path : "";
            $data['company_name']= Auth::user()->company->company_name;
            $data['company_address']= Auth::user()->company->address;
            $data['company_city']= Auth::user()->company->city;
            $data['company_country']= Auth::user()->company->country->countryName;
            $data['company_phone']= Auth::user()->company->phone;
            $data['company_email']= Auth::user()->company->email;
            $data['show_room_status']= Auth::user()->company->show_room_status;

            $data['report_head_sub_text']= Auth::user()->company->report_head_sub_text;
            $data['report_head_image']= !empty(Auth::user()->company->report_head_image) ? Auth::user()->company->report_head_image_path : "";

        }else{
            $data['company_logo']= !empty(config('company.logo')) ? human_words(config('company.logo')) : "";
            $data['company_name'] = human_words(config('company.name'));
            $data['company_address']= human_words(config('company.address'));
            $data['company_city']= human_words(config('company.city'));
            $data['company_country']= human_words(config('company.country'));
            $data['company_phone']= human_words(config('company.phone'));
            $data['company_email']= human_words(config('company.email'));
            $data['show_room_status']= Auth::user()->company->show_room_status;
        }

        return $data;
    }

    public function checkCompanySet()
    {
        if( !isset(Auth::user()->company_id) && Auth::user()->company_id == null)
        {
            return 0;
        }else{
            return 1;
        }
    }
}
