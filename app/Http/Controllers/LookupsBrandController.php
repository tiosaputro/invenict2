<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lookup_Refs;
use App\Models\Mng_user;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseFormatter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LookupsBrandController extends Controller
{
    protected $userMenu;
    protected $to;
    function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/referensi-brand";
        $this->middleware(function ($request, $next) {
          $this->userMenu = Mng_User::menu();
            if($this->userMenu->contains($this->to)){    
                return $next($request);
            } else {
                return response(["message"=>"Cannot Access"],403);
            }
        });
    }
    function lookupBrand(){
            $ref = Lookup_Refs::select('lookup_code','lookup_type',DB::raw("CASE WHEN lookup_status = 'T' Then 'Aktif' WHEN lookup_status = 'F' Then 'Tidak Aktif' end as lookup_status"),'lookup_desc')
            ->where('lookup_type','Merk')
            ->get();
            return ResponseFormatter::success($ref,'Successfully get data');
        }
    function saveBrand(Request $request) 
        {
            $message = [
                'lookup_code.unique' => 'Kode Sudah Ada',
                'lookup_code.required'=>'Kode Belum Diisi',
                'lookup_type.unique' => 'Tipe Sudah Ada',
                'lookup_type.required' => 'Tipe Belum Diisi',
                'lookup_desc.required' => 'Deskripsi Belum Diisi',
                'lookup_status.required' => 'Status Belum Diisi'
            ];
            $request->validate([
                'lookup_code' => [
                    'required',
                    Rule::unique('lookup_refs')->where(function ($query) use($request)
                {
                    return $query->where('lookup_code',$request->lookup_code)
                                 ->where('lookup_type',$request->lookup_type);
                }),
                ],
                'lookup_type' => [
                    'required',
                    Rule::unique('lookup_refs')->where(function ($query) use($request) 
                {
                    return $query->where('lookup_code',$request->lookup_code)
                                 ->where('lookup_type',$request->lookup_type);
                }),
                ],
                'lookup_desc'=>'required',
                'lookup_status'=>'required',
            ],$message);
            
            $createLookup = Lookup_Refs::Create([
                'lookup_code' => $request->lookup_code,
                'lookup_type' => $request->lookup_type,
                'lookup_desc' => $request->lookup_desc,
                'lookup_status' => $request->lookup_status,
                'creation_date' => now(),
                'created_by' => Auth::user()->usr_id,
                'program_name' => "Lookups_Save",
            ]);
            return ResponseFormatter::success($createLookup,'Successfully Created Lookup');
    }
    function editBrand($code,$type)
        {
            $ref = Lookup_Refs::Where('lookup_code', $code)->where('lookup_type',$type)->first();
            return response()->json($ref);
    }
    function updateBrand(Request $request,$code,$type)
        {
            $message = [
                'lookup_desc.required' => 'Deskripsi Belum Diisi',
                'lookup_status.required' => 'Status Belum Diisi'
            ];
            $request->validate([
                'lookup_desc'=>'required',
                'lookup_status'=>'required',
            ],$message);
            $ref = DB::table('lookup_refs')
            ->where('lookup_code',$code)
            ->where('lookup_type',$type)
            ->update([
                'lookup_desc' => $request->lookup_desc,
                'lookup_status' => $request->lookup_status,
                'last_update_date' => now(),
                'last_updated_by' => Auth::user()->usr_id,
                'program_name' => "Lookups_Update",
            ]);
            return ResponseFormatter::success($ref,'Successfully Updated Lookup');
    }
    function deleteBrand($lookup_code,$lookup_type){
        $ref = Lookup_Refs::where('lookup_code',$lookup_code)->where('lookup_type',$lookup_type)->delete();
            
        return ResponseFormatter::success($ref,'Successfully Deleted Data Lookup');
    }
}
