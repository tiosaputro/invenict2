<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Ict;
use App\Models\IctDetail;
use App\Models\Lookup_Refs;
use App\Models\Link;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;
use App\Jobs\SendNotifRequest;
use App\Models\Location;
use App\Models\Divisi_refs;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IctExportPermohonan;
use App\Exports\IctExportVerifikasi;
use App\Exports\IctExportReject;
use App\Exports\IctExportAssignmentRequest;
use App\Exports\IctExportSedangDikerjakan;
use App\Exports\IctExportSudahDikerjakan;
use App\Exports\IctExportSelesai;
use App\Exports\IctExportTabReviewer;
use App\Services\IctRequestorServices;
use App\Services\SupervisorServices;
use Illuminate\Support\Facades\DB;

class IctRequestRequestorController extends Controller
{
    protected $to;
    protected $userMenu;
    protected $requestorServices;

    function __construct(IctRequestorServices $services){
        $this->requestorServices = $services;
        $this->middleware('auth:sanctum');
        $this->to = "/ict-request";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    
    function getIct()
    {
        $data['ict'] = $this->requestorServices->getDataWithFilter('P',NULL,NULL,'status',NULL);
        $data['ict1'] = $this->requestorServices->getDataWithFilter('A1','A2',NULL,NULL,NULL);
        $data['ict2'] = $this->requestorServices->getDataWithFilter('RR','RA1','RA2',NULL,NULL);
        $data['ict3'] = $this->requestorServices->getDataWithFilter('T',NULL,NULL,NULL,NULL);
        $data['ict4'] = $this->requestorServices->getDetailIct('D',Auth::user()->usr_id);
        $data['ict5'] = $this->requestorServices->getDetailIct('C',Auth::user()->usr_id);
        $data['ict6'] = $this->requestorServices->getDataWithFilter('NA1','NA2',NULL,NULL,NULL);
        $data['ict7'] = $this->requestorServices->getDataWithFilter('P',NULL,NULL,NULL,NULL);
        $data['ict8'] = $this->requestorServices->getDataWithFilter('NT','RT',NULL,NULL,NULL);
        $data['ict9'] = $this->requestorServices->getDataWithFilter('T','NT',NULL,NULL,NULL);
        $data['ict10'] = $this->requestorServices->getDataWithFilter(NULL,NULL,NULL,NULL,'status');
        return json_encode($data);

    }
    function save(Request $request)
    {
        $save = $this->requestorServices->saveRequest($request);
        return ResponseFormatter::success($save,'Successfully Save Request');
    }
    function edit($code)
    {
        $data['ict'] = DB::table('ireq_mst as im')
            ->SELECT('im.ireq_id','mu.usr_fullname as ireq_requestor','im.ireq_no','im.ireq_prio_level','im.ireq_type','im.ireq_date','im.ireq_bu','im.ireq_divisi_user','im.ireq_user')
            ->LEFTJOIN('lookup_refs as lr',function ($join) {
                $join->on('im.ireq_type','lr.lookup_code')
                      ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
                })
            ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_id')
            ->WHERE('im.ireq_id',$code)
            ->first();
        $data['ref'] = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->WHERE('lookup_status','T')
            ->WHERERaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
            ->ORDERBY('lookup_desc','ASC')
            ->get();
    
        $data['bisnis'] = DB::table('v_company_refs')->get();
    
        $data['divisi'] = DB::table('divisi_refs')
            ->SELECT('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))
            ->ORDERBY('div_name','ASC')
            ->get();
    
        $data['priority'] = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->WHERE('lookup_status','T')
            ->WHERERaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
            ->ORDERBY('lookup_desc','ASC')
            ->get();

        $data['userlist']= Mng_user::orderby('usr_fullname','ASC')->get();
            return json_encode($data);
    }
    function update(Request $request, $code)
    {
       $save = $this->requestorServices->updateRequest($request,$code);
       return ResponseFormatter::success($save,'Successfully Updated Request');
    }
    function delete($ireq_id)
    {
        $save = $this->requestorServices->deleteRequest($ireq_id);
        return ResponseFormatter::success($save,'Successfully Deleted Request');
    }
    function submitRating(Request $request)
    {
        if($request->rating <= '2'){
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$request->id)
            ->where('ireq_id',$request->ireq_id)
            ->update([
                'ireq_value' => $request->rating,
                'ireq_note' => $request->ket
            ]);
            
            return ResponseFormatter::success($dtl,'Successfully Submitted Rating');
        }
        else{
            $dtl = DB::table('ireq_dtl')
            ->where('ireqd_id',$request->id)
            ->where('ireq_id',$request->ireq_id)
            ->update([
                'ireq_value' => $request->rating
            ]);
            return ResponseFormatter::success($dtl,'Successfully Submitted Rating');
        }
    }
    function getAddReq()
    {
        $data['ref'] = Lookup_Refs::Type();
        $data['priority'] = Lookup_Refs::Prioritas();
        $data['requestor'] = Auth::user();
        $data['listSupervisor'] = app(SupervisorServices::class)->getAllData();
        return response()->json($data);

    }
    function getNoreq()
    {
        $data = $this->requestorServices->listNoRequest();
        return ResponseFormatter::success($data,'Successfully get data');
    }
    function getNameBu($noreq,$dtl)
    {
        $ict = DB::table('ireq_mst as im')
        ->SELECT('im.ireq_requestor as req','im.ireq_no','im.ireq_id','vr.name as bu','id.ireqd_id','im.ireq_user',
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY') as ireq_date"))
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as id','im.ireq_id','id.ireq_id')
        ->WHERE('im.ireq_id',$noreq)
        ->WHERE('id.ireqd_id',$dtl)
        ->first();
            return response()->json($ict);
    }

    function detailNoRequest($code)
    {
        $ict = DB::table('ireq_mst as im')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('mng_users as mu','im.ireq_approver1','mu.usr_name')
        ->LEFTJOIN('mng_users as muu','im.ireq_approver2','muu.usr_name')
        ->SELECT('im.ireq_no','im.ireq_date','vr.name as ireq_bu','im.ireq_approver1_date as date_approver1',
        'im.ireq_approver2_date as date_approver2','dr.div_name','im.ireq_user','im.ireq_requestor','mu.usr_fullname as ireq_approval1','muu.usr_fullname as ireq_approval2')
        ->WHERE('im.ireq_id',$code)
        ->get();
        return json_encode($ict);
    }
    function updateStatusSubmit($ireq_id)
    {
        $ICT = Ict::where('ireq_id',$ireq_id)->first();
            $ICT->ireq_status = 'P';
            $ICT->ireq_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ICT->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
            $ICT->last_updated_by = Auth::user()->usr_name;
            $ICT->program_name = "IctController_updateStatusSubmit";
            $ICT->save();

        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'P',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_updateStatusSubmit",
        ]);
        Link::createLinkReviewer($ireq_id);
        $Ict = DB::table('ireq_dtl as id')
            ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->LEFTJOIN('lookup_refs as lrfs',function ($join) {
                $join->on('id.ireq_type','lrfs.lookup_code')
                     ->WHERERaw('LOWER(lrfs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->LEFTJOIN('vcompany_refs as vr',function ($join) {
                $join->on('im.ireq_bu','vr.company_code');
            })
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('mng_users as mu','im.ireq_requestor','mu.usr_name')
            ->SELECT('im.ireq_no','mu.usr_fullname','mu.usr_email','im.ireq_no','id.ireqd_id','vr.name as ireq_bu','im.ireq_id','dr.div_name', 'mu.usr_name',DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),'im.ireq_requestor',
                    'im.ireq_user',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),'id.ireq_qty','lrfs.lookup_desc as ireq_type','id.ireq_remark')
            ->WHERE('im.ireq_id',$ireq_id)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        $LINK = Link::where('ireq_id',$ireq_id)->first();
        if(env('APP_ENV') != 'local'){
            $send_mail = Location::select('loc_email')->WHERE('loc_code',Auth::user()->usr_loc)->pluck('loc_email');
        } else {
            $send_mail = 'adhitya.saputro@emp.id';
        }
        SendNotifRequest::dispatchAfterResponse($send_mail,$Ict,$LINK);
        return ResponseFormatter::success($ICT,'Successfully Submitted Request');
    }  

    function getDetail($noreq)
    {
        $ict = IctDetail::select('ireqd_id as code')->WHERE('ireq_id',$noreq)->get();
            return response()->json($ict);
    }
    function cetak_pdf_permohonan()
    {
            $ict =  DB::table('ireq_mst as id')
            ->LEFTJOIN('vcompany_refs as vr','id.ireq_bu','vr.company_code')
            ->LEFTJOIN('ireq_dtl as idm','id.ireq_id','idm.ireq_id')
            ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
            ->LEFTJOIN('lookup_refs as llr',function ($join) {
                $join->on('id.ireq_status','llr.lookup_code')
                    ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
            ->SELECT('vr.name as ireq_bu','id.ireq_id','id.ireq_no',
             DB::raw("TO_CHAR(id.ireq_date,' dd Mon YYYY') as ireq_date"),'id.ireq_user','dr.div_name',
            'id.ireq_requestor',DB::raw('count(DISTINCT(idm.ireq_id)) as count'),'llr.lookup_desc as ireq_status')
            ->WHERE('id.created_by',Auth::user()->usr_name)
            ->WHERE('id.ireq_status','P')
            ->groupBy('vr.name','llr.lookup_desc','id.ireq_id','id.ireq_no','id.ireq_user',
            'id.creation_date','dr.div_name','id.ireq_requestor','id.ireq_date')
            ->ORDERBY('id.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Permohonan', compact('ict'));
    }
    function cetak_excel_permohonan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportPermohonan($usr_name),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
    function cetak_pdf_tab_reviewer()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','NA1')
            ->orwhere('im.ireq_status','NA2');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Tab_Reviewer', compact('ict'));
    }
    function cetak_excel_tab_reviewer()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportTabReviewer($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Reviewer) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_verifikasi()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','A1')
            ->orwhere('im.ireq_status','A2');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Verifikasi', compact('ict'));
    }
    function cetak_excel_verifikasi()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportVerifikasi($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Terverifikasi) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_reject()
    {
        $ict =  DB::table('ireq_mst as im')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->SELECT('im.ireq_reason','vr.name as ireq_bu','im.ireq_requestor','im.ireq_status as status','dr.div_name','im.ireq_id','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','lr.lookup_desc as ireq_status')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE(function($query){
            return $query
            ->WHERE('im.ireq_status','RA1')
            ->orwhere('im.ireq_status','RA2')
            ->orwhere('im.ireq_status','RR');
        })            
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Reject', compact('ict'));
    }
    function cetak_excel_reject()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportReject($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Direject) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_assignment_request(){
        $ict = DB::table('ireq_mst as im')
            ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
            ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
            ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
            ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
            ->WHERE(function($query){
                return $query
                ->WHERE('im.ireq_status','NT')
                ->orWhere('im.ireq_status','RT');
                })
            ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
            ->WHERE('im.created_by',Auth::user()->usr_name)
            ->groupBy('vr.name','im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
            ->ORDERBY('im.ireq_date','DESC')
            ->get();
            return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_assignment_request(){
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportAssignmentRequest($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Direject) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_sedang_dikerjakan()
    {
        $ict =  DB::table('ireq_mst as im')
        ->SELECT('vr.name as ireq_bu','im.ireq_requestor','im.ireq_id','im.ireq_status as status','im.ireq_no',DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_user','im.ireq_requestor','dr.div_name',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1) AS ireq_assigned_to"),'lr.lookup_desc as ireq_status')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('vcompany_refs as vr','im.ireq_bu','vr.company_code')
        ->LEFTJOIN('lookup_refs as lr','im.ireq_status','lr.lookup_code')
        ->WHERE('im.ireq_status','T')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->groupBy('vr.name','im.ireq_requestor','im.ireq_status','im.ireq_id','im.ireq_no','im.ireq_date','im.ireq_user','im.ireq_requestor','dr.div_name','im.creation_date','lr.lookup_desc',DB::raw("COALESCE(im.ireq_assigned_to2,im.ireq_assigned_to1)"))          
        ->ORDERBY('im.ireq_date','DESC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sedang_Dikerjakan', compact('ict'));
    }
    function cetak_excel_sedang_dikerjakan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSedangDikerjakan($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Sedang Dikerjakan) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_sudah_dikerjakan()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','D')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Sudah_Dikerjakan', compact('ict'));
    }
    function cetak_excel_sudah_dikerjakan()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSudahDikerjakan($usr_name),'ICT REQUEST STATUS REPORT LIST ON (Sudah Dikerjakan) '.$newCreation.'.xlsx');
    }
    function cetak_pdf_selesai()
    {
        $ict =  DB::table('ireq_dtl as id')
        ->LEFTJOIN('ireq_mst as im','id.ireq_id','im.ireq_id')
        ->LEFTJOIN('divisi_refs as dr','im.ireq_divisi_user','dr.div_id')
        ->LEFTJOIN('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_status','lr.lookup_code')
                  ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.ireq_type','lrs.lookup_code')
                  ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->SELECT('im.ireq_no','id.ireq_id','id.ireq_remark',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"),'id.ireqd_id',
        'lr.lookup_desc as ireq_status','lrs.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),DB::raw("TO_CHAR(im.ireq_date,' dd Mon YYYY') as ireq_date"),'im.ireq_requestor','im.ireq_user',
        'dr.div_name','id.ireq_qty','id.ireq_status as status')
        ->WHERE('id.ireq_status','C')
        ->WHERE('im.created_by',Auth::user()->usr_name)
        ->ORDERBY('im.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get();
        return view('pdf/Laporan_IctRequest_Selesai', compact('ict'));
    }
    function cetak_excel_selesai()
    {
        $usr_name = Auth::user()->usr_name;
        $newCreation = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('d M Y');
        return Excel::download(new IctExportSelesai($usr_name),'ICT REQUEST STATUS REPORT LIST ON '.$newCreation.'.xlsx');
    }
}
