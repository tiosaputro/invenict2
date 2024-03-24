<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Exports\MutasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Mng_user;
use App\Helpers\ResponseFormatter;
use App\Services\MutasiServices;
use Illuminate\Support\Facades\View;

class MutasiController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $mutasiServices;
    function __construct(MutasiServices $service){
        $this->mutasiServices = $service;
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
    public function index(){
        $mutasi = $this->mutasiServices->getDataWithFilter();
        return ResponseFormatter::success($mutasi,'Successfully get data');
    }
    function add(){
        $data['kode'] = DB::table('invent_mst as im')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.invent_brand','lr.lookup_code')
                ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->select(
            'im.invent_code as code',
            DB::raw("(im.invent_desc || '-' || lr.lookup_desc || '-' ||  im.invent_type) as name"))
        ->orderBy('im.invent_desc','ASC')
        ->get();
        $data['listUser'] = Mng_user::orderBy('usr_fullname','ASC')->get();
        return ResponseFormatter::success($data,'Successfully get data');
    }
    public function save(Request $request){
        $fromDate = Carbon::parse($request->fromdate);
        $toDate = $request->todate ? Carbon::parse($request->fromdate) : null;

        $saveMutasi = Mutasi::create([
            'invent_code_dtl'=> $request->invent_sn,
            'imutasi_tgl_dari' => $fromDate,
            'imutasi_tgl_sd' => $toDate,
            'imutasi_lokasi' => $request->lokasi,
            'imutasi_pengguna' => $request->user,
            'imutasi_keterangan' => $request->ket,
            'creation_date'=> now(),
            'created_by' => Auth::user()->usr_id,
            'program_name'=> "Mutasi_Save"
        ]);

        return ResponseFormatter::success($saveMutasi, 'Successfully Created Mutasi');
    }
    public function edit($code){
        $mut = $this->mutasiServices->getDataWithFilter($code);
        return ResponseFormatter::success($mut,'Successfully get data');
    }
    public function update(Request $request, $code){
        $newFromDate = Carbon::parse($request->imutasi_tgl_dari)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $mut = Mutasi::where('imutasi_id',$code)->first();
        $mut->imutasi_tgl_dari = $newFromDate;
        $mut->imutasi_lokasi = $request->imutasi_lokasi;
        if ($request->imutasi_tgl_sd){
            $newToDate = Carbon::parse($request->imutasi_tgl_sd)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
            $mut->imutasi_tgl_sd = $newToDate;
        }
        $mut->imutasi_pengguna = $request->imutasi_pengguna;
        $mut->imutasi_divisi = $request->imutasi_divisi;
        $mut->imutasi_bu = $request->imutasi_bu;
        $mut->imutasi_keterangan = $request->imutasi_keterangan;
        $mut->last_update_date = now();
        $mut->last_updated_by = Auth::user()->usr_id;
        $mut->program_name = "Mutasi_Update";
        $mut->save();
        
        return ResponseFormatter::success($mut,'Successfully Updated Mutasi');
    }
    public function delete($imutasi_id){
        $mut = Mutasi::find($imutasi_id)->delete();
        return ResponseFormatter::success($mut,'Successfully Deleted Mutasi');
    }
    function cetak_excel(){
        return Excel::download(new MutasiExport,'Laporan_Mutasi.xlsx');
    }
    function cetak_pdf(){
        $mut = $this->mutasiServices->getDataWithFilter();
        $data['htmlContent'] = View::make('pdf.Laporan_Mutasi', compact('mut'))->render();
        return ResponseFormatter::success($data,'Successfully Print Report');
    }
}
