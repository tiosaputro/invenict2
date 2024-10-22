<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Master;

class MasterServices
{
    public function getDataWithFilter($code = null){
        $data = Master::query();
        $data->LEFTJOIN('invent_dtl as id', 'invent_mst.invent_code', 'id.invent_code');
        $data->leftjoin('lookup_refs as lr', 'invent_mst.invent_brand', 'lr.lookup_code');
        $data->SELECT('invent_mst.invent_code', DB::RAW('COUNT(id.invent_code) as countstok'),
                'invent_mst.invent_type', 'lr.lookup_desc as invent_brand','invent_mst.invent_desc');
        $data->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%']);
        $data->groupBy('invent_mst.invent_code', 'invent_mst.invent_type', 'lr.lookup_desc', 'invent_mst.invent_desc');
        $data->orderBy('invent_mst.invent_code', 'ASC');
        return $data->get();
    }
}
