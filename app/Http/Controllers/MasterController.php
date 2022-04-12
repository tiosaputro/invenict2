<?php

namespace App\Http\Controllers;
use App\Master;
use App\Exports\MasterExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;
use App\Lookup_Refs;
use Auth;
use Excel;
use App\Mng_usr_roles;
use App\Mng_role_menu;

class MasterController extends Controller
{
    function __construct(){
        $date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->to = "/master-peripheral";
    }
    public function index()
        {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
            $mas = DB::table('invent_mst as im')
            ->select('im.*','vf.name as invent_bu','lr.lookup_desc as invent_brand')
            ->leftjoin('vcompany_refs as vf','im.invent_bu','vf.company_code')
            ->leftjoin('lookup_refs as lr','im.invent_brand','lr.lookup_code')
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])
            ->orderBy('im.invent_code','ASC')
            ->get();
            return json_encode($mas);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
          }
        }
    public function save(Request $request)
    {
        $message = [
            'code.unique' => 'Kode Sudah Ada',
            'code.required'=> 'Kode Belum Diisi',
            'nama.required' => 'Nama Belum Diisi',
            'merk.required' => 'Merk Belum Diisi',
            'type.required' => 'Tipe Belum Diisi',
            'sn.required' => 'S/N Belum Diisi',
            'tgl.required' => 'Tanggal Belum Diisi',
            'kondisi.required'=>'Kondisi Belum Diisi',
            'garansi.required' => 'Garansi Belum Diisi',
            'garansi.numeric' => 'Garansi Belum Diisi',
            // 'barcode.required' => 'Barcode Belum Diisi',
            // 'lastloct.required' => 'Lokasi Terakhir Belum Diisi',
            // 'lastuser.required' => 'Pengguna Terakhir Belum Diisi',
            // 'prevloct.required' => 'Lokasi Sebelumnya Belum Diisi',
            // 'prevuser.required' => 'User Sebelumnya Belum Diisi',
            'bu.required' => 'Bisnis Unit Belum Diisi'
        ];
        $request->validate([
            'code' => 'required|unique:invent_mst,invent_code',
            'nama' => 'required',
            'merk' => 'required',
            'type' => 'required',
            'sn' => 'required',
            'tgl'=>'required',
            'kondisi'=>'required',
            'garansi'=>'required|numeric',
            // 'barcode'=>'required',
            // 'lastloct'=>'required',
            // 'lastuser' => 'required',
            // 'prevloct'=> 'required',
            // 'prevuser'=> 'required',
            'bu'=> 'required'
        ],$message);

        $newDate = Carbon::createFromFormat('D M d Y H:i:s e+',$request->tgl)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        if($request->foto){
            $image= $request->foto;
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
        $mas = Master::Create([
            'invent_code' => $request->code,
            'invent_desc' => $request->nama,
            'invent_brand' => $request->merk,
            'invent_type' => $request->type,
            'invent_sn' => $request->sn,
            'invent_tgl_perolehan' => $newDate,
            'invent_lama_garansi' => $request->garansi,
            'invent_kondisi' => $request->kondisi,
            'creation_date' => $this->newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name' => "Master_Save",
            // 'invent_barcode' => $request->barcode,
            // 'invent_lokasi_update' => $request->lastloct,
            // 'invent_pengguna_update' => $request->lastuser,
            'invent_photo' => $nama_file,
            // 'invent_lokasi_previous' => $request->prevloct,
            // 'invent_pengguna_previous' => $request->prevuser,
            'invent_bu' => $request->bu,
        ]);
        $msg = [
            'success' => true,
            'message' => 'Created Successfully'
        ];
        return json_encode($msg);
    }
    public function edit($code)
    {
        $role = Mng_usr_roles::select('rol_id')->where('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->whereIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->select('controller')->whereIn('menu_id',$menu)->pluck('controller');

        if($aksesmenu->contains($this->to)){
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
            $bisnis = DB::table('v_company_refs')->get();
            $mas = DB::table('invent_mst as im')
            ->select('im.invent_code','im.invent_desc','im.invent_brand','im.invent_type','im.invent_sn','im.invent_kondisi',
                    'im.invent_lama_garansi','im.invent_barcode','im.invent_lokasi_update','im.invent_pengguna_update','im.invent_photo',
                    'im.invent_lokasi_previous', 'im.invent_pengguna_previous', 'im.invent_bu', 
                    DB::raw("TO_CHAR(im.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"))
            ->where('im.invent_code',$code)
            ->first();
            return json_encode(['merk'=>$merk,'kondisi'=>$kondisi,'bisnis'=>$bisnis,'mas'=>$mas],200);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    
    public function detailPeripheral($invent_code)
    {
        $mas = DB::table('invent_mst as im')
        ->leftjoin('lookup_refs as lr','im.invent_brand','lr.lookup_code')
        ->leftjoin('lookup_refs as llr','im.invent_kondisi','llr.lookup_code')
        ->leftjoin('vcompany_refs as vr','im.invent_bu','vr.company_code')
        ->select('im.invent_code','im.invent_desc','lr.lookup_desc as invent_brand','im.invent_type','im.invent_sn','llr.lookup_desc as invent_kondisi',
                'im.invent_lama_garansi','im.invent_lokasi_update','im.invent_pengguna_update','im.invent_photo',
                'im.invent_lokasi_previous', 'im.invent_pengguna_previous', 'vr.name as invent_bu', 
                DB::raw("TO_CHAR(im.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"),DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->where('im.invent_code',$invent_code)
        ->where('lr.lookup_type','Merk')
        ->where('llr.lookup_type','Kondisi')
        ->first();
        return json_encode($mas);
    }
    public function update(Request $request, $code)
    {
        $newDate = Carbon::parse($request->invent_tgl_perolehan)->copy()->tz('Asia/Jakarta')->format('Y-m-d');
        $mas = Master::find($code);
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
            $nama_file = '';
        }
            $mas->invent_desc = $request->invent_desc;
            $mas->invent_brand = $request->invent_brand;
            $mas->invent_type = $request->invent_type;
            $mas->invent_sn =$request->invent_sn;
            $mas->invent_tgl_perolehan =$newDate;
            $mas->invent_lama_garansi = $request->invent_lama_garansi;
            $mas->invent_kondisi = $request->invent_kondisi;
            $mas->last_update_date = $this->newUpdate;
            $mas->last_updated_by = Auth::user()->usr_name;
            $mas->program_name = "Master_Update";
            // $mas->invent_barcode = $request->invent_barcode;
            // $mas->invent_lokasi_update =$request->invent_lokasi_update;
            // $mas->invent_pengguna_update=  $request->invent_pengguna_update;
            // $mas->invent_lokasi_previous = $request-> invent_lokasi_previous;
            // $mas->invent_pengguna_previous = $request-> invent_pengguna_previous;
            $mas->invent_bu = $request->invent_bu;
            $mas->invent_photo = $nama_file;
            $mas->save();
        
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
 
        return json_encode($msg);
    }
    public function delete($invent_code)
    {
        $mas = Master::find($invent_code);
        if($mas->invent_photo){
            unlink(Storage_path('app/public/master_peripheral/'.$mas->invent_photo));
        }
        $mas->delete();
            return json_encode('Successfully deleted');
    }
    public function cetak_pdf()
    {
        $mass = DB::table('invent_mst as im')
        ->select('im.invent_code','im.invent_desc','im.invent_brand','im.invent_type','im.invent_sn',
                'im.invent_lama_garansi','im.invent_barcode','im.invent_lokasi_update','im.invent_pengguna_update','im.invent_photo',
                'im.invent_lokasi_previous', 'im.invent_pengguna_previous', 'im.invent_bu', 'vcompany_refs.name as invent_bu','lookup_refs.lookup_desc as invent_brand', 'lf.lookup_desc as invent_kondisi',
        DB::raw("TO_CHAR(im.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"))
        ->leftjoin('lookup_refs', function($join)
        {
            $join->on('im.invent_brand', '=', 'lookup_refs.lookup_code');
        })
        ->leftjoin('lookup_refs as lf', function($join)
        {
            $join->on('im.invent_kondisi', '=', 'lf.lookup_code');
        })
        ->leftjoin('vcompany_refs', function($join)
        {
            $join->on('im.invent_bu','=','vcompany_refs.company_code');
        })
        ->orderBy('im.invent_code','ASC')
        ->get();
        return view ('pdf/Laporan_Master',compact ('mass'));
    }
    public function cetak_excel()
    {
        return Excel::download(new MasterExport,'Laporan_Master.xlsx');
    }
    public function getKode()
    {
        $mas = Master::Select('invent_code as code',DB::raw("(invent_code ||'-'|| invent_desc) as name"))->get();
        return json_encode($mas);
    }
    public function getKodeIct($code)
    {
        $mas = DB::table('invent_mst as im')->select('im.invent_code as code', DB::raw("(im.invent_code ||'-'|| im.invent_desc) as name"))
        ->whereNotExists(function($query) use($code)
        {
            $query->select(DB::raw(1))
            ->from('ireq_dtl')
            ->join('ireq.dtl.invent_code','=','im.invent_code')
            ->whereRaw('ireq_dtl.ireq_id',$code);
        })->get();
        return json_encode($mas);
    }
    public function getImage($kode)
    {
        $mas = Master::select('invent_photo as photo')->where('invent_code',$kode)->first();
        return json_encode($mas);
    }
    public function getBarcode($invent_code)
    {
        $mas = DB::table('invent_mst as im')
            ->leftjoin('vcompany_refs as vr','im.invent_bu','vr.company_code')
            ->leftjoin('lookup_refs as lr','im.invent_brand','lr.lookup_code')
            ->select('im.invent_code','lr.lookup_desc as invent_brand','vr.name as invent_bu','im.invent_desc',
                     'im.invent_type','im.invent_sn','im.invent_lama_garansi','im.invent_pengguna_previous',
                     'im.invent_lokasi_previous',DB::raw("TO_CHAR(im.invent_tgl_perolehan,' dd Mon YYYY') as invent_tgl_perolehan"))
            ->whereRaw('LOWER(lr.lookup_type) LIKE ? ',[trim(strtolower('merk')).'%'])     
            ->where('im.invent_code',$invent_code)    
            ->first();
            return json_encode($mas);
    }
}
