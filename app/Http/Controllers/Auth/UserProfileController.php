<?php

namespace App\Http\Controllers\Auth;

use App\Models\EmployeeProject;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\FileUploadTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    use FileUploadTrait;

    public function showProfile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {

        $inputs = $request->except('email');

        $user = User::find(auth()->user()->id);
        $user->update($inputs);

        $inputs['photo'] = '';

        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');

            $upload = $this->fileUpload($image, '/photo/', ['width' => 50, 'height' => 50]);

            if (!$upload) {
                $status = ['type' => 'danger', 'message' => 'Image Must be JPG, JPEG, PNG, GIF'];
                return back()->with('status', $status);
            }

            $user->photo = $upload;
            $user->save();
        }

        $status = ['type' => 'success', 'message' => 'Profile Updated Successfully.'];

        return back()->with('status', $status);
    }


    public function user_profile(Request $request)
    {
        $id = Auth::user()->id;
        $data['profile'] = $profile = User::where('id', $id)->with(['employee'])->first();
        $employee_id = $profile->employee->id;


        if (Auth::user()->hasRole(['user'])) {

            $data['tasks'] = Task::where('employee_info_id', $employee_id)
                ->with(['project'])
                ->orderBy('created_at', 'desc')->get();

        } elseif (Auth::user()->hasRole(['project_manager'])) {

            $data['tasks'] = EmployeeProject::where('employee_id', $employee_id)->with(['project'])->get();

        }

        return view('member.users.user_profile', $data);
    }
}
