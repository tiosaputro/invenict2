<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Ict;
use App\Exports\CashExport;
use Excel;
use DB;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use App\Mng_usr_roles;
use App\Mng_role_menu;

class CashController extends Controller
{
    function __construct(){
        $this->to = "/cash-advance";
    }
    function index()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->to)){
            $cash = DB::table('v_cash_advance')->get();
            return json_encode($cash);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function detail($ca_idd){
        $dtl = DB::table('ireq_dtl as id')
        ->select('imm.ireq_no','id.invent_code','id.ireq_assigned_to','id.ireqd_id','lr.lookup_desc as ireq_type','im.invent_desc','id.ireq_remark','id.ireq_desc', 'id.ireq_qty',
        DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"),'llr.lookup_desc as ireq_status')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->leftJoin('ireq_mst as imm',function ($join) {
            $join->on('id.ireq_id','imm.ireq_id');
        })
        ->where('imm.ireq_no',$ca_idd)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('id.ireqd_id','ASC')
        ->get();

            return json_encode($dtl);
    }
    function save(Request $request)
    {
        $message = [
            'noreq.required'=>'No Request Belum Diisi',
            'noreq.unique'=>'No request ini sudah pernah dibuatkan cash advancenya',
            'jum.numeric'=>'Jumlah Belum Diisi',
            'tglsub.required'=>'Tgl Submit Belum diisi',
            // 'tglrecvunit.required'=>'Tgl Terima Barang Belum Diisi',
            // 'tglbuy.required'=>'Tgl Pembelian Belum Diisi',
            // 'tglrecvcash.required'=>'Tgl. Terima cash Belum Diisi',
            // 'tgltouser.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            // 'tglclosing.required'=>'Tgl Closing Belum Diisi'
        ];
            $request->validate([
                'noreq' => 'required|unique:ca_mst,ireq_id',
                'jum'=>'numeric',
                'tglsub'=>'required',
                // 'tglrecvunit'=>'required',
                // 'tglbuy'=>'required',
                // 'tglrecvcash' => 'required',
                // 'tgltouser' => 'required',
                // 'tglclosing' => 'required'
            ],$message);

        $date = Carbon::now();

        $newCreation = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newTglSub = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglsub)->copy()->tz('Asia/Jakarta')->format('Y-m-d');

        if($request->tglrecvunit){
            $newTglRecUnit = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglrecvunit)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglRecUnit = '';
        }
        if($request->tglbuy){
            $newTglbuy = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglbuy)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglbuy='';
        }
        if($request->tglrecvcash){
            $newTglRecCash = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglrecvcash)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglRecCash='';
        }
        if($request->tgltouser){
            $newTglToUser = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tgltouser)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglToUser = '';
        }
        if ($request->tglclosing) {
             $newTglClosing = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglclosing)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
            } 
        else{
            $newTglClosing = '';
        }

        $cash = Cash::create([
            'ireq_id' =>$request->noreq,
            'ca_pic_name'=>$request->jum,
            'ca_submit_date'=>$newTglSub,
            'ca_recv_cash_date'=>$newTglRecCash,
            'ca_purchase_date'=>$newTglbuy,
            'ca_recv_item_date' => $newTglRecUnit,
            'ca_hand_over_date'=> $newTglToUser,
            'ca_settlement_date' => $newTglClosing,
            'creation_date' => $newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name'=>"Cash_Save",
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return json_encode($msg);
    }
    function edit($code)
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');
        
        if($aksesmenu->contains($this->to)){
        $cash = DB::table('ca_mst as cm')
            ->select('im.ireq_no as ca_idd','im.ireq_requestor as req', 'vr.name as bu','cm.ca_pic_name',
                    DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                    DB::raw("TO_CHAR(cm.ca_submit_date, 'dd Mon YYYY') as ca_submit_date"),
                    DB::raw("TO_CHAR(cm.ca_recv_cash_date, 'dd Mon YYYY') as ca_recv_cash_date"),
                    DB::raw("TO_CHAR(cm.ca_purchase_date, 'dd Mon YYYY') as ca_purchase_date"),
                    DB::raw("TO_CHAR(cm.ca_recv_item_date, 'dd Mon YYYY') as ca_recv_item_date"),
                    DB::raw("TO_CHAR(cm.ca_settlement_date, 'dd Mon YYYY') as ca_settlement_date"),
                    DB::raw("TO_CHAR(cm.ca_hand_over_date, 'dd Mon YYYY') as ca_hand_over_date"))
            ->leftjoin('ireq_mst as im','cm.ireq_id','im.ireq_id')
            ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->where('cm.ca_id',$code)
            ->first();
            return json_encode($cash);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function update(Request $request,$code)
    {
        $message = [
            'ca_submit_date.required'=>'Tgl Submit Belum diisi',
            // 'ca_recv_item_date.required'=>'Tgl Terima Barang Belum diisi',
            // 'ca_purchase_date.required'=>'Tgl Pembelian Belum Diisi',
            // 'ca_recv_cash_date.required'=>'Tgl. Terima cash Belum Diisi',
            // 'ca_hand_over_date.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            'ca_pic_name.required'=>'Jumlah Belum Diisi',
            // 'ca_settlement_date.required'=>'Tgl Closing Belum Diisi'
        ];
            $request->validate([
                'ca_submit_date' => 'required',
                // 'ca_recv_item_date'=>'required',
                // 'ca_purchase_date'=>'required',
                // 'ca_recv_cash_date'=>'required',
                // 'ca_hand_over_date'=>'required',
                'ca_pic_name' => 'required',
                // 'ca_settlement_date'=>'required'
            ],$message);
        $date = Carbon::now();
        if($request->ca_recv_item_date){
            $newTglRecUnit = Carbon::parse($request->ca_recv_item_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglRecUnit = '';
        }
        if($request->ca_purchase_date){
            $newTglbuy = Carbon::parse($request->ca_purchase_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglbuy='';
        }
        if($request->ca_recv_cash_date){
            $newTglRecCash = Carbon::parse($request->ca_recv_cash_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglRecCash='';
        }
        if($request->ca_hand_over_date){
            $newTglToUser = Carbon::parse($request->ca_hand_over_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglToUser = '';
        }
        if ($request->ca_settlement_date) {
             $newTglClosing = Carbon::parse($request->ca_settlement_date)->tz('Asia/Jakarta')->format('Y-m-d');
            } 
        else{
            $newTglClosing = '';
        }

        $newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newTglSub = Carbon::parse($request->ca_submit_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');

        $cash = Cash::find($code);
        $cash->ca_pic_name = $request->ca_pic_name;
        $cash->ca_submit_date = $newTglSub;
        $cash->ca_recv_cash_date = $newTglRecCash;
        $cash->ca_purchase_date = $newTglbuy;
        $cash->ca_recv_item_date = $newTglRecUnit;
        $cash->ca_hand_over_date = $newTglToUser;
        $cash->ca_settlement_date = $newTglClosing;
        $cash->last_update_date = $newUpdate;
        $cash->last_updated_by = Auth::user()->usr_name;
        $cash->program_name = "Cash_Update";
        $cash->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return json_encode($msg);
    }
    function delete($ca_id)
    {
        $cash = Cash::find($ca_id);
        $cash->delete();
            return json_encode('Successfully deleted');
    }
    function cetak_pdf()
    {
        $cash = DB::table('ca_mst as cm')
        ->select('im.ireq_no as ca_idd','im.ireq_requestor as req', 'vr.name as bu','cm.ca_pic_name','im.ireq_requestor as ireq_id',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                DB::raw("TO_CHAR(cm.ca_submit_date, 'dd Mon YYYY') as ca_submit_date"),
                DB::raw("TO_CHAR(cm.ca_recv_cash_date, 'dd Mon YYYY') as ca_recv_cash_date"),
                DB::raw("TO_CHAR(cm.ca_purchase_date, 'dd Mon YYYY') as ca_purchase_date"),
                DB::raw("TO_CHAR(cm.ca_recv_item_date, 'dd Mon YYYY') as ca_recv_item_date"),
                DB::raw("TO_CHAR(cm.ca_settlement_date, 'dd Mon YYYY') as ca_settlement_date"),
                DB::raw("TO_CHAR(cm.ca_hand_over_date, 'dd Mon YYYY') as ca_hand_over_date"))
        ->join('ireq_mst as im','cm.ireq_id','im.ireq_id')
        ->join('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->get();
        return view('pdf/Laporan_Cash',compact('cash'));
    }
    function cetak_excel()
    {
        return Excel::download(new CashExport,'Laporan_cash_advance.xlsx');
    }
}
