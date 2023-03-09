<?php

namespace App\Model;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Mng_menu extends Model
{
    protected $fillable = [
        'menu_id',
        'mod_id',
        'mng_menu_id',
        'menu_name',
        'menu_desc',
        'menu_display',
        'menu_type',
        'menu_stat',
        'action',
        'parent_id',
        'controller',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name'
    ];
    
    protected $table = 'mng_menus';
    protected $primaryKey = 'menu_id';
    public $incrementing = false;
    public $timestamps = false;
    public function childs() {
        return $this->hasMany('App\Mng_menu','parent_id','menu_id') ;
    }
    public static function getMenu(){
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $rolemenu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $query = DB::table('mng_menus')
                ->select('controller')
                ->groupBy('parent_id','menu_id','controller','menu_display')
                ->whereIn('menu_id',$rolemenu)
                ->whereNotNull('controller')
                ->orderBy('menu_display','ASC')
                ->pluck('controller');
        return $query;
    }
    public static function getData(){
        $menu = DB::table('mng_menus as mm')
        ->select('mm.menu_id','mmm.mod_name','mm.menu_name','mm.menu_desc','mm.menu_desc','mm.menu_display')
        ->leftjoin('mng_modules as mmm','mm.mod_id','mmm.mod_id')
        ->where('mm.menu_stat','T')
        ->orderBy('mm.menu_id','ASC')
        ->get();

        return $menu;
    }
    public static function getParent(){
        $data = Mng_menu::select('menu_id as code','menu_name as name')
        ->where('menu_type','N')
        ->get();
        return $data;
    }
}
