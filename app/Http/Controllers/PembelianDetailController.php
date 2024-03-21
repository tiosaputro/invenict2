<?php

namespace App\Http\Controllers;


use Illuminate\Validation\Rule;
use App\Models\PembelianDetail;
use App\Models\Pembelian;
use App\Models\Master;
use App\Models\Mng_user;
Use App\Models\Lookup_Refs;
use App\Models\Supplier;
use App\Helpers\ResponseFormatter;
use App\Exports\PembelianDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    protected $to;
    protected $userMenu;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/pembelian-peripheral";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function index($code)
    {
        $dtl = DB::table('purchase_dtl as pd')
            ->select('pd.*','llr.lookup_desc as invent_brand','im.invent_type','lr.lookup_desc as dpurchase_sat','im.invent_desc','pm.valuta_code',
                    DB::raw("CASE WHEN pd.dpurchase_stat = 'T' Then 'Aktif' WHEN pd.dpurchase_stat = 'F' Then 'Tidak Aktif' end as dpurchase_stat "))
            ->leftjoin('purchase_mst as pm','pd.purchase_id','pm.purchase_id')
            ->leftjoin('invent_mst as im','pd.invent_code','im.invent_code')
            ->leftJoin('lookup_refs as llr',function ($join) {
                $join->on('im.invent_brand','llr.lookup_code')
                    ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
            })
            ->leftJoin('lookup_refs as lr',function ($join) {
                $join->on('pd.dpurchase_sat','lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('satuan')).'%']);
            })
            ->where('pd.purchase_id',$code)
            ->orderBy('pd.creation_date','DESC')
            ->get();
        $suppDate = DB::table('purchase_mst as pm')
            ->select('sm.suplier_name as suplier_code','pm.purchase_date')
            ->leftjoin('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
            ->where('pm.purchase_id',$code)
            ->first();
            return ResponseFormatter::success(array('detail'=>$dtl,'suppdate'=>$suppDate),'Successfully get data');
    }
    function save(Request $request,$code)
    {
        $message = [
            'invent_code.unique' => 'Item ini sudah dimasukan, silahkan edit jika ingin menambah qty',
        ];
        $request->validate([
            'invent_code' => [
                Rule::unique('purchase_dtl')->where(function ($query) use($request,$code)
            {
                return $query->where('purchase_id',$code)
                             ->where('invent_code',$request->invent_code);
            }),
            ],
        ],$message);
        $save = PembelianDetail::Create([
            'purchase_id'=>$code,
            'invent_code'=>$request->invent_code,
            'dpurchase_qty'=>$request->qty,
            'dpurchase_sat'=>$request->satuan,
            'dpurchase_prc_sat'=>$request->hrgsatuan,
            'dpurchase_prc'=>$request->pricetotal,
            'dpurchase_remark'=>$request->ket,
            'creation_date'=> now(),
            'created_by '=> Auth::user()->usr_id,
            'program_name'=> "PembelianDetail_Save"
        ]);

        return ResponseFormatter::success($save,'Successfully Created Data');
    }
    function edit($purchase)
    {
        $dtl= PembelianDetail::select('invent_code','dpurchase_qty','dpurchase_sat','dpurchase_stat','dpurchase_prc_sat',
                'dpurchase_prc','dpurchase_remark','purchase_id')
        ->where('dpurchase_id',$purchase)
        ->first();
        $valuta = Pembelian::Select('valuta_code')->where('purchase_id',$dtl->purchase_id)->first();
        $mas = Master::Select('invent_code as code',DB::raw("(invent_code ||'-'|| invent_desc) as name"))->get();
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('satuan')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return ResponseFormatter::success(array('dtl'=>$dtl,'mas'=>$mas,'ref'=>$ref,'valuta'=>$valuta),'Successfully get data');
    }
    function update(Request $request,$code,$purchase)
    {
       $dtl = PembelianDetail::find($purchase);

        $dtl->dpurchase_qty = $request->dpurchase_qty;
        $dtl->dpurchase_sat = $request->dpurchase_sat;
        $dtl->dpurchase_prc_sat = $request->dpurchase_prc_sat;
        $dtl->dpurchase_prc = $request->dpurchase_prc;
        $dtl->dpurchase_remark = $request->dpurchase_remark;
        $dtl->last_updated_by = Auth::user()->usr_id;
        $dtl->last_update_date = now();
        $dtl->program_name = "PembelianDetail_Update";
        $dtl->save();
           
        return ResponseFormatter::success($dtl,'Successfully Updated Data');
    }
    function delete($code,$dpurchase_id)
    {
        $dtl = PembelianDetail::Where('dpurchase_id',$dpurchase_id)->first();
          $dtl->delete();
          return ResponseFormatter::success($dtl,'Successfully Deleted Data');
    }
    function getValuta($code)
    {
        $dtl = Pembelian::Select('valuta_code')->where('purchase_id',$code)->first();
        $mas = DB::table('invent_mst as im')
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('im.invent_brand','lr.lookup_code')
                ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->select('im.invent_code as code',DB::raw("(im.invent_desc || '-' || lr.lookup_desc || '-' ||  im.invent_type) as name"))
        ->orderBy('im.invent_desc','ASC')
        ->orderBy('lr.lookup_desc','ASC')
        ->orderBy('im.invent_type','ASC')
        ->get();
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('satuan')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return ResponseFormatter::success(array('dtl'=>$dtl,'mas'=>$mas,'ref'=>$ref),'Successfully get data');
    }
    function cetak_pdf($purchase_id)
    {
        $pembelian = DB::table('purchase_mst as pm')
        ->Select('pm.*','pd.*','im.invent_desc','lr.lookup_desc as dpurchase_sat','sm.suplier_name',
                'llr.lookup_desc as purchase_pay_methode', DB::raw("CASE WHEN pm.purchase_status = 'T' Then 'Aktif' WHEN pm.purchase_status = 'F' Then 'Tidak Aktif' end as purchase_status "),
                DB::raw("CASE WHEN pd.dpurchase_stat = 'T' Then 'Aktif' WHEN pd.dpurchase_stat = 'F' Then 'Tidak Aktif' end as dpurchase_stat "), 
                DB::raw("TO_CHAR(pm.purchase_date,' dd Mon YYYY') as purchase_date"))
        ->join('purchase_dtl as pd','pm.purchase_id','pd.purchase_id')
        ->join('lookup_refs as llr','pm.purchase_pay_methode','llr.lookup_code')
        ->join('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
        ->join('invent_mst as im','pd.invent_code','im.invent_code')
        ->join('lookup_refs as lr','pd.dpurchase_sat','lr.lookup_code')
        ->where('pm.purchase_id', $purchase_id)
        ->orderBy('pm.creation_date','ASC') 
        ->get();
        return view('pdf/Laporan_PembelianDetail',compact('pembelian'));
    }
    function cetak_excel($purchase_id)
    {
        return Excel::download(new PembelianDetailExport($purchase_id),'Laporan_Pembelian.xlsx');
    }
}
