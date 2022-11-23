<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Supplier;
use App\Exports\SupplierExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\Mng_usr_roles;
use App\Model\Mng_role_menu;

class SupplierController extends Controller
{
    public function __construct(){
        $this->supplier = "/referensi-supplier";
    }
    public function index()
    {
        $role = Mng_usr_roles::select('rol_id')->WHERE('usr_id',Auth::user()->usr_id)->pluck('rol_id');
        $menu = Mng_role_menu::select('menu_id')->WHEREIn('rol_id',$role)->pluck('menu_id');
        $aksesmenu = DB::table('mng_menus')->SELECT('controller')->WHEREIn('menu_id',$menu)->pluck('controller');
        if($aksesmenu->contains($this->supplier)){
            $supp = Supplier::select('suplier_code','suplier_name','suplier_contact','suplier_fax')
            ->orderBy('creation_date','ASC')
            ->get();
            return response()->json($supp);
        }
        else{
            return response(["message"=>"Cannot Access"],403);
        }
    }
    public function save(Request $request)
    {
        $message = [
            'code.unique' => 'Kode Sudah Ada',
            'code.required'=>'Kode Belum Diisi',
            'nama.required'=>'Nama Belum Diisi',
            'alamat1.required'=>'Alamat 1 Belum diisi',
            'kota.required'=>'Kota Belum Diisi',
            'provinsi.required'=>'Provinsi Belum Diisi',
            'email.required'=>'Email Belum Diisi',
            'contact.required'=>'Contact Belum Diisi',
            'notlp1.required' => 'No. Tlp-1 wajib diisi',
            'fax.required' => 'Fax wajib diisi',
            'contact.alpha' => 'Masukan Contact Dengan Benar',
            'notlp2.numeric' => 'Masukan No. Tlp-2 dengan benar',
            'fax.numeric' => 'Masukan Fax dengan benar'
        ];
        if($request->notlp2) {
            $request->validate([
                'code' => 'required|unique:suplier_mst,suplier_code',
                'nama'=>'required',
                'alamat1'=>'required',
                'kota'=>'required',
                'provinsi'=>'required',
                'email' => 'required',
                'fax' => 'required|numeric',
                'notlp1' => 'required|numeric',
                'notlp2' => 'numeric',
                'contact'=> 'required|alpha'
            ],$message);
         }
        $request->validate([
            'code' => 'required|unique:suplier_mst,suplier_code',
            'nama'=>'required',
            'alamat1'=>'required',
            'kota'=>'required',
            'provinsi'=>'required',
            'email' => 'required',
            'fax' => 'required|numeric',
            'notlp1' => 'required|numeric',
            'contact'=> 'required|alpha'
        ],$message);
        $date = Carbon::now();
        $newCreation = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $supp = Supplier::Create([
            'suplier_code' => $request->code,
            'suplier_name' => $request->nama,
            'suplier_contact' => $request->contact,
            'suplier_address1' => $request->alamat1,
            'suplier_address2' => $request->alamat2,
            'suplier_city' => $request->kota,
            'suplier_prov' => $request->provinsi,
            'suplier_email'=> $request->email,
            'suplier_fax' => $request->fax,
            'suplier_tlp1' => $request->notlp1,
            'suplier_tlp2' => $request->notlp2,
            'suplier_status' => "T",
            'creation_date' => $newCreation,
            'created_by' => Auth::user()->usr_name,
            'program_name' => "Supplier_Save",
        ]);
        return response()->json([
            'success' => true,
            'message' => $supp
        ]);
    }
    public function edit($code)
    {
        $supp = DB::Table('suplier_mst')
        ->where('suplier_code',$code)
        ->get(['suplier_code','suplier_name','suplier_contact','suplier_address1','suplier_address2','suplier_city','suplier_prov','suplier_email','suplier_fax','suplier_tlp1','suplier_tlp2'])
        ->map(function($data) {
            if ($data->suplier_address2 == 'null' ) {
                $data->suplier_address2 = '';
            }
            if($data->suplier_tlp2 == 'null') {
                $data->suplier_tlp2 = '';
            }
            return $data;
        })->first();
        return response()->json($supp);
    }
    public function show($suplier_code)
    {
        $supp = DB::Table('suplier_mst')
        ->where('suplier_code',$suplier_code)
        ->get(['suplier_code','suplier_name','suplier_contact','suplier_address1','suplier_address2','suplier_city','suplier_prov','suplier_email','suplier_fax','suplier_tlp1','suplier_tlp2'])
        ->map(function($data) {
            if ($data->suplier_address2  == 'null') {
                $data->suplier_address2  = '';
            }
            if($data->suplier_tlp2== 'null') {
                $data->suplier_tlp2 = '';
            }
            return $data;
        })->first();
        return response()->json($supp);
    }

