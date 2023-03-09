<?php

namespace App\Model;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IctDetail extends Model
{
    protected $fillable = [
        'ireqd_id',
        'ireq_date_done',
        'ireq_id',
        'ireq_type',
        'invent_code',
        'ireq_desc',
        'ireq_qty',
        'ireq_status',
        'ireq_reason',
        'ireq_assigned_to1',
        'ireq_assigned_to1_reason',
        'ireq_assigned_to2',
        'ireq_assigned_date',
        'ireq_assigned_remark',
        'ireq_remark',
        'creation_date',
        'created_by',
        'last_update_date',
        'last_updated_by',
        'program_name',
        'ireq_value',
        'ireq_note',
        'ireq_note_personnel',
        'ireq_date_closing',
        'ireq_attachment'
    ];
    protected $table = 'ireq_dtl';
    protected $primaryKey ='ireqd_id';
    public $timestamps = false;
    public $incrementing = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public static function ApprovedByAtasan($code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A1',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByAtasan",
        ]);
        return $dtl;
    }
    
    public static function RejectedByAtasan($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectByManager",
        ]);
        return $dtl;
    }

    public static function ApprovedByIctManager($code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'A2',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_approveByManager",
        ]);
        return $dtl;
    }
     
    public static function RejectedByIctManager($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RA2',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectIctManager",
        ]);
        
        return $dtl;
    }

    public static function RejectedByReviewer($request, $code){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$code)
        ->update([
            'ireq_status' => 'RR',
            'ireq_reason' => $request->ket,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_rejectReviewer",
        ]);
        
        return $dtl;
    }

    public static function needApprovalByHigherLevel($ireq_id){
        DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA1',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalAtasan",
        ]);
    }

    public static function needApprovalByIctManager($ireq_id){
        $dtl = DB::table('ireq_dtl')
        ->WHERE('ireq_id',$ireq_id)
        ->update([
            'ireq_status' => 'NA2',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctController_needApprovalManager",
        ]);
        return $dtl;
    }

    public static function AcceptByPersonnel($ireq_id){
        DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_fullname)
        ->update([
            'ireq_status' => 'T',
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_abp",
        ]);
    }

    public static function rejectedByPersonnel($request, $ireq_id){
        DB::table('ireq_dtl')
        ->where('ireq_id',$ireq_id)
        ->where('ireq_assigned_to1',Auth::user()->usr_fullname)
        ->update([
            'ireq_status' => 'RT',
            'ireq_assigned_to1_reason' => $request->ireq_reason,
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "IctDetailController_rbp"
        ]);
    }
}
