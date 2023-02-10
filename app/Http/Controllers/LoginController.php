<?php

namespace App\Http\Controllers;

use App\Mng_User;;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ldap_connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
     if (env('APP_ENV') != 'local')
     {
        $ldap = new ldap_connection();
        $userlogin = str_contains($request->email, '@');
            if (!$userlogin) {
                $userlogin = $request->email . '@emp-one.com';
                $mailUser = $request->email;
            } else {
                $userlogin = $request->email;
                $mailUser = str_replace(array('@emp-one.com','@emp.id'), '', $request->email);
            }
            $filter = "(|(mail=" . $userlogin . ")(userprincipalname=" . $userlogin . ")(samaccountname=" . $request->email . "))";
            $check = $ldap->search($filter, $userlogin, $request->password);
            if (!empty($check)){
              $checkUser = Mng_User::where('usr_email', $mailUser)->first();
               if(empty($checkUser)){
                $createUser = Mng_user::createUser($check['streetaddress'],$check['division'],$request->password,$check['company'],$check['physicaldeliveryofficename'],$check['samaccountname'],$check['displayname'],$mailUser);
                $dataUser = Mng_user::where('usr_id',$createUser->usr_id)->first();
                $token = $dataUser->createToken('ApiToken')->plainTextToken;
                return response([
                    "success" => true, 
                    "message" => "You have logged in successfully",
                    "token"=>$token,
                    "usr_name"  => ucwords(strtolower($dataUser->usr_fullname)),
                    "usr_loc"=>$dataUser->usr_loc],200);
               } else { // if exists
                $token = $checkUser->createToken('ApiToken')->plainTextToken;
                return response([
                    "success" => true, 
                    "message" => "You have logged in successfully",
                    "token"=>$token,
                    "usr_name"  => ucwords(strtolower($checkUser->usr_fullname)),
                    "usr_loc"=>$checkUser->usr_loc],200);
               } 
            } else {
                return response("Can't login. Please check your username and password",422);
            }
        } else { // if local
            $user= Mng_User::where('usr_email', $request->email)->first();              
            if (!is_null ($user)) {
                if(Hash::check($request->password, $user->usr_passwd)) {
                $token = $user->createToken('ApiToken')->plainTextToken;
                    $username = ucwords(strtolower($user->usr_fullname));
                        $response = [
                            'success'   => true,
                            'token'     => $token,
                            'usr_name'  => $username,
                            'usr_loc'   => $user->usr_loc
                        ];
                        return json_encode($response, 400);
                    }else{
                        return response("Can't login. Please check your password",422);
                        }
                    }else{
                        return response("Username not registered.",422);
                    }
        }
    }
                           
    public function loginFromEmail(Request $request)
    {
        $user= Mng_User::where('usr_id',$request->usr_id)->first();
        
        $token = $user->createToken('ApiToken')->plainTextToken;
            $response = [
                'success'   => true,
                'token'     => $token,
                'usr_name'  => $user->usr_fullname,
                'usr_loc'   => $user->usr_loc
            ];
        return json_encode($response, 200);
    }
    public function logout()
    {
        $user = Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
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