<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Mng_role_menu extends Model
{
    protected $fillable = [
        'rolm_id',
        'menu_id',
        'rol_id',
        'rolm_priv',
        'rolm_stat',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name'
    ];
    
    protected $table = 'mng_role_menus';
    protected $primaryKey = 'rolm_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function saveRoleMenu($request,$role){
        $menus = $request->menu;
        foreach($menus as $m){
            $menu = Mng_role_menu::create([
                'menu_id' => $m,
                'rol_id' => $role->rol_id,
                'rolm_stat' => 'T',
                'creation_date' => now(),
                'created_by'=> Auth::user()->usr_id,
                'program_name'=>'MngRoleMenuController_SAVE'
            ]);
      } 
      return $menu;
    }
}
