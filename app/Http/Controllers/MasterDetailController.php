<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Model\MasterDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MasterDetailController extends Controller
{
    protected $newCreation;
    protected $newUpdate;
    protected $to;
    public function __construct(){
        $date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->to = "/master-peripheral-detail";
    }
    function index($code){
        $dtl = MasterDetail::select('invent_sn','invent_code_dtl','invent_code','invent_lokasi_previous','invent_lokasi_update','invent_pengguna_previous','invent_pengguna_update')
        ->where('invent_code',$code)
        ->orderBy('creation_date','DESC')
        ->get();

        $mas = DB::table('invent_mst as im')
        ->leftjoin('lookup_refs as lr','im.invent_brand','lr.lookup_code')
        ->select(DB::raw("(im.invent_desc ||' - '|| lr.lookup_desc ||' - '|| im.invent_type) as name"))
        ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])
        ->where('im.invent_code',$code)
        ->first();
        return response()->json(['dtl'=>$dtl,'mas'=>$mas],200);
    }
    function addDetail($code){
        $dtl = DB::table('invent_mst as im')
        ->leftJoin('lookup_refs as lrs',function ($join) {
            $join->on('im.invent_brand','lrs.lookup_code')
                  ->whereRaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->select('im.*','lrs.lookup_desc as tes')
        ->where('im.invent_code',$code)
        ->first();
        return response()->json($dtl);
    }
    function saveDetail(Request $request){
        $message = [
            'invent_sn.required' => 'S/N Belum Diisi',
            'invent_sn.unique' => 'S/N Sudah Ada',
            'invent_kondisi.required'=>'Kondisi Belum Diisi',
            'invent_bu.required' => 'Bisnis Unit Belum Diisi'
        ];
        $request->validate([
            'invent_sn' => 'required|unique:invent_dtl,invent_sn',
            'invent_kondisi'=>'required',
            'invent_bu'=> 'required'
        ],$message);

        if($request->invent_tgl_perolehan == 'null'){
            $newDate = '';   
        }
        else{
            $newDate = Carbon::parse($request->invent_tgl_perolehan)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        }
        if($request->image){
            $image= $request->image;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $fotoo = str_replace($replace, '', $image);
            $foto= str_replace(' ', '+', $fotoo); 
            $nama_file = time().".".$extension;
            Storage::disk('master_peripheral')->put($nama_file, base64_decode($foto));
        }
        else{
            $nama_file = '';
        }
        $mas = MasterDetail::Create([
            'invent_code' => $request->invent_code,
            'invent_sn' => $request->invent_sn,
            'invent_tgl_perolehan' => $newDate,
            'invent_lama_garansi' => $request->invent_garansi,
            'invent_kondisi' => $request->invent_kondisi,
            'creation_date' => $this->newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name' => "MasterDetail_Save",
            'invent_photo' => $nama_file,
            'invent_bu' => $request->invent_bu,
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return response()->json($msg);
    }
    function editDetail($code){
        $mas = DB::table('invent_dtl as id')
        ->leftjoin('invent_mst as im','id.invent_code','im.invent_code')
        ->leftJoin('lookup_refs as lrs',function ($join) {
            $join->on('im.invent_brand','lrs.lookup_code')
                  ->whereRaw('LOWER(lrs.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%']);
        })
        ->leftJoin('divisi_refs as dr',function ($join) {
            $join->on('id.invent_divisi_update','dr.div_id');
        })
        ->leftJoin('divisi_refs as drs',function ($join) {
            $join->on('id.invent_divisi_previous','drs.div_id');
        })
        ->leftJoin('vcompany_refs as vr',function ($join) {
            $join->on('id.invent_bu_update','vr.company_code');
        })
        ->leftJoin('vcompany_refs as vrs',function ($join) {
            $join->on('id.invent_bu_previous','vrs.company_code');
        })
        ->select('im.invent_code','im.invent_type','id.invent_photo','im.invent_desc','lrs.lookup_desc as invent_brand',
        'id.invent_sn','id.invent_tgl_perolehan','id.invent_lama_garansi','id.invent_kondisi','id.invent_bu',
        'id.invent_lokasi_previous','id.invent_lokasi_update','vrs.name as invent_bu_previous','vr.name as invent_bu_update',
        'id.invent_pengguna_previous','id.invent_pengguna_update','dr.div_name as invent_divisi_update','drs.div_name as invent_divisi_previous')
        ->where('id.invent_code_dtl',$code)
        ->first();
        return response()->json($mas);
    }
    function updateDetail(Request $request,$code){
        $newDate = Carbon::parse($request->invent_tgl_perolehan)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $mas = MasterDetail::find($code);
        if($request->image) {
            if($mas->invent_photo){
                unlink(Storage_path('app/public/master_peripheral/'.$mas->invent_photo));
            }
            $image= $request->image;
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $fotoo = str_replace($replace, '', $image);
            $foto= str_replace(' ', '+', $fotoo); 
            $nama_file = time().".".$extension;
            Storage::disk('master_peripheral')->put($nama_file, base64_decode($foto));
        }else{
            $nama_file = $mas->invent_photo;
        }
            $mas->invent_sn =$request->invent_sn;
            $mas->invent_tgl_perolehan =$newDate;
            $mas->invent_lama_garansi = $request->invent_lama_garansi;
            $mas->invent_kondisi = $request->invent_kondisi;
            $mas->last_update_date = $this->newUpdate;
            $mas->last_updated_by = Auth::user()->usr_name;
            $mas->program_name = "MasterDetail_Update";
            $mas->invent_bu = $request->invent_bu;
            $mas->invent_photo = $nama_file;
            $mas->save();
        
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
 
        return response()->json($msg);
    }
    function deleteDetail($invent_code_dtl){
        $mas = MasterDetail::find($invent_code_dtl);
        if($mas->invent_photo){
            unlink(Storage_path('app/public/master_peripheral/'.$mas->invent_photo));
        }
        $mas->delete();
            return response()->json('Successfully deleted');
    }
    
}
