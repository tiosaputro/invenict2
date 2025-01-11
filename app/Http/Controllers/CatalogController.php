<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Catalog;
use App\Models\Mng_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    protected $to;
    protected $userMenu;
    public function __construct(){
        $this->middleware('auth:sanctum');
        $this->to = "/catalog-refs";
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
        $catalog = Catalog::select('catalog_id', 'catalog_name', 'catalog_desc', DB::raw("CASE WHEN catalog_request_type = 'P' Then 'Peripheral' WHEN catalog_request_type = 'S' Then 'Service' end as catalog_request_type"), DB::raw("CASE WHEN catalog_priority = 'L' Then 'LOW' WHEN catalog_priority = 'N' Then 'Normal' WHEN catalog_priority = 'H' Then 'High' end as catalog_priority"))
            ->get();
        return ResponseFormatter::success($catalog, "Successfully get data catalog");

    }
    public function save(Request $request){
        $message = [
            'catalog_name.required' => 'Catalog Name Not Filled',
            'catalog_stat.required' => 'Catalog Stat Not Filled',
            'catalog_type.required' => 'Catalog Type Not Filled',
            'catalog_request_type.required' => 'Catalog Request Type Not Filled',
        ];
        $request->validate([
            'catalog_name' => 'required',
            'catalog_type' => 'required',
            'catalog_stat' => 'required',
            'catalog_request_type' => 'required',
        ], $message);
        $catalog = Catalog::create([
            'catalog_name' => $request->catalog_name,
            'catalog_type' => $request->catalog_type,
            'catalog_desc' => $request->catalog_desc,
            'catalog_stat' => $request->catalog_stat,
            'catalog_priority' => $request->catalog_priority,
            'catalog_request_type' => $request->catalog_request_type,
            'parent_id' => $request->parent_id,
            'created_by' => Auth::user()->usr_id,
            'creation_date' => now(),
            'program_name' => 'catalogController@save',
        ]);
        return ResponseFormatter::success($catalog, 'Successfully Create Catalog');
    }
    public function edit($code){
        $catalog = Catalog::find($code);
        return ResponseFormatter::success($catalog, "Successfully find catalog");
    }
    public function update(Request $request, $code){
        $message = [
            'catalog_name.required' => 'Catalog Name Not Filled',
            'catalog_stat.required' => 'Catalog Stat Not Filled',
            'catalog_type.required' => 'Catalog Type Not Filled',
            'catalog_request_type.required' => 'Catalog Request Type Not Filled',
        ];
        $request->validate([
            'catalog_name' => 'required',
            'catalog_type' => 'required',
            'catalog_stat' => 'required',
            'catalog_request_type' => 'required',
        ], $message);

        $cat = Catalog::find($code);
        $cat->catalog_name = $request->catalog_name;
        $cat->catalog_type = $request->catalog_type;
        $cat->catalog_desc = $request->catalog_desc;
        $cat->catalog_priority = $request->catalog_priority;
        $cat->catalog_stat = $request->catalog_stat;
        $cat->catalog_request_type = $request->catalog_request_type;
        $cat->parent_id = $request->parent_id;
        $cat->created_by = Auth::user()->usr_id;
        $cat->creation_date = now();
        $cat->program_name = 'catalogController@save';
        $cat->save();

        return ResponseFormatter::success($cat, 'Successfully Updated Catalog');
    }
    public function delete($catalog_id){
        $catalog = Catalog::find($catalog_id)->delete();
        return ResponseFormatter::success($catalog, 'Successfully Deleted Catalog');
    }
    public function parentCatalog(){
        $catalog = Catalog::select('catalog_name as name', 'catalog_id as code')->where('catalog_type', 'N')->get();
        return json_encode($catalog);
    }

}
