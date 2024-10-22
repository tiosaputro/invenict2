<?php

namespace App\Http\Controllers;

use App\Exports\MasterExport;
use App\Helpers\ResponseFormatter;
use App\Models\Lookup_Refs;
use App\Models\Master;
use App\Models\Mng_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\MasterServices;

class MasterController extends Controller
{
    protected $userMenu;
    protected $to;
    protected $masterServices;
    public function __construct(MasterServices $services){
        $this->masterServices = $services;
        $this->middleware(['auth:sanctum', function ($request, $next) {
            $this->to = "/master-peripheral";
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        }]);
    }
    public function index(){
        $mas = $this->masterServices->getDataWithFilter();
        return ResponseFormatter::success($mas, 'Successfully get data');
    }
    public function getAddMaster(){
        $data['merk'] = Lookup_Refs::Merk();
        $data['kondisi'] = Lookup_Refs::Kondisi();
        $data['nama'] = Lookup_Refs::Kategori_peripheral();
        $data['bisnis'] = DB::table('v_company_refs')->get();

        return ResponseFormatter::success($data, 'Successfully get data');
    }
    public function save(Request $request){
        $message = [
            'nama.required' => 'Nama Peripheral Belum Diisi',
            'merk.required' => 'Merk Belum Diisi',
            'type.required' => 'Tipe Belum Diisi',
        ];
        $request->validate([
            'nama' => 'required',
            'merk' => 'required',
            'type' => 'required',
        ], $message);
        $createMas = Master::Create([
            'invent_desc' => $request->nama,
            'invent_brand' => $request->merk,
            'invent_type' => $request->type,
            'creation_date' => now(),
            'created_by' => Auth::user()->usr_id,
            'program_name' => "Master_Save",
        ]);
        return ResponseFormatter::success($createMas, 'Successfully Created Data Master');
    }
    public function edit($code){
        $mas = DB::table('invent_mst as im')
            ->leftjoin('lookup_refs as lr', 'im.invent_brand', 'lr.lookup_code')
            ->select('im.invent_code', 'im.invent_desc', 'lr.lookup_desc', 'im.invent_type')
            ->where('im.invent_code', $code)
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%'])
            ->first();
        return ResponseFormatter::success($mas, 'Successfully get data');
    }

