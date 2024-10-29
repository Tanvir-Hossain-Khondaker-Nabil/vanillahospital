<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index(){
        $items = Item::get();
        $categories = Category::get();
        return view('frontend.modules.index',compact('items','categories'));
    }

    public function category(){
        return view('frontend.modules.category');
    }

    public function product($id){
        $item = Item::where('id', $id)->first();
        return view('frontend.modules.product',compact('item'));
    }
}
