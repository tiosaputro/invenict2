<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PekerjaServices
{
    public function getPekerja(){
        if(Auth::user()->usr_loc == "OJ"){
            $loc = ["OJ"];
        }
        if(Auth::user()->usr_loc == "OB" OR Auth::user()->usr_loc == "FB"){
            $loc = ["OB","FB"];
        }
        if(Auth::user()->usr_loc == "OK" OR Auth::user()->usr_loc == "FK" ){
            $loc = ["OK","FK"];
        }
        $data = DB::table('vpekerja_ict')->select('usr_id as code','official_name as name')
            ->whereIN('loc_code',$loc)
            ->orderBy('name','ASC')
            ->get();
        return $data;
    }
}