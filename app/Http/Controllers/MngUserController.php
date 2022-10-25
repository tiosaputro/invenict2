<?php

namespace App\Http\Controllers;

use App\Mng_User;
use carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MngUserController extends Controller
{
    protected $newCreation;
    protected $newUpdate;
    public function __construct(){
        $date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    public function index()
    {
        $user = DB::table('v_mng_users')->get();
        return response()->json($user);
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
            'creation_date'=> $this->newCreation,
            'usr_loc'=>$request->usr_loc,
            'program_name'=>'MngUser_SAVE'
        ]);
       
        return response()->json([
            'success' => true,
            'message' => $user
        ]);
    }
    public function edit($code)
    {
        $user = DB::table('mng_users as mu')
        ->select('mu.usr_id','mu.usr_name','mu.usr_loc','mu.usr_bu','mu.usr_stat','mu.div_id','mu.usr_fullname', 'mu.usr_email','mu.usr_alamat','mu.usr_foto','mg.rol_id')
        ->leftjoin('mng_usr_roles as mg','mu.usr_id','mg.usr_id')
        ->where('mu.usr_id',$code)
        ->first();
        return response()->json($user);
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
                    $user->last_update_date = $this->newUpdate;
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
                    $user->last_update_date = $this->newUpdate;
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
                $user->last_update_date = $this->newUpdate;
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
            $user->last_update_date = $this->newUpdate;
            $user->last_updated_by = Auth::user()->usr_name;
            $user->program_name = 'MngUserController_UPDATE';
            $user->save();
        }
    }
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
 
        return response()->json($msg);
    }
    public function delete($usr_id)
    {
        $user = Mng_user::find($usr_id);
        unlink(Storage_path('app/public/profile/'.$user->usr_foto));
        $user->delete();
            return response()->json('Successfully deleted');
    }
    public function getVerif($div_id)
    {
        $user = Mng_user::select('usr_email as name')->where('div_id',$div_id)->get();
        return response()->json($user);
    }
}
