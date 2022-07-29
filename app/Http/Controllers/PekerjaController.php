<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;

class PekerjaController extends Controller
{
    public function getPekerja()
    {
        if(Auth::user()->usr_loc == 'OJ'){
            $pekerja = DB::table('vpekerja_ict')->select('employee_number as code','official_name as name')
            ->where('loc_code','OJ')
            ->orderBy('name','ASC')
            ->get();
            return json_encode($pekerja);
        }
        else if(Auth::user()->usr_loc == 'OB' OR Auth::user()->usr_loc == 'FB'){
            $pekerja = DB::table('vpekerja_ict')->select('employee_number as code','official_name as name')
            ->where('loc_code','OB')
            ->orwhere('loc_code','FB')
            ->orderBy('name','ASC')
            ->get();
            return json_encode($pekerja);
        }
        else if (Auth::user()->usr_loc == 'OK' OR Auth::user()->usr_loc == 'FK'){
            $pekerja = DB::table('vpekerja_ict')->select('employee_number as code','official_name as name')
            ->where('loc_code','OK')
            ->orwhere('loc_code','FK')
            ->orderBy('name','ASC')
            ->get();
            return json_encode($pekerja);
        }
    }
}
