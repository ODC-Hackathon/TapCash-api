<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFamilyMember;
use App\Models\FamilyMember;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    //


    public function get_users(Request $request)
    {
        $users = User::select('user_name')->get();
        return $this->success($users);
    }

    public function storeFamilyMember(CreateFamilyMember $request)
    {

        try
        {
            FamilyMember::create([
                'sponsor_id'=>User::where('user_name',$request->sponser)->first()->id,
                'user_name' => $request->user_name,
                'phone_number' => $request->phone_number,
                'percentage' => $request->percentage,
                'pincode' => $request->pincode,
                'name'=> $request->name,
            ]);
            return $this->success(null);
        }catch(Exception $e)
        {
            return $this->error($e->getMessage());
        }



    }
}
