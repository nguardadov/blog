<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Validator;
use App\User;
class TokensController extends Controller
{
    public function login(Request $request)
    {
         $credential = $request->only('email','password');

         //validando que los campos vengan llenos
         $validator = Validator::make($credential,[
             'email'=>'required|email',
             'password'=>'required'
         ]);

         if($validator->fails())
         {
             return response()->json([
                 'success'=> false,
                 'message'=> 'wrong validation',
                 'errors' => $validator->errors()
             ],422); //es el status
         }

         $token = JWTAuth::attempt($credential);

        if($token){
            return response()->json([
                 'success'=> true,
                 'token'=> $token,
                 'user' => User::where('email',$credential["email"])->get()->first()
             ],200); //es el status
        }else{
             return response()->json([
                 'success'=> false,
                 'message'=> 'wrong credential',
                 'errors' => $validator->errors()
             ],401); //es el status
        }
    }

    public function refreshToken(){
        $token = JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);
             return response()->json([
                 'success'=> true,
                 'token'=> $token
             ],200); //es el status
        }
        catch(TokenExpiredException $ex){
             return response()->json([
                 'success'=> false,
                 'message'=> 'Need to login again! (expired)',
             ],422); //es el status
        }
        catch(TokenBlacklistedException $ex){
             return response()->json([
                 'success'=> false,
                 'message'=> 'Need to login again! (black listed)',
             ],422); //es el status
        }
    }

    public function logout(){
         $token = JWTAuth::getToken();

         try{
             $token = JWTAuth::invalidate($token);
             return response()->json([
                 'success'=> true,
                 'message'=> 'Logout successfull'

             ],200);
         }catch(JWTException $ex){
              return response()->json([
                 'success'=> false,
                 'message'=> 'Failed Logout, please try again'

             ],422);
         }
    }
}
