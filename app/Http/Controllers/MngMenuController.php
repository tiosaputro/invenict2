<?php

namespace App\Http\Controllers;
use App\Models\Mng_menu;
use App\Models\Mng_user;
use carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;

class MngMenuController extends Controller
{
    protected $userMenu;
    protected $to;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/mng-menu";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function index()
    {
         $data = Mng_menu::getData();
        return response()->json($data);
 
    }
    function getParent()
    {
        $data = Mng_menu::getParent();
        return response()->json($data);
    }
    function save(Request $request)
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
    function edit($code)
    {
        $menu = Mng_menu::find($code);
        return response()->json($menu);
    }
    function update(Request $request, $code)
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
    function delete($menu_id)
    {
        $menu = Mng_menu::find($menu_id)->delete();
        
        return ResponseFormatter::success($menu,'Successfully Deleted data menu');

    }
}
