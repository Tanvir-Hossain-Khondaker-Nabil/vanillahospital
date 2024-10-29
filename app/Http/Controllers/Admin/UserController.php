<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create()
    {
        $data['memberships'] = Membership::where('status', 'active')->pluck('display_text','id');

        return view('users.create');
    }

    public function create_support_pin()
    {

    }

    public function support_pin_generate(Request $request)
    {
        $user_id = $request->user_id;

        if(!empty($user_id))
        {
            $user = User::find($user_id);
            $user->support_pin = generate_pin();
            $user->save();
        }
        
        // if($user_id == "all" ){
            $users = User::select('id', 'full_name')->get();

            foreach($users as $user)
            {
                $user->support_pin = $pin = generate_pin();

                $user->photo = $pin;
                $user->save();
            }
        // }else{

        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modal = User::findOrFail($id);
        $modal->delete();

        return response()->json([
            'data' => [
                'message' => 'Successfully deleted'
            ]
        ], 200);
    }

}
