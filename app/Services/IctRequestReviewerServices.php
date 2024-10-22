<?php

namespace App\Services;

use App\Jobs\SendNotifApproval;
use App\Jobs\SendNotifIctManager;
use App\Jobs\SendNotifPersonnel;
use App\Jobs\SendNotifRejectByReviewer;
use App\Jobs\SendNotifToRequestor;
use App\Jobs\SendNotifWaitingApprovalFromHigherLevel;
use App\Jobs\SendNotifWaitingApprovalFromIctManager;
use App\Jobs\SendNotifWaitingRecieveByPersonnel;
use App\Models\Ict;
use App\Models\IctDetail;
use App\Models\Link;
use App\Models\Lookup_Refs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Mng_usr_roles;

class IctRequestReviewerServices
{
    private $loc;
    public function getDataWithFilter($status1, $status2, $status3, $status4)
    {
        if (Auth::user()->usr_loc == "OJ") {
            $this->loc = ["OJ"];
        }
        elseif (Auth::user()->usr_loc == "OB" or Auth::user()->usr_loc == "FB") {
            $this->loc = ["OB", "FB"];
        }
        elseif (Auth::user()->usr_loc == "OK" or Auth::user()->usr_loc == "FK") {
            $this->loc = ["OK", "FK"];
        }
        $data = Ict::query();
        $data->SELECT(
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_verificator_remark',
            'mud.usr_division',
            'mu.usr_email',
            'ms.usr_fullname as ireq_requestor',
            'mud.usr_fullname as ireq_user',
            'ireq_mst.ireq_status as status',
            'ireq_mst.ireq_no',
            'ireq_mst.ireq_reason',
            'ireq_mst.ireq_date',
            'ireq_mst.ireq_assigned_to2',
            'ireq_mst.ireq_assigned_to1',
            'lr.lookup_desc as ireq_status',
            'ireq_mst.ireq_status as status',
            DB::raw("(usr.usr_fullname ||' - '|| usr.usr_jabatan) as spv"),
            DB::raw('count(ireq_mst.ireq_verificator_remark) as count_remark'),
            DB::raw('count(idd.ireq_assigned_to1) as ireq_count_status'),
            DB::raw('count(idd.ireq_assigned_to2) as count_assigned2'),
            DB::raw('count(idd.ireq_id) as ireq_count_id'),
            DB::raw('count(ireq_mst.ireq_spv) as ireq_count_spv'),
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            DB::raw('count(ireq_mst.ireq_approver2_remark) as count_remark_approver2'), 
            'ireq_mst.ireq_approver2_remark'
        );
        $data->LEFTJOIN('mng_users mu', 'ireq_mst.ireq_requestor', 'mu.usr_id');
        $data->LEFTJOIN('mng_users usr', 'ireq_mst.ireq_spv', 'usr.usr_id');
        $data->LEFTJOIN('vpekerja_ict vi', function ($join) {
            $join->on('ireq_mst.ireq_assigned_to1', 'vi.usr_id')
                ->whereNotNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function ($join) {
            $join->on('ireq_mst.ireq_assigned_to2', 'vii.usr_id')
                ->whereNull('ireq_mst.ireq_assigned_to1');
        });
        $data->LEFTJOIN('ireq_dtl idd', 'ireq_mst.ireq_id', 'idd.ireq_id');
        $data->LEFTJOIN('lookup_refs lr', 'ireq_mst.ireq_status', 'lr.lookup_code');
        $data->LEFTJOIN('mng_users ms', 'ireq_mst.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mud', 'ireq_mst.ireq_user', 'mud.usr_domain');
        $data->WHERE(function ($query) use ($status1, $status2, $status3, $status4) {
            if (!empty($status1)) {
                $query->WHERE('ireq_mst.ireq_status', $status1);
            }
            if (!empty($status2)) {
                $query->Orwhere('ireq_mst.ireq_status', $status2);
            }
            if (!empty($status3)) {
                $query->Orwhere('ireq_mst.ireq_status', $status3);
            }
            if (!empty($status4)) {
                $query->whereNotNull('ireq_mst.ireq_status');
            }
        });
        $data->WHEREIn('ireq_mst.ireq_loc', $this->loc);
        $data->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        $data->GroupBy(
            'ireq_mst.ireq_assigned_to2',
            'ms.usr_fullname',
            'mud.usr_fullname',
            'mud.usr_division',
            'ireq_mst.ireq_assigned_to1',
            'ireq_mst.ireq_id',
            'ireq_mst.ireq_reason',
            'ireq_mst.ireq_verificator_remark',
            DB::raw("(usr.usr_fullname ||' - '|| usr.usr_jabatan)"),
            DB::raw("COALESCE(vi.official_name,vii.official_name)"),
            'ireq_mst.ireq_status', 'ireq_mst.ireq_no', 'ireq_mst.ireq_date',
            'mu.usr_email',
            'lr.lookup_desc',
            'ireq_mst.ireq_status',
            'ireq_mst.ireq_approver2_remark');
        $data->ORDERBY('ireq_mst.ireq_date', 'DESC');
        return $data->get();
    }
    public function getDetailWithFilter($status, $code = null, $loc = 'TRUE', $type = null)
    {
        if (Auth::user()->usr_loc == "OJ") {
            $this->loc = ["OJ"];
        }
        elseif (Auth::user()->usr_loc == "OB" or Auth::user()->usr_loc == "FB") {
            $this->loc = ["OB", "FB"];
        }
        elseif (Auth::user()->usr_loc == "OK" or Auth::user()->usr_loc == "FK") {
            $this->loc = ["OK", "FK"];
        }
        $data = IctDetail::Query();
        $data->LEFTJOIN('ireq_mst as im', 'ireq_dtl.ireq_id', 'im.ireq_id');
        $data->LEFTJOIN('vpekerja_ict vi', function ($join) {
            $join->on('ireq_dtl.ireq_assigned_to1', 'vi.usr_id')
                ->whereNotNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('vpekerja_ict vii', function ($join) {
            $join->on('ireq_dtl.ireq_assigned_to2', 'vii.usr_id')
                ->whereNull('ireq_dtl.ireq_assigned_to1');
        });
        $data->LEFTJOIN('mng_users ms', 'im.ireq_requestor', 'ms.usr_id');
        $data->LEFTJOIN('mng_user_domain mus', 'im.ireq_user', 'mus.usr_domain');
        $data->LEFTJOIN('mng_users usr', 'im.ireq_spv', 'usr.usr_id');
        $data->LEFTJOIN('lookup_refs as lr', function ($join) {
            $join->on('ireq_dtl.ireq_status', 'lr.lookup_code')
                ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('ict_status')) . '%']);
        });
        $data->LEFTJOIN('lookup_refs as lrs', function ($join) {
            $join->on('ireq_dtl.ireq_type', 'lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ', [trim(strtolower('req_type')) . '%']);
        });
        $data->LEFTJOIN('catalog_refs as cr', function ($join) {
            $join->on('ireq_dtl.invent_code', 'cr.catalog_id');
        });
        $data->LEFTJOIN('catalog_refs as crs', function ($join) {
            $join->on('cr.parent_id', 'crs.catalog_id');
        });
        $data->SELECT(
            'ireq_dtl.ireq_attachment',
            DB::raw("COALESCE(vi.official_name,vii.official_name) AS ireq_assigned_to"),
            'ms.usr_fullname as ireq_requestor',
            'ms.usr_email as mail_requestor',
            'mus.usr_fullname as ireq_user',
            'usr.usr_fullname as spv_name',
            'usr.usr_id as spv_id',
            'mus.usr_division as division_user',
            'im.ireq_no',
            'im.ireq_reason',
            'im.ireq_verificator_remark',
            'ireq_dtl.ireq_status as status',
            'ireq_dtl.ireq_id',
            'ireq_dtl.ireq_remark',
            'ireq_dtl.ireqd_id',
            'lr.lookup_desc as ireq_status',
            'lrs.lookup_desc as ireq_type',
            DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as kategori"),
            'im.ireq_date',
            'mus.usr_division',
            'ireq_dtl.ireq_qty'
        );
        if (!empty($code)) {
            $data->WHERE('im.ireq_id', $code);
        }
        if (!empty($status)) {
            $data->WHERE('ireq_dtl.ireq_status', $status);
        }
        if (!empty($loc)) {
            $data->WHEREIn('im.ireq_loc', $this->loc);
        }
        $data->ORDERBY('im.ireq_date', 'DESC');
        $data->ORDERBY('ireq_dtl.ireqd_id', 'ASC');

        if (!empty($type)) {
            return $data->first();
        } else {
            return $data->get();
        }
    }
    public function RejectedByReviewer($request, $code)
    {
        $ict = Ict::where('ireq_id', $code)->first();
        $ict->ireq_status = 'RR';
        $ict->ireq_approver1 = Auth::user()->usr_id;
        $ict->ireq_approver1_date = now();
        $ict->ireq_reason = $request->ket;
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_rejectByReviewer";
        $ict->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $code)
            ->update([
                'ireq_status' => 'RR',
                'ireq_reason' => $request->ket,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_rejectReviewer",
            ]);

