<?php

namespace App\Exports;

use DB;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IctExportReviewerSelesai implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if(Auth::user()->usr_loc == 'OJ'){
        return view('excel/Laporan_Ict_Sudah_Dikerjakan', [ 'Ict' => DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            'lrs.lookup_desc as name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','C')
        ->WHERE('imm.ireq_loc','OJ')
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get()
        ]);
    }else if (Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
        return view('excel/Laporan_Ict_Sudah_Dikerjakan', [ 'Ict' => DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            'lrs.lookup_desc as name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','C')
        ->WHERE(function ($query){
            return $query
            ->WHERE('imm.ireq_loc','OB')
            ->OrWhere('imm.ireq_loc','FB');
        })
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get()
        ]);
    }else if(Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
        return view('excel/Laporan_Ict_Sudah_Dikerjakan', [ 'Ict' => DB::table('ireq_dtl as id')
        ->SELECT('imm.ireq_no','id.ireq_desc','id.ireq_qty','id.ireq_remark','id.ireqd_id','dr.div_name',
            'imm.ireq_user','llr.lookup_desc as ireq_status', 'imm.ireq_requestor',
            'vr.name as ireq_bu','lr.lookup_desc as ireq_type',DB::raw("TO_CHAR(imm.ireq_date,' dd Mon YYYY') as ireq_date"),
            'lrs.lookup_desc as name',DB::raw("COALESCE(id.ireq_assigned_to2,id.ireq_assigned_to1) AS ireq_assigned_to"))
        ->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('id.invent_code','lrs.lookup_code')
                ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%']);
        })
        ->LEFTJOIN('lookup_refs as lr','id.ireq_type','lr.lookup_code')
        ->join('ireq_mst as imm','id.ireq_id','imm.ireq_id')        
        ->LEFTJOIN('lookup_refs as llr','imm.ireq_status','llr.lookup_code')
        ->LEFTJOIN('vcompany_refs as vr','imm.ireq_bu','vr.company_code')
        ->LEFTJOIN('divisi_refs as dr','imm.ireq_divisi_user','dr.div_id')
        ->WHERE('id.ireq_status','C')
        ->WHERE(function ($query){
            return $query
            ->WHERE('imm.ireq_loc','OK')
            ->OrWhere('imm.ireq_loc','FK');
        })
        ->WHERERaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->WHERERaw('LOWER(llr.lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->ORDERBY('imm.ireq_date','DESC')
        ->ORDERBY('id.ireqd_id','ASC')
        ->get()
        ]);
    }
    }
}
