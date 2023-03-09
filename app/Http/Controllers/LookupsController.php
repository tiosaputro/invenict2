<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Lookup_Refs;
use App\Model\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LookupExport;
use Illuminate\Validation\Rule;
use App\Model\Mng_usr_roles;
use App\Model\Mng_role_menu;
use App\Helpers\ResponseFormatter;

class LookupsController extends Controller
{
    protected $kategori;
    protected $lookup;
    protected $requestor;
    protected $brand;
    public function __construct(){
        $this->brand = "/referensi-brand";
        $this->lookup = "/referensi-lookups";
        $this->kategori = "/referensi-kategori";
        $this->requestor='/ict-request';
    }
    public function index()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->lookup)){
            $ref = DB::Table('v_lookup_refs')->get();
            return response()->json($ref);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function lookupBrand(){
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->brand)){
            $ref = DB::table('lookup_refs')
            ->select('lookup_code','lookup_type',DB::raw("CASE WHEN lookup_status = 'T' Then 'Aktif' WHEN lookup_status = 'F' Then 'Tidak Aktif' end as lookup_status"),'lookup_desc')
            ->where('lookup_type','Merk')
            ->get();
            return response()->json($ref);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    function lookupKategori(){
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->kategori)){
            $ref = DB::table('lookup_refs')
            ->select('lookup_code','lookup_type',DB::raw("CASE WHEN lookup_status = 'T' Then 'Aktif' WHEN lookup_status = 'F' Then 'Tidak Aktif' end as lookup_status"),'lookup_desc')
            ->where('lookup_type','Kat_Peripheral')
            ->get();
            return json_encode($ref);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    public function save(Request $request) 
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
            'creation_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'created_by' => Auth::user()->usr_name,
            'program_name' => "Lookups_Save",
        ]);
        return ResponseFormatter::success($createLookup,'Successfully Created Lookup');
    }
    public function edit($code,$type)
    {
        $ref = Lookup_Refs::Where('lookup_code', $code)->where('lookup_type',$type)->first();
        return response()->json($ref);
    }
    public function update(Request $request,$code,$type)
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
            'last_update_date' => Carbon::parse(Carbon::now())->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'last_updated_by' => Auth::user()->usr_name,
            'program_name' => "Lookups_Update",
        ]);
        return ResponseFormatter::success($ref,'Successfully Updated Lookup');
    }
    public function delete($lookup_code,$lookup_type)
    {
        $ref = Lookup_Refs::where('lookup_code',$lookup_code)->where('lookup_type',$lookup_type)->delete();
        
        return ResponseFormatter::success($ref,'Successfully Deleted Lookup');
    }
    public function cetak_pdf()
    {
        $ref = DB::table('lookup_refs as ls')
        ->Select('ls.*', DB::raw("CASE WHEN ls.lookup_status = 'T' Then 'Aktif' WHEN ls.lookup_status = 'F' Then 'Tidak Aktif' end as lookup_status "))
        ->orderBy('lookup_type','ASC')
        ->get();
        return view('pdf/Laporan_Lookups', compact('ref'));
    }
    public function cetak_excel()
    {
        return Excel::download(new LookupExport,'Laporan_Lookups.xlsx');
    }
    public function getMerk()
    {
        $merk = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($merk);
    }
    public function getKondisi()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('kondisi')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }
    public function getStatus()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_status')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }
    public function getStatusIct()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->where(function($query){
            return $query
            ->where('lookup_code','D')
            ->orwhere('lookup_code','T');
        })
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('ict_status')).'%'])
        ->orderBy('lookup_desc','DESC')
        ->get();
        return response()->json($ref);
    }
    Public function getType()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }
    
    Public function getAddReq()
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->requestor)){
            $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();

            $bisnis = DB::table('v_company_refs')->get();

            $divisi = DB::table('divisi_refs')
            ->select('div_id as code',DB::raw("(div_code ||'-'|| div_name) as name"))
            ->orderBy('div_name','ASC')
            ->get();

            $priority = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
            ->where('lookup_status','T')
            ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_prio')).'%'])
            ->orderBy('lookup_desc','ASC')
            ->get();
            $fullname = Auth::user()->usr_fullname;

            return response()->json(['ref'=>$ref,'bisnis'=>$bisnis,'prio'=>$priority,'divisi'=>$divisi,'fullname'=>$fullname],200);
        }else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    Public function getAddDetail()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('req_type')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();

        return response()->json(['ref'=>$ref],200);
    }
    public function getAddMaster()
    {
        $merk = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();

        $kondisi = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('kondisi')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();

        $nama = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('kat_peripheral')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();

        $bisnis = DB::table('v_company_refs')->get();
        
        return response()->json(['merk'=>$merk,'kondisi'=>$kondisi,'bisnis'=>$bisnis,'nama'=>$nama],200);
    }
    public function getAddPemb()
    {
        $uang = Lookup_Refs::Select('lookup_code as code',DB::raw("(lookup_code ||'-'|| lookup_desc) as name"))
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('mata uang')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        

        $metode = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('pay methode')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();

        $supp = Supplier::Select('suplier_code as code',DB::raw("(suplier_code ||'-'|| suplier_name) as name"))
        ->orderBy('suplier_code','ASC')
        ->get();

        $user = Auth::user()->usr_name;
        
        return response()->json(['uang'=>$uang,'metode'=>$metode,'supp'=>$supp,'user'=>$user],200);
    }
    Public function getMataUang()
    {
        $ref = Lookup_Refs::Select('lookup_code as code',DB::raw("(lookup_code ||'-'|| lookup_desc) as name"))
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('mata uang')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }
    Public function getMethodePurch()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('pay methode')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }
    Public function getSatuan()
    {
        $ref = Lookup_Refs::Select('lookup_code as code','lookup_desc as name')
        ->where('lookup_status','T')
        ->whereRaw('LOWER(lookup_type) LIKE ? ',[trim(strtolower('satuan')).'%'])
        ->orderBy('lookup_desc','ASC')
        ->get();
        return response()->json($ref);
    }

}
