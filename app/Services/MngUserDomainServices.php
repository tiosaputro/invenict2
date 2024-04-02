<?php

namespace App\Services;

use App\Models\UserDomain;
use Illuminate\Support\Facades\DB;

class MngUserDomainServices
{
    public function getAllData(){
        $data = UserDomain::orderBy('usr_fullname')->get();
        return $data;
    }
    public function addSpv(){
        
    }
    public function deleteDirectory()
    {
        return UserDomain::truncate();
    }
}