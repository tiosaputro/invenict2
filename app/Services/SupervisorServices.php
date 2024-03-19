<?php

namespace App\Services;

use App\Models\Mng_User;
use App\Models\SupervisorModel;
use Illuminate\Support\Facades\DB;
class SupervisorServices
{
    public function getAllData(){
        $data = SupervisorModel::Query();
        $data->LEFTJOIN('mng_users mu','supervisor_refs.spv_name','mu.usr_id');
        $data->SELECT('spv_id','mu.usr_fullname as spv_name','spv_job_title',DB::raw("(spv_job_title ||' - '|| mu.usr_fullname ) as spvnamejob"));
        return $data->get();
    }
    public function addSpv(){
        $data = Mng_User::all();
        return $data;
    }
}