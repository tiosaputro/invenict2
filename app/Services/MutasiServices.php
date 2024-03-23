<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Mutasi;

class MutasiServices
{
    public function getDataWithFilter($code = null){
        $data = Mutasi::query();
        $data->select(
            DB::raw("(imm.invent_desc ||'-'|| lrs.lookup_desc ||'-'|| imm.invent_type ) as invent_desc"),
            'usr.usr_fullname as imutasi_pengguna',
            'usr.usr_division as imutasi_divisi',
            'usr.usr_nm_perush as imutasi_bu',
            'invent_mutasi.imutasi_tgl_dari',
            'invent_mutasi.imutasi_tgl_sd',
            'invent_mutasi.imutasi_lokasi',
            'id.invent_code_dtl',
            'invent_mutasi.imutasi_id',
            'id.invent_code',
            'id.invent_sn'
        );
        $data->leftjoin('invent_dtl id','invent_mutasi.invent_code_dtl','id.invent_code_dtl');
        $data->leftjoin('invent_mst imm','id.invent_code','imm.invent_code');
        $data->leftjoin('mng_users usr','invent_mutasi.imutasi_pengguna','usr.usr_id');
        $data->LEFTJOIN('lookup_refs as lrs',function ($join) {
            $join->on('imm.invent_brand','lrs.lookup_code')
                 ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        });
        if(!empty($code)){
            $data->where('invent_mutasi.imutasi_id',$code);
        }
        $data->orderBy('invent_mutasi.creation_date','DESC');
        return $data->get();
    }
}