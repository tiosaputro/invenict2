<?php

namespace App\Http\Controllers;

use App\Model\Mutasi;
use App\Exports\MutasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mng_User;
use App\Helpers\ResponseFormatter;

class MutasiController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/mutasi-peripheral";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    Public Function index()
    {
        $mutasi = DB::table('invent_mutasi as im')
            ->select(DB::raw("(imm.invent_desc ||'-'|| lrs.lookup_desc ||'-'|| imm.invent_type ) as invent_desc"),'im.imutasi_pengguna','im.imutasi_tgl_dari','im.imutasi_tgl_sd',
            'im.imutasi_lokasi','id.invent_code_dtl','im.imutasi_id','id.invent_code',
            'id.invent_sn','dr.div_name as imutasi_divisi',
            'vr.name as imutasi_bu')
            ->leftjoin('invent_dtl as id','im.invent_code_dtl','id.invent_code_dtl')
            ->leftjoin('invent_mst as imm','id.invent_code','imm.invent_code')
            ->LEFTJOIN('lookup_refs as lrs',function ($join) {
                $join->on('imm.invent_brand','lrs.lookup_code')
                      ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
            })
            ->leftjoin('divisi_refs as dr','im.imutasi_divisi','dr.div_id')
            ->leftjoin('vcompany_refs as vr','im.imutasi_bu','vr.company_code')
            ->orderBy('im.creation_date','DESC')
            ->get();
        return response()->json($mutasi);
    }
    Public function save(Request $request)
    {
      if ($request->todate){   
            $newToDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->todate)->copy()->tz('Asia/Jakarta')->format('Y-m-d');

        }else{
            $newToDate = '';
        }
            $saveMutasi = Mutasi::Create([
                'invent_code_dtl'=> $request->invent_sn,
                'imutasi_tgl_dari' => Carbon::createFromFormat('D M d Y H:i:s e+',$request->fromdate)->copy()->tz('Asia/Jakarta')->format('Y-m-d'),
                'imutasi_tgl_sd'=>$newToDate,
                'imutasi_lokasi' => $request->lokasi,
                'imutasi_pengguna' => $request->user,
                'imutasi_divisi' => $request->invent_divisi,
                'imutasi_bu' => $request->invent_bu,
                'imutasi_keterangan' => $request->ket,
                'creation_date'=> Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'created_by' => Auth::user()->usr_name,
                'program_name'=> "Mutasi_Save"
            ]);
            return ResponseFormatter::success($saveMutasi,'Successfully Created Mutasi');
    }
    Public function edit($code)
    {
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
    Public function update(Request $request, $code)
    {
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
            $mut->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
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
            $mut->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $mut->last_updated_by = Auth::user()->usr_name;
            $mut->program_name = "Mutasi_Update";
            $mut->save();
        }
        
        return ResponseFormatter::success($mut,'Successfully Updated Mutasi');
    }
    Public function delete($imutasi_id)
    {
        $mut = Mutasi::find($imutasi_id)->delete();
        return ResponseFormatter::success($mut,'Successfully Deleted Mutasi');
    }
    public function cetak_excel()
    {
        return Excel::download(new MutasiExport,'Laporan_Mutasi.xlsx');
    }
    public function cetak_pdf()
    {
        $mut = DB::table('invent_mutasi as im')
        ->LEFTJOIN('invent_dtl as id','im.invent_code_dtl','id.invent_code_dtl')
        ->LEFTJOIN('invent_mst as ivm','id.invent_code','ivm.invent_code')
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('ivm.invent_brand','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->select('im.imutasi_pengguna','im.imutasi_lokasi','im.imutasi_keterangan','ivm.invent_code','ivm.invent_desc','ivm.invent_type','id.invent_sn','lrs.lookup_desc as invent_brand',
            DB::raw("TO_CHAR(im.imutasi_tgl_dari,' dd Mon YYYY') as imutasi_tgl_dari"),
            DB::raw("TO_CHAR(im.imutasi_tgl_sd,' dd Mon YYYY') as imutasi_tgl_sd"))
        ->orderBy('im.creation_date','DESC')
        ->get();
        return view('pdf/Laporan_Mutasi', compact('mut'));
    }
}
