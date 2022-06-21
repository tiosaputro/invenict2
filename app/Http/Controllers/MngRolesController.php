<?php

namespace App\Http\Controllers;
use App\Mng_roles;
use DB;
use Auth;
use carbon\Carbon;
use Illuminate\Http\Request;

class MngRolesController extends Controller
{
    function __construct(){
        $date = Carbon::now();
        $this->newCreation =Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
        $this->newUpdate = Carbon::parse($date)->copy()->tz('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
    function index()
    {
       $roles = DB::table('mng_roles as mr')
       ->select('mr.rol_id','mr.rol_name','mr.rol_desc',
         DB::raw("CASE WHEN mr.rol_stat = 'T' Then 'Aktif' WHEN mr.rol_stat = 'F' Then 'Tidak Aktif' end as rol_stat"))
       ->orderBy('mr.rol_name','ASC')
       ->get();
       return response()->json($roles);
    }
    function getRole()
    {
        $roles =  Mng_roles::select('rol_id as code','rol_name as name')->where('rol_stat','T')->orderBy('rol_id','ASC')->get();
        return response()->json($roles);
    }
    function save(Request $request)
    {
        $message = [
            'rol_name.required'=>'Role Name Belum Diisi',
            'rol_desc.required'=>'Role Description Belum Diisi',
            'rol_stat.required'=>'Role Status Belum Diisi',
        ];
        $request->validate([
            'rol_name' => 'required',
            'rol_desc'=>'required',
            'rol_stat'=>'required'
        ],$message);

            $role = Mng_roles::create([
                'rol_name'=>$request->rol_name,
                'rol_desc'=>$request->rol_desc,
                'rol_stat'=>$request->rol_stat,
                'created_by'=> Auth::user()->usr_name,
                'creation_date'=>$this->newCreation,
                'program_name'=>'MngRoles_SAVE'
            ]);
    }
    function edit($code)
        {
            $role = Mng_roles::select('rol_id','rol_name','rol_desc','rol_stat')
                ->where('rol_id',$code)
                ->first();
            return response()->json($role);
        }
    function update(Request $request, $code)
    {
        $message = [
            'rol_name.required'=>'Role Name Belum Diisi',
            'rol_desc.required'=>'Role Description Belum Diisi',
            'rol_stat.required'=>'Role Status Belum Diisi',
        ];
        $request->validate([
            'rol_name' => 'required',
            'rol_desc'=>'required',
            'rol_stat'=>'required'
        ],$message);
        
        $role = Mng_roles::find($code);
        $role->rol_name = $request->rol_name;
        $role->rol_desc = $request->rol_desc;
        $role->rol_stat = $request->rol_stat;
        $role->last_updated_by = Auth::user()->usr_name;
        $role->last_update_date = $this->newUpdate;
        $role->program_name = 'MngRolesController_UUPDATE';
        $role->save();

       $msg = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
 
        return response()->json($msg);
    }
    function delete($rol_id)
    {
        $role = Mng_roles::find($rol_id);
        $role->delete();
        $msg = [
            'success' => true,
            'message' => 'Successfully deleted'
        ];
        return response()->json($msg);
    }
}
