<?php

use App\Http\Controllers\BisnisController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MasterDetailController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DivisiRefsController;
use App\Http\Controllers\MngRoleMenuController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MngRolesController;
use App\Http\Controllers\MngUsrRoleController;
use App\Http\Controllers\MngUserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LookupsController;
use App\Http\Controllers\LookupsBrandController;
use App\Http\Controllers\LookupsKategoriController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\MngMenuController;
use App\Http\Controllers\IctRequestReviewerController;
use App\Http\Controllers\IctRequestManagerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IctRequestRequestorController;
use App\Http\Controllers\IctRequestPersonnelController;
use App\Http\Controllers\IctRequestHigherLevelController;
use App\Http\Controllers\IctRequestAdminController;
use App\Http\Controllers\IctDetailController;
use App\Http\Controllers\PekerjaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\SupervisorController;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/cek-verif-id/{code}',[LinkController::class,'cekVerif']);
Route::post('/login', [LoginController::class,'index']);
Route::post('/login-intranet', [LoginController::class,'loginFromIntranet']);
Route::get('/detail-peripherall/{invent_code}',[MasterController::class,'detailPeripheral']);
Route::post('/login-approval', [LoginController::class,'loginFromEmail']);
Route::get('/logout', [LoginController::class,'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/cek-user',[LoginController::class,'cekUser']);
  //referensi_location
    Route::get('/loc', [LocationController::class,'index']);
    Route::post('/add-loc',[LocationController::class,'save']);
    Route::get('/edit-loc/{code}',[LocationController::class,'edit']);
    Route::put('/update-loc/{code}',[LocationController::class,'update']);
    Route::delete('/delete-loc/{loc_code}',[LocationController::class,'delete']);
    Route::get('/ref-loc', [LocationController::class,'getLocation']);

  //referensi_lookups
    Route::get('/ref', [LookupsController::class,'index']);
    Route::get('/ref-lookup-brand', [LookupsBrandController::class,'lookupBrand']);
    Route::get('/ref-lookup-kategori', [LookupsKategoriController::class,'lookupKategori']);
    Route::get('/ref-lookup-service', [LookupsController::class,'lookupService']);
    Route::post('/add-ref',[LookupsController::class,'save']);
    Route::get('/edit-ref/{code}/{type}',[LookupsController::class,'edit']);
    Route::put('/update-ref/{code}/{type}',[LookupsController::class,'update']);
    Route::delete('/delete-ref/{lookup_code}/{lookup_type}',[LookupsController::class,'delete']);
    Route::get('/report-lookups-pdf',[LookupsController::class,'cetak_pdf']);
    Route::get('/report-lookups-excel',[LookupsController::class,'cetak_excel']);

  //lookup kategori
    Route::get('/ref-lookup-kategori', [LookupsKategoriController::class,'lookupKategori']);
    Route::post('/add-kategori',[LookupsKategoriController::class,'saveKategori']);
    Route::get('/edit-kategori/{code}/{type}',[LookupsKategoriController::class,'editKategori']);
    Route::put('/update-kategori/{code}/{type}',[LookupsKategoriController::class,'updateKategori']);
    Route::delete('/delete-kategori/{lookup_code}/{lookup_type}',[LookupsKategoriController::class,'deleteKategori']);
  //lookup brand
    Route::get('/ref-lookup-brand', [LookupsBrandController::class,'lookupBrand']);
    Route::post('/add-brand',[LookupsBrandController::class,'saveBrand']);
    Route::get('/edit-brand/{code}/{type}',[LookupsBrandController::class,'editBrand']);
    Route::put('/update-brand/{code}/{type}',[LookupsBrandController::class,'updateBrand']);
    Route::delete('/delete-brand/{lookup_code}/{lookup_type}',[LookupsBrandController::class,'deleteBrand']);

  //supplier
    Route::get('/supp', [SupplierController::class,'index']);
    Route::post('/add-supp', [SupplierController::class,'save']);
    Route::get('/edit-supp/{code}', [SupplierController::class,'edit']);
    Route::get('/show-supp/{suplier_code}', [SupplierController::class,'show']);
    Route::put('/update-supp/{code}', [SupplierController::class,'update']);
    Route::delete('/delete-supp/{suplier_code}', [SupplierController::class,'delete']);

    //pekerja
    Route::get('/get-pekerja',[PekerjaController::class,'getPekerja']);
    
    //master peripheral
    Route::get('/mas',[MasterController::class,'index']);
    Route::post('/add-mas',[MasterController::class,'save']);
    Route::get('/edit-mas/{code}',[MasterController::class,'edit']);
    Route::put('/update-mas/{code}',[MasterController::class,'update']);
    Route::delete('/delete-mas/{invent_code}',[MasterController::class,'delete']);
    Route::get('/get-kode',[MasterController::class,'getKode']);
    Route::get('/get-kode-peripheral',[MasterController::class,'getPeripheral']);
    Route::get('/get-sn-peripheral/{kode}',[MasterController::class,'getSn']);
    Route::get('/get-kode-ict/{code}',[MasterController::class,'getKodeIct']);
    Route::get('/rsrcsupp',[MasterController::class,'getAddMaster']);
    Route::get('/getImage/{kode}',[MasterController::class,'getImage']);
    Route::get('/getBarcode/{invent_code}',[MasterController::class,'getBarcode']);
    Route::get('/detail-peripheral/{invent_code}',[MasterController::class,'detailPeripheral']);
    Route::get('/report-master-pdf',[MasterController::class,'cetak_pdf']);
    Route::get('/report-master-excel',[MasterController::class,'cetak_excel']);
    //Route::get('/get-kode',[MasterController::class,'getKode']);

    //master peripheral detail
    Route::get('/master-detail/{code}',[MasterDetailController::class,'index']);
    Route::get('/add-master-detail/{code}',[MasterDetailController::class,'addDetail']);
    Route::post('/save-master-detail',[MasterDetailController::class,'SaveDetail']);
    Route::get('/edit-master-detail/{code}',[MasterDetailController::class,'editDetail']);
    Route::put('/update-master-detail/{code}',[MasterDetailController::class,'updateDetail']);
    Route::delete('/delete-master-detail/{invent_code_dtl}',[MasterDetailController::class,'deleteDetail']);

    //mutasi peripheral
    Route::get('/mut',[MutasiController::class,'index']);
    Route::post('/add-mut',[MutasiController::class,'save']);
    Route::get('/edit-mut/{code}',[MutasiController::class,'edit']);
    Route::put('/update-mut/{code}',[MutasiController::class,'update']);
    Route::delete('/delete-mut/{imutasi_id}',[MutasiController::class,'delete']);
    Route::get('/report-mutasi-pdf',[MutasiController::class,'cetak_pdf']);
    Route::get('/report-mutasi-excel',[MutasiController::class,'cetak_excel']);

    //pembelian peripheral
    Route::get('/pem',[PembelianController::class,'index']);
    Route::get('/rsrcsuppo',[PembelianController::class,'getAddPemb']);
    Route::post('/add-pem',[PembelianController::class,'save']);
    Route::get('/edit-pem/{code}',[PembelianController::class,'edit']);
    Route::put('/update-pem/{code}',[PembelianController::class,'update']);
    Route::delete('/delete-pem/{purchase_id}',[PembelianController::class,'delete']);
    Route::get('/report-pem-pdf',[PembelianController::class,'cetak_pdf']);
    Route::get('/report-pem-excel',[PembelianController::class,'cetak_excel']);

    //Detail pembelian peripheral
    Route::get('/detail-pem/{code}',[PembelianDetailController::class,'index']);
    Route::post('/add-detail-pem/{code}',[PembelianDetailController::class,'save']);
    Route::get('/edit-detail-pem/{purchase}',[PembelianDetailController::class,'edit']);
    Route::put('/update-detail-pem/{code}/{purchase}',[PembelianDetailController::class,'update']);
    Route::delete('/delete-detail-pem/{code}/{dpurchase_id}',[PembelianDetailController::class,'delete']);
    Route::get('/getValuta/{code}',[PembelianDetailController::class,'getValuta']);
    Route::get('/report-pem-detail-pdf/{purchase_id}',[PembelianDetailController::class,'cetak_pdf']);
    Route::get('/report-pem-detail-excel/{purchase_id}',[PembelianDetailController::class,'cetak_excel']);

    //cash advance
    Route::get('/cash',[CashController::class,'index']);
    Route::post('/add-cash',[CashController::class,'save']);
    Route::get('/edit-cash/{code}',[CashController::class,'edit']);
    Route::get('/detail-request/{code}',[CashController::class,'detail']);
    Route::put('/update-cash/{code}',[CashController::class,'update']);
    Route::get('/list-no-request',[CashController::class,'getNoRequest']);
    Route::delete('/delete-cash/{ca_id}',[CashController::class,'delete']);
    //Route::get('/report-cash-pdf',[CashController::class,'cetak_pdf']);
    //Route::get('/report-cash-excel',[CashController::class,'cetak_excel']);

    //payment request
    Route::get('/payment-request',[PaymentController::class,'index']);
    Route::post('/add-payment-request',[PaymentController::class,'save']);
    Route::get('/edit-payment-request/{code}',[PaymentController::class,'edit']);
    Route::put('/update-payment-request/{code}',[PaymentController::class,'update']);
    Route::delete('/delete-payment-request/{pr_id}',[PaymentController::class,'delete']);

    //supervisor
    Route::get('/get-data-supervisor',[SupervisorController::class,'index']);
    Route::get('/add-data-supervisor',[SupervisorController::class,'addSpv']);
    Route::get('/find-supervisor/{code}',[SupervisorController::class,'find']);
    Route::post('/save-supervisor',[SupervisorController::class,'store']);
    Route::delete('/delete-supervisor/{code}',[SupervisorController::class,'delete']);

    //mng_user
    Route::get('/get-user',[MngUserController::class,'index']);
    Route::post('/add-user',[MngUserController::class,'save']);
    Route::get('/detail-add-request-user',[MngUserController::class,'detailAddRequest']);
    Route::get('/edit-user/{code}',[MngUserController::class,'edit']);
    Route::put('/update-user/{code}',[MngUserController::class,'update']);
    Route::delete('/delete-user/{usr_id}',[MngUserController::class,'delete']);
    Route::get('/user-list',[MngUserController::class,'userList']);
    Route::get('/data-divbu/{code}',[MngUserController::class,'divisionBu']);
    Route::get('/user',[MngUserController::class,'showUser']);
    //divisi_refs
    Route::get('/divisi',[DivisiRefsController::class,'index']);
    Route::get('/get-divisi',[DivisiRefsController::class,'getDivisi']);
    Route::get('/get-verificator/{div_id}',[DivisiRefsController::class,'getVerif']);
    Route::get('/edit-divisi/{code}',[DivisiRefsController::class,'edit']);
    Route::put('/update-divisi/{code}',[DivisiRefsController::class,'update']);
    Route::delete('/delete-divisi/{div_id}',[DivisiRefsController::class,'delete']);
    //mng_roles
    Route::get('/role',[MngRolesController::class,'index']);
    Route::post('/save-role',[MngRolesController::class,'save']);
    Route::get('/get-role',[MngRolesController::class,'getRole']);
    Route::get('/edit-role/{code}',[MngRolesController::class,'edit']);
    Route::put('/update-role/{code}',[MngRolesController::class,'update']);
    Route::delete('/delete-role/{rol_id}',[MngRolesController::class,'delete']);

    //Mng_usr_roles
    Route::get('/menu-user',[MngUsrRoleController::class,'getRole']);
    Route::post('/save-usr-role',[MngUsrRoleController::class,'save']);
    Route::get('/edit-usr-role/{code}',[MngUsrRoleController::class,'edit']);
    Route::put('/update-usr-role/{code}',[MngUsrRoleController::class,'update']);
    Route::get('/cek-role',[MngUsrRoleController::class,'cekRole']);

    //Mng_role_menu
    Route::post('/save-role-menu',[MngRoleMenuController::class,'save']);
    Route::get('/get-menu',[MngRoleMenuController::class,'getMenu']);
    Route::get('/edit-role-menu/{code}',[MngRoleMenuController::class,'edit']);
    Route::put('/update-role-menu/{code}',[MngRoleMenuController::class,'update']);

    //Mng_module
    Route::get('/module',[ModuleController::class,'index']);
    Route::get('/get-module',[ModuleController::class,'getModule']);
    Route::post('/save-module',[ModuleController::class,'save']);
    Route::get('/edit-module/{code}',[ModuleController::class,'edit']);
    Route::put('/update-module/{code}',[ModuleController::class,'update']);
    Route::delete('/delete-module/{mod_id}',[ModuleController::class,'delete']);
    // catalog
    Route::get('/get-catalog',[CatalogController::class,'index']);
    Route::get('/get-parent-catalog',[CatalogController::class,'parentCatalog']);
    Route::post('/save-catalog',[CatalogController::class,'save']);
    Route::get('/edit-catalog/{code}',[CatalogController::class,'edit']);
    Route::put('/update-catalog/{code}',[CatalogController::class,'update']);
    Route::delete('/delete-catalog/{catalog_id}',[CatalogController::class,'delete']);

    //Mng_menu
    Route::post('/get-menu-user',[MngMenuController::class,'getMenuUser']);
    Route::get('/menu',[MngMenuController::class,'index']);
    Route::get('/get-parent',[MngMenuController::class,'getParent']);
    Route::get('/edit-menu/{code}',[MngMenuController::class,'edit']);
    Route::post('/save-menu',[MngMenuController::class,'save']);
    Route::put('/update-menu/{code}',[MngMenuController::class,'update']);
    Route::delete('/delete-menu/{menu_id}',[MngMenuController::class,'delete']);

    //dashboard
    Route::get('/data-dashboard',[DashboardController::class,'index']);
    Route::get('/getCountUser',[DashboardController::class,'countUser']);
    Route::get('/getCountDivisi1',[DashboardController::class,'countHigherLevel']);
    Route::get('/getCountReviewerBentu',[DashboardController::class,'CountReviewerBentu']);
    Route::get('/getCountReviewerKurau',[DashboardController::class,'CountReviewerKurau']);
    Route::get('/getCountReviewerJakarta',[DashboardController::class,'CountReviewerJakarta']);
    Route::get('/getCountDivisi3',[DashboardController::class,'countPersonnel']);
    Route::get('/getCountDivisi4',[DashboardController::class,'countIctManager']);
    Route::get('/getCountAdmin',[DashboardController::class,'countAdmin']);
    Route::get('/status-per-divisi',[DashboardController::class,'countPerStatusPerDivisi']);
    Route::get('/count-per-status',[DashboardController::class,'countPerStatus']);
    Route::get('/get-tahun',[DashboardController::class,'getTahun']);
    Route::get('/get-tahun-user/{bulanUser}',[DashboardController::class,'getTahunUser']);
    Route::get('/get-tahun-requestor/{bulanRequestor}',[DashboardController::class,'getTahunRequestor']);
    Route::get('/get-bulan',[DashboardController::class,'getBulan']);
    Route::get('/count-per-divuser-tahun/{tahunUser}',[DashboardController::class,'countPerDivUserTahun']);
    Route::get('/count-per-divreq-tahun/{tahunRequestor}',[DashboardController::class,'countPerDivRequestorTahun']);
    Route::get('/count-per-divuser-bulan/{tahunnUser}/{bulanUser}',[DashboardController::class,'countPerDivUserBulan']);
    Route::get('/count-per-divreq-bulan/{tahunnRequestor}/{bulanRequestor}',[DashboardController::class,'countPerDivRequestorBulan']);
    Route::get('/get-status',[DashboardController::class,'getStatus']);
    Route::get('/count-per-divuser-status/{statusUser}',[DashboardController::class,'countPerDivUserStatus']);
    Route::get('/count-per-divreq-status/{statusRequestor}',[DashboardController::class,'countPerDivRequestorStatus']);
    Route::get('/count-per-personel',[DashboardController::class,'countPerPersonnel']);
    Route::get('/get-personnel',[DashboardController::class,'getPersonnel']);
    Route::get('/count-per-status-ict/{ictPersonnel}',[DashboardController::class,'countPerStatusIct']);

    //ict request reviewer
    
    Route::get('/edit-spv/{code}',[IctRequestReviewerController::class,'editSPv']);
    Route::post('/save-spv',[IctRequestReviewerController::class,'saveSpv']);
    Route::get('/ict-detail-penugasan-reviewer/{code}',[IctRequestReviewerController::class,'detailPenugasan']);
    Route::get('/ict-detail-reviewer/{code}',[IctRequestReviewerController::class,'index']);
    Route::get('/edit-ict-detail-reviewer/{ireq}/{code}',[IctRequestReviewerController::class,'editDataDetail']);
    Route::get('/get-data-reviewer',[IctRequestReviewerController::class,'getDataReviewer']);  
    Route::post('/updateAssign',[IctRequestReviewerController::class,'updateAssignPerRequest']);
    Route::put('/updateAssignPerDetail/{code}',[IctRequestReviewerController::class,'updateAssignPerDetail']);
    Route::get('/appd/{ireqd_id}/{code}',[IctRequestReviewerController::class,'assignedPersonnelDetail']);
    Route::get('/updateStatusPenugasan/{ireq_id}',[IctRequestReviewerController::class,'updateStatusPenugasan']);
    Route::get('/updateStatusClosing/{ireq_id}',[IctRequestReviewerController::class,'updateStatusClosing']);
    Route::put('/reject-by-reviewer/{code}',[IctRequestReviewerController::class,'rejectReviewer']);
    Route::get('/naa/{ireq_id}',[IctRequestReviewerController::class,'needApprovalAtasan']);
    Route::get('/nam/{ireq_id}',[IctRequestReviewerController::class,'needApprovalManager']);
    Route::post('/aprr',[IctRequestReviewerController::class,'asignPerRequestReviewer']);
    Route::get('/sapr/{ireq_id}',[IctRequestReviewerController::class,'submitAssignPerRequest']);
    Route::get('/dataIct',[IctRequestReviewerController::class,'getDataIct']);
    Route::get('/getdataIctByStatus/{statuss}',[IctRequestReviewerController::class,'getdataIctByStatus']);
    Route::get('/detail-request-reviewer/{code}',[IctRequestReviewerController::class,'detailRequestReviewer']);
    Route::get('/get-remark-reviewer/{ireq_id}',[IctRequestReviewerController::class,'getRemarkReviewer']);
    Route::post('/save-remark-reviewer',[IctRequestReviewerController::class,'SaveRemarkReviewer']);
    Route::get('/updateStatusClosingDetail/{ireqd_id}/{ireq_no}',[IctRequestReviewerController::class,'updateStatusClosingDetail']);
    Route::get('/detailrequest-tomail/{ireq_id}',[IctRequestReviewerController::class,'detailRequestToMail']);
    Route::post('/sendMailtoRequestor',[IctRequestReviewerController::class,'sendMailtoRequestor']);
    Route::put('/updateAssignPerDetailFromReject/{code}',[IctRequestReviewerController::class,'updateAssignFromReject']);
    Route::get('/report-ict-excel-reviewer-permohonan',[IctRequestReviewerController::class,'cetak_excel_reviewer_permohonan']);
    Route::get('/report-ict-pdf-reviewer-permohonan',[IctRequestReviewerController::class,'cetak_pdf_reviewer_permohonan']);
    Route::get('/report-ict-excel-reviewer-atasan-divisi',[IctRequestReviewerController::class,'cetak_excel_reviewer_atasan_divisi']);
    Route::get('/report-ict-pdf-reviewer-atasan-divisi',[IctRequestReviewerController::class,'cetak_pdf_reviewer_atasan_divisi']);
    Route::get('/report-ict-excel-reviewer-ict-manager',[IctRequestReviewerController::class,'cetak_excel_reviewer_ict_manager']);
    Route::get('/report-ict-pdf-reviewer-ict-manager',[IctRequestReviewerController::class,'cetak_pdf_reviewer_ict_manager']);
    Route::get('/report-ict-excel-reviewer-reject',[IctRequestReviewerController::class,'cetak_excel_reviewer_reject']);
    Route::get('/report-ict-pdf-reviewer-reject',[IctRequestReviewerController::class,'cetak_pdf_reviewer_reject']);
    Route::get('/report-ict-excel-reviewer-assignment-request',[IctRequestReviewerController::class,'cetak_excel_reviewer_assignment_request']);
    Route::get('/report-ict-pdf-reviewer-assignment-request',[IctRequestReviewerController::class,'cetak_pdf_reviewer_assignment_request']);
    Route::get('/report-ict-excel-reviewer-sedang-dikerjakan',[IctRequestReviewerController::class,'cetak_excel_reviewer_sedang_dikerjakan']);
    Route::get('/report-ict-pdf-reviewer-sedang-dikerjakan',[IctRequestReviewerController::class,'cetak_pdf_reviewer_sedang_dikerjakan']);
    Route::get('/report-ict-excel-reviewer-sudah-dikerjakan',[IctRequestReviewerController::class,'cetak_excel_reviewer_sudah_dikerjakan']);
    Route::get('/report-ict-pdf-reviewer-sudah-dikerjakan',[IctRequestReviewerController::class,'cetak_pdf_reviewer_sudah_dikerjakan']);
    Route::get('/report-ict-excel-reviewer-selesai',[IctRequestReviewerController::class,'cetak_excel_reviewer_selesai']);
    Route::get('/report-ict-pdf-reviewer-selesai',[IctRequestReviewerController::class,'cetak_pdf_reviewer_selesai']);

    //ict request manager
    Route::get('/get-data-manager',[IctRequestManagerController::class,'getDataManager']);
    Route::get('/ict-detail-manager/{code}',[IctRequestManagerController::class,'detailRequest']);
    Route::get('/ict-detail-penugasan-manager/{code}',[IctRequestManagerController::class,'detailPenugasan']);
    Route::get('/get-data-manager-verifikasi/{code}',[IctRequestManagerController::class,'getDataManagerVerifikasi']);
    Route::put('/abm/{code}',[IctRequestManagerController::class,'approveByManager']);
    Route::put('/rbm/{code}',[IctRequestManagerController::class,'rejectByManager']);
    Route::get('/get-verif-manager/{code}',[IctRequestManagerController::class,'getDetailVerif']);
    Route::get('/report-ict-excel-manager-permohonan',[IctRequestManagerController::class,'cetak_excel_manager_permohonan']);
    Route::get('/report-ict-pdf-manager-permohonan',[IctRequestManagerController::class,'cetak_pdf_manager_permohonan']);
    Route::get('/report-ict-excel-manager-verifikasi',[IctRequestManagerController::class,'cetak_excel_manager_verifikasi']);
    Route::get('/report-ict-pdf-manager-verifikasi',[IctRequestManagerController::class,'cetak_pdf_manager_verifikasi']);
    Route::get('/report-ict-excel-manager-reject',[IctRequestManagerController::class,'cetak_excel_manager_reject']);
    Route::get('/report-ict-pdf-manager-reject',[IctRequestManagerController::class,'cetak_pdf_manager_reject']);
    Route::get('/report-ict-excel-manager-assignment-request',[IctRequestManagerController::class,'cetak_excel_manager_assignment_request']);
    Route::get('/report-ict-pdf-manager-assignment-request',[IctRequestManagerController::class,'cetak_pdf_manager_assignment_request']);
    Route::get('/report-ict-excel-manager-sedang-dikerjakan',[IctRequestManagerController::class,'cetak_excel_manager_sedang_dikerjakan']);
    Route::get('/report-ict-pdf-manager-sedang-dikerjakan',[IctRequestManagerController::class,'cetak_pdf_manager_sedang_dikerjakan']);
    Route::get('/report-ict-excel-manager-sudah-dikerjakan',[IctRequestManagerController::class,'cetak_excel_manager_sudah_dikerjakan']);
    Route::get('/report-ict-pdf-manager-sudah-dikerjakan',[IctRequestManagerController::class,'cetak_pdf_manager_sudah_dikerjakan']);
    Route::get('/report-ict-excel-manager-selesai',[IctRequestManagerController::class,'cetak_excel_manager_selesai']);
    Route::get('/report-ict-pdf-manager-selesai',[IctRequestManagerController::class,'cetak_pdf_manager_selesai']);

    //ict request requestor
    Route::get('/get-ict',[IctRequestRequestorController::class,'getIct']);
    Route::post('/add-ict',[IctRequestRequestorController::class,'save']);
    Route::get('/updateStatusSubmit/{ireq_id}',[IctRequestRequestorController::class,'updateStatusSubmit']);
    Route::get('/edit-ict/{code}',[IctRequestRequestorController::class,'edit']);
    Route::put('/update-ict/{code}',[IctRequestRequestorController::class,'update']);
    Route::delete('/delete-ict/{ireq_id}',[IctRequestRequestorController::class,'delete']);
    Route::get('/getAddReq',[IctRequestRequestorController::class,'getAddReq']);
    Route::post('/submit-rating',[IctRequestRequestorController::class,'submitRating']);
    Route::get('/getNoreq',[IctRequestRequestorController::class,'getNoreq']);
    Route::get('/getDetail/{noreq}',[IctRequestRequestorController::class,'getDetail']);
    Route::get('/getNameBu/{noreq}/{dtl}',[IctRequestRequestorController::class,'getNameBu']);
    Route::get('/detail-norequest/{code}',[IctRequestRequestorController::class,'detailNoRequest']);
    Route::get('/report-ict-excel-permohonan',[IctRequestRequestorController::class,'cetak_excel_permohonan']);
    Route::get('/report-ict-pdf-permohonan',[IctRequestRequestorController::class,'cetak_pdf_permohonan']);
    Route::get('/report-ict-pdf-tab-reviewer',[IctRequestRequestorController::class,'cetak_pdf_tab_reviewer']);
    Route::get('/report-ict-excel-tab-reviewer',[IctRequestRequestorController::class,'cetak_excel_tab_reviewer']);
    Route::get('/report-ict-excel-verifikasi',[IctRequestRequestorController::class,'cetak_excel_verifikasi']);
    Route::get('/report-ict-pdf-verifikasi',[IctRequestRequestorController::class,'cetak_pdf_verifikasi']);
    Route::get('/report-ict-excel-reject',[IctRequestRequestorController::class,'cetak_excel_reject']);
    Route::get('/report-ict-pdf-reject',[IctRequestRequestorController::class,'cetak_pdf_reject']);
    Route::get('/report-ict-pdf-assignment-request',[IctRequestRequestorController::class,'cetak_pdf_assignment_request']);
    Route::get('/report-ict-excel-assignment-request',[IctRequestRequestorController::class,'cetak_excel_assignment_request']);
    Route::get('/report-ict-excel-sedang-dikerjakan',[IctRequestRequestorController::class,'cetak_excel_sedang_dikerjakan']);
    Route::get('/report-ict-pdf-sedang-dikerjakan',[IctRequestRequestorController::class,'cetak_pdf_sedang_dikerjakan']);
    Route::get('/report-ict-excel-tab-sudah-dikerjakan',[IctRequestRequestorController::class,'cetak_excel_sudah_dikerjakan']);
    Route::get('/report-ict-pdf-tab-sudah-dikerjakan',[IctRequestRequestorController::class,'cetak_pdf_sudah_dikerjakan']);
    Route::get('/report-ict-excel-selesai',[IctRequestRequestorController::class,'cetak_excel_selesai']);
    Route::get('/report-ict-pdf-selesai',[IctRequestRequestorController::class,'cetak_pdf_selesai']);


    //ict request personnel 
    Route::get('/get-sedang-dikerjakan',[IctRequestPersonnelController::class,'getDataPersonnel']);
    Route::get('/get-detail-done-personnel/{code}',[IctRequestPersonnelController::class,'getDetailDone']);
    Route::put('/rejectPersonnel/{ireq_id}',[IctRequestPersonnelController::class,'rejectedByPersonnel']);
    Route::get('/acceptPersonnel/{ireq_id}',[IctRequestPersonnelController::class,'acceptedByPersonnel']);
    Route::put('/save-remark-assigned/{code}',[IctRequestPersonnelController::class,'saveRemark']);
    Route::put('/update-note/{code}',[IctRequestPersonnelController::class,'updateNote']);
    Route::put('/update-status-done/{code}',[IctRequestPersonnelController::class,'updateStatusDone']);
    Route::get('/report-ict-excel-personnel-assignment-request',[IctRequestPersonnelController::class,'cetak_excel_personnel_assignment_request']);
    Route::get('/report-ict-pdf-personnel-assignment-request',[IctRequestPersonnelController::class,'cetak_pdf_personnel_assignment_request']);
    Route::get('/report-ict-excel-personnel-reject',[IctRequestPersonnelController::class,'cetak_excel_personnel_reject']);
    Route::get('/report-ict-pdf-personnel-reject',[IctRequestPersonnelController::class,'cetak_pdf_personnel_reject']);
    Route::get('/report-ict-excel-personnel-sedang-dikerjakan',[IctRequestPersonnelController::class,'cetak_excel_personnel_sedang_dikerjakan']);
    Route::get('/report-ict-pdf-personnel-sedang-dikerjakan',[IctRequestPersonnelController::class,'cetak_pdf_personnel_sedang_dikerjakan']);
    Route::get('/report-ict-excel-personnel-sudah-dikerjakan',[IctRequestPersonnelController::class,'cetak_excel_personnel_sudah_dikerjakan']);
    Route::get('/report-ict-pdf-personnel-sudah-dikerjakan',[IctRequestPersonnelController::class,'cetak_pdf_personnel_sudah_dikerjakan']);
    Route::get('/report-ict-excel-personnel-selesai',[IctRequestPersonnelController::class,'cetak_excel_personnel_selesai']);
    Route::get('/report-ict-pdf-personnel-selesai',[IctRequestPersonnelController::class,'cetak_pdf_personnel_selesai']);
    
    //ict request admin
    Route::get('/get-ict-admin',[IctRequestAdminController::class,'getDataIct']);

    //ict request higher level
    Route::get('/updateStatusPermohonan/{code}',[IctRequestHigherLevelController::class,'approveByAtasan']);
    Route::put('/updateStatusReject/{code}',[IctRequestHigherLevelController::class,'rejectByAtasan']);
    Route::get('/get-permohonan',[IctRequestHigherLevelController::class,'getPermohonan']);   
    Route::get('/get-verif-higher-level/{code}',[IctRequestHigherLevelController::class,'getDetailVerif']);
    Route::get('/ict-detail-higher-level/{code}',[IctRequestHigherLevelController::class,'detailRequest']);
    Route::get('/report-ict-excel-atasan-permohonan',[IctRequestHigherLevelController::class,'cetak_excel_atasan_permohonan']);
    Route::get('/report-ict-pdf-atasan-permohonan',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_permohonan']);
    Route::get('/report-ict-excel-atasan-verifikasi',[IctRequestHigherLevelController::class,'cetak_excel_atasan_verifikasi']);
    Route::get('/report-ict-pdf-atasan-verifikasi',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_verifikasi']);
    Route::get('/report-ict-excel-atasan-reject',[IctRequestHigherLevelController::class,'cetak_excel_atasan_reject']);
    Route::get('/report-ict-pdf-atasan-reject',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_reject']);
    Route::get('/report-ict-excel-atasan-assignment-request',[IctRequestHigherLevelController::class,'cetak_excel_atasan_assignment_request']);
    Route::get('/report-ict-pdf-atasan-assignment-request',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_assignment_request']);
    Route::get('/report-ict-excel-atasan-sedang-dikerjakan',[IctRequestHigherLevelController::class,'cetak_excel_atasan_sedang_dikerjakan']);
    Route::get('/report-ict-pdf-atasan-sedang-dikerjakan',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_sedang_dikerjakan']);
    Route::get('/report-ict-excel-atasan-sudah-dikerjakan',[IctRequestHigherLevelController::class,'cetak_excel_atasan_sudah_dikerjakan']);
    Route::get('/report-ict-pdf-atasan-sudah-dikerjakan',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_sudah_dikerjakan']);
    Route::get('/report-ict-atasan-excel-selesai',[IctRequestHigherLevelController::class,'cetak_excel_atasan_selesai']);
    Route::get('/report-ict-atasan-pdf-selesai',[IctRequestHigherLevelController::class,'cetak_pdf_atasan_selesai']);


    //ict request (detail)
    Route::get('/get-catalog-request/{tipereq}',[IctDetailController::class,'CatalogRequest']);
    Route::get('/ict-detail/{code}',[IctDetailController::class,'index']);
    Route::get('/ict-detail-penugasan/{code}',[IctDetailController::class,'detailPenugasan']);
    Route::get('/get-detail-done/{code}',[IctDetailController::class,'getDetailDone']);
    Route::post('/add-ict-detail/{code}',[IctDetailController::class,'save']);
    Route::get('/edit-ict-detail/{ireq}/{code}',[IctDetailController::class,'edit']);
    Route::put('/update-ict-detail/{ireq}/{code}',[IctDetailController::class,'update']);
    Route::delete('/delete-ict-detail/{ireqd_id}/{code}',[IctDetailController::class,'delete']);
    Route::get('/get-noreq/{code}',[IctDetailController::class,'getNo_req']);
    Route::get('/get-verif/{code}',[IctDetailController::class,'getDetailVerif']);
    Route::get('/detail/{ireqd_id}/{ireq_id}',[IctDetailController::class,'getDetail']);
    
    //laporan
    // Route::get('/req-per-status-excel',[LaporanController::class,'cetak_excel_per_status']);
    // Route::get('/req-per-status-pdf',[LaporanController::class,'cetak_pdf_per_status']);
    // Route::get('/req-div-req-per-bulan-pdf/{tahunnRequestor}/{bulanRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_bulan']);
    // Route::get('/req-div-req-per-bulan-excel/{tahunnRequestor}/{bulanRequestor}',[LaporanController::class,'cetak_excel_div_req_per_bulan']);
    // Route::get('/req-div-user-per-bulan-excel/{tahunnUser}/{bulanUser}',[LaporanController::class,'cetak_excel_div_user_per_bulan']);
    // Route::get('/req-div-user-per-bulan-pdf/{tahunnUser}/{bulanUser}',[LaporanController::class,'cetak_pdf_div_user_per_bulan']);
    // Route::get('/req-div-req-per-tahun-excel/{tahunRequestor}',[LaporanController::class,'cetak_excel_div_req_per_tahun']);
    // Route::get('/req-div-req-per-tahun-pdf/{tahunRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_tahun']);
    // Route::get('/req-div-user-per-tahun-excel/{tahunUser}',[LaporanController::class,'cetak_excel_div_user_per_tahun']);
    // Route::get('/req-div-user-per-tahun-pdf/{tahunUser}',[LaporanController::class,'cetak_pdf_div_user_per_tahun']);
    // Route::get('/req-div-req-per-status-excel/{statusRequestor}',[LaporanController::class,'cetak_excel_div_req_per_status']);
    // Route::get('/req-div-req-per-status-pdf/{statusRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_status']);
    // Route::get('/req-div-user-per-status-excel/{statusUser}',[LaporanController::class,'cetak_excel_div_user_per_status']);
    // Route::get('/req-div-user-per-status-pdf/{statusUser}',[LaporanController::class,'cetak_pdf_div_user_per_status']);
    // Route::get('/req-per-personnel-excel',[LaporanController::class,'cetak_excel_per_personnel']);
    // Route::get('/req-per-personnel-pdf',[LaporanController::class,'cetak_pdf_per_personnel']);
    // Route::get('/req-per-status-per-personnel-pdf/{ictPersonnel}',[LaporanController::class,'cetak_pdf_per_status_per_personnel']);
    // Route::get('/req-per-status-per-personnel-excel/{ictPersonnel}',[LaporanController::class,'cetak_excel_per_status_per_personnel']);
    // Route::post('/filterByDate',[LaporanController::class,'filterByDate']);
    // Route::get('/cetak-pdf-filter-ict/{start}/{end}/{status}',[LaporanController::class,'cetakPdf']);
    
    //bisnis
    Route::get('/get-bisnis',[BisnisController::class,'getBisnis']);
    Route::get('/report-cash-pdf',[CashController::class,'cetak_pdf']);
    Route::get('/report-cash-excel',[CashController::class,'cetak_excel']);
    Route::get('/report-supplier-pdf',[SupplierController::class,'cetak_pdf']);
    Route::get('/report-supplier-excel',[SupplierController::class,'cetak_excel']);
    //report request (requestor)
   

    //report detail request (requestor)
    Route::get('/report-ict-detail-pdf/{code}',[IctDetailController::class,'cetak_pdf']);
    Route::get('/report-ict-detaill-pdf/{code}',[IctDetailController::class,'cetak_pdff']);

    Route::get('/report-ict-detail-excel/{code}',[IctDetailController::class,'cetak_excel']);
    Route::get('/report-ict-detail-pdf-tab-reviewer/{code}',[IctDetailController::class,'cetak_pdf_tab_reviewer']);
    Route::get('/report-ict-detail-excel-tab-reviewer/{code}',[IctDetailController::class,'cetak_excel_tab_reviewer']);
    Route::get('/report-ict-detail-pdf-tab-verifikasi/{code}',[IctDetailController::class,'cetak_pdf_tab_verifikasi']);
    Route::get('/report-ict-detail-excel-tab-verifikasi/{code}',[IctDetailController::class,'cetak_excel_tab_verifikasi']);
    Route::get('/report-ict-detail-pdf-tab-reject/{code}',[IctDetailController::class,'cetak_pdf_reject']);
    Route::get('/report-ict-detail-excel-tab-reject/{code}',[IctDetailController::class,'cetak_excel_reject']);
    Route::get('/print-out-ict-request/{code}',[IctDetailController::class,'printout_ictrequest']);
    Route::get('/report-ict-detail-excel-tab-sedang-dikerjakan/{code}',[IctDetailController::class,'cetak_excel_sedang_dikerjakan']);

 
});
    Route::get('/req-per-status-excel',[LaporanController::class,'cetak_excel_per_status']);
    Route::get('/req-per-status-pdf',[LaporanController::class,'cetak_pdf_per_status']);
    Route::get('/req-div-req-per-bulan-pdf/{tahunnRequestor}/{bulanRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_bulan']);
    Route::get('/req-div-req-per-bulan-excel/{tahunnRequestor}/{bulanRequestor}',[LaporanController::class,'cetak_excel_div_req_per_bulan']);
    Route::get('/req-div-user-per-bulan-excel/{tahunnUser}/{bulanUser}',[LaporanController::class,'cetak_excel_div_user_per_bulan']);
    Route::get('/req-div-user-per-bulan-pdf/{tahunnUser}/{bulanUser}',[LaporanController::class,'cetak_pdf_div_user_per_bulan']);
    Route::get('/req-div-req-per-tahun-excel/{tahunRequestor}',[LaporanController::class,'cetak_excel_div_req_per_tahun']);
    Route::get('/req-div-req-per-tahun-pdf/{tahunRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_tahun']);
    Route::get('/req-div-user-per-tahun-excel/{tahunUser}',[LaporanController::class,'cetak_excel_div_user_per_tahun']);
    Route::get('/req-div-user-per-tahun-pdf/{tahunUser}',[LaporanController::class,'cetak_pdf_div_user_per_tahun']);
    Route::get('/req-div-req-per-status-excel/{statusRequestor}',[LaporanController::class,'cetak_excel_div_req_per_status']);
    Route::get('/req-div-req-per-status-pdf/{statusRequestor}',[LaporanController::class,'cetak_pdf_div_req_per_status']);
    Route::get('/req-div-user-per-status-excel/{statusUser}',[LaporanController::class,'cetak_excel_div_user_per_status']);
    Route::get('/req-div-user-per-status-pdf/{statusUser}',[LaporanController::class,'cetak_pdf_div_user_per_status']);
    Route::get('/req-per-personnel-excel',[LaporanController::class,'cetak_excel_per_personnel']);
    Route::get('/req-per-personnel-pdf',[LaporanController::class,'cetak_pdf_per_personnel']);
    Route::get('/req-per-status-per-personnel-pdf/{ictPersonnel}',[LaporanController::class,'cetak_pdf_per_status_per_personnel']);
    Route::get('/req-per-status-per-personnel-excel/{ictPersonnel}',[LaporanController::class,'cetak_excel_per_status_per_personnel']);
    Route::post('/filterByDate',[LaporanController::class,'filterByDate']);
    Route::get('/cetak-pdf-filter-ict/{start}/{end}/{status}',[LaporanController::class,'cetakPdf']);