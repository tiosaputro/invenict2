<?php

namespace App\Http\Controllers;

use App\Mng_User;
use App\Model\Mng_roles;
use App\Model\Divisi_refs;
use carbon\Carbon;
use App\Model\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use App\Model\Mng_usr_roles;

class MngUserController extends Controller
{
    protected $userMenu;
    protected $to;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/mng-user";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    public function index()
    {
        $user = DB::table('v_mng_users')->get();
        return response()->json($user);
    }
    
    function getLocation(){
        $loc = Location::select('loc_code as code','loc_desc as name')->orderBy('loc_desc','ASC')->get();
        return json_encode($loc);
    }
    public function save(Request $request)
    {
        $message = [
            'usr_name.required'=>'User Name Belum Diisi',
            'usr_fullname.required'=>'User Full Name Belum Diisi',
            'usr_email.required'=>'Email Belum Diisi',
            'usr_email.unique'=>'Email Sudah Pernah Didaftarkan',
            'usr_alamat.required'=>'Alamat Belum Diisi',
            'usr_status.required'=>'Status Belum Diisi',
            'usr_passwd.required'=>'Password Belum Diisi',
            'div.required'=>'Divisi Belum Diisi',
            'usr_bu.required'=>'Bisnis Unit Belum Diisi',
            'usr_loc'=>'Lokasi Belum Diisi'
        ];
        $request->validate([
            'usr_name' => 'required',
            'usr_passwd'=>'required',
            'usr_email'=>'required|unique:mng_users,usr_email',
            'usr_alamat'=>'required',
            'usr_fullname'=>'required',
            'usr_status'=>'required',
            'div'=>'required',
            'usr_bu'=>'required',
            'usr_loc'=>'required'
        ],$message);

        $newfullName = strtoupper($request->usr_fullname);
        $image = $request->image;
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $replace = substr($image, 0, strpos($image, ',')+1); 
        $fotoo = str_replace($replace, '', $image);
        $foto= str_replace(' ', '+', $fotoo); 
        $nama_file = time().".".$extension;
        Storage::disk('profile')->put($nama_file, base64_decode($foto));
        $user = Mng_user::create([
            'usr_name'=>$request->usr_name,
            'usr_fullname'=>$newfullName,
            'usr_passwd'=> Hash::make($request->usr_passwd),
            'usr_alamat'=> $request->usr_alamat,
            'usr_stat'=> $request->usr_status,
            'usr_email'=> $request->usr_email,
            'usr_bu'=>$request->usr_bu,
            'div_id'=>$request->div,
            'usr_foto'=> $nama_file,
            'created_by'=> Auth::user()->usr_name,
            'creation_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'usr_loc'=>$request->usr_loc,
            'program_name'=>'MngUser_SAVE'
        ]);
       
        return ResponseFormatter::success($user,'Successfully Created User');
    }
    public function edit($code)
    {
        $user = Mng_User::find($code);
        $role = Mng_usr_roles::getRole($code);
        return response()->json(['user'=>$user,'role'=>$role],200);
    }
    public function update(Request $request,$code)
    {
        $user = Mng_user::find($code);
        $message = [
            'usr_fullname.required'=>'User Full Name Belum Diisi',
            'usr_email.required'=>'Email Belum Diisi',
            'usr_email.unique'=>'Email Sudah Pernah Didaftarkan',
            'usr_alamat.required'=>'Alamat Belum Diisi',
            'usr_stat.required'=>'Status Belum Diisi',
            'div_id.required'=>'Divisi Belum Diisi',
            'usr_bu.required'=>'Bisnis Unit Belum Diisi',
            'usr_loc.required'=>'Location Belum Diisi',
        ];
        $request->validate([
            'usr_email'=>[
                'required',
                Rule::unique('mng_users','usr_email')->ignore($code,'usr_id')
        ],
            'usr_alamat'=>'required',
            'usr_fullname'=>'required',
            'usr_stat'=>'required',
            'div_id'=>'required',
            'usr_loc'=>'required',
            'usr_bu'=>'required'
        ],$message);
        $newfullName = strtoupper($request->usr_fullname);
        if($request->image){
            if($request->usr_password){
                unlink(Storage_path('app/public/profile/'.$user->usr_foto));
                    $image= $request->image;
                    $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $fotoo = str_replace($replace, '', $image);
                    $foto= str_replace(' ', '+', $fotoo); 
                    $nama_file = time().".".$extension;
                    Storage::disk('profile')->put($nama_file, base64_decode($foto));
                    $user->usr_fullname = $newfullName;
                    $user->usr_alamat = $request->usr_alamat;
                    $user->usr_passwd = Hash::make($request->usr_password);
                    $user->usr_stat = $request->usr_stat;
                    $user->usr_email = $request->usr_email;
                    $user->div_id = $request->div_id;
                    $user->usr_bu = $request->usr_bu;
                    $user->usr_foto = $nama_file;
                    $user->usr_loc = $request->usr_loc;
                    $user->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
                    $user->last_updated_by = Auth::user()->usr_name;
                    $user->program_name = 'MngUserController_UPDATE';
                    $user->save();
            } else{
                    unlink(Storage_path('app/public/profile/'.$user->usr_foto));
                    $image= $request->image;
                    $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                    $replace = substr($image, 0, strpos($image, ',')+1); 
                    $fotoo = str_replace($replace, '', $image);
                    $foto= str_replace(' ', '+', $fotoo); 
                    $nama_file = time().".".$extension;
                    Storage::disk('profile')->put($nama_file, base64_decode($foto));
                    $user->usr_fullname = $newfullName;
                    $user->usr_alamat = $request->usr_alamat;
                    $user->usr_stat = $request->usr_stat;
                    $user->usr_email = $request->usr_email;
                    $user->div_id = $request->div_id;
                    $user->usr_loc = $request->usr_loc;
                    $user->usr_bu = $request->usr_bu;
                    $user->usr_foto = $nama_file;
                    $user->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
                    $user->last_updated_by = Auth::user()->usr_name;
                    $user->program_name = 'MngUserController_UPDATE';
                    $user->save();
                    }
        }else{
            if($request->usr_password){
                $user->usr_fullname = $newfullName;
                $user->usr_alamat = $request->usr_alamat;
                $user->usr_stat = $request->usr_stat;
                $user->usr_email = $request->usr_email;
                $user->usr_passwd = Hash::make($request->usr_password);
                $user->div_id = $request->div_id;
                $user->usr_loc = $request->usr_loc;
                $user->usr_bu = $request->usr_bu;
                $user->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
                $user->last_updated_by = Auth::user()->usr_name;
                $user->program_name = 'MngUserController_UPDATE';
                $user->save();
            }
        else{
            $user->usr_fullname = $newfullName;
            $user->usr_alamat = $request->usr_alamat;
            $user->usr_stat = $request->usr_stat;
            $user->usr_email = $request->usr_email;
            $user->div_id = $request->div_id;
            $user->usr_loc = $request->usr_loc;
            $user->usr_bu = $request->usr_bu;
            $user->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $user->last_updated_by = Auth::user()->usr_name;
            $user->program_name = 'MngUserController_UPDATE';
            $user->save();
        }
    }
    return ResponseFormatter::success($user,'Successfully Updated User');
    }
    public function delete($usr_id)
    {
        $user = Mng_user::find($usr_id);
        if($user->usr_foto){
            unlink(Storage_path('app/public/profile/'.$user->usr_foto));
        }
        $user->delete();
        return ResponseFormatter::success($user,'Successfully Deleted Role');
    }
    function detailAddRequest(){
        $bisnis = DB::table('v_company_refs')->get();
        $roles =  Mng_roles::select('rol_id as code','rol_name as name')->where('rol_stat','T')->orderBy('rol_id','ASC')->get();
        $divisi = Divisi_refs::ListDivision();
        $location = Location::listLocation();
        return json_encode(['bisnis'=>$bisnis,'roles'=>$roles,'divisi'=>$divisi,'location'=>$location],200);
    }
   
}
