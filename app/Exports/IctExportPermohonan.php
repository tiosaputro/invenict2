<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportPermohonan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($usr_name) {
        $this->usr_name = $usr_name;
    }
    public function view(): View{
        return view('excel/Laporan_Ict_Permohonan', [ 'Ict' => DB::table('ireq_mst as id')
        ->LEFTJOIN('vcompany_refs as vr','id.ireq_bu','vr.company_code')
        ->LEFTJOIN('ireq_dtl as idm','id.ireq_id','idm.ireq_id')
        ->LEFTJOIN('lookup_refs as lr','id.ireq_status','lr.lookup_code')
        ->LEFTJOIN('lookup_refs as llr',function ($join) {
            $join->on('id.ireq_status','llr.lookup_code')
                ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%']);
        })
        ->LEFTJOIN('divisi_refs as dr','id.ireq_divisi_user','dr.div_id')
        ->SELECT(DB::raw("TO_CHAR(id.ireq_date,' dd Mon YYYY') as ireq_date"),'vr.name as ireq_bu','id.ireq_id','id.ireq_status as status','id.ireq_no','id.ireq_date','id.ireq_user','dr.div_name','id.ireq_requestor',
                DB::raw('count(DISTINCT(idm.ireq_id)) as count'),'llr.lookup_desc as ireq_status')
        ->WHERE('id.created_by',$this->usr_name)
        ->WHERE('id.ireq_status','P')
        ->groupBy('vr.name','llr.lookup_desc','id.ireq_status','id.ireq_id','id.ireq_no','id.ireq_date','id.ireq_user','id.creation_date','dr.div_name','id.ireq_status','id.ireq_requestor')
        ->ORDERBY('id.ireq_date','DESC')
        ->get()
        ]);
    }
}
