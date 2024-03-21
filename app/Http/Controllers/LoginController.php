<?php

namespace App\Http\Controllers;

use App\Models\Mng_user;
use App\Models\Mng_usr_roles;
use App\Models\Mng_role_menu;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ldap_connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function example(Request $request)
    {
        return view('app');
    }
    function index(Request $request)
    {
     if (env('APP_ENV') != 'local'){ //use ldap
        $ldap = new ldap_connection();
        $userlogin = str_contains($request->email, '@');
        if (!$userlogin) {
            $userlogin = $request->email . '@emp-one.com';
        } else if (str_contains($request->email, '@emp.id')){
            $userlogin = str_replace('@emp.id', '', $request->email) . '@emp-one.com';
        } else{
            $userlogin = $request->email;
        }
        $filter = "(|(mail=" . $userlogin . ")(userprincipalname=" . $userlogin . ")(samaccountname=" . $request->email . "))";
        $check = $ldap->search($filter, $userlogin, $request->password);
            
            if (!empty($check)){ //* if check AD Valid
              $checkUser = Mng_User::where('usr_domain', $userlogin)->first();
               if(empty($checkUser)){
                    $createUser = Mng_user::createUser($check);
                    $dataUser = Mng_user::find($createUser->usr_id);
                    $token = $dataUser->createToken('ApiToken')->plainTextToken;

               } else { // if exists    
                    Mng_user::updateUser($check);
                    $dataUser = Mng_user::find($checkUser->usr_id);
                    $token = $checkUser->createToken('ApiToken')->plainTextToken;
               }
                return response([
                    "success" => true,
                    "message" => "You have logged in successfully",
                    "token"=> $token,
                    "usr_name"  => ucwords(strtolower($dataUser->usr_fullname)),
                    "usr_loc"=> $dataUser->usr_loc],200);
            } else {
                return response()->json(['message' => 'Periksa Kembali Akun Anda!!', 'state' => 'failed'], 401);
            }
     } else { // if local
        $userlogin = str_contains($request->email, '@');
        if (!$userlogin) {
            $userlogin = $request->email . '@emp-one.com';
        } else if (str_contains($request->email, '@emp.id')){
            $userlogin = str_replace('@emp.id', '', $request->email) . '@emp-one.com';
        } else{
            $userlogin = $request->email;
        }
            $user= Mng_User::where('usr_domain', $userlogin)->first();
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
                        return response()->json(['message' => 'Periksa Kembali Akun Anda!!', 'state' => 'failed'], 401);
                        }
                    }else{
                        return response()->json(['message' => 'Periksa Kembali Akun Anda!!', 'state' => 'failed'], 401);
                    }
     }
    }
    function loginFromIntranet(Request $request){
        $emailUser = str_replace('"','',$request->samaccountname);
        $cekUser = Mng_user::where('usr_email','like',$emailUser)->first();
        if(!empty($cekUser)){
            $token = $cekUser->createToken('ApiToken')->plainTextToken;
            $response = [
                'success'   => true,
                "message" => "You have logged in successfully",
                'token'     => $token,
                'usr_name'  => $cekUser->usr_fullname,
                'usr_loc'   => $cekUser->usr_loc
            ];
        return json_encode($response, 200);
        } else {
            $password = 'P@ssw0rd27';
            $createUser = Mng_user::createUser($request->streetaddress,$request->division,$password,$request->company,$request->physicaldeliveryofficename,$request->displayname,$request->samaccountname,$emailUser);
            $dataUser = Mng_user::where('usr_id',$createUser->usr_id)->first();
                $token = $dataUser->createToken('ApiToken')->plainTextToken;
                return response([
                    "success" => true, 
                    "message" => "You have logged in successfully",
                    "token"=> $token,
                    "usr_name"  => ucwords(strtolower($dataUser->usr_fullname)),
                    "usr_loc"=>$dataUser->usr_loc],200);
        }
    }
    function loginFromEmail(Request $request)
    {
        $user= Mng_User::where('usr_id',$request->usr_id)->first();
        
        $token = $user->createToken('ApiToken')->plainTextToken;
            $response = [
                'success'   => true,
                "message" => "You have logged in successfully",
                'token'     => $token,
                'usr_name'  => $user->usr_fullname,
                'usr_loc'   => $user->usr_loc
            ];
        return json_encode($response, 200);
    }
    function logout()
    {
        $user = Auth::user()->tokens()->where('id', Auth::user()->currentAccessToken()->id)->delete();
        return ResponseFormatter::success($user,'Successfully Logout');
    }

    function cekUser()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('menu_display as name','controller as to')->whereIn('menu_id',$menu)->get();
        return response()->json($aksesmenu);
    }
}