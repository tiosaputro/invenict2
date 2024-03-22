<?php

namespace App\Services;

use App\Models\Ict;
use App\Models\IctDetail;
use Illuminate\Support\Facades\Auth;

class DashboardServices
{
    public function CountDataDashboard(){
        $data = Ict::All();
        return $data;
    }
    public function countHigherlevel(){
        $data = Ict::query();
        $data->LEFTJOIN('supervisor_refs sr','ireq_mst.ireq_spv','sr.spv_id');
        $data->SELECT('ireq_mst.ireq_id','ireq_mst.ireq_status');
        $data->where('sr.spv_name',Auth::user()->usr_id);
        return $data->get();
    }
    public function countPersonnel(){
        $data = IctDetail::all();
        return $data;
    }
}