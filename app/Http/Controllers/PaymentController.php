<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Auth;
use App\Payment_request;
use App\Mng_usr_roles;
use App\Mng_role_menu;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function __construct(){
        $this->to = "/payment-request";
    }
    function index()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->to)){

            $pr = DB::table('pr_mst as pr')
            ->leftjoin('ireq_mst as im','pr.ireq_id','im.ireq_id')
            ->select('pr.pr_id','im.ireq_no','pr.ireqd_id','pr.pr_submit_date','pr.pr_pic_name','im.ireq_requestor')
            ->get();
            return response()->json($pr);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function save(Request $request)
    {
        $message = [
            'ireq_id.required'=>'No Request Belum Diisi',
            'ireq_id.unique'=>'No request ini sudah pernah dibuatkan payment requestnya',
            'ireqd_id.required'=>'No Detail Belum Diisi',
            'ireqd_id.unique'=>'No Detail ini sudah pernah dibuatkan payment requestnya',
            'jum.numeric'=>'Jumlah Belum Diisi',
            'tglsub.required'=>'Tgl Submit Belum diisi',
            // 'tglrecvunit.required'=>'Tgl Terima Barang Belum Diisi',
            // 'tglbuy.required'=>'Tgl Pembelian Belum Diisi',
            // 'tglrecvcash.required'=>'Tgl. Terima cash Belum Diisi',
            // 'tgltouser.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            // 'tglclosing.required'=>'Tgl Closing Belum Diisi'
        ];
            $request->validate([
                'ireq_id' => [
                    'required',
                    Rule::unique('pr_mst')->where(function ($query) use($request)
                {
                    return $query->where('ireq_id',$request->ireq_id)
                                 ->where('ireqd_id',$request->ireqd_id);
                }),
                ],
                'ireqd_id' => [
                    'required',
                    Rule::unique('pr_mst')->where(function ($query) use($request)
                {
                    return $query->where('ireq_id',$request->ireq_id)
                                 ->where('ireqd_id',$request->ireqd_id);
                }),
                ],
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
        $newTglSub = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tglsub)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');

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

        $pr = Payment_request::create([
            'ireq_id' =>$request->ireq_id,
            'ireqd_id'=>$request->ireqd_id,
            'pr_pic_name'=>$request->jum,
            'pr_submit_date'=>$newTglSub,
            'pr_recv_cash_date'=>$newTglRecCash,
            'pr_purchase_date'=>$newTglbuy,
            'pr_recv_item_date' => $newTglRecUnit,
            'pr_hand_over_date'=> $newTglToUser,
            'pr_settlement_date' => $newTglClosing,
            'creation_date' => $newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name'=>"PaymentController_Save",
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully',
        ];
        $result = DB::connection('oracle')->getPdo()->exec("begin SP_PR_IREQ_MST($request->ireq_id); end;");
        return response()->json($msg);
    }

    function edit($code)
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');
        
        if($aksesmenu->contains($this->to)){
        $pr = DB::table('pr_mst as cm')
            ->select('im.ireq_no as pr_idd','im.ireq_requestor as req', 'vr.name as bu','cm.pr_pic_name','cm.ireqd_id','im.ireq_user',
                    DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                    DB::raw("TO_CHAR(cm.pr_submit_date, 'dd Mon YYYY') as pr_submit_date"),
                    DB::raw("TO_CHAR(cm.pr_recv_cash_date, 'dd Mon YYYY') as pr_recv_cash_date"),
                    DB::raw("TO_CHAR(cm.pr_purchase_date, 'dd Mon YYYY') as pr_purchase_date"),
                    DB::raw("TO_CHAR(cm.pr_recv_item_date, 'dd Mon YYYY') as pr_recv_item_date"),
                    DB::raw("TO_CHAR(cm.pr_settlement_date, 'dd Mon YYYY') as pr_settlement_date"),
                    DB::raw("TO_CHAR(cm.pr_hand_over_date, 'dd Mon YYYY') as pr_hand_over_date"))
            ->leftjoin('ireq_mst as im','cm.ireq_id','im.ireq_id')
            ->leftjoin('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->where('cm.pr_id',$code)
            ->first();
            return response()->json($pr);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function update(Request $request,$code)
    {
        $message = [
            'pr_submit_date.required'=>'Tgl Submit Belum diisi',
            // 'pr_recv_item_date.required'=>'Tgl Terima Barang Belum diisi',
            // 'pr_purchase_date.required'=>'Tgl Pembelian Belum Diisi',
            // 'pr_recv_cash_date.required'=>'Tgl. Terima cash Belum Diisi',
            // 'pr_hand_over_date.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            'pr_pic_name.required'=>'Jumlah Belum Diisi',
            // 'pr_settlement_date.required'=>'Tgl Closing Belum Diisi'
        ];
            $request->validate([
                'pr_submit_date' => 'required',
                // 'pr_recv_item_date'=>'required',
                // 'pr_purchase_date'=>'required',
                // 'pr_recv_cash_date'=>'required',
                // 'pr_hand_over_date'=>'required',
                'pr_pic_name' => 'required',
                // 'pr_settlement_date'=>'required'
            ],$message);
        $date = Carbon::now();
        if($request->pr_recv_item_date){
            $newTglRecUnit = Carbon::parse($request->pr_recv_item_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglRecUnit = '';
        }
        if($request->pr_purchase_date){
            $newTglbuy = Carbon::parse($request->pr_purchase_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }
        else{
            $newTglbuy='';
        }
        if($request->pr_recv_cash_date){
            $newTglRecCash = Carbon::parse($request->pr_recv_cash_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglRecCash='';
        }
        if($request->pr_hand_over_date){
            $newTglToUser = Carbon::parse($request->pr_hand_over_date)->tz('Asia/Jakarta')->format('Y-m-d');
        }else{
            $newTglToUser = '';
        }
        if ($request->pr_settlement_date) {
             $newTglClosing = Carbon::parse($request->pr_settlement_date)->tz('Asia/Jakarta')->format('Y-m-d');
            } 
        else{
            $newTglClosing = '';
        }

        $newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $newTglSub = Carbon::parse($request->pr_submit_date)->copy()->tz('Asia/Jakarta')->format('Y-m-d');

        $pr = Payment_request::find($code);
        $pr->pr_pic_name = $request->pr_pic_name;
        $pr->pr_submit_date = $newTglSub;
        $pr->pr_recv_cash_date = $newTglRecCash;
        $pr->pr_purchase_date = $newTglbuy;
        $pr->pr_recv_item_date = $newTglRecUnit;
        $pr->pr_hand_over_date = $newTglToUser;
        $pr->pr_settlement_date = $newTglClosing;
        $pr->last_update_date = $newUpdate;
        $pr->last_updated_by = Auth::user()->usr_name;
        $pr->program_name = "PaymentController_update";
        $pr->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return response()->json($msg);
    }
    function delete($pr_id)
    {
        $pr = Payment_request::find($pr_id);
        $pr->delete();
            return response()->json('Successfully deleted');
    }
}
