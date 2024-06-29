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
        $data->LEFTJOIN('mng_users usr','ireq_mst.ireq_spv','usr.usr_id');
        $data->SELECT('ireq_mst.ireq_id','ireq_mst.ireq_status');
        $data->where('usr.usr_id',Auth::user()->usr_id);
        return $data->get();
    }
    public function countPersonnel(){
        $data = IctDetail::all();
        return $data;
    }
}