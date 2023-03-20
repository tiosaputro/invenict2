<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Mng_user;

class LocationController extends Controller
{
    protected $to;
    protected $userMenu;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/referensi-location";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function index(){
            $loc = Location::Select('loc_code','loc_desc','loc_email')->orderBy('loc_desc','ASC')->get();
            return json_encode($loc);
    }
    function save(Request $request){
        $message = [
            'loc_code.unique' => 'Kode Sudah Ada',
            'loc_code.required'=>'Kode Belum Diisi',
            'loc_desc.required' => 'Deskripsi Belum Diisi',
            'loc_email.required' => 'Email Belum Diisi',
        ];
        $request->validate([
            'loc_code' => 'required|unique:location_refs,loc_code',
            'loc_desc'=>'required',
            'loc_email'=>'required',
        ],$message);
        
        $loc = Location::Create([
            'loc_code' => $request->loc_code,
            'loc_desc' => $request->loc_desc,
            'loc_email' => $request->loc_email,
            'creation_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'created_by' => Auth::user()->usr_name,
            'program_name' => "Location_Save",
        ]);
        return ResponseFormatter::success($loc,'Successfully Created Location');
    }
    function edit($code){
        $loc = Location::find($code);
        return json_encode($loc);
    }
    function update(Request $request,$code){
        $message = [
            'loc_desc.required' => 'Deskripsi Belum Diisi',
            'loc_email.required' => 'Email Belum Diisi'
        ];
        $request->validate([
            'loc_desc'=>'required',
            'loc_email'=>'required',
        ],$message);
        $loc = Location::find($code);
        $loc->loc_desc = $request->loc_desc;
        $loc->loc_email = $request->loc_email;
        $loc->last_updated_by = Auth::user()->usr_name;
        $loc->last_update_date = Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $loc->program_name = "LocationController@update";
        $loc->save();
        
        return ResponseFormatter::success($loc,'Successfully Updated Location');
    }
    function delete($loc_code){
        $loc = Location::find($loc_code)->delete();
        return ResponseFormatter::success($loc,'Successfully Deleted Location');
    }
}
