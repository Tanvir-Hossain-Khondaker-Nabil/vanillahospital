<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    public function index(){

        return view('landing.pages.index');
    }


    public function contact(){

        return view('landing.pages.contact');
    }


    public function send_contact(Request $request)
    {

        $data = $request->all();
        $email = "hisebionline@gmail.com";

        $res = Mail::to($email)->send(new ContactMail($data));

        if ($res) {
            echo 'Your Message has been sent successfully!';
        }else{
            echo 'Something went wrong, Please Try Again.';
        }
    }


    public function pricing(){

        return view('landing.pages.pricing');
    }

    public function partner(){

        return view('landing.pages.partner');
    }

    public function feature(){

        return view('landing.pages.feature');
    }

}
