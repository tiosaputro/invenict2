<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Location;
use App\Models\Mng_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct()
    {
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/referensi-location";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    public function index()
    {
        $loc = Location::Select('loc_code', 'loc_desc', 'loc_email')->orderBy('loc_desc', 'ASC')->get();
        return json_encode($loc);
    }
    public function save(Request $request)
    {
        $message = [
            'loc_code.unique' => 'Kode Sudah Ada',
            'loc_code.required' => 'Kode Belum Diisi',
            'loc_desc.required' => 'Deskripsi Belum Diisi',
            'loc_email.required' => 'Email Belum Diisi',
        ];
        $request->validate([
            'loc_code' => 'required|unique:location_refs,loc_code',
            'loc_desc' => 'required',
            'loc_email' => 'required',
        ], $message);

        $loc = Location::Create([
            'loc_code' => $request->loc_code,
            'loc_desc' => $request->loc_desc,
            'loc_email' => $request->loc_email,
            'creation_date' => now(),
            'created_by' => Auth::user()->usr_id,
            'program_name' => "Location_Save",
        ]);
        return ResponseFormatter::success($loc, 'Successfully Created Location');
    }
    public function edit($code)
    {
        $loc = Location::find($code);
        return json_encode($loc);
    }
    public function update(Request $request, $code)
    {
        $message = [
            'loc_desc.required' => 'Deskripsi Belum Diisi',
            'loc_email.required' => 'Email Belum Diisi',
        ];
        $request->validate([
            'loc_desc' => 'required',
            'loc_email' => 'required',
        ], $message);
        $loc = Location::find($code);
        $loc->loc_desc = $request->loc_desc;
        $loc->loc_email = $request->loc_email;
        $loc->last_updated_by = Auth::user()->usr_id;
        $loc->last_update_date = now();
        $loc->program_name = "LocationController@update";
        $loc->save();

        return ResponseFormatter::success($loc, 'Successfully Updated Location');
    }
    public function delete($loc_code)
    {
        $loc = Location::find($loc_code)->delete();
        return ResponseFormatter::success($loc, 'Successfully Deleted Location');
    }
}
