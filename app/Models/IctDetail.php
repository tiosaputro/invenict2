<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendNotifInProgress;

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

    protected function setKeysForSaveQuery(Builder $query){
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null){
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public function getDataDetailRequest($code){
        $dtl = DB::table('ireq_dtl as id')
            ->select(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name) AS ireq_assigned_to"),
                'id.ireq_attachment',
                'id.ireq_id',
                'id.ireq_assigned_to1_reason',
                'id.invent_code',
                'vi1.official_name as ireq_assigned_to1',
                'id.ireq_status as status',
                'vi2.official_name as ireq_assigned_to2',
                'id.ireqd_id',
                'lr.lookup_desc as ireq_type',
                'id.ireq_remark',
                'id.ireq_desc', 
                'id.ireq_qty',
                DB::raw('COUNT(id.ireq_assigned_to2) as ireq_count_personnel2'),
                DB::raw('COUNT(id.ireq_assigned_to1_reason) as ireq_count_reason'),
                DB::raw('COUNT(id.ireq_assigned_to1) as ireq_count_status'),
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
                'llr.lookup_desc as ireq_status',
                'id.ireq_status as cekStatus')
            ->leftJoin('vpekerja_ict as vi1', 'id.ireq_assigned_to1','vi1.usr_id')
            ->leftJoin('vpekerja_ict as vi2', function($join) {
                $join->on('id.ireq_assigned_to2','vi2.usr_id')
                      ->whereNotNull('id.ireq_assigned_to2');
            })
            ->leftJoin('catalog_refs as cr',function ($join){
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->leftJoin('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->leftJoin('lookup_refs as lr',function ($join) {
                $join->on('id.ireq_type','lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
            })
            ->leftJoin('lookup_refs as llr',function ($join) {
                $join->on('id.ireq_status','llr.lookup_code')
                    ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
            })
            ->where('id.ireq_id',$code)
            ->groupBy(
                DB::raw("COALESCE(vi2.official_name,vi1.official_name)"),
                'id.ireq_attachment',
                'id.ireq_id',
                'id.ireq_assigned_to1_reason',
                'id.invent_code',
                'vi1.official_name',
                'id.ireq_status',
                'vi2.official_name',
                'id.ireqd_id',
                'lr.lookup_desc',
                'id.ireq_remark',
                'id.ireq_desc', 
                'id.ireq_qty',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),
                'llr.lookup_desc')
            ->orderBy('id.ireqd_id','ASC')
            ->get();
            return $dtl;
    }
    public static function detailDone($code){
        $data = DB::table('ireq_dtl as id')
            ->select('id.ireq_assigned_to1','id.ireq_attachment','id.ireqd_id','lr.lookup_desc as ireq_type', 
                    DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),'id.ireq_remark','id.ireq_qty','id.ireq_desc')
            ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
            ->LEFTJOIN('catalog_refs as cr',function ($join) {
                $join->on('id.invent_code','cr.catalog_id');
            })
            ->LEFTJOIN('catalog_refs as crs',function ($join) {
                $join->on('cr.parent_id','crs.catalog_id');
            })
            ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->where('imm.ireq_id',$code)
            ->where('id.ireq_assigned_to1',Auth::user()->usr_id)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->orderBy('id.ireqd_id','ASC')
            ->get();
        return $data;
    }
    public static function cekStatusPenugasan($code){
        $data = DB::table('ireq_dtl as id')
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
            ->LEFTJOIN('vpekerja_ict vi', function($join) {
                $join->on('im.ireq_assigned_to1','vi.usr_id')
                      ->whereNotNull('im.ireq_assigned_to1');
            })
            ->LEFTJOIN('vpekerja_ict vii', function($join) {
                $join->on('im.ireq_assigned_to2', 'vii.usr_id')
                      ->whereNull('im.ireq_assigned_to1');
            })
            ->LEFTJOIN('mng_users mu','im.ireq_requestor','mu.usr_id')
            ->LEFTJOIN('location_refs loc','im.ireq_loc','loc.loc_code')
            ->LEFTJOIN('supervisor_refs sr','im.ireq_spv','sr.spv_id')
            ->LEFTJOIN('mng_users mus','sr.spv_name','mus.usr_id')
            ->LEFTJOIN('mng_users usr','im.ireq_user','usr.usr_id')
            ->SELECT(
                'mus.usr_email as spv_mail',
                'loc.loc_email',
                'mu.usr_fullname',
                'sr.spv_name',
                'mu.usr_email as user_mail',
                'im.ireq_no',
                'id.ireqd_id',
                'vr.name as ireq_bu',
                'im.ireq_id',
                'mu.usr_division as div_name', 
                DB::raw("TO_CHAR(im.ireq_date, 'dd Mon YYYY HH24:MM') as ireq_date"),
                'mu.usr_fullname as ireq_requestor',
                'usr.usr_fullname as ireq_user',
                DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_code"),
                'id.ireq_qty',
                'lrfs.lookup_desc as ireq_type',
                'id.ireq_remark')
            ->WHERE('im.ireq_id',$code)
            ->ORDERBY('id.ireqd_id','ASC')
            ->get();
        if(env('APP_ENV') != 'local'){
            $mail_user = $data[0]->usr_email;
        } else {
            $mail_user = 'adhitya.saputro@emp.id';
        }
        SendNotifInProgress::dispatchAfterResponse($mail_user,$data);
        $message = 'Success';
        return $message;

    }
    public static function detailRequestForAssignment($code){
        $dtl = DB::table('ireq_dtl as id')
        ->select(DB::raw('COUNT(id.ireq_note_personnel) as count_note'),'id.ireq_attachment','id.ireq_qty','id.ireq_status as status','id.ireq_remark','id.ireqd_id','id.ireq_note_personnel',
            'lr.lookup_desc as ireq_type','llr.lookup_desc as ireq_status','id.ireq_desc',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as name"),
            DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->leftJoin('lookup_refs as lr',function ($join) {
            $join->on('id.ireq_type','lr.lookup_code')
                  ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%']);
        })
        ->leftJoin('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                  ->whereRaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->where('id.ireq_id',$code)
        ->where(function($query){
            return $query
            ->where('id.ireq_status','NT')
            ->Orwhere('id.ireq_status','RT')
            ->Orwhere('id.ireq_status','T');
        })
        ->groupBy('id.ireq_attachment','id.ireq_qty','id.ireq_status','id.ireq_remark','id.ireqd_id','id.ireq_note_personnel',
        'lr.lookup_desc','llr.lookup_desc','id.ireq_desc',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name)"),
        DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1)"))
        ->orderBy('id.ireqd_id','ASC')
        ->get();
            return $dtl;
    }
    public static function FindDetailRequest($ireq,$code){
        $data = DB::table('ireq_dtl as id')
            ->select('id.ireqd_id','id.ireq_attachment','id.ireq_type','id.invent_code','cr.catalog_name as invent_desc','id.ireq_desc','id.ireq_qty','id.ireq_remark','imm.ireq_no')
            ->leftjoin('catalog_refs as cr','id.invent_code','cr.catalog_id')
            ->leftjoin('ireq_mst as imm','id.ireq_id','imm.ireq_id')
            ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
            ->where('id.ireqd_id',$ireq)
            ->where('id.ireq_id',$code)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->first();
        return $data;
    }
    public static function detailVerification($code){
        $data = DB::table('ireq_dtl as id')
        ->select('id.*','lr.lookup_desc as ireq_type',DB::raw("(crs.catalog_name ||' - '|| cr.catalog_name) as invent_desc"))
        ->leftjoin('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->LEFTJOIN('catalog_refs as cr',function ($join) {
            $join->on('id.invent_code','cr.catalog_id');
        })
        ->LEFTJOIN('catalog_refs as crs',function ($join) {
            $join->on('cr.parent_id','crs.catalog_id');
        })
        ->where('id.ireq_id',$code)
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderby('id.ireqd_id','ASC')
        ->get();
        return $data;
    }

    
    
}
