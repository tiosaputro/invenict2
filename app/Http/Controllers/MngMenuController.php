<?php

namespace App\Http\Controllers;
use App\Model\Mng_menu;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Mng_usr_roles;
use App\Model\Mng_role_menu;
use App\Helpers\ResponseFormatter;

class MngMenuController extends Controller
{
    protected $menu;
    public function __construct(){
        $this->menu = "/mng-menu";
    }
    function index()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->menu)){
            $data = Mng_menu::getData();
            return response()->json($data);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function getParent()
    {
        $data = Mng_menu::getParent();
        return response()->json($data);
    }
    Public function getMenu()
    {
        $menu = DB::table('mng_menus as mm')
        ->rightjoin('mng_menus as m','mm.menu_id','m.parent_id')
        ->Select('m.menu_id as code', DB::raw("(mm.menu_name ||'-'|| m.menu_name) as name"))
        ->orderBy('mm.menu_name')
        ->get();
        return response()->json($menu);
    }
    Public function save(Request $request)
    {
        $message = [
            'mod_id.required'=>'Module Name Belum Diisi',
            'menu_name.required'=>'Menu Name Belum Diisi',
            'menu_desc.required'=>'Menu Description Belum Diisi',
            'menu_display.required'=>'Menu Display Belum Diisi',
            'menu_type.required'=>'Metu Type Belum Diisi',
            'menu_stat.required'=>'Menu Status Belum Diisi',
        ];
        $request->validate([
            'mod_id' => 'required',
            'menu_name'=>'required',
            'menu_desc' => 'required',
            'menu_display'=>'required',
            'menu_type' => 'required',
            'menu_stat'=>'required',
        ],$message);

        $createMenu = Mng_menu::create([
            'mod_id'=>$request->mod_id,
            'menu_name'=>$request->menu_name,
            'menu_desc'=>$request->menu_desc,
            'menu_display'=>$request->menu_display,
            'menu_type'=>$request->menu_type,
            'menu_stat'=>$request->menu_stat,
            'controller'=>$request->controller,
            'action'=>$request->action,
            'parent_id'=>$request->parent_id,
            'creation_date'=>Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'created_by'=> Auth::user()->usr_name,
            'program_name'=> 'MngMenuController_Save'
        ]);
        return ResponseFormatter::success($createMenu,'Successfully Created data menu');
    }
    Public function edit($code)
    {
        $module = Mng_menu::find($code);
        return response()->json($module);
    }
    Public function update(Request $request, $code)
    {
        $message = [
            'mod_id.required'=>'Module Name Belum Diisi',
            'menu_name.required'=>'Menu Name Belum Diisi',
            'menu_desc.required'=>'Menu Description Belum Diisi',
            'menu_display.required'=>'Menu Display Belum Diisi',
            'menu_type.required'=>'Metu Type Belum Diisi',
            'menu_stat.required'=>'Menu Status Belum Diisi',
        ];
        $request->validate([
            'mod_id' => 'required',
            'menu_name'=>'required',
            'menu_desc' => 'required',
            'menu_display'=>'required',
            'menu_type' => 'required',
            'menu_stat'=>'required',    
        ],$message);

        $menu = Mng_menu::find($code);
        $menu->mod_id = $request->mod_id;
        $menu->menu_name = $request->menu_name;
        $menu->menu_desc = $request->menu_desc;
        $menu->menu_display = $request->menu_display;
        $menu->menu_type = $request->menu_type;
        $menu->menu_stat = $request->menu_stat;
        $menu->controller = $request->controller;
        $menu->action = $request->action;
        $menu->parent_id = $request->parent_id;
        $menu->last_updated_by = Auth::user()->usr_name;
        $menu->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $menu->program_name = "MngMenuController@update";
        $menu->save();
        
        return ResponseFormatter::success($menu,'Successfully Updated data menu');
    }
    Public function delete($menu_id)
    {
        $menu = Mng_menu::find($menu_id);
        $menu->delete();
        
        return ResponseFormatter::success($menu,'Successfully Deleted data menu');

    }
}
