<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/cek-verif-id/{code}','LinkController@cekVerif');
Route::post('/login', 'LoginController@index');
Route::post('/login-intranet', 'LoginController@loginFromIntranet');
Route::get('/detail-peripherall/{invent_code}','MasterController@detailPeripheral');
Route::post('/login-approval', 'LoginController@loginFromEmail');
Route::get('/logout', 'LoginController@logout')->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/cek-user','LoginController@cekUser');
    Route::get('/user','LoginController@show');
//referensi_location
    Route::get('/loc', 'LocationController@index');
    Route::post('/add-loc','LocationController@save');
    Route::get('/edit-loc/{code}','LocationController@edit');
    Route::put('/update-loc/{code}','LocationController@update');
    Route::delete('/delete-loc/{loc_code}','LocationController@delete');
    Route::get('/ref-loc', 'LocationController@getLocation');

//referensi_lookups
    Route::get('/ref', 'LookupsController@index');
    Route::get('/ref-lookup-brand', 'LookupsController@lookupBrand');
    Route::get('/ref-lookup-kategori', 'LookupsController@lookupKategori');
    Route::get('/ref-lookup-service', 'LookupsController@lookupService');
    Route::post('/add-ref','LookupsController@save');
    Route::get('/edit-ref/{code}/{type}','LookupsController@edit');
    Route::put('/update-ref/{code}/{type}','LookupsController@update');
    Route::delete('/delete-ref/{lookup_code}/{lookup_type}','LookupsController@delete');
    Route::get('/report-lookups-pdf','LookupsController@cetak_pdf');
    Route::get('/report-lookups-excel','LookupsController@cetak_excel');

//lookup kategori
    Route::get('/ref-lookup-kategori', 'LookupsKategoriController@lookupKategori');
    Route::post('/add-kategori','LookupsKategoriController@saveKategori');
    Route::get('/edit-kategori/{code}/{type}','LookupsKategoriController@editKategori');
    Route::put('/update-kategori/{code}/{type}','LookupsKategoriController@updateKategori');
    Route::delete('/delete-kategori/{lookup_code}/{lookup_type}','LookupsKategoriController@deleteKategori');
//lookup brand
    Route::get('/ref-lookup-brand', 'LookupsBrandController@lookupBrand');
    Route::post('/add-brand','LookupsBrandController@saveBrand');
    Route::get('/edit-brand/{code}/{type}','LookupsBrandController@editBrand');
    Route::put('/update-brand/{code}/{type}','LookupsBrandController@updateBrand');
    Route::delete('/delete-brand/{lookup_code}/{lookup_type}','LookupsBrandController@deleteBrand');

