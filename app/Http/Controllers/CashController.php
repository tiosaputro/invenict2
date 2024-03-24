<?php

namespace App\Http\Controllers;

use App\Exports\CashExport;
use App\Helpers\ResponseFormatter;
use App\Models\Cash;
use App\Models\Ict;
use App\Models\Mng_user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CashController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $detailLog, $newTime;
    public function __construct(){
        $this->detailLog = array();
        $this->middleware('auth:sanctum');
        $this->to = "/cash-advance";
        $this->middleware(function ($request, $next) {
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        });
    }
    public function index(){
        $data['cash'] = DB::table('v_cash_advance')->get();
        return ResponseFormatter::success($data, 'Successfully get data');
    }
    public function getNoRequest(){
        $data = Ict::listNoRequest();
        return ResponseFormatter::success($data);
    }
    public function detail($ca_idd){
        $dtl = DB::table('ireq_dtl as id')
            ->select(
                'imm.ireq_no',
                'id.ireqd_id',
                'id.invent_code',
                DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),
                'id.ireqd_id',
                'lr.lookup_desc as ireq_type',
                'im.invent_desc',
                'id.ireq_remark',
                'id.ireq_desc',
                'id.ireq_qty',
                DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"),
                'llr.lookup_desc as ireq_status'
            )
            ->leftjoin('invent_mst as im', 'id.invent_code', 'im.invent_code')
            ->leftjoin('lookup_refs as lr', 'id.ireq_type', 'lr.lookup_code')
            ->leftJoin('lookup_refs as llr', function ($join) {
                $join->on('id.ireq_status', 'llr.lookup_code')
                    ->whereRaw('LOWER(llr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
            })
            ->leftJoin('ireq_mst as imm', function ($join) {
                $join->on('id.ireq_id', 'imm.ireq_id');
            })
            ->where('imm.ireq_id', $ca_idd)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%'])
            ->orderBy('id.ireqd_id', 'ASC')
            ->get();

        return response()->json($dtl);
    }
    public function save(Request $request){
        $message = [
            'ireq_id.required' => 'No Request Belum Diisi',
            'ireq_id.unique' => 'No request ini sudah pernah dibuatkan cash advancenya',
            'ireqd_id.required' => 'No Detail Belum Diisi',
            'ireqd_id.unique' => 'No Detail ini sudah pernah dibuatkan cash advancenya',
            'jum.numeric' => 'Jumlah Belum Diisi',
            'tglsub.required' => 'Tgl Submit Belum diisi',
            // 'tglrecvunit.required'=>'Tgl Terima Barang Belum Diisi',
            // 'tglbuy.required'=>'Tgl Pembelian Belum Diisi',
            // 'tglrecvcash.required'=>'Tgl. Terima cash Belum Diisi',
            // 'tgltouser.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            // 'tglclosing.required'=>'Tgl Closing Belum Diisi'
        ];
        $request->validate([
            // 'noreq' => 'required|unique:ca_mst,ireq_id',
            'ireq_id' => [
                'required',
                Rule::unique('ca_mst')->where(function ($query) use ($request) {
                    return $query->where('ireq_id', $request->ireq_id)
                        ->where('ireqd_id', $request->ireqd_id);
                }),
            ],
            'ireqd_id' => [
                'required',
                Rule::unique('ca_mst')->where(function ($query) use ($request) {
                    return $query->where('ireq_id', $request->ireq_id)
                        ->where('ireqd_id', $request->ireqd_id);
                }),
            ],
            'jum' => 'numeric',
            'tglsub' => 'required',
            // 'tglrecvunit'=>'required',
            // 'tglbuy'=>'required',
            // 'tglrecvcash' => 'required',
            // 'tgltouser' => 'required',
            // 'tglclosing' => 'required'
        ], $message);

        $newCreation = now();
        $newTglSub = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tglsub)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');

        if ($request->tglrecvunit) {
            $newTglRecUnit = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tglrecvunit)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglRecUnit = '';
        }
        if ($request->tglbuy) {
            $newTglbuy = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tglbuy)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglbuy = '';
        }
        if ($request->tglrecvcash) {
            $newTglRecCash = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tglrecvcash)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglRecCash = '';
        }
        if ($request->tgltouser) {
            $newTglToUser = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tgltouser)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglToUser = '';
        }
        if ($request->tglclosing) {
            $newTglClosing = Carbon::createFromFormat('D M d Y H:i:s e+', $request->tglclosing)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglClosing = '';
        }

        $cash = Cash::create([
            'ireq_id' => $request->ireq_id,
            'ireqd_id' => $request->ireqd_id,
            'ca_pic_name' => $request->jum,
            'ca_submit_date' => $newTglSub,
            'ca_recv_cash_date' => $newTglRecCash,
            'ca_purchase_date' => $newTglbuy,
            'ca_recv_item_date' => $newTglRecUnit,
            'ca_hand_over_date' => $newTglToUser,
            'ca_settlement_date' => $newTglClosing,
            'creation_date' => $newCreation,
            'created_by' => Auth::user()->usr_id,
            'program_name' => "Cash_Save",
        ]);
        DB::getPdo()->exec("begin SP_CA_IREQ_MST($request->ireq_id); end;");
        return ResponseFormatter::success($cash, 'Successfully Create CA');
    }
    public function edit($code){
        $cash = DB::table('ca_mst as cm')
            ->select('im.ireq_no as ca_idd', 'im.ireq_requestor as req', 'vr.name as bu', 'cm.ca_pic_name', 'cm.ireqd_id', 'im.ireq_user',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                DB::raw("TO_CHAR(cm.ca_submit_date, 'dd Mon YYYY') as ca_submit_date"),
                DB::raw("TO_CHAR(cm.ca_recv_cash_date, 'dd Mon YYYY') as ca_recv_cash_date"),
                DB::raw("TO_CHAR(cm.ca_purchase_date, 'dd Mon YYYY') as ca_purchase_date"),
                DB::raw("TO_CHAR(cm.ca_recv_item_date, 'dd Mon YYYY') as ca_recv_item_date"),
                DB::raw("TO_CHAR(cm.ca_settlement_date, 'dd Mon YYYY') as ca_settlement_date"),
                DB::raw("TO_CHAR(cm.ca_hand_over_date, 'dd Mon YYYY') as ca_hand_over_date"))
            ->leftjoin('ireq_mst as im', 'cm.ireq_id', 'im.ireq_id')
            ->leftjoin('vcompany_refs as vr', 'im.ireq_bu', 'vr.company_code')
            ->where('cm.ca_id', $code)
            ->first();
        return response()->json($cash);
    }
    public function update(Request $request, $code){
        $message = [
            'ca_submit_date.required' => 'Tgl Submit Belum diisi',
            // 'ca_recv_item_date.required'=>'Tgl Terima Barang Belum diisi',
            // 'ca_purchase_date.required'=>'Tgl Pembelian Belum Diisi',
            // 'ca_recv_cash_date.required'=>'Tgl. Terima cash Belum Diisi',
            // 'ca_hand_over_date.required'=>'Tgl Penyerahan Ke User Belum Diisi',
            'ca_pic_name.required' => 'Jumlah Belum Diisi',
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
        ], $message);
        if ($request->ca_recv_item_date) {
            $newTglRecUnit = Carbon::parse($request->ca_recv_item_date)->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglRecUnit = '';
        }
        if ($request->ca_purchase_date) {
            $newTglbuy = Carbon::parse($request->ca_purchase_date)->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglbuy = '';
        }
        if ($request->ca_recv_cash_date) {
            $newTglRecCash = Carbon::parse($request->ca_recv_cash_date)->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglRecCash = '';
        }
        if ($request->ca_hand_over_date) {
            $newTglToUser = Carbon::parse($request->ca_hand_over_date)->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglToUser = '';
        }
        if ($request->ca_settlement_date) {
            $newTglClosing = Carbon::parse($request->ca_settlement_date)->tz('Asia/Jakarta')->format('Y-m-d');
        } else {
            $newTglClosing = '';
        }

        $newUpdate = now();
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
        $cash->last_updated_by = Auth::user()->usr_id;
        $cash->program_name = "Cash_Update";
        $cash->save();

        return ResponseFormatter::success($cash, 'Successfully Update CA');
    }
    public function delete($ca_id){
        $cash = Cash::find($ca_id)->delete();

        return ResponseFormatter::success($cash, 'Successfully Deleted CA');
    }
    public function cetak_pdf(){
        $cash = DB::table('ca_mst as cm')
            ->select('im.ireq_no as ca_idd', 'im.ireq_requestor as req', 'vr.name as bu', 'cm.ca_pic_name', 'im.ireq_requestor as ireq_id',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"),
                DB::raw("TO_CHAR(cm.ca_submit_date, 'dd Mon YYYY') as ca_submit_date"),
                DB::raw("TO_CHAR(cm.ca_recv_cash_date, 'dd Mon YYYY') as ca_recv_cash_date"),
                DB::raw("TO_CHAR(cm.ca_purchase_date, 'dd Mon YYYY') as ca_purchase_date"),
                DB::raw("TO_CHAR(cm.ca_recv_item_date, 'dd Mon YYYY') as ca_recv_item_date"),
                DB::raw("TO_CHAR(cm.ca_settlement_date, 'dd Mon YYYY') as ca_settlement_date"),
                DB::raw("TO_CHAR(cm.ca_hand_over_date, 'dd Mon YYYY') as ca_hand_over_date"))
            ->join('ireq_mst as im', 'cm.ireq_id', 'im.ireq_id')
            ->join('vcompany_refs as vr', 'im.ireq_bu', 'vr.company_code')
            ->get();
        return view('pdf/Laporan_Cash', compact('cash'));
    }
    public function cetak_excel(){
        return Excel::download(new CashExport, 'Laporan_cash_advance.xlsx');
    }
}