    public function detailPeripheral($invent_code_dtl){
        $mas = DB::table('invent_dtl as id')
            ->leftjoin('invent_mst as im', 'id.invent_code', 'im.invent_code')
            ->leftjoin('mng_users usr1', 'id.invent_pengguna_previous', 'usr1.usr_id')
            ->leftjoin('mng_users usr2', 'id.invent_pengguna_update', 'usr2.usr_id')
            ->leftJoin('lookup_refs as lrs', function ($join) {
                $join->on('im.invent_brand', 'lrs.lookup_code')
                    ->whereRaw('LOWER(lrs.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%']);
            })
            ->leftJoin('lookup_refs as lrfs', function ($join) {
                $join->on('id.invent_kondisi', 'lrfs.lookup_code')
                    ->whereRaw('LOWER(lrfs.lookup_type) LIKE ? ', [trim(strtolower('kondisi')) . '%']);
            })
            ->leftJoin('vcompany_refs as vr', function ($join) {
                $join->on('id.invent_bu', 'vr.company_code');
            })
            ->select(
                'im.invent_code',
                'im.invent_type',
                'id.invent_photo',
                'im.invent_desc',
                'lrs.lookup_desc as invent_brand',
                'id.invent_sn',
                DB::RAW("(im.invent_desc|| '-' || lrs.lookup_desc || '-' || im.invent_type ) as name"),
                DB::raw("TO_CHAR(id.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"),
                'id.invent_lama_garansi',
                'lrfs.lookup_desc as invent_kondisi',
                'vr.name as invent_bu',
                'id.invent_lokasi_previous',
                'id.invent_lokasi_update',
                'usr1.usr_nm_perush as invent_bu_previous',
                'usr2.usr_nm_perush as invent_bu_update',
                'usr1.usr_fullname as invent_pengguna_previous',
                'usr2.usr_fullname as invent_pengguna_update'
            )
            ->where('id.invent_code_dtl', $invent_code_dtl)
            ->first();
        return response()->json($mas);
    }
    public function update(Request $request, $code){
        $mas = Master::find($code);
        $mas->invent_type = $request->invent_type;
        $mas->last_update_date = now();
        $mas->last_updated_by = Auth::user()->usr_id;
        $mas->program_name = "Master_Update";
        $mas->save();
        return ResponseFormatter::success($mas, 'Successfully Updated Data Master');
    }
    public function delete($invent_code){
        $mas = Master::find($invent_code);
        if ($mas->invent_photo) {
            unlink(Storage_path('app/public/master_peripheral/' . $mas->invent_photo));
        }
        $mas->delete();

        return ResponseFormatter::success($mas, 'Successfully Deleted Data Master');
    }
    public function cetak_pdf(){
        $mass = DB::table('invent_mst as im')
            ->select('im.invent_code', 'im.invent_desc', 'im.invent_type', 'lrs.lookup_desc as invent_brand')
            ->LEFTJOIN('lookup_refs as lrs', function ($join) {
                $join->on('im.invent_brand', 'lrs.lookup_code')
                    ->WHERERaw('LOWER(lrs.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%']);
            })
            ->leftjoin('vcompany_refs', function ($join) {
                $join->on('im.invent_bu', '=', 'vcompany_refs.company_code');
            })
            ->orderBy('im.invent_code', 'ASC')
            ->get();
        return view('pdf/Laporan_Master', compact('mass'));
    }
    public function cetak_excel(){
        return Excel::download(new MasterExport, 'Laporan_Master.xlsx');
    }
    public function getKode(){
        $mas = Master::Select('invent_code as code', DB::raw("(invent_code ||'-'|| invent_desc) as name"))->orderBy('invent_desc', 'ASC')->get();
        return response()->json($mas);
    }
    public function getKodeIct($code){
        $mas = DB::table('invent_mst as im')
            ->select('im.invent_code as code', DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
            ->whereNotExists(function ($query) use ($code) {
                $query->select(DB::raw(1))
                    ->from('ireq_dtl')
                    ->join('ireq.dtl.invent_code', 'im.invent_code')
                    ->whereRaw('ireq_dtl.ireq_id', $code);
            })->get();
        return response()->json($mas);
    }
    public function getImage($kode){
        $mas = DB::table('invent_dtl')->select('invent_photo as photo')->where('invent_code_dtl', $kode)->first();
        return response()->json($mas);
    }
    public function getBarcode($invent_code){
        $mas = DB::table('invent_mst as im')
            ->leftjoin('vcompany_refs as vr', 'im.invent_bu', 'vr.company_code')
            ->leftjoin('lookup_refs as lr', 'im.invent_brand', 'lr.lookup_code')
            ->select('im.invent_code', 'lr.lookup_desc as invent_brand', 'vr.name as invent_bu', 'im.invent_desc',
                'im.invent_type', 'im.invent_sn', 'im.invent_lama_garansi', 'im.invent_pengguna_previous',
                'im.invent_lokasi_previous', DB::raw("TO_CHAR(im.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"))
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%'])
            ->where('im.invent_code', $invent_code)
            ->first();
        return response()->json($mas);
    }
    public function getPeripheral(){
        $data['kode'] = DB::table('invent_mst as im')
            ->leftJoin('lookup_refs as lr', function ($join) {
                $join->on('im.invent_brand', 'lr.lookup_code')
                    ->whereRaw('LOWER(lr.lookup_type) LIKE ? ', [trim(strtolower('merk')) . '%']);
            })
            ->select('im.invent_code as code', DB::raw("(im.invent_desc || '-' || lr.lookup_desc || '-' ||  im.invent_type) as name"))
            ->orderBy('im.invent_desc', 'ASC')
            ->get();

        $data['divisi'] = DB::table('divisi_refs')->select('div_id as code', 'div_name as name')->orderBy('div_name', 'ASC')->get();
        $data['bu'] = DB::table('vcompany_refs')->select('company_code as code', 'name')->orderBy('name', 'ASC')->get();
        return ResponseFormatter::success($data, 'Successfully get data');
    }
    public function getSn($kode){
        $sn = DB::table('invent_dtl')
            ->select('invent_code_dtl as code', 'invent_sn as name')
            ->orderBy('invent_sn', 'ASC')
            ->where('invent_code', $kode)
            ->get();
        return json_encode($sn);
    }
}
