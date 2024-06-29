<?php

namespace App\Services;

use App\Models\UserDomain;
use Illuminate\Support\Facades\DB;

class UserDomainServices
{
    public function getAllData(){
        $data = UserDomain::Query();
        $data->SELECT('usr_domain',DB::raw("(usr_fullname ||' - '|| usr_division ) as name"));
        $data->orderBy('usr_fullname','ASC');
        return $data->get();
    }
}