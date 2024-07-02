<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Mng_roles;
use App\Models\Mng_role_menu;
use App\Models\Mng_user;
use App\Models\Mng_usr_roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MngUsrRoleController extends Controller
{
    protected $detailLog, $newTime;
    public function __construct()
    {
        $this->middleware(['auth:sanctum', function ($request, $next) {
            if (Auth::user()) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);

    }
    public function getRole(){
        $role = Mng_usr_roles::select('rol_id')->where('usr_id', Auth::user()->usr_id)->pluck('rol_id');
        $rolemenu = Mng_role_menu::select('menu_id')->whereIn('rol_id', $role)->pluck('menu_id');
        $query = DB::table('mng_menus')
            ->select('menu_display', 'controller', 'menu_id', 'parent_id')
            ->groupBy('parent_id', 'menu_id', 'controller', 'menu_display')
            ->whereIn('menu_id', $rolemenu)
            ->orderBy('menu_display', 'ASC')
            ->get();

        $data['tree'] = $this->parseTree($query);
        $data['user'] = Auth::user();
        return ResponseFormatter::success($data, 'Successfully Get Data Menu');
    }

    public function parseTree($tree, $root = 0){
        $return = array();
        foreach ($tree as $child => $parent) {
            if ($parent->parent_id == $root) {
                unset($tree[$child]);
                $return[] = array(
                    'label' => $parent->menu_display,
                    'to' => $parent->controller,
                    'items' => $this->parseTree($tree, $parent->menu_id),
                );
            }
        }
        return empty($return) ? null : $return;
    }
    public function save(Request $request){
        $user = Mng_User::select('usr_id')->where('usr_domain', $request->usr_domain)->first();
        $roles = $request->usr_roles;
        foreach ($roles as $r) {
            Mng_usr_roles::create([
                'usr_id' => $user->usr_id,
                'rol_id' => $r,
                'urol_stat' => 'T',
                'creation_date' => now(),
                'created_by' => Auth::user()->usr_id,
                'program_name' => 'MngUsrRoleController_SAVE',
            ]);
        }
    }
    public function edit($code){
        $role = Mng_usr_roles::getRole($code);
        return response()->json($role);
    }
    public static function update(Request $request, $code){
        $role = $request->role;
        $roles = Mng_usr_roles::where('usr_id', $code)->first();
        $createday = $roles->creation_date;
        $created_by = $roles->created_by;
        Mng_usr_roles::where('usr_id', $code)->delete();
        foreach ($role as $r) {
            Mng_usr_roles::create([
                'usr_id' => $code,
                'rol_id' => $r,
                'urol_stat' => 'T',
                'creation_date' => $createday,
                'created_by' => $created_by,
                'last_updated_by' => Auth::user()->usr_id,
                'last_updated_date' => now(),
                'program_name' => 'MngUsrRoleController_UPDATE',
            ]);
        }
    }
    public function cekRole(){
        $id = Auth::user()->usr_id;
        $getRole = Mng_usr_roles::select('rol_id')->where('usr_id', $id)->pluck('rol_id');
        $cek = Mng_roles::select('rol_id', 'rol_name')->whereIn('rol_id', $getRole)->get();
        return response()->json($cek);
    }
}
