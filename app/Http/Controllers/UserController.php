<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Auth;
Use DB;
use Hash;

class UserController extends Controller
{
    public $errorHead = null;

    public function __construct()
    {
        $this->errorHead = config('settings.controller_code.TruckController');
    }

    public function profileEdit()
    {
        return view('users.profile.edit');
    }

    /**
     * action for user profile update
     */
    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $errorCode = 0;
        
        if(!Hash::check($request->get('currentPassword'), Auth::User()->password)) {
            return redirect()->back()->with("message", "Authentication Failed! Invalid password.")->with("alert-class", "error");
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $user = Auth::User();
            if(!empty($request->get('name'))) {
                $user->name     = $request->get('name');
            }
            if(!empty($request->get('email'))) {
                $user->email    = $request->get('email');
            }
            if(!empty($request->get('password'))) {
                $user->password = Hash::make($request->get('password'));
            }
            $user->save();

            DB::commit();

            return redirect(route('user.profile.edit'))->with("message", "Profile Successfully Updated!")->with("alert-class", "success");
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            $errorCode = (($e->getMessage() == "CustomError") ? $e->getCode() : 1);
        }

        return redirect()->back()->with("message", "Profile Update failed!#". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
