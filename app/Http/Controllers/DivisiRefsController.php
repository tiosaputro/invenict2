<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Model\Divisi_refs;
Use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\Mng_usr_roles;
use App\Model\Mng_role_menu;

class DivisiRefsController extends Controller
{
    protected $divisi;

    public function __construct(){
        $this->divisi = "/divisi-refs";
    }
    function index()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->divisi)){
            $divisi = Divisi_refs::select('div_id','div_code','div_name','div_verificator')->orderBy('div_name','ASC')->get();
            return response()->json($divisi);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function getDivisi(){
        $divisi = DB::table('divisi_refs')->select('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))->orderBy('div_name','ASC')->get();
        return response()->json($divisi);
    }
    function edit($code)
    {
        $divisi = Divisi_refs::select('div_id','div_code','div_name','div_verificator')->where('div_id',$code)->first();
        return response()->json($divisi);
    }
    function update(Request $request,$code)
    {
        $message = [
            'div_code.required'=>'Divisi Code Belum Diisi',
            'div_name.required'=>'Divisi Name Belum diisi',
            'div_verificator.required'=>'Divisi Verificator Belum Diisi'
        ];
            $request->validate([
                'div_code' => 'required',
                'div_name'=>'required',
                'div_verificator'=>'required',
            ],$message);

        $newUpdate = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $div = Divisi_refs::find($code);
        $div->div_name = $request->div_name;
        $div->div_code = $request->div_code;
        $div->div_verificator = $request->div_verificator;
        $div->last_updated_by = Auth::user()->usr_name;
        $div->last_update_date = $newUpdate;
        $div->program_name = "DivisiRefs_Update";
        $div->save();
        return ResponseFormatter::success($div,'Successfully Updated Division');
    }
    public function delete($div_id)
    {
        $divisi = Divisi_refs::find($div_id)->delete();
        return ResponseFormatter::success($divisi,'Successfully Deleted Division');
    }
    function getDivisionRequest($bisnis){

        $divisi = DB::table('divisi_refs')
        ->select('div_id as code','div_name as name')
        ->where(function($query) use($bisnis){
            return $query
            ->where(DB::raw('substr(div_code,1,2)'),$bisnis)
            ->orwhere(DB::raw('substr(div_code,1,2)'),'99');
        })
        ->orderBy('div_name','ASC')
        ->get();
        return json_encode($divisi);
    }
}
