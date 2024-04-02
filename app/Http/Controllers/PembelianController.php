<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Lookup_Refs;
use App\Models\Supplier;
use App\Exports\PembelianExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;

class PembelianController extends Controller
{
    protected $to;
    protected $userMenu;
    function __construct(){
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/pembelian-peripheral";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    function index(){
        $pembelian = DB::table('purchase_mst as pm')
        ->select('pm.purchase_id','pm.purchase_total','sm.suplier_name as suplier_code','pm.purchase_date','pm.valuta_code')
        ->leftjoin('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
        ->orderBy('pm.creation_date','ASC')
        ->get();
        return ResponseFormatter::success($pembelian,'Successfully get data');
    }
    function getAddPemb(){
        $uang = Lookup_Refs::Valuta();
        $metode = Lookup_Refs::Pay_Methode();
        $supp = Supplier::ListSupplier();
        $user = Auth::user()->usr_id;
        return ResponseFormatter::success(array('uang'=>$uang,'metode'=>$metode,'supp'=>$supp,'user'=>$user),'Successfully get data');
    }
    public function save(Request $request){
        $saveData = Pembelian::createDataPembelian($request);
        return ResponseFormatter::success($saveData,'Successfully Created Data');
    }
    public function edit($code){
        $pem = DB::table('purchase_mst as pm')
            ->select('pm.purchase_petugas','pm.suplier_code',
                    'pm.purchase_pay_methode','pm.valuta_code','pm.purchase_total','pm.purchase_status','pm.purchase_remark',
                    DB::raw("TO_CHAR(pm.purchase_date,' dd Mon YYYY') as purchase_date"))
            ->where('pm.purchase_id',$code)
        ->first();

        $uang = Lookup_Refs::Valuta();
        $metode = Lookup_Refs::Pay_Methode();
        $supp = Supplier::ListSupplier();
        return ResponseFormatter::success(array('pem'=>$pem,'uang'=>$uang,'metode'=>$metode,'supp'=>$supp),'Successfully get data');
    }
    public function update(Request $request,$code){
        $pem = Pembelian::find($code);
        $pem->purchase_date = Carbon::parse($request->purchase_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');;
        $pem->purchase_petugas = $request->purchase_petugas;
        $pem->suplier_code = $request->suplier_code;
        $pem->purchase_pay_methode = $request->purchase_pay_methode;
        $pem->valuta_code = $request->valuta_code;
        $pem->purchase_total = $request->purchase_total;
        $pem->purchase_status = $request->purchase_status;
        $pem->purchase_remark = $request->purchase_remark;
        $pem->last_update_date = now();
        $pem->last_updated_by = Auth::user()->usr_id;
        $pem->program_name = "Pembelian_Update";
        $pem->save();
        
        return ResponseFormatter::success($pem,'Successfully Updated Data');
    }

    public function delete($purchase_id){
        $pem = Pembelian::find($purchase_id)->delete();

        return ResponseFormatter::success($pem,'Successfully Deleted Data');
    }
    function cetak_pdf(){
        $pem = DB::table('purchase_mst as pm')
        ->Select('pm.*','lr.lookup_desc as valuta_code','sm.suplier_name',
                'llr.lookup_desc as purchase_pay_methode', DB::raw("TO_CHAR(pm.purchase_date,' dd Mon YYYY') as purchase_date"),DB::raw("CASE WHEN pm.purchase_status = 'T' Then 'Aktif' WHEN pm.purchase_status = 'F' Then 'Tidak Aktif' end as purchase_status "))
        ->leftjoin('lookup_refs as llr','pm.purchase_pay_methode','llr.lookup_code')
        ->leftjoin('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
        ->leftjoin('lookup_refs as lr','pm.valuta_code','lr.lookup_code')
        ->orderBy('pm.creation_date','DESC')
        ->get();
        
        return view('pdf/Laporan_Pembelian',compact('pem'));
    }
    function cetak_excel(){
        return Excel::download(new PembelianExport,'Laporan_Pembelian.xlsx');
    }
}   
