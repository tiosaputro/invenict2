<?php

namespace App\Http\Controllers;

use App\Mutasi;
use App\Master;
use App\Exports\MutasiExport;
use Excel;
use DB;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mng_usr_roles;
use App\Mng_role_menu;

class MutasiController extends Controller
{
    function __construct(){
        $this->to = "/mutasi-peripheral";
    }
    Public Function index()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
            $mutasi = DB::table('invent_mutasi as im')
            ->select('im.imutasi_pengguna','im.imutasi_lokasi','im.imutasi_id','id.invent_code','id.invent_sn','imm.invent_type','imm.invent_desc','dr.div_name as imutasi_divisi','vr.name as imutasi_bu')
            ->leftjoin('invent_dtl as id','im.invent_code_dtl','id.invent_code_dtl')
            ->leftjoin('invent_mst as imm','id.invent_code','imm.invent_code')
            ->leftjoin('divisi_refs as dr','im.imutasi_divisi','dr.div_id')
            ->leftjoin('vcompany_refs as vr','im.imutasi_bu','vr.company_code')
            ->orderBy('im.creation_date','DESC')
            ->get();
            return response()->json($mutasi);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function save(Request $request)
    {
        $date = Carbon::now();
        $newCreation = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newFromDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->fromdate)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        if ($request->todate){   
        $newToDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->todate)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $mut = Mutasi::Create([
            'invent_code_dtl'=> $request->invent_sn,
            'imutasi_tgl_dari' => $newFromDate,
            'imutasi_tgl_sd'=>$newToDate,
            'imutasi_lokasi' => $request->lokasi,
            'imutasi_pengguna' => $request->user,
            'imutasi_divisi' => $request->invent_divisi,
            'imutasi_bu' => $request->invent_bu,
            'imutasi_keterangan' => $request->ket,
            'creation_date'=> $newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name'=> "Mutasi_Save"
        ]);
    }else{
    $mut = Mutasi::Create([
        'invent_code_dtl'=> $request->invent_sn,
        'imutasi_tgl_dari' => $newFromDate,
        'imutasi_lokasi' => $request->lokasi,
        'imutasi_pengguna' => $request->user,
        'imutasi_divisi' => $request->invent_divisi,
        'imutasi_bu' => $request->invent_bu,
        'imutasi_keterangan' => $request->ket,
        'creation_date'=> $newCreation,
        'created_by' => Auth::user()->usr_name,
        'program_name'=> "Mutasi_Save"
    ]);
}
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return response()->json($msg);
    }
    Public function edit($code)
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
            $mut = DB::table('invent_mutasi as im')
                ->Select(DB::raw("(imm.invent_desc || '-' || lr.lookup_desc || '-' ||  imm.invent_type) as invent_code"),'id.invent_sn',
                'im.imutasi_lokasi','im.imutasi_pengguna','im.imutasi_keterangan','id.invent_photo','im.imutasi_divisi','im.imutasi_bu',
                        DB::raw("TO_CHAR(im.imutasi_tgl_dari,' dd Mon YYYY') as imutasi_tgl_dari"),
                        DB::raw("TO_CHAR(im.imutasi_tgl_sd,' dd Mon YYYY') as imutasi_tgl_sd"))
                ->leftjoin('invent_dtl as id','im.invent_code_dtl','id.invent_code_dtl')
                ->leftjoin('invent_mst as imm','id.invent_code','imm.invent_code')
                ->leftJoin('lookup_refs as lr',function ($join) {
                    $join->on('imm.invent_brand','lr.lookup_code')
                        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
                })
                ->Where('im.imutasi_id',$code)
                ->first();
                return response()->json($mut);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function update(Request $request, $code)
    {
        $date = Carbon::now();
        $newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newFromDate = Carbon::parse($request->imutasi_tgl_dari)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        
        if ($request->imutasi_tgl_sd){
            $newToDate = Carbon::parse($request->imutasi_tgl_sd)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
            $mut = Mutasi::where('imutasi_id',$code)->first();
            $mut->imutasi_tgl_dari = $newFromDate;
            $mut->imutasi_tgl_sd = $newToDate;
            $mut->imutasi_lokasi = $request->imutasi_lokasi;
            $mut->imutasi_pengguna = $request->imutasi_pengguna;
            $mut->imutasi_divisi = $request->imutasi_divisi;
            $mut->imutasi_bu = $request->imutasi_bu;
            $mut->imutasi_keterangan = $request->imutasi_keterangan;
            $mut->last_update_date = $newUpdate;
            $mut->last_updated_by = Auth::user()->usr_name;
            $mut->program_name = "Mutasi_Update";
            $mut->save();
        }else{
            $mut = Mutasi::where('imutasi_id',$code)->first();
            $mut->imutasi_tgl_dari = $newFromDate;
            $mut->imutasi_tgl_sd = $request->imutasi_tgl_sd;
            $mut->imutasi_lokasi = $request->imutasi_lokasi;
            $mut->imutasi_pengguna = $request->imutasi_pengguna;
            $mut->imutasi_divisi = $request->imutasi_divisi;
            $mut->imutasi_bu = $request->imutasi_bu;
            $mut->imutasi_keterangan = $request->imutasi_keterangan;
            $mut->last_update_date = $newUpdate;
            $mut->last_updated_by = Auth::user()->usr_name;
            $mut->program_name = "Mutasi_Update";
            $mut->save();
        }
        $msg = [
                'success' => true,
                'message' => 'Updated Successfully'
            ];
        return response()->json($msg);
    }
    Public function delete($imutasi_id)
    {
        $mut = Mutasi::find($imutasi_id);
        $mut->delete();
        return response()->json('Successfully deleted');
    }
    public function cetak_excel()
    {
        return Excel::download(new MutasiExport,'Laporan_Mutasi.xlsx');
    }
    public function cetak_pdf()
    {
        $mut = DB::table('invent_mutasi as im')
        ->select('im.*','invent_mst.invent_code','invent_mst.invent_desc',
            DB::raw("TO_CHAR(im.imutasi_tgl_dari,' dd Mon YYYY') as imutasi_tgl_dari"),
            DB::raw("TO_CHAR(im.imutasi_tgl_sd,' dd Mon YYYY') as imutasi_tgl_sd"))
        ->join('invent_mst','im.invent_code','invent_mst.invent_code')
        ->orderBy('im.creation_date','ASC')
        ->get();
        return view('pdf/Laporan_Mutasi', compact('mut'));
    }
}
