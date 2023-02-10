<?php

namespace App;;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Model\Mng_usr_roles;
use Illuminate\Support\Facades\Hash;
use App\Model\Location;
use Illuminate\Support\Facades\DB;
use App\Model\Divisi_refs;
use App\Model\Mng_roles;
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

    public static function createUser($address,$division, $password,$company,$location,$fullname,$usr_name,$mail){
        $division = str_replace('"','',$division);
        $address = str_replace('"','',$address);
        $password = str_replace('"','',$password);
        $company = str_replace('"','',$company);
        $location = str_replace('"','',$location);
        $location = strtoupper($location);
        $fullname = str_replace('"','',$fullname);
        $usr_name = str_replace('"','',$usr_name);
        $checkDivision = Divisi_refs::select('div_id')->where('div_name', 'like', $division)->first();
        $checkBisnisUnit = DB::table('vcompany_refs')->select('company_code')->where('name','like',$company)->first();
        $checkLocation = Location::select('loc_code')->where('loc_desc','like',$location)->first();
        $createUser = Mng_user::create([
            'usr_fullname'=>strtoupper($fullname),
            'usr_name'=> $usr_name,
            'usr_email' => $mail,
            'usr_passwd' => Hash::make($password),
            'usr_stat'=> 'T',
            'usr_alamat'=>$address,
            'div_id'=> $checkDivision->div_id,
            'usr_bu'=>$checkBisnisUnit->company_code,
            'usr_loc'=>$checkLocation->loc_code,
            'creation_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'created_by'=> 'INVENICT',
            'program_name'=>'Mng_user'
        ]);
        $id = Mng_user::select('usr_id')->where('usr_email',$mail)->first();
        $role = Mng_roles::select('rol_id')->where('rol_name','like','Default Role')->first();
        Mng_usr_roles::create([
                'usr_id' => $id->usr_id,
                'rol_id' => $role->rol_id,
                'urol_stat' => 'T',
                'creation_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'created_by'=> 'INVENICT',
                'program_name'=>'Mng_user'
        ]);
        return $id;
    }
}
