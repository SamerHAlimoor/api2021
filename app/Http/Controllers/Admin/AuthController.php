<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    use GeneralTrait;

    public function login(Request $request)
    {
//validator
        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                  $code = $this->returnCodeAccordingToInput($validator);
                   $validatorg= $validator->errors()->first();
                   return  $this->returnValidationError($code, $validatorg);
            }

           // Login
           $credentials = $request->only(['email', 'password']);
           //admin-api in auth that called guard
           $token = Auth::guard('admin-api')->attempt($credentials);
           // ازا كانت صحيحة برجعلك توكن كبير ازا كان غلط ما برجعلك شي
           if (!$token)
           return $this->returnError('E001', 'بيانات الدخول غير صحيحة');

           $admin = Auth::guard('admin-api')->user();
           $admin->token = $token;
           //return token
           return $this->returnData('admin', $admin);


        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

    public function logout(Request $request)
    {
         $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        }else{
            $this -> returnError('','some thing went wrongs');
        }

    }
}