    public function update(Request $request, $code)
    {
        $message = [
            'suplier_name.required'=>'Nama Belum Diisi',
            'suplier_address1.required'=>'Alamat 1 Belum diisi',
            'suplier_city.required'=>'Kota Belum Diisi',
            'suplier_prov.required'=>'Provinsi Belum Diisi',
            'suplier_email.required'=>'Email Belum Diisi',
            'suplier_tlp1.required' => 'No. Tlp-1 wajib diisi',
            'suplier_fax.required' => 'Fax wajib diisi',
            'suplier_contact.required'=>'Contact Belum Diisi',
            'suplier_contact.alpha' => 'Masukan Contact Dengan Benar',
            'suplier_tlp2.numeric' => 'Masukan No. Tlp-2 dengan benar',
            'suplier_fax.numeric' => 'Masukan Fax dengan benar'
        ];
        if($request->suplier_tlp2){

        $request->validate([
            'suplier_name' => 'required',
            'suplier_contact' => 'required|alpha',
            'suplier_address1' => 'required',
            'suplier_city' => 'required',
            'suplier_prov' => 'required',
            'suplier_email' => 'required',
            'suplier_fax' => 'required|numeric',
            'suplier_tlp1' => 'required|numeric',
            'suplier_tlp2' => 'numeric'
        ],$message);
    }
    $request->validate([
        'suplier_name' => 'required',
        'suplier_contact' => 'required|alpha',
        'suplier_address1' => 'required',
        'suplier_city' => 'required',
        'suplier_prov' => 'required',
        'suplier_email' => 'required',
        'suplier_fax' => 'required|numeric',
        'suplier_tlp1' => 'required|numeric'
    ],$message);
        $date = Carbon::now();
        $newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');

        $supp = Supplier::find($code);
        $supp->suplier_name = $request->suplier_name;
        $supp->suplier_contact = $request->suplier_contact;
        $supp->suplier_address1 = $request->suplier_address1;
        $supp->suplier_address2 = $request->suplier_address2;
        $supp->suplier_city = $request->suplier_city;
        $supp->suplier_prov = $request->suplier_prov;
        $supp->suplier_email = $request->suplier_email;
        $supp->suplier_fax = $request->suplier_fax;
        $supp->suplier_tlp1 = $request->suplier_tlp1;
        $supp->suplier_tlp2 = $request->suplier_tlp2;
        $supp->last_update_date = $newUpdate;
        $supp->last_updated_by = Auth::user()->usr_name;
        $supp->program_name = "Supplier_Update";
        $supp->save();
        $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
        return response()->json($msg);
    }
    public function delete($suplier_code)
    {
        $supp = Supplier::find($suplier_code);
        $supp->delete();
        return response()->json('Successfully deleted');
    }
    public function cetak_pdf()
    {
        $supp = DB::Table('suplier_mst')
        ->get(['suplier_code','suplier_name','suplier_contact','suplier_address1','suplier_address2','suplier_city','suplier_prov','suplier_email','suplier_fax','suplier_tlp1','suplier_tlp2'])
        ->map(function($data) {
            if ($data->suplier_address2 == 'NULL') {
                $data->suplier_address2 = '';
            }
            if($data->suplier_tlp2 == 'NULL') {
                $data->suplier_tlp2 = '';
            }
            return $data;
        });
        return view('pdf/Laporan_Supplier', compact('supp'));
    }
    public function cetak_excel()
    {
        return Excel::download(new SupplierExport,'Laporan_Supplier.xlsx');
    }
    Public function getSupp()
    {
        $supp = Supplier::Select('suplier_code as code',DB::raw("(suplier_code ||'-'|| suplier_name) as name"))
                ->orderBy('suplier_code','ASC')
                ->get();    
        return response()->json($supp);
    }
}
