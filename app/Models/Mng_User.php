<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use carbon\Carbon;


class Mng_User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'usr_id',
        'ureg_num',
        'kdsatker',
        'kddept',
        'kdunit',
        'usr_name',
        'usr_fullname',
        'usr_passwd',
        'usr_alamat',
        'usr_nip',
        'usr_email',
        'usr_npwp',
        'usr_jabatan',
        'usr_nm_perush',
        'usr_stat',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'kddept_unit',
        'waba_id',
        'usr_foto',
        'div_id',
        'usr_bu',
        'usr_loc'
    ];
    protected $hidden = [
        'user_passwd'
    ];
    protected $table = 'mng_users';
    protected $primaryKey = 'usr_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function createUser($address, $password, $location, $fullname, $usr_name, $mail){
        $address = str_replace('"','',$address);
        $password = str_replace('"','',$password);
        $location = str_replace('"','',$location);
        $location = strtoupper($location);
        $fullname = str_replace('"','',$fullname);
        $usr_name = str_replace('"','',$usr_name);
        $checkLocation = Location::select('loc_code')->where('loc_desc','like',$location)->first();
        $idUser = generate_id_number();
        $data = Mng_user::create([
            'usr_id'=> $idUser,
            'usr_fullname'=>strtoupper($fullname),
            'usr_name'=> $usr_name,
            'usr_email' => $mail,
            'usr_passwd' => Hash::make($password),
            'usr_stat'=> 'T',
            'usr_alamat'=> $address,
            'usr_loc'=> ($checkLocation) ? $checkLocation->loc_code : 'OJ',
            'created_by'=> 'INVENICT',
            'creation_date' => now(),
            'program_name'=>'Mng_user'
        ]);
        $role = Mng_roles::select('rol_id')->where('rol_name','like','Requestor Divisi')->first();
        Mng_usr_roles::create([
                'usr_id' => $idUser,
                'rol_id' => $role->rol_id,
                'urol_stat' => 'T',
                'creation_date' => now(),
                'created_by'=> 'INVENICT',
                'program_name'=>'Mng_user'
        ]);
        return $data;
    }
    public static function findUser($email){
        $user = Mng_user::where('usr_fullname','like',$email)->first();
        return $user;
    }
    public static function menu(){
        return Mng_menu::getMenu();
    }
    public static function roles(){
        return Mng_usr_roles::getRoles();
    }
    public static function findVerificatorDivision($div_id){
        $data = Mng_user::select('usr_email as name')->where('div_id',$div_id)->get();
        return $data;
    }
}
