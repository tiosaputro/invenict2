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
        'usr_domain',
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
        'usr_division',
        'usr_department',
        'usr_loc'
    ];
    protected $hidden = [
        'user_passwd'
    ];
    protected $table = 'mng_users';
    protected $primaryKey = 'usr_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function createUser($request){
        $location = strtoupper(str_replace('"','',$request['physicaldeliveryofficename']));
        $checkLocation = Location::select('loc_code')->where('loc_desc','like',$location)->first();
        $idUser = generate_id_number();
        $data = Mng_user::create([
            'usr_id'=> $idUser,
            'usr_fullname'=>strtoupper(str_replace('"','',$request['displayname'])),
            'usr_name'=> str_replace('"','',$request['givenname']),
            'usr_email' => str_replace('"','',$request['mail']),
            'usr_domain' => str_replace('"','',$request['userprincipalname']),
            'usr_passwd' => Hash::make('123'),
            'usr_division' => str_replace('"','',$request['division']),
            'usr_department' => str_replace('"','',$request['department']),
            'usr_nm_perush' => str_replace('"','',$request['company']),
            'usr_nip' => str_replace('"','',$request['employeeid']),
            'usr_jabatan' => str_replace('"','',$request['extensionattribute15']),
            'usr_stat'=> 'T',
            'usr_alamat'=> str_replace('"','',$request['streetaddress']),
            'usr_loc'=> ($checkLocation) ? $checkLocation->loc_code : 'OJ',
            'created_by'=> 'INVENICT',
            'creation_date' => now(),
            'program_name'=>'Mng_user@createUser'
        ]);
        $role = Mng_roles::select('rol_id')->where('rol_name','like','Requestor Divisi')->first();
        Mng_usr_roles::create([
                'usr_id' => $idUser,
                'rol_id' => $role->rol_id,
                'urol_stat' => 'T',
                'creation_date' => now(),
                'created_by'=> $idUser,
                'program_name'=>'Mng_user@createUser'
        ]);
        return $data;
    }

    public static function updateUser($request){
        $location = strtoupper(str_replace('"','',$request['physicaldeliveryofficename']));
        $checkLocation = Location::select('loc_code')->where('loc_desc','like',$location)->first();

        $data = Mng_user::where('usr_domain',str_replace('"','',$request['userprincipalname']))->first();
        $data->usr_fullname = strtoupper(str_replace('"','',$request['displayname']));
        $data->usr_name = str_replace('"','',$request['givenname']);
        $data->usr_email = str_replace('"','',$request['mail']);
        $data->usr_domain = str_replace('"','',$request['userprincipalname']);
        $data->usr_division = str_replace('"','',$request['division']);
        $data->usr_passwd = Hash::make('123');
        $data->usr_department = str_replace('"','',$request['department']);
        $data->usr_nm_perush = str_replace('"','',$request['company']);
        $data->usr_nip = str_replace('"','',$request['employeeid']);
        $data->usr_jabatan = str_replace('"','',$request['extensionattribute15']);
        $data->usr_stat = 'T';
        $data->usr_alamat =str_replace('"','',$request['streetaddress']);
        $data->usr_loc = ($checkLocation) ? $checkLocation->loc_code : 'OJ';
        $data->LAST_UPDATED_BY = 'INVENICT';
        $data->LAST_UPDATE_DATE = now();
        $data->program_name = 'Mng_user@updateUser';
        $data->save();
    }
    public static function findUserByUserDomain($mail){
        $user = Mng_user::where('usr_domain','like',$mail)->first();
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
