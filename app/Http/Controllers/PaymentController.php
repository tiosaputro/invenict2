<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment_request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;

class PaymentController extends Controller
{
    protected $to;
    protected $userMenu;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/payment-request";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function index()
    {
        $pr = DB::table('pr_mst as pr')
            ->leftjoin('ireq_mst as im','pr.ireq_id','im.ireq_id')
            ->select('pr.pr_id','im.ireq_no','pr.ireqd_id','pr.pr_submit_date','pr.pr_pic_name','im.ireq_requestor')
            ->get();
            return response()->json($pr);
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
            ],$message);



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

        $createPr = Payment_request::create([
            'ireq_id' =>$request->ireq_id,
            'ireqd_id'=>$request->ireqd_id,
            'pr_pic_name'=>$request->jum,
            'pr_submit_date'=>Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'pr_recv_cash_date'=>$newTglRecCash,
            'pr_purchase_date'=>$newTglbuy,
            'pr_recv_item_date' => $newTglRecUnit,
            'pr_hand_over_date'=> $newTglToUser,
            'pr_settlement_date' => $newTglClosing,
            'creation_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'created_by' => Auth::user()->usr_name,
            'program_name'=>"PaymentController_Save",
        ]);
        DB::getPdo()->exec("begin SP_PR_IREQ_MST($request->ireq_id); end;");
        
        return ResponseFormatter::success($createPr,'Successfully Created Data');
    }

    function edit($code)
    {
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
    function update(Request $request,$code)
    {
        $message = [
            'pr_submit_date.required'=>'Tgl Submit Belum diisi',
            'pr_pic_name.required'=>'Jumlah Belum Diisi',
        ];
            $request->validate([
                'pr_submit_date' => 'required',
                'pr_pic_name' => 'required',
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

        $pr = Payment_request::find($code);
        $pr->pr_pic_name = $request->pr_pic_name;
        $pr->pr_submit_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $pr->pr_recv_cash_date = $newTglRecCash;
        $pr->pr_purchase_date = $newTglbuy;
        $pr->pr_recv_item_date = $newTglRecUnit;
        $pr->pr_hand_over_date = $newTglToUser;
        $pr->pr_settlement_date = $newTglClosing;
        $pr->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $pr->last_updated_by = Auth::user()->usr_name;
        $pr->program_name = "PaymentController_update";
        $pr->save();
        return ResponseFormatter::success($pr,'Successfully Updated Data');
    }
    function delete($pr_id)
    {
        $pr = Payment_request::find($pr_id)->delete();
        return ResponseFormatter::success($pr,'Successfully Deleted Data');
    }
}
