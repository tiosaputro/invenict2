<?php

namespace App\Http\Controllers;

use App\Pembelian;
use App\Lookup_Refs;
use App\Supplier;
use App\Exports\PembelianExport;
use Carbon\Carbon;
use DB;
use Excel;
use Auth;
use Illuminate\Http\Request;
use App\Mng_usr_roles;
use App\Mng_role_menu;

class PembelianController extends Controller
{
    function __construct(){
        $this->to = "/pembelian-peripheral";
    }
    public function index()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
            $pembelian = DB::table('purchase_mst as pm')
            ->select('pm.*','sm.suplier_name as suplier_code','pm.purchase_date',
                    DB::raw("CASE WHEN pm.purchase_status = 'T' Then 'Aktif' WHEN pm.purchase_status = 'F' Then 'Tidak Aktif' end as purchase_status "))
            ->join('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
            ->orderBy('pm.creation_date','ASC')
            ->get();
            return $pembelian->toJson();
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function save(Request $request)
    {
        $date = Carbon::now();
        $newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->purch_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $pemb = Pembelian::create([
            'purchase_date'=>$newDate,
            'purchase_petugas'=>$request->petugas,
            'suplier_code'=>$request->supp,
            'purchase_pay_methode'=>$request->pay,
            'valuta_code'=> $request->money,
            // 'purchase_status'=>$request->status,
            'purchase_remark'=>$request->ket,
            'creation_date'=> $newCreation,
            'created_by'=> Auth::user()->usr_name,
            'program_name'=> "Pembelian_Save"
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return json_encode($msg);
    }
    Public function edit($code)
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
            $pem = DB::table('purchase_mst as pm')
            ->select('pm.purchase_petugas','pm.suplier_code',
                    'pm.purchase_pay_methode','pm.valuta_code','pm.purchase_total','pm.purchase_status','pm.purchase_remark',
                    DB::raw("TO_CHAR(pm.purchase_date,' dd Mon YYYY') as purchase_date"))
            ->where('pm.purchase_id',$code)
            ->first();

            $uang = Lookup_Refs::Select('lookup_code as code',DB::raw("(lookup_code ||'-'|| lookup_desc) as name"))
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('mata uang')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();
            

            $metode = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('pay methode')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();

            $supp = Supplier::Select('suplier_code as code',DB::raw("(suplier_code ||'-'|| suplier_name) as name"))
            ->orderBy('suplier_code','ASC')
            ->get();
            return json_encode(['pem'=>$pem,'uang'=>$uang,'metode'=>$metode,'supp'=>$supp],200);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function update(Request $request,$code)
    {
        $date = Carbon::now();
        $newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newDate = Carbon::parse($request->purchase_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $pem = Pembelian::find($code);
        
        $pem->purchase_date = $newDate;
        $pem->purchase_petugas = $request->purchase_petugas;
        $pem->suplier_code = $request->suplier_code;
        $pem->purchase_pay_methode = $request->purchase_pay_methode;
        $pem->valuta_code = $request->valuta_code;
        $pem->purchase_total = $request->purchase_total;
        $pem->purchase_status = $request->purchase_status;
        $pem->purchase_remark = $request->purchase_remark;
        $pem->last_update_date = $newUpdate;
        $pem->last_updated_by = Auth::user()->usr_name;
        $pem->program_name = "Pembelian_Update";
        $pem->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
 
        return json_encode($msg);
    }

    Public function delete($purchase_id)
    {
        $pem = Pembelian::find($purchase_id);
        $pem->delete();
            return json_encode('Successfully deleted');
    }
    public function cetak_pdf()
    {
        $pem = DB::table('purchase_mst as pm')
        ->Select('pm.*','lr.lookup_desc as valuta_code','sm.suplier_name',
                'llr.lookup_desc as purchase_pay_methode', DB::raw("TO_CHAR(pm.purchase_date,' dd Mon YYYY') as purchase_date"),DB::raw("CASE WHEN pm.purchase_status = 'T' Then 'Aktif' WHEN pm.purchase_status = 'F' Then 'Tidak Aktif' end as purchase_status "))
        ->join('lookup_refs as llr','pm.purchase_pay_methode','llr.lookup_code')
        ->join('suplier_mst as sm','pm.suplier_code','sm.suplier_code')
        ->join('lookup_refs as lr','pm.valuta_code','lr.lookup_code')
        ->orderBy('pm.creation_date','ASC')
        ->get();
        
        return view('pdf/Laporan_Pembelian',compact('pem'));
    }
    public function cetak_excel()
    {
        return Excel::download(new PembelianExport,'Laporan_Pembelian.xlsx');
    }
}   
