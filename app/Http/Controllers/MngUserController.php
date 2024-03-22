<?php

namespace App\Http\Controllers;

use Adldap\Models\User;
use App\Models\Mng_user;
use App\Models\Mng_roles;
use App\Models\Divisi_refs;
use App\Models\Mng_usr_roles;
use carbon\Carbon;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use App\Helpers\ldap_connection;

class MngUserController extends Controller
{
    protected $userMenu;
    protected $to;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/mng-user";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        })->only('index');
    }
    function index()
    {
        $user = DB::table('v_mng_users')->get();
        return response()->json($user);
    }
    
    function getLocation(){
        $loc = Location::select('loc_code as code','loc_desc as name')->orderBy('loc_desc','ASC')->get();
        return json_encode($loc);
    }
    function save(Request $request)
    {
        $message = [
            'usr_domain.required'=>'User Domain Belum Diisi',
            // 'usr_domain.unique'=>'User Domain Sudah Pernah Didaftarkan',
            'usr_status.required'=>'Status Belum Diisi',
            'usr_loc'=>'Lokasi Belum Diisi'
        ];
        $request->validate([
            'usr_domain'=>'required',
            'usr_status'=>'required',
            'usr_loc'=>'required'
        ],$message);

        $image = $request->image;
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $replace = substr($image, 0, strpos($image, ',')+1); 
        $fotoo = str_replace($replace, '', $image);
        $foto= str_replace(' ', '+', $fotoo); 
        $nama_file = time().".".$extension;
        Storage::disk('profile')->put($nama_file, base64_decode($foto));
        $ldap = new ldap_connection();
        $userDomain = str_contains($request->usr_domain, '@');
        if (!$userDomain) {
            $userDomain = $request->usr_domain . '@emp-one.com';
        } else if (str_contains($request->usr_domain, '@emp.id')){
            $userDomain = str_replace('@emp.id', '', $request->usr_domain) . '@emp-one.com';
        } else{
            $userDomain = $request->usr_domain;
        }
        $check = $ldap->findUser($userDomain);
        if($check){
            $user = Mng_user::create([
                'usr_id'=> generate_id_number(),
                'usr_name'=> str_replace('"', '', $check['displayname']),
                'usr_fullname'=> str_replace('"', '', $check['name']),
                'usr_alamat'=> str_replace('"', '', $check['streetaddress']),
                'usr_nm_perush'=> str_replace('"', '', $check['company']),
                'usr_stat'=> $request->usr_status,
                'usr_domain'=> $userDomain,
                'usr_department'=> str_replace('"', '', $check['department']),
                'usr_division'=> str_replace('"', '', $check['division']),
                'usr_email'=> str_replace('"', '', $check['mail']),
                'usr_nip'=> str_replace('"', '', $check['employeeid']),
                'usr_jabatan'=> str_replace('"', '', $check['extensionattribute15']),
                'usr_foto'=> $nama_file,
                'created_by'=> Auth::user()->usr_id,
                'creation_date'=> now(),
                'usr_loc'=>$request->usr_loc,
                'program_name'=>'MngUser_SAVE'
            ]);
            return ResponseFormatter::success($user,'Successfully Created User');
        }else {
            return response()->json(['message' => 'User Domain Not Found!!', 'state' => 'failed'], 401);
        }
    }
    function edit($code)
    {
        $user = Mng_User::find($code);
        $role = Mng_usr_roles::getRole($code);
        return response()->json(['user'=>$user,'role'=>$role],200);
    }
    function update(Request $request,$code)
    {
        $user = Mng_user::find($code);
        $message = [
            'usr_stat.required'=>'Status Belum Diisi',
            'usr_loc.required'=>'Location Belum Diisi',
        ];
        $request->validate([
            'usr_stat'=>'required',
            'usr_loc'=>'required',
        ],$message);
        if($request->image){
            unlink(Storage_path('app/public/profile/'.$user->usr_foto));
            $image= $request->image;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $fotoo = str_replace($replace, '', $image);
            $foto= str_replace(' ', '+', $fotoo); 
            $nama_file = time().".".$extension;
            Storage::disk('profile')->put($nama_file, base64_decode($foto));
        }else{
            $nama_file = $user->usr_foto;
        }
            $user->usr_stat = $request->usr_stat;
            $user->usr_foto = $nama_file;
            $user->usr_loc = $request->usr_loc;
            $user->last_update_date = now();
            $user->last_updated_by = Auth::user()->usr_id;
            $user->program_name = 'MngUserController_UPDATE';
            $user->save();
        return ResponseFormatter::success($user,'Successfully Updated User');
    }
    function delete($usr_id)
    {
        $user = Mng_user::find($usr_id);
        if($user->usr_foto){
            unlink(Storage_path('app/public/profile/'.$user->usr_foto));
        }
        $user->delete();
        return ResponseFormatter::success($user,'Successfully Deleted Role');
    }
    function detailAddRequest(){
        $data['bisnis'] = DB::table('v_company_refs')->get();
        $data['roles'] =  Mng_roles::select('rol_id as code','rol_name as name')->where('rol_stat','T')->orderBy('rol_id','ASC')->get();
        $data['divisi'] = Divisi_refs::ListDivision();
        $data['location'] = Location::listLocation();
        return json_encode($data);
    }
    function userList(){
        $data['userlist']= Mng_user::orderby('usr_fullname','ASC')->get();
        $data['requestor']= Auth::user();
        return json_encode($data);
    }
    function showUser(){
        $data['user'] = Auth::user();
        return json_encode($data);
    }
    function divisionBu($code){
        $check = Mng_user::find($code);
        $data['division'] = $check->usr_division;
        $data['bu'] = $check->usr_nm_perush;
        $data['department'] = $check->usr_department;
        return json_encode($data);
    }
   
}
