<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Mng_User;
use App\Model\Mng_roles;
use App\Model\Mng_usr_roles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Appstract\Opcache\OpcacheFacade as OPcache;
use Adldap\Laravel\Facades\Adldap;

class LoginController extends Controller
{
    function __construct(){
        OPcache::clear();
    }
    public function index(Request $request)
    {
        // $email = $request->email."@emp.id";
        //     if (Adldap::auth()->attempt($email,$request->password)) {
        //         $user = Mng_User::where('usr_email', $request->email)->first();
        //           if (!is_null ($user)) {
        //             $token = $user->createToken('ApiToken')->plainTextToken;
        //             $authuser = Auth::user();
        //             $id = $user->usr_id;
        //             $usr_name = $user->usr_name;
        //              return response([
        //                     "success" => true, 
        //                         "message" => "You have logged in successfully",
        //                         "token"=>$token,
        //                         "usr_loc"=>$user->usr_loc],200);
        //              }else{
        //                     return response(["success" => false, "email" => "Username not registered"],422);
        //                   }
        //                 }else{
        //                     $email2 = $request->email."@emp-one.com";
        //                     if (Adldap::auth()->attempt($email2,$request->password)) {
        //                         $user = Mng_User::where('usr_email', $request->email)->first();
        //                         if (!is_null ($user)) {
        //                             $token = $user->createToken('ApiToken')->plainTextToken;
        //                             $authuser = Auth::user();
        //                             $id = $user->usr_id;
        //                             $usr_name = $user->usr_name;
        //                         return response([
        //                                 "success" => true, 
                                            // "message" => "You have logged in successfully",
                                            // "token"=>$token,
        //                                  "usr_loc"=>$user->usr_loc],200);
        //                         }else{
        //                             return response(["success" => false, "email" => "Username not registered"],422);
        //                             }
        //                 }else{
        //                         return response(["success" => false, "password" => "Can't login. Please check your password."],404);
        //                     }
        //                 }
    

        $user= Mng_User::where('usr_email', $request->email)->first();
        
        if (!is_null ($user)) {
            if(Hash::check($request->password, $user->usr_passwd)) {
                $token = $user->createToken('ApiToken')->plainTextToken;
                $id = $user->usr_id;
                Session::put('id', $user->usr_id);
                $roleid = Mng_usr_roles::select('rol_id')->where('usr_id',$id)->pluck('rol_id');
                $role = Mng_roles::select('rol_name')->whereIn('rol_id',$roleid)->pluck('rol_name');
                    $response = [
                        'success'   => true,
                        'token'     => $token,
                        'usr_loc'   => $user->usr_loc

                    ];
                    return json_encode($response, 200);
                }else{
                    return response(["password" => "Can't login. Please check your password"],422);
                    }
                 }else{
                    return response(["email" => "Username not registered."],422);
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
                'usr_loc'   => $user->usr_loc,
                'usr_name'  => $user->usr_name
            ];
        return json_encode($response, 200);
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
    public function show()
    {
        $user = Auth::user();
        return json_encode($user);
    }
}