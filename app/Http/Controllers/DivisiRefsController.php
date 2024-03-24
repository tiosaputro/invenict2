<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Divisi_refs;
use App\Models\Mng_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisiRefsController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/divisi-refs";
        $this->middleware(function ($request, $next) {
            $this->userMenu = Mng_User::menu();
            if ($this->userMenu->contains($this->to)) {
                return $next($request);
            } else {
                return response(["message" => "Cannot Access"], 403);
            }
        });
    }
    public function index(){
        $divisi = Divisi_refs::Divisi();
        return response()->json($divisi);
    }
    public function getDivisi(){
        $divisi = Divisi_refs::ListDivision();
        return response()->json($divisi);
    }
    public function edit($code){
        $divisi = Divisi_refs::select('div_id', 'div_code', 'div_name', 'div_verificator')->where('div_id', $code)->first();
        return response()->json($divisi);
    }
    public function update(Request $request, $code){
        $message = [
            'div_code.required' => 'Divisi Code Belum Diisi',
            'div_name.required' => 'Divisi Name Belum diisi',
            'div_verificator.required' => 'Divisi Verificator Belum Diisi',
        ];
        $request->validate([
            'div_code' => 'required',
            'div_name' => 'required',
            'div_verificator' => 'required',
        ], $message);

        $newUpdate = now();
        $div = Divisi_refs::find($code);
        $div->div_name = $request->div_name;
        $div->div_code = $request->div_code;
        $div->div_verificator = $request->div_verificator;
        $div->last_updated_by = Auth::user()->usr_id;
        $div->last_update_date = $newUpdate;
        $div->program_name = "DivisiRefs_Update";
        $div->save();
        return ResponseFormatter::success($div, 'Successfully Updated Division');
    }
    public function delete($div_id){
        $divisi = Divisi_refs::find($div_id)->delete();
        return ResponseFormatter::success($divisi, 'Successfully Deleted Division');
    }
    public function getVerif($div_id){
        $user = Mng_user::findVerificatorDivision($div_id);
        return response()->json($user);
    }
}
