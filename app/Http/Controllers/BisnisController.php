<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class BisnisController extends Controller
{
    public function getBisnis()
    {
        $bisnis = DB::table('v_company_refs')->get();
        return response()->json($bisnis);
    }
}
