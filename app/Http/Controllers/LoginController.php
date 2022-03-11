<?php

namespace App\Http\Controllers;

use Session;
use App\Mng_User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request){

        $user= Mng_User::where('usr_email', $request->email)->first();
        
        if (!is_null ($user)) {
            if(Hash::check($request->password, $user->usr_passwd)) {
                $token = $user->createToken('ApiToken')->plainTextToken;
                $id = $user->usr_id;
                    $response = [
                        'success'   => true,
                        'user'      => $user,
                        'token'     => $token,
                        'id'        => $id,
                        'usr_name'  => $user->usr_name
                    ];
                    return json_encode($response, 201);
                }else{
                    return response(["password" => "Unable to login. Incorrect password."],422);
                    }
                 }else{
                    return response(["email" => "Email doesnt exist."],422);
                }
    } 

    public function loginFromEmail(Request $request)
    {
        $user= Mng_User::where('usr_id',$request->usr_id)->first();
        
        $token = $user->createToken('ApiToken')->plainTextToken;
        $id = $user->usr_id;
            $response = [
                'success'   => true,
                'user'      => $user,
                'token'     => $token,
                'id'        => $id,
                'usr_name'  => $user->usr_name
            ];
        return json_encode($response, 201);
    }
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return json_encode([
            'success'    => true,
            'message'    => $user,
        ], 200);
    }
}