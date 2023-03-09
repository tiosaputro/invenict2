<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Mng_role_menu;
use App\Model\Mng_roles;
Use carbon\Carbon;
use App\Mng_User;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MngRoleMenuController extends Controller
{
    protected $userMenu;
    protected $to;
    public function __construct(){
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
        $role = Mng_role_menu::select('menu_id as code')->where('rol_id',$code)->pluck('code');
        return response()->json($role);
    }
    function update(Request $request,$code)
    {
        $menus = $request->menu;
        $menu = Mng_role_menu::select('creation_date','created_by')->where('rol_id',$code)->first();
        $createday = $menu->creation_date;
        $createdby = $menu->created_by;
        $mm = Mng_role_menu::where('rol_id',$code)->delete();
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
        $menu = DB::table('mng_menus as mm')
        ->rightjoin('mng_menus as m','mm.menu_id','m.parent_id')
        ->Select('m.menu_id as code', DB::raw("(mm.menu_name ||'-'|| m.menu_name) as name"))
        ->orderBy('mm.menu_name')
        ->get();
        return response()->json($menu);
    }
}
