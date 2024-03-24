<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Mng_modules;
use App\Models\Mng_user;
use App\Helpers\ResponseFormatter;

class ModuleController extends Controller
{
    protected $to;
    protected $userMenu;
    // function __construct(){
    //     $this->middleware('auth:sanctum')->only('index','save','edit','update','delete');
    //     $this->to = "/mng-module";
    //     $this->middleware(function ($request, $next) {
    //       $this->userMenu = Mng_User::menu();
    //         if($this->userMenu->contains($this->to)){    
    //             return $next($request);
    //         } else {
    //             return response(["message"=>"Cannot Access"],403);
    //         }
    //     });
    // }
    function index(){
        $module = DB::table('v_mng_modules')->get();
        return $module->toJson();
    }
    public function getModule(){
        $module = Mng_modules::select('mod_id as code','mod_name as name')
        ->where('mod_stat','T')
        ->orderBy('mod_name', 'ASC')
        ->get();
        return response()->json($module);
    }
    public function save(Request $request){
        $message = [
            'mod_name.required'=>'Module Name Belum Diisi',
            'mod_desc.required'=>'Module Description Belum Diisi',
            'mod_stat.required'=>'Module Status Belum Diisi',
        ];
        $request->validate([
            'mod_name' => 'required',
            'mod_desc'=>'required',
            'mod_stat'=>'required'
        ],$message);

        
        $newCreation = now();
        $module = Mng_modules::create([
            'mod_name'=>$request->mod_name,
            'mod_desc'=>$request->mod_desc,
            'mod_stat'=>$request->mod_stat,
            'created_by'=> Auth::user()->usr_id,
            'creation_date'=>$newCreation,
            'program_name'=>'ModuleController_SAVE'
        ]);
        return ResponseFormatter::success($module,'Successfully Created Module');
    }
    function edit($code){
        $module = Mng_modules::select('mod_id','mod_name','mod_desc','mod_stat')->where('mod_id',$code)->first();
        return response()->json($module);
    }
    function update(Request $request,$code){
        $message = [
            'mod_name.required'=>'Module Name Belum Diisi',
            'mod_desc.required'=>'Module Description Belum Diisi',
            'mod_stat.required'=>'Module Status Belum Diisi',
        ];
        $request->validate([
            'mod_name' => 'required',
            'mod_desc'=>'required',
            'mod_stat'=>'required'
        ],$message);

        $newUpdate = now();
        $mod = Mng_modules::find($code);
        $mod->mod_name = $request->mod_name;
        $mod->mod_desc = $request->mod_desc;
        $mod->mod_stat = $request->mod_stat;
        $mod->last_updated_by = Auth::user()->usr_id;
        $mod->last_update_date = $newUpdate;
        $mod->save();
        
        return ResponseFormatter::success($mod,'Successfully Updated Module');
    }
    public function delete($mod_id){
        $module = Mng_modules::find($mod_id)->delete();
        return ResponseFormatter::success($module,'Successfully Deleted Module');
    }
}
