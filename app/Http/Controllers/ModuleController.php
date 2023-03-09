<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Model\Mng_modules;
use App\Helpers\ResponseFormatter;

class ModuleController extends Controller
{
    public function index()
    {
        $module = DB::table('v_mng_modules')->get();
        return $module->toJson();
    }
    Public function getModule()
    {
        $module = Mng_modules::select('mod_id as code','mod_name as name')
        ->where('mod_stat','T')
        ->orderBy('mod_name', 'ASC')
        ->get();
        return response()->json($module);
    }
    Public function save(Request $request)
    {
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

        
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $module = Mng_modules::create([
            'mod_name'=>$request->mod_name,
            'mod_desc'=>$request->mod_desc,
            'mod_stat'=>$request->mod_stat,
            'created_by'=> Auth::user()->usr_name,
            'creation_date'=>$newCreation,
            'program_name'=>'ModuleController_SAVE'
        ]);
        return ResponseFormatter::success($module,'Successfully Created Module');
    }
    public function edit($code)
    {
        $module = Mng_modules::select('mod_id','mod_name','mod_desc','mod_stat')->where('mod_id',$code)->first();
        return response()->json($module);
    }
    public function update(Request $request,$code)
    {
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

        $newUpdate = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $mod = Mng_modules::find($code);
        $mod->mod_name = $request->mod_name;
        $mod->mod_desc = $request->mod_desc;
        $mod->mod_stat = $request->mod_stat;
        $mod->last_updated_by = Auth::user()->usr_name;
        $mod->last_update_date = $newUpdate;
        $mod->save();
        
        return ResponseFormatter::success($mod,'Successfully Updated Module');
    }
    Public function delete($mod_id)
    {
        $module = Mng_modules::find($mod_id)->delete();
        return ResponseFormatter::success($module,'Successfully Deleted Module');
    }
}
