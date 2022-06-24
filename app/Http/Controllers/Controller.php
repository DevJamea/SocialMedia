<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function register(Request $request)
    {

         $validated = validator::make($request->all() , [
             'name' => 'required|min:5',
             'email' => 'required|email|unique:users',
             'password' => 'required|min:6',
         ] , [
             'name' => 'الأسم',
             'email' => 'الإيميل',
             'password' => 'كلمة المرور',
         ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {

        $validated = validator::make($request->all() , [
            'email' => 'required',
            'password' => 'required|min:6',
        ] , [
            'email' => 'الإيميل',
            'password' => 'كلمة المرور',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $user = User::where('email' , $request->email)->first();
        if(!$user){
            return response()->json(['message'=> 'عذرا هذا الايميل غير موجود'],401);
        }

        if (Hash::check($request->password , $user->password)){
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response( $response , 200);
        }else{
            return response()->json(['message' => 'كلمة السر خاطئة'] , 422);
        }
    }

}