//supplier
    Route::get('/supp', 'SupplierController@index');
    Route::post('/add-supp', 'SupplierController@save');
    Route::get('/edit-supp/{code}', 'SupplierController@edit');
    Route::get('/show-supp/{suplier_code}', 'SupplierController@show');
    Route::put('/update-supp/{code}', 'SupplierController@update');
    Route::delete('/delete-supp/{suplier_code}', 'SupplierController@delete');

    //pekerja
    Route::get('/get-pekerja','PekerjaController@getPekerja');
    
    //master peripheral
    Route::get('/mas','MasterController@index');
    Route::post('/add-mas','MasterController@save');
    Route::get('/edit-mas/{code}','MasterController@edit');
    Route::put('/update-mas/{code}','MasterController@update');
    Route::delete('/delete-mas/{invent_code}','MasterController@delete');
    Route::get('/get-kode','MasterController@getKode');
    Route::get('/get-kode-peripheral','MasterController@getPeripheral');
    Route::get('/get-sn-peripheral/{kode}','MasterController@getSn');
    Route::get('/get-kode-ict/{code}','MasterController@getKodeIct');
    Route::get('/rsrcsupp','MasterController@getAddMaster');
    Route::get('/getImage/{kode}','MasterController@getImage');
    Route::get('/getBarcode/{invent_code}','MasterController@getBarcode');
    Route::get('/detail-peripheral/{invent_code}','MasterController@detailPeripheral');
    Route::get('/report-master-pdf','MasterController@cetak_pdf');
    Route::get('/report-master-excel','MasterController@cetak_excel');
    //Route::get('/get-kode','MasterController@getKode');

    //master peripheral detail
    Route::get('/master-detail/{code}','MasterDetailController@index');
    Route::get('/add-master-detail/{code}','MasterDetailController@addDetail');
    Route::post('/save-master-detail','MasterDetailController@SaveDetail');
    Route::get('/edit-master-detail/{code}','MasterDetailController@editDetail');
    Route::put('/update-master-detail/{code}','MasterDetailController@updateDetail');
    Route::delete('/delete-master-detail/{invent_code_dtl}','MasterDetailController@deleteDetail');

    //mutasi peripheral
    Route::get('/mut','MutasiController@index');
    Route::post('/add-mut','MutasiController@save');
    Route::get('/edit-mut/{code}','MutasiController@edit');
    Route::put('/update-mut/{code}','MutasiController@update');
    Route::delete('/delete-mut/{imutasi_id}','MutasiController@delete');
    Route::get('/report-mutasi-pdf','MutasiController@cetak_pdf');
    Route::get('/report-mutasi-excel','MutasiController@cetak_excel');

    //pembelian peripheral
    Route::get('/pem','PembelianController@index');
    Route::get('/rsrcsuppo','PembelianController@getAddPemb');
    Route::post('/add-pem','PembelianController@save');
    Route::get('/edit-pem/{code}','PembelianController@edit');
    Route::put('/update-pem/{code}','PembelianController@update');
    Route::delete('/delete-pem/{purchase_id}','PembelianController@delete');
    Route::get('/report-pem-pdf','PembelianController@cetak_pdf');
    Route::get('/report-pem-excel','PembelianController@cetak_excel');

    //Detail pembelian peripheral
    Route::get('/detail-pem/{code}','PembelianDetailController@index');
    Route::post('/add-detail-pem/{code}','PembelianDetailController@save');
    Route::get('/edit-detail-pem/{purchase}','PembelianDetailController@edit');
    Route::put('/update-detail-pem/{code}/{purchase}','PembelianDetailController@update');
    Route::delete('/delete-detail-pem/{code}/{dpurchase_id}','PembelianDetailController@delete');
    Route::get('/getSuppDate/{code}','PembelianDetailController@getSuppDate');
    Route::get('/getValuta/{code}','PembelianDetailController@getValuta');
    Route::get('/report-pem-detail-pdf/{purchase_id}','PembelianDetailController@cetak_pdf');
    Route::get('/report-pem-detail-excel/{purchase_id}','PembelianDetailController@cetak_excel');

    //cash advance
    Route::get('/cash','CashController@index');
    Route::post('/add-cash','CashController@save');
    Route::get('/edit-cash/{code}','CashController@edit');
    Route::get('/detail-request/{code}','CashController@detail');
    Route::put('/update-cash/{code}','CashController@update');
    Route::get('/list-no-request','CashController@getNoRequest');
    Route::delete('/delete-cash/{ca_id}','CashController@delete');
    //Route::get('/report-cash-pdf','CashController@cetak_pdf');
    //Route::get('/report-cash-excel','CashController@cetak_excel');

    //payment request
    Route::get('/payment-request','PaymentController@index');
    Route::post('/add-payment-request','PaymentController@save');
    Route::get('/edit-payment-request/{code}','PaymentController@edit');
    Route::put('/update-payment-request/{code}','PaymentController@update');
    Route::delete('/delete-payment-request/{pr_id}','PaymentController@delete');

    //mng_user
    Route::get('/get-user','MngUserController@index');
    Route::post('/add-user','MngUserController@save');
    Route::get('/detail-add-request-user','MngUserController@detailAddRequest');
    Route::get('/edit-user/{code}','MngUserController@edit');
    Route::put('/update-user/{code}','MngUserController@update');
    Route::delete('/delete-user/{usr_id}','MngUserController@delete');
    //divisi_refs
    Route::get('/divisi','DivisiRefsController@index');
    Route::get('/get-divisi','DivisiRefsController@getDivisi');
    Route::get('/get-verificator/{div_id}','DivisiRefsController@getVerif');
    Route::get('/edit-divisi/{code}','DivisiRefsController@edit');
    Route::put('/update-divisi/{code}','DivisiRefsController@update');
    Route::delete('/delete-divisi/{div_id}','DivisiRefsController@delete');
    //mng_roles
    Route::get('/role','MngRolesController@index');
    Route::post('/save-role','MngRolesController@save');
    Route::get('/get-role','MngRolesController@getRole');
    Route::get('/edit-role/{code}','MngRolesController@edit');
    Route::put('/update-role/{code}','MngRolesController@update');
    Route::delete('/delete-role/{rol_id}','MngRolesController@delete');

    //Mng_usr_roles
    Route::get('/menu-user','MngUsrRoleController@getRole');
    Route::post('/save-usr-role','MngUsrRoleController@save');
    Route::get('/edit-usr-role/{code}','MngUsrRoleController@edit');
    Route::put('/update-usr-role/{code}','MngUsrRoleController@update');
    Route::get('/cek-role','MngUsrRoleController@cekRole');

    //Mng_role_menu
    Route::post('/save-role-menu','MngRoleMenuController@save');
    Route::get('/get-menu','MngRoleMenuController@getMenu');
    Route::get('/edit-role-menu/{code}','MngRoleMenuController@edit');
    Route::put('/update-role-menu/{code}','MngRoleMenuController@update');

    //Mng_module
    Route::get('/module','ModuleController@index');
    Route::get('/get-module','ModuleController@getModule');
    Route::post('/save-module','ModuleController@save');
    Route::get('/edit-module/{code}','ModuleController@edit');
    Route::put('/update-module/{code}','ModuleController@update');
    Route::delete('/delete-module/{mod_id}','ModuleController@delete');
    // catalog
    Route::get('/get-catalog','CatalogController@index');
    Route::get('/get-parent-catalog','CatalogController@parentCatalog');
    Route::post('/save-catalog','CatalogController@save');
    Route::get('/edit-catalog/{code}','CatalogController@edit');
    Route::put('/update-catalog/{code}','CatalogController@update');
    Route::delete('/delete-catalog/{catalog_id}','CatalogController@delete');

    //Mng_menu
    Route::post('/get-menu-user','MngMenuController@getMenuUser');
    Route::get('/menu','MngMenuController@index');
    Route::get('/get-parent','MngMenuController@getParent');
    Route::get('/edit-menu/{code}','MngMenuController@edit');
    Route::post('/save-menu','MngMenuController@save');
    Route::put('/update-menu/{code}','MngMenuController@update');
    Route::delete('/delete-menu/{menu_id}','MngMenuController@delete');

    //dashboard
    Route::get('/getCountUser','DashboardController@countUser');
    Route::get('/getCountDivisi1','DashboardController@countDivisi1');
    Route::get('/getCountReviewerBentu','DashboardController@CountReviewerBentu');
    Route::get('/getCountReviewerKurau','DashboardController@CountReviewerKurau');
    Route::get('/getCountReviewerJakarta','DashboardController@CountReviewerJakarta');
    Route::get('/getCountDivisi3','DashboardController@countDivisi3');
    Route::get('/getCountDivisi4','DashboardController@countDivisi4');
    Route::get('/getCountAdmin','DashboardController@countAdmin');
    Route::get('/status-per-divisi','DashboardController@countPerStatusPerDivisi');
    Route::get('/count-per-status','DashboardController@countPerStatus');
    Route::get('/get-tahun','DashboardController@getTahun');
    Route::get('/get-tahun-user/{bulanUser}','DashboardController@getTahunUser');
    Route::get('/get-tahun-requestor/{bulanRequestor}','DashboardController@getTahunRequestor');
    Route::get('/get-bulan','DashboardController@getBulan');
    Route::get('/count-per-divuser-tahun/{tahunUser}','DashboardController@countPerDivUserTahun');
    Route::get('/count-per-divreq-tahun/{tahunRequestor}','DashboardController@countPerDivRequestorTahun');
    Route::get('/count-per-divuser-bulan/{tahunnUser}/{bulanUser}','DashboardController@countPerDivUserBulan');
    Route::get('/count-per-divreq-bulan/{tahunnRequestor}/{bulanRequestor}','DashboardController@countPerDivRequestorBulan');
    Route::get('/get-status','DashboardController@getStatus');
    Route::get('/count-per-divuser-status/{statusUser}','DashboardController@countPerDivUserStatus');
    Route::get('/count-per-divreq-status/{statusRequestor}','DashboardController@countPerDivRequestorStatus');
    Route::get('/count-per-personel','DashboardController@countPerPersonnel');
    Route::get('/get-personnel','DashboardController@getPersonnel');
    Route::get('/count-per-status-ict/{ictPersonnel}','DashboardController@countPerStatusIct');

    //ict request reviewer
    
    Route::get('/ict-detail-penugasan-reviewer/{code}','IctRequestReviewerController@detailPenugasan');
    Route::get('/ict-detail-reviewer/{code}','IctRequestReviewerController@index');
    Route::get('/edit-ict-detail-reviewer/{ireq}/{code}','IctRequestReviewerController@editDataDetail');
    Route::get('/get-data-reviewer','IctRequestReviewerController@getDataReviewer');  
    Route::post('/updateAssign','IctRequestReviewerController@updateAssignPerRequest');
    Route::put('/updateAssignPerDetail/{code}','IctRequestReviewerController@updateAssignPerDetail');
    Route::get('/appd/{ireqd_id}/{code}','IctRequestReviewerController@assignedPersonnelDetail');
    Route::get('/updateStatusPenugasan/{ireq_id}','IctRequestReviewerController@updateStatusPenugasan');
    Route::get('/updateStatusClosing/{ireq_id}','IctRequestReviewerController@updateStatusClosing');
    Route::put('/reject-by-reviewer/{code}','IctRequestReviewerController@rejectReviewer');
    Route::get('/naa/{ireq_id}','IctRequestReviewerController@needApprovalAtasan');
    Route::get('/nam/{ireq_id}','IctRequestReviewerController@needApprovalManager');
    Route::post('/aprr','IctRequestReviewerController@asignPerRequestReviewer');
    Route::get('/sapr/{ireq_id}','IctRequestReviewerController@submitAssignPerRequest');
    Route::get('/dataIct','IctRequestReviewerController@getDataIct');
    Route::get('/getdataIctByStatus/{statuss}','IctRequestReviewerController@getdataIctByStatus');
    Route::get('/detail-request-reviewer/{code}','IctRequestReviewerController@detailRequestReviewer');
    Route::get('/get-remark-reviewer/{ireq_id}','IctRequestReviewerController@getRemarkReviewer');
    Route::post('/save-remark-reviewer','IctRequestReviewerController@SaveRemarkReviewer');
    Route::get('/updateStatusClosingDetail/{ireqd_id}/{ireq_no}','IctRequestReviewerController@updateStatusClosingDetail');
    Route::get('/detailrequest-tomail/{ireq_id}','IctRequestReviewerController@detailRequestToMail');
    Route::post('/sendMailtoRequestor','IctRequestReviewerController@sendMailtoRequestor');
    Route::put('/updateAssignPerDetailFromReject/{code}','IctRequestReviewerController@updateAssignFromReject');
    Route::get('/report-ict-excel-reviewer-permohonan','IctRequestReviewerController@cetak_excel_reviewer_permohonan');
    Route::get('/report-ict-pdf-reviewer-permohonan','IctRequestReviewerController@cetak_pdf_reviewer_permohonan');
    Route::get('/report-ict-excel-reviewer-atasan-divisi','IctRequestReviewerController@cetak_excel_reviewer_atasan_divisi');
    Route::get('/report-ict-pdf-reviewer-atasan-divisi','IctRequestReviewerController@cetak_pdf_reviewer_atasan_divisi');
    Route::get('/report-ict-excel-reviewer-ict-manager','IctRequestReviewerController@cetak_excel_reviewer_ict_manager');
    Route::get('/report-ict-pdf-reviewer-ict-manager','IctRequestReviewerController@cetak_pdf_reviewer_ict_manager');
    Route::get('/report-ict-excel-reviewer-reject','IctRequestReviewerController@cetak_excel_reviewer_reject');
    Route::get('/report-ict-pdf-reviewer-reject','IctRequestReviewerController@cetak_pdf_reviewer_reject');
    Route::get('/report-ict-excel-reviewer-assignment-request','IctRequestReviewerController@cetak_excel_reviewer_assignment_request');
    Route::get('/report-ict-pdf-reviewer-assignment-request','IctRequestReviewerController@cetak_pdf_reviewer_assignment_request');
    Route::get('/report-ict-excel-reviewer-sedang-dikerjakan','IctRequestReviewerController@cetak_excel_reviewer_sedang_dikerjakan');
    Route::get('/report-ict-pdf-reviewer-sedang-dikerjakan','IctRequestReviewerController@cetak_pdf_reviewer_sedang_dikerjakan');
    Route::get('/report-ict-excel-reviewer-sudah-dikerjakan','IctRequestReviewerController@cetak_excel_reviewer_sudah_dikerjakan');
    Route::get('/report-ict-pdf-reviewer-sudah-dikerjakan','IctRequestReviewerController@cetak_pdf_reviewer_sudah_dikerjakan');
    Route::get('/report-ict-excel-reviewer-selesai','IctRequestReviewerController@cetak_excel_reviewer_selesai');
    Route::get('/report-ict-pdf-reviewer-selesai','IctRequestReviewerController@cetak_pdf_reviewer_selesai');

    //ict request manager
    Route::get('/get-data-manager','IctRequestManagerController@getDataManager');
    Route::get('/ict-detail-manager/{code}','IctRequestManagerController@detailRequest');
    Route::get('/ict-detail-penugasan-manager/{code}','IctRequestManagerController@detailPenugasan');
    Route::get('/get-data-manager-verifikasi/{code}','IctRequestManagerController@getDataManagerVerifikasi');
    Route::put('/abm/{code}','IctRequestManagerController@approveByManager');
    Route::put('/rbm/{code}','IctRequestManagerController@rejectByManager');
    Route::get('/get-verif-manager/{code}','IctRequestManagerController@getDetailVerif');
    Route::get('/report-ict-excel-manager-permohonan','IctRequestManagerController@cetak_excel_manager_permohonan');
    Route::get('/report-ict-pdf-manager-permohonan','IctRequestManagerController@cetak_pdf_manager_permohonan');
    Route::get('/report-ict-excel-manager-verifikasi','IctRequestManagerController@cetak_excel_manager_verifikasi');
    Route::get('/report-ict-pdf-manager-verifikasi','IctRequestManagerController@cetak_pdf_manager_verifikasi');
    Route::get('/report-ict-excel-manager-reject','IctRequestManagerController@cetak_excel_manager_reject');
    Route::get('/report-ict-pdf-manager-reject','IctRequestManagerController@cetak_pdf_manager_reject');
    Route::get('/report-ict-excel-manager-assignment-request','IctRequestManagerController@cetak_excel_manager_assignment_request');
    Route::get('/report-ict-pdf-manager-assignment-request','IctRequestManagerController@cetak_pdf_manager_assignment_request');
    Route::get('/report-ict-excel-manager-sedang-dikerjakan','IctRequestManagerController@cetak_excel_manager_sedang_dikerjakan');
    Route::get('/report-ict-pdf-manager-sedang-dikerjakan','IctRequestManagerController@cetak_pdf_manager_sedang_dikerjakan');
    Route::get('/report-ict-excel-manager-sudah-dikerjakan','IctRequestManagerController@cetak_excel_manager_sudah_dikerjakan');
    Route::get('/report-ict-pdf-manager-sudah-dikerjakan','IctRequestManagerController@cetak_pdf_manager_sudah_dikerjakan');
    Route::get('/report-ict-excel-manager-selesai','IctRequestManagerController@cetak_excel_manager_selesai');
    Route::get('/report-ict-pdf-manager-selesai','IctRequestManagerController@cetak_pdf_manager_selesai');

    //ict request requestor
    Route::get('/get-ict','IctRequestRequestorController@getIct');
    Route::post('/add-ict','IctRequestRequestorController@save');
    Route::get('/updateStatusSubmit/{ireq_id}','IctRequestRequestorController@updateStatusSubmit');
    Route::get('/edit-ict/{code}','IctRequestRequestorController@edit');
    Route::put('/update-ict/{code}','IctRequestRequestorController@update');
    Route::delete('/delete-ict/{ireq_id}','IctRequestRequestorController@delete');
    Route::get('/getAddReq','IctRequestRequestorController@getAddReq');
    Route::post('/submit-rating','IctRequestRequestorController@submitRating');
    Route::get('/getNoreq','IctRequestRequestorController@getNoreq');
    Route::get('/getDetail/{noreq}','IctRequestRequestorController@getDetail');
    Route::get('/getNameBu/{noreq}/{dtl}','IctRequestRequestorController@getNameBu');
    Route::get('/detail-norequest/{code}','IctRequestRequestorController@detailNoRequest');
    Route::get('/report-ict-excel-permohonan','IctRequestRequestorController@cetak_excel_permohonan');
    Route::get('/report-ict-pdf-permohonan','IctRequestRequestorController@cetak_pdf_permohonan');
    Route::get('/report-ict-pdf-tab-reviewer','IctRequestRequestorController@cetak_pdf_tab_reviewer');
    Route::get('/report-ict-excel-tab-reviewer','IctRequestRequestorController@cetak_excel_tab_reviewer');
    Route::get('/report-ict-excel-verifikasi','IctRequestRequestorController@cetak_excel_verifikasi');
    Route::get('/report-ict-pdf-verifikasi','IctRequestRequestorController@cetak_pdf_verifikasi');
    Route::get('/report-ict-excel-reject','IctRequestRequestorController@cetak_excel_reject');
    Route::get('/report-ict-pdf-reject','IctRequestRequestorController@cetak_pdf_reject');
    Route::get('/report-ict-pdf-assignment-request','IctRequestRequestorController@cetak_pdf_assignment_request');
    Route::get('/report-ict-excel-assignment-request','IctRequestRequestorController@cetak_excel_assignment_request');
    Route::get('/report-ict-excel-sedang-dikerjakan','IctRequestRequestorController@cetak_excel_sedang_dikerjakan');
    Route::get('/report-ict-pdf-sedang-dikerjakan','IctRequestRequestorController@cetak_pdf_sedang_dikerjakan');
    Route::get('/report-ict-excel-tab-sudah-dikerjakan','IctRequestRequestorController@cetak_excel_sudah_dikerjakan');
    Route::get('/report-ict-pdf-tab-sudah-dikerjakan','IctRequestRequestorController@cetak_pdf_sudah_dikerjakan');
    Route::get('/report-ict-excel-selesai','IctRequestRequestorController@cetak_excel_selesai');
    Route::get('/report-ict-pdf-selesai','IctRequestRequestorController@cetak_pdf_selesai');


    //ict request personnel 
    Route::get('/get-sedang-dikerjakan','IctRequestPersonnelController@getDataPersonnel');
    Route::get('/get-detail-done-personnel/{code}','IctRequestPersonnelController@getDetailDone');
    Route::put('/rejectPersonnel/{ireq_id}','IctRequestPersonnelController@rejectedByPersonnel');
    Route::get('/acceptPersonnel/{ireq_id}','IctRequestPersonnelController@acceptedByPersonnel');
    Route::put('/save-remark-assigned/{code}','IctRequestPersonnelController@saveRemark');
    Route::put('/update-note/{code}','IctRequestPersonnelController@updateNote');
    Route::put('/update-status-done/{code}','IctRequestPersonnelController@updateStatusDone');
    Route::get('/report-ict-excel-personnel-assignment-request','IctRequestPersonnelController@cetak_excel_personnel_assignment_request');
    Route::get('/report-ict-pdf-personnel-assignment-request','IctRequestPersonnelController@cetak_pdf_personnel_assignment_request');
    Route::get('/report-ict-excel-personnel-reject','IctRequestPersonnelController@cetak_excel_personnel_reject');
    Route::get('/report-ict-pdf-personnel-reject','IctRequestPersonnelController@cetak_pdf_personnel_reject');
    Route::get('/report-ict-excel-personnel-sedang-dikerjakan','IctRequestPersonnelController@cetak_excel_personnel_sedang_dikerjakan');
    Route::get('/report-ict-pdf-personnel-sedang-dikerjakan','IctRequestPersonnelController@cetak_pdf_personnel_sedang_dikerjakan');
    Route::get('/report-ict-excel-personnel-sudah-dikerjakan','IctRequestPersonnelController@cetak_excel_personnel_sudah_dikerjakan');
    Route::get('/report-ict-pdf-personnel-sudah-dikerjakan','IctRequestPersonnelController@cetak_pdf_personnel_sudah_dikerjakan');
    Route::get('/report-ict-excel-personnel-selesai','IctRequestPersonnelController@cetak_excel_personnel_selesai');
    Route::get('/report-ict-pdf-personnel-selesai','IctRequestPersonnelController@cetak_pdf_personnel_selesai');
    
    //ict request admin
    Route::get('/get-ict-admin','IctRequestAdminController@getDataIct');

    //ict request higher level
    Route::get('/updateStatusPermohonan/{code}','IctRequestHigherLevelController@approveByAtasan');
    Route::put('/updateStatusReject/{code}','IctRequestHigherLevelController@rejectByAtasan');
    Route::get('/get-permohonan','IctRequestHigherLevelController@getPermohonan');   
    Route::get('/get-verif-higher-level/{code}','IctRequestHigherLevelController@getDetailVerif');
    Route::get('/ict-detail-higher-level/{code}','IctRequestHigherLevelController@detailRequest');
    Route::get('/report-ict-excel-atasan-permohonan','IctRequestHigherLevelController@cetak_excel_atasan_permohonan');
    Route::get('/report-ict-pdf-atasan-permohonan','IctRequestHigherLevelController@cetak_pdf_atasan_permohonan');
    Route::get('/report-ict-excel-atasan-verifikasi','IctRequestHigherLevelController@cetak_excel_atasan_verifikasi');
    Route::get('/report-ict-pdf-atasan-verifikasi','IctRequestHigherLevelController@cetak_pdf_atasan_verifikasi');
    Route::get('/report-ict-excel-atasan-reject','IctRequestHigherLevelController@cetak_excel_atasan_reject');
    Route::get('/report-ict-pdf-atasan-reject','IctRequestHigherLevelController@cetak_pdf_atasan_reject');
    Route::get('/report-ict-excel-atasan-assignment-request','IctRequestHigherLevelController@cetak_excel_atasan_assignment_request');
    Route::get('/report-ict-pdf-atasan-assignment-request','IctRequestHigherLevelController@cetak_pdf_atasan_assignment_request');
    Route::get('/report-ict-excel-atasan-sedang-dikerjakan','IctRequestHigherLevelController@cetak_excel_atasan_sedang_dikerjakan');
    Route::get('/report-ict-pdf-atasan-sedang-dikerjakan','IctRequestHigherLevelController@cetak_pdf_atasan_sedang_dikerjakan');
    Route::get('/report-ict-excel-atasan-sudah-dikerjakan','IctRequestHigherLevelController@cetak_excel_atasan_sudah_dikerjakan');
    Route::get('/report-ict-pdf-atasan-sudah-dikerjakan','IctRequestHigherLevelController@cetak_pdf_atasan_sudah_dikerjakan');
    Route::get('/report-ict-atasan-excel-selesai','IctRequestHigherLevelController@cetak_excel_atasan_selesai');
    Route::get('/report-ict-atasan-pdf-selesai','IctRequestHigherLevelController@cetak_pdf_atasan_selesai');


    //ict request (detail)
    Route::get('/getAddDetail','IctDetailController@getAddDetail');
    Route::get('/get-catalog-request/{tipereq}','IctDetailController@CatalogRequest');
    Route::get('/ict-detail/{code}','IctDetailController@index');
    Route::get('/ict-detail-penugasan/{code}','IctDetailController@detailPenugasan');
    Route::get('/get-detail-done/{code}','IctDetailController@getDetailDone');
    Route::post('/add-ict-detail/{code}','IctDetailController@save');
    Route::get('/edit-ict-detail/{ireq}/{code}','IctDetailController@edit');
    Route::put('/update-ict-detail/{ireq}/{code}','IctDetailController@update');
    Route::delete('/delete-ict-detail/{ireqd_id}/{code}','IctDetailController@delete');
    Route::get('/get-noreq/{code}','IctDetailController@getNo_req');
    Route::get('/get-verif/{code}','IctDetailController@getDetailVerif');
    Route::get('/detail/{ireqd_id}/{ireq_id}','IctDetailController@getDetail');
    
    //laporan
    // Route::get('/req-per-status-excel','LaporanController@cetak_excel_per_status');
    // Route::get('/req-per-status-pdf','LaporanController@cetak_pdf_per_status');
    // Route::get('/req-div-req-per-bulan-pdf/{tahunnRequestor}/{bulanRequestor}','LaporanController@cetak_pdf_div_req_per_bulan');
    // Route::get('/req-div-req-per-bulan-excel/{tahunnRequestor}/{bulanRequestor}','LaporanController@cetak_excel_div_req_per_bulan');
    // Route::get('/req-div-user-per-bulan-excel/{tahunnUser}/{bulanUser}','LaporanController@cetak_excel_div_user_per_bulan');
    // Route::get('/req-div-user-per-bulan-pdf/{tahunnUser}/{bulanUser}','LaporanController@cetak_pdf_div_user_per_bulan');
    // Route::get('/req-div-req-per-tahun-excel/{tahunRequestor}','LaporanController@cetak_excel_div_req_per_tahun');
    // Route::get('/req-div-req-per-tahun-pdf/{tahunRequestor}','LaporanController@cetak_pdf_div_req_per_tahun');
    // Route::get('/req-div-user-per-tahun-excel/{tahunUser}','LaporanController@cetak_excel_div_user_per_tahun');
    // Route::get('/req-div-user-per-tahun-pdf/{tahunUser}','LaporanController@cetak_pdf_div_user_per_tahun');
    // Route::get('/req-div-req-per-status-excel/{statusRequestor}','LaporanController@cetak_excel_div_req_per_status');
    // Route::get('/req-div-req-per-status-pdf/{statusRequestor}','LaporanController@cetak_pdf_div_req_per_status');
    // Route::get('/req-div-user-per-status-excel/{statusUser}','LaporanController@cetak_excel_div_user_per_status');
    // Route::get('/req-div-user-per-status-pdf/{statusUser}','LaporanController@cetak_pdf_div_user_per_status');
    // Route::get('/req-per-personnel-excel','LaporanController@cetak_excel_per_personnel');
    // Route::get('/req-per-personnel-pdf','LaporanController@cetak_pdf_per_personnel');
    // Route::get('/req-per-status-per-personnel-pdf/{ictPersonnel}','LaporanController@cetak_pdf_per_status_per_personnel');
    // Route::get('/req-per-status-per-personnel-excel/{ictPersonnel}','LaporanController@cetak_excel_per_status_per_personnel');
    // Route::post('/filterByDate','LaporanController@filterByDate');
    // Route::get('/cetak-pdf-filter-ict/{start}/{end}/{status}','LaporanController@cetakPdf');
    
    //bisnis
    Route::get('/get-bisnis','BisnisController@getBisnis');
    Route::get('/report-cash-pdf','CashController@cetak_pdf');
    Route::get('/report-cash-excel','CashController@cetak_excel');
    Route::get('/report-supplier-pdf','SupplierController@cetak_pdf');
    Route::get('/report-supplier-excel','SupplierController@cetak_excel');
    //report request (requestor)
   

    //report detail request (requestor)
    Route::get('/report-ict-detail-pdf/{code}','IctDetailController@cetak_pdf');
    Route::get('/report-ict-detaill-pdf/{code}','IctDetailController@cetak_pdff');

    Route::get('/report-ict-detail-excel/{code}','IctDetailController@cetak_excel');
    Route::get('/report-ict-detail-pdf-tab-reviewer/{code}','IctDetailController@cetak_pdf_tab_reviewer');
    Route::get('/report-ict-detail-excel-tab-reviewer/{code}','IctDetailController@cetak_excel_tab_reviewer');
    Route::get('/report-ict-detail-pdf-tab-verifikasi/{code}','IctDetailController@cetak_pdf_tab_verifikasi');
    Route::get('/report-ict-detail-excel-tab-verifikasi/{code}','IctDetailController@cetak_excel_tab_verifikasi');
    Route::get('/report-ict-detail-pdf-tab-reject/{code}','IctDetailController@cetak_pdf_reject');
    Route::get('/report-ict-detail-excel-tab-reject/{code}','IctDetailController@cetak_excel_reject');
    Route::get('/print-out-ict-request/{code}','IctDetailController@printout_ictrequest');
    Route::get('/report-ict-detail-excel-tab-sedang-dikerjakan/{code}','IctDetailController@cetak_excel_sedang_dikerjakan');

 
});
    Route::get('/req-per-status-excel','LaporanController@cetak_excel_per_status');
    Route::get('/req-per-status-pdf','LaporanController@cetak_pdf_per_status');
    Route::get('/req-div-req-per-bulan-pdf/{tahunnRequestor}/{bulanRequestor}','LaporanController@cetak_pdf_div_req_per_bulan');
    Route::get('/req-div-req-per-bulan-excel/{tahunnRequestor}/{bulanRequestor}','LaporanController@cetak_excel_div_req_per_bulan');
    Route::get('/req-div-user-per-bulan-excel/{tahunnUser}/{bulanUser}','LaporanController@cetak_excel_div_user_per_bulan');
    Route::get('/req-div-user-per-bulan-pdf/{tahunnUser}/{bulanUser}','LaporanController@cetak_pdf_div_user_per_bulan');
    Route::get('/req-div-req-per-tahun-excel/{tahunRequestor}','LaporanController@cetak_excel_div_req_per_tahun');
    Route::get('/req-div-req-per-tahun-pdf/{tahunRequestor}','LaporanController@cetak_pdf_div_req_per_tahun');
    Route::get('/req-div-user-per-tahun-excel/{tahunUser}','LaporanController@cetak_excel_div_user_per_tahun');
    Route::get('/req-div-user-per-tahun-pdf/{tahunUser}','LaporanController@cetak_pdf_div_user_per_tahun');
    Route::get('/req-div-req-per-status-excel/{statusRequestor}','LaporanController@cetak_excel_div_req_per_status');
    Route::get('/req-div-req-per-status-pdf/{statusRequestor}','LaporanController@cetak_pdf_div_req_per_status');
    Route::get('/req-div-user-per-status-excel/{statusUser}','LaporanController@cetak_excel_div_user_per_status');
    Route::get('/req-div-user-per-status-pdf/{statusUser}','LaporanController@cetak_pdf_div_user_per_status');
    Route::get('/req-per-personnel-excel','LaporanController@cetak_excel_per_personnel');
    Route::get('/req-per-personnel-pdf','LaporanController@cetak_pdf_per_personnel');
    Route::get('/req-per-status-per-personnel-pdf/{ictPersonnel}','LaporanController@cetak_pdf_per_status_per_personnel');
    Route::get('/req-per-status-per-personnel-excel/{ictPersonnel}','LaporanController@cetak_excel_per_status_per_personnel');
    Route::post('/filterByDate','LaporanController@filterByDate');
    Route::get('/cetak-pdf-filter-ict/{start}/{end}/{status}','LaporanController@cetakPdf');