<?php

namespace App\Http\Controllers;
use App\Model\Divisi_refs;
Use carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use Appstract\Opcache\OpcacheFacade as OPcache;

class DivisiRefsController extends Controller
{
    public function index()
    {
        $divisi = Divisi_refs::select('div_id','div_code','div_name','div_verificator')->orderBy('div_name','ASC')->get();
        return response()->json($divisi);
    }
    public function getDivisi(){
        $divisi = DB::table('divisi_refs')->select('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))->orderBy('div_name','ASC')->get();
        return response()->json($divisi);
    }
    public function edit($code)
    {
        $divisi = Divisi_refs::select('div_id','div_code','div_name','div_verificator')->where('div_id',$code)->first();
        return response()->json($divisi);
    }
    public function update(Request $request,$code)
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

        $date = Carbon::now();
        $newUpdate =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $div = Divisi_refs::find($code);
        $div->div_name = $request->div_name;
        $div->div_code = $request->div_code;
        $div->div_verificator = $request->div_verificator;
        $div->last_updated_by = Auth::user()->usr_name;
        $div->last_update_date = $newUpdate;
        $div->program_name = "DivisiRefs_Update";
        $div->save();
        return response()->json('Updated Successfully');
    }
    public function delete($div_id)
    {
        $divisi = Divisi_refs::find($div_id);
        $divisi->delete();
        return response()->json('Deleted Successfully');
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
