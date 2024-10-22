<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\MasterDetail;

class MasterDetailServices
{
    public function getDataWithFilter($code = null){
        $data = MasterDetail::query();
        $data->SELECT('invent_sn', 'invent_code_dtl', 'invent_code', 'invent_lokasi_previous', 'invent_lokasi_update', 'usr.usr_fullname as invent_pengguna_update', 'mu.usr_fullname as invent_pengguna_previous');
        $data->LEFTJOIN('mng_users mu','invent_dtl.invent_pengguna_previous','mu.usr_id');
        $data->LEFTJOIN('mng_users usr','invent_dtl.invent_pengguna_update','usr.usr_id');
        if(!empty($code)){
            $data->where('invent_code', $code);
        }
        $data->OrderBy('invent_dtl.creation_date','DESC');
        return $data->get();
    }
}