        $data = $this->getDetailWithFilter(null, $code, null);
        $email_address = $data[0]->mail_requestor;
        SendNotifRejectByReviewer::dispatchAfterResponse($email_address, $data);

        return $data;
    }
    public function NeedApprovalByHigherLevel($code)
    {
        $Ict = Ict::find($code);
        $Ict->ireq_status = 'NA1';
        $Ict->ireq_verificator = Auth::user()->usr_id;
        $Ict->last_update_date = now();
        $Ict->last_updated_by = Auth::user()->usr_id;
        $Ict->program_name = "IctController_needApprovalAtasan";
        $Ict->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $code)
            ->update([
                'ireq_status' => 'NA1',
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_needApprovalAtasan",
            ]);

        $data = $this->getDetailWithFilter(null, $code, null);

        Link::createLink($data[0]->spv_id, $code, 'Ict Request Verifikasi From Email');

        $LINK = Link::where('ireq_id', $code)->WHERE('usr_id', $data[0]->spv_id)->orderBy('created_at', 'desc')->first();

        if (env('APP_ENV') != 'local') {
            $spv_mail = $data[0]->spv_mail;
            $user_mail = $data[0]->user_mail;
        } else {
            $spv_mail = 'adhitya.saputro@emp.id';
            $user_mail = 'adhitya.saputro@emp.id';
        }

        SendNotifApproval::dispatchAfterResponse($spv_mail, $data, $LINK);
        SendNotifWaitingApprovalFromHigherLevel::dispatchAfterResponse($user_mail, $data);
        return $Ict;
    }
    public function needApprovalByIctManager($code)
    {
        $ICT = Ict::where('ireq_id', $code)->first();
        $ICT->ireq_status = 'NA2';
        $ICT->ireq_verificator = Auth::user()->usr_id;
        $ICT->last_update_date = now();
        $ICT->last_updated_by = Auth::user()->usr_id;
        $ICT->program_name = "IctController_needApprovalManager";
        $ICT->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $code)
            ->update([
                'ireq_status' => 'NA2',
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_needApprovalManager",
            ]);

        $ictManager = Lookup_Refs::Select('lookup_desc', 'lookup_code')->where('lookup_type', 'ict_manager')->first();

        $data = $this->getDetailWithFilter(null, $code, null);

        Link::createLink($ictManager->lookup_code, $code, 'Ict Request Verifikasi From Email ICT Manager');
        $LINK = Link::where('ireq_id', $code)->where('usr_id', $ictManager->lookup_code)->orderBy('created_at', 'desc')->first();
        if (env('APP_ENV') != 'local') {
            $mail_manager = $ictManager->lookup_desc;
            $mail_user = $data[0]->user_mail;
        } else {
            $mail_manager = 'adhitya.saputro@emp.id';
            $mail_user = 'adhitya.saputro@emp.id';
        }
        SendNotifIctManager::dispatchAfterResponse($mail_manager, $data, $LINK);
        SendNotifWaitingApprovalFromIctManager::dispatchAfterResponse($mail_user, $data);

        return $ICT;
    }
    public function assignPerRequest($request)
    {
        $ict = Ict::where('ireq_id', $request->id)->first();
        if ($ict->ireq_status == 'RT') {
            $ict->ireq_assigned_to2 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_id;
            $ict->last_update_date = now();
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            DB::table('ireq_dtl')
                ->WHERE('ireq_id', $request->id)
                ->update([
                    'ireq_assigned_to2' => $request->name,
                    'last_updated_by' => Auth::user()->usr_id,
                    'last_update_date' => now(),
                    'program_name' => "IctController_asignPerRequestReviewer",
                ]);
            return $ict;
        } else {
            $ict->ireq_assigned_to1 = $request->name;
            $ict->last_updated_by = Auth::user()->usr_id;
            $ict->last_update_date = now();
            $ict->program_name = "IctController_asignPerRequestReviewer";
            $ict->save();

            DB::table('ireq_dtl')
                ->WHERE('ireq_id', $request->id)
                ->update([
                    'ireq_assigned_to1' => $request->name,
                    'last_update_date' => now(),
                    'last_updated_by' => Auth::user()->usr_id,
                    'program_name' => "IctController_asignPerRequestReviewer",
                ]);
            return $ict;
        }
    }
    public function submitAssignPerRequets($ireq_id)
    {
        $ict = Ict::where('ireq_id', $ireq_id)->first();
        $dtll = IctDetail::where('ireq_id', $ireq_id)->get();
        $name = [];
        $email = [];
        if ($ict->ireq_status == 'RT') {
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to2);
            }
            $status = 'T';
        } else {
            foreach ($dtll as $d) {
                array_push($name, $d->ireq_assigned_to1);
            }
            $status = 'NT';
        }
        $ict->ireq_status = $status;
        $ict->ireq_verificator = Auth::user()->usr_id;
        $ict->ireq_assigned_date = now();
        $ict->last_update_date = now();
        $ict->last_updated_by = Auth::user()->usr_id;
        $ict->program_name = "IctController_submitAssignPerRequest";
        $ict->save();

        DB::table('ireq_dtl')
            ->WHERE('ireq_id', $ireq_id)
            ->update([
                'ireq_status' => $status,
                'ireq_assigned_date' => now(),
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "IctController_submitAssignPerRequest",
            ]);
        $usr_email = DB::table('mng_users')->SELECT('usr_email')->WHEREIn('usr_id', $name)->pluck('usr_email');
        foreach ($usr_email as $s) {
            $emailpush = $s;
            array_push($email, $emailpush);
        }
        $dataForPersonnel = $this->getDetailWithFilter(null, $ireq_id, null, 'TRUE');
        $dataForRequestor = $this->getDetailWithFilter(null, $ireq_id, null);

        if (env('APP_ENV') != 'local') {
            $email_address = $dataForRequestor[0]->mail_requestor;
            $email = $email;
        } else {
            $email = $email;
            $email_address = 'adhitya.saputro@emp.id';
        }
        foreach ($email as $val) {
            SendNotifPersonnel::dispatchAfterResponse($val, $dataForPersonnel);
        }
        SendNotifWaitingRecieveByPersonnel::dispatchAfterResponse($email_address, $dataForRequestor);

        return $ict;
    }
    public function sendMailToRequestor($request)
    {
        $Ict = $this->getDetailWithFilter(null, $request->ireq_id, null);
        $to = $request->to;
        $footer = $request->footer;
        $body = $request->body;
        SendNotifToRequestor::dispatchAfterResponse($Ict, $to, $footer, $body);
        return $Ict;
    }
    public function SaveSpv($request){
        $data = Ict::find($request->ireq_id);
        $data->ireq_spv = $request->ireq_spv;
        $data->last_update_date = now();
        $data->last_updated_by = Auth::user()->usr_id;
        $data->program_name = "IctRequestReviewerController__saveSpv";
        $data->save();
        
        return $data;
    }
    
    public function CheckIsHigherLevel($id){
        $UserRole = Mng_usr_roles::select('rol_id')->where('usr_id', $id)->get();
        $roleApproval = Lookup_Refs::select('lookup_code')->where('lookup_type','role_approval_spv')->first();
        foreach($UserRole as $val){
            $check[] = $val->rol_id;
        }
        if(in_array($roleApproval->lookup_code,$check)){
            return true;
        } else{
            $role = $check;
            array_push($role,$roleApproval->lookup_code);
            $roles = Mng_usr_roles::where('usr_id', $id)->first();
            $createday = $roles->creation_date;
            $created_by = $roles->created_by;
            Mng_usr_roles::where('usr_id', $id)->delete();
            foreach ($role as $r) {
                $create = Mng_usr_roles::create([
                    'usr_id' => $id,
                    'rol_id' => $r,
                    'urol_stat' => 'T',
                    'creation_date' => $createday,
                    'created_by' => $created_by,
                    'last_updated_by' => Auth::user()->usr_id,
                    'last_updated_date' => now(),
                    'program_name' => 'MngUsrRoleController_UPDATE',
                ]);
            }
            return $create;
        }
        
    }
}
