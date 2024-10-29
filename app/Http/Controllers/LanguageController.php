<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function setLang($lang)
    {
        if (! in_array($lang, ['en', 'bn','pt'])) {
            abort(400);
        }

        Session::put('locale',$lang);

        return redirect()->back();
    }
}
