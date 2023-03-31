<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mng_role_menu;
use App\Models\Mng_roles;
Use carbon\Carbon;
use App\Models\Mng_user;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MngRoleMenuController extends Controller
{
    protected $userMenu;
    protected $to;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/mng-role";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function save(Request $request)
    {
        $role = Mng_roles::select('rol_id')->where('rol_name',$request->rol_name)->first();
        $saveRoleMenu = Mng_role_menu::saveRoleMenu($request,$role);
        return ResponseFormatter::success($saveRoleMenu,'Successfully Created');
    }
    function edit($code)
    {
        $role = Mng_role_menu::select('menu_id')->where('rol_id',$code)->pluck('menu_id'); 
        $menu = Mng_menu::select('menu_id',DB::raw("(CASE WHEN menu_id IS NOT NULL THEN 'True' ELSE 'false' END) AS checked"))
            ->whereIn('menu_id',$role)
            ->get()
            ->keyBy('menu_id')
            ->toarray();
       
        return response()->json($menu);
    }
    function update(Request $request,$code)
    {
        $menus = $request->all();
        $menu = Mng_role_menu::select('creation_date','created_by')->where('rol_id',$code)->first();
        $createday = $menu->creation_date;
        $createdby = $menu->created_by;
        Mng_role_menu::where('rol_id',$code)->delete();
         foreach ($menus as $m) {
            $menu = Mng_role_menu::create([
                'menu_id' => $m,
                'rol_id' => $code,
                'rolm_stat' => 'T',
                'creation_date' => $createday,
                'created_by'=> $createdby,
                'last_updated_by'=> Auth::user()->usr_name,
                'last_update_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'program_name'=>'MngRoleMenuController_UPDATE'
            ]);
        }
      return ResponseFormatter::success($menu,'Successfully Updated');
    }
    function getMenu()
    {
        $menu = Mng_menu::select('menu_display','controller','menu_id','parent_id')
        ->groupBy('parent_id','menu_id','controller','menu_display')
        ->orderBy('menu_display','ASC')
        ->get();
        $tree = $this->parseTree($menu);
        return response()->json($tree);
    }
    function parseTree($tree, $root = 0) {
        $return = array();
        foreach($tree as $child => $parent) {
            if($parent->parent_id == $root) {
            unset($tree[$child]);
                $return[] = array(
                        'key'        => $parent->menu_id,
                        'label'     => $parent->menu_display,
                        'children'  => $this->parseTree($tree, $parent->menu_id)  
                );
            }
        }
        return empty($return) ? null : $return;    
    }
}
