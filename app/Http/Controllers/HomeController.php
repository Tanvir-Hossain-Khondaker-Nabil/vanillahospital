<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasRole(['admin','super-admin','developer','master-member']))
        {
            return redirect('/admin/dashboard');
        }else{
            return redirect('/member/dashboard');
        }
//        return view('home');
    }


    public function left_sidebar_toggle()
    {

        if(Session::get('left_sidebar')=='on')
            Session::put('left_sidebar', 'off');
        else
            Session::put('left_sidebar', 'on');
    }

    public function menu_change($id)
    {
        Session::put('sidebar_menu', $id);

        return redirect()->route('admin.dashboard');
    }


}
