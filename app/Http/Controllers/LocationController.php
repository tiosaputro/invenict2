<?php

namespace App\Http\Controllers;

use App\Model\Location;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Model\Mng_usr_roles;
use App\Model\Mng_role_menu;

class LocationController extends Controller
{
    protected $date;
    protected $newCreation;
    protected $newUpdate;
    public function __construct(){
        $this->location = "/referensi-location";
        $date = Carbon::now();
        $this->date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    function index(){
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->location)){
            $loc = Location::Select('loc_code','loc_desc','loc_email')->orderBy('loc_desc','ASC')->get();
            return json_encode($loc);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
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
            'creation_date' => $this->newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name' => "Location_Save",
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Succes Created'
        ]);
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
        $loc->last_update_date = $this->newUpdate;
        $loc->program_name = "LocationController@update";
        $loc->save();
        return response()->json([
            'success' => true,
            'message' => 'Success Updated',
        ]);
    }
    function delete($loc_code){
        $loc = Location::find($loc_code);
        $loc->delete();
        return json_encode('Success Deleted');
    }
    function getLocation(){
        $loc = Location::select('loc_code as code','loc_desc as name')->orderBy('loc_desc','ASC')->get();
        return json_encode($loc);
    }
}
