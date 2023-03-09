<?php

namespace App\Model;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mng_usr_roles extends Model
{
    protected $fillable = [
        'urol_id',
        'usr_id',
        'rol_id',
        'urol_stat',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
    ];
    protected $table = 'mng_usr_roles';
    protected $primaryKey = 'urol_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function getRoles(){
       $ceKroles = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
       $role = Mng_roles::Select('rol_name')->whereIn('rol_id',$ceKroles)->pluck('rol_name');
       return $role;
    }
    public static function getRole($code){
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',$code)->pluck('rol_id');
        return $role;
     }

}
