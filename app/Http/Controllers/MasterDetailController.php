<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use DB;
use App\MasterDetail;
use Auth;
use Illuminate\Http\Request;

class MasterDetailController extends Controller
{
    function index($code){
        $dtl = MasterDetail::select('invent_sn','invent_lokasi_previous','invent_lokasi_update','invent_pengguna_previous','invent_pengguna_update')
        ->where('invent_code',$code)->get();
        return response()->json($dtl);
    }
    function addDetail($code){
        $dtl = DB::table('invent_mst as im')
        ->leftJoin('lookup_refs as lrs',function ($join) {
            $join->on('im.invent_brand','lrs.lookup_code')
                  ->whereRaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->select('im.*','lrs.lookup_desc as tes')
        ->where('im.invent_code',$code)
        ->get();
        return response()->json($dtl);
    }
}
