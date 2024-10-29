<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use App\Models\District;
use App\Models\Division;
use App\Models\Region;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Upazilla;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreaApiController extends Controller
{

    public function get_districts()
    {
        $data['districts'] = District::orderBy('id', 'DESC')->get()->pluck('name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }

    public function get_regions()
    {
        $data['regions'] = Region::orderBy('id', 'DESC')->get()->pluck('name', 'id');


        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }


    public function get_upazillas()
    {
        $data['upazillas'] = Upazilla::orderBy('id', 'DESC')->get()->pluck('name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }


    public function get_unions()
    {
        $data['unions'] = Union::orderBy('id', 'DESC')->get()->pluck('name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }


    public function get_thanas()
    {
        $data['areas'] = Thana::orderBy('id', 'DESC')->get()->pluck('name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }

    public function get_areas()
    {
        $data['areas'] = Area::orderBy('id', 'DESC')->get()->pluck('name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }

    public function get_divisions()
    {
        $data['divisions'] = Division::orderBy('id', 'DESC')->get()->pluck('name', 'id');

        $data['status'] = 'success';
        header('Content-Type: application/json');

        return response($data);
    }


}
