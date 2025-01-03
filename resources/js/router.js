import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        redirect: '/login' // Redirect root URL to login page
    },
    {
        name: 'Checking Token SSO',
        path: '/sso/:token',
        component: () => import('./pages/Views/CheckingToken.vue'),
    },
    {
        name: 'Dashboard',
        path: '/dashboard',
        component: () => import('./pages/Views/Dashboard.vue'),
    },
    {
        name: 'Login',
        path: '/login',
        component: () => import('./pages/Views/Login.vue'),

    },
    {
        name: 'Login Legality',
        path: '/loginn/:status/:code',
        component: () => import('./pages/Views/LoginLegality.vue'),

    },
    {
        name: 'Ict Request Detail Desc',
        path: '/ict-request-descc/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_desc_detail.vue'),
    },
    {
        name: 'Ict Request Detail Desc Requestor',
        path: '/ict-request-desc/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_desc_detail_requestor.vue'),
    },
    {
        name: 'Ict Request Desc Detail Divisi 3',
        path: '/Ict-request-desc/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_divisi3_detail_desc.vue'),
    },
    {
        name: 'Ict Request Desc Detail Divisi 4',
        path: '/ict-request-divisi4-desc/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_divisi4_detail_desc.vue'),
    },
    {
        name: 'Ict Request Desc Verifikasi',
        path: '/Ict-Request-desc/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_desc_verifikasi.vue'),
    },
    {
        name: 'Ict Request Desc Assign Per Detail',
        path: '/ict-request-desc-assign/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_assign_per_detail_desc.vue'),
    },
    {
        name: 'Ict Request Desc Detail Penugasan',
        path: '/ict-request-desc-detail-penugasan/:code',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_desc_detail_penugasan.vue'),
    },
    {
        name: 'Desc',
        path: '/ict-request-desc',
        component: () => import('./pages/ICT-Request/ict_request_desc/ict_request_desc.vue'),
    },
    //referensi_location
    {
        name: 'Referensi Location',
        path: '/referensi-location',
        component: () => import('./pages/Admin/location_refs/Referensi_location.vue'),
    },
    {
        name: 'Create Referensi Location',
        path: '/Add-referensi-loc',
        component: () => import('./pages/Admin/location_refs/Referensi_location_create.vue'),
    },
    {
        name: 'Edit Referensi Location',
        path: '/Edit-referensi-loc/:code',
        component: ()=> import('./pages/Admin/location_refs/Referensi_location_edit.vue'),
    },
    //user domain
    {
        name: 'User Domain',
        path: '/user-domain',
        component: ()=> import('./pages/Admin/user_domain/v_user_domain.vue'),
    },
    //lookup_admin
    {
        name: 'Referensi Lookups',
        path: '/referensi-lookups',
        component: () => import('./pages/Admin/referensi_lookups/Referensi_lookups.vue'),
    },
    {
        name: 'Create Referensi Lookups',
        path: '/Add-referensi-lookups',
        component: () => import('./pages/Admin/referensi_lookups/Referensi_lookups_create.vue'),
    },
    {
        name: 'Edit Referensi Lookups',
        path: '/Edit-referensi-lookups/:code/:type',
        component: ()=> import('./pages/Admin/referensi_lookups/Referensi_lookups_edit.vue'),
    },
    //lookup_brand
    {
        name: 'Referensi Brand',
        path: '/referensi-brand',
        component: () => import('./pages/Admin/referensi_lookups_brand/Referensi_lookups_brand.vue'),
    },
    {
        name: 'Create Referensi Brand',
        path: '/Add-referensi-brand',
        component: () => import('./pages/Admin/referensi_lookups_brand/Referensi_lookups_brand_create.vue'),
    },
    {
        name: 'Edit Referensi Brand',
        path: '/Edit-referensi-brand/:code/:type',
        component: ()=> import('./pages/Admin/referensi_lookups_brand/Referensi_lookups_brand_edit.vue'),
    },
    //lookup_kategori
    {
        name: 'Referensi Kategori',
        path: '/referensi-kategori',
        component: () => import('./pages/Admin/referensi_lookups_kategori/Referensi_lookups_kategori.vue'),
    },
    {
        name: 'Create Referensi Kategori',
        path: '/Add-referensi-kategori',
        component: () => import('./pages/Admin/referensi_lookups_kategori/Referensi_lookups_kategori_create.vue'),
    },
    {
        name: 'Edit Referensi Kategori',
        path: '/Edit-referensi-kategori/:code/:type',
        component: ()=> import('./pages/Admin/referensi_lookups_kategori/Referensi_lookups_kategori_edit.vue'),
    },
    //supplier
    {
        name: 'Referensi Supplier',
        path: '/referensi-supplier',
        component: ()=> import('./pages/Admin/referensi_supplier/Referensi_supplier.vue'),
    },
    {
        name: 'Create Referensi Supplier',
        path: '/Add-referensi-supplier',
        component: ()=> import('./pages/Admin/referensi_supplier/Referensi_supplier_create.vue'),
    },
    {
        name: 'Edit Referensi Supplier',
        path: '/Edit-referensi-supplier/:code',
        component: ()=> import('./pages/Admin/referensi_supplier/Referensi_supplier_edit.vue'),
    },
    //master peripheral
    {
        name: 'Master Peripheral',
        path: '/master-peripheral',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral/Master_peripheral.vue'),
    },
    {
        name: 'Scan',
        path: '/scan',
        component: ()=> import('./pages/Components/Scan.vue'),

    },
    {
        name: 'Detail Peripheral',
        path: '/detPeripheral/:invent_code_dtl',
        component: ()=> import('./pages/Views/Master_peripheral_detail_qrcode.vue'),

    },
    {
        name: 'Add Master Peripheral',
        path: '/Add-master-peripheral',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral/Master_peripheral_create.vue'),
    },
    {
        name: 'Edit Master Peripheral',
        path: '/Edit-master-peripheral/:code',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral/Master_peripheral_edit.vue'),
    },

    //master peripheral detail
    {
        name: 'Master Peripheral Detail',
        path: '/master-peripheral-detail/:code',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral_detail/Master_peripheral_detail.vue'),
    },
    {
        name: 'Add Master Peripheral Detail',
        path: '/Add-master-peripheral-detail/:code',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral_detail/Master_peripheral_detail_create.vue'),
    },
    {
        name: 'Edit Master Peripheral Detail',
        path: '/Edit-master-peripheral/:code/:kode',
        component: ()=> import('./pages/ICT-Inventory/master_peripheral_detail/Master_peripheral_detail_edit.vue'),
    },
    //mutasi peripheral
    {
        name: 'Mutasi Peripheral',
        path: '/mutasi-peripheral',
        component: ()=> import('./pages/ICT-Inventory/mutasi_peripheral/Mutasi_peripheral.vue'),
    },
    {
        name: 'Add Mutasi Peripheral',
        path: '/Add-mutasi-peripheral',
        component: ()=> import('./pages/ICT-Inventory/mutasi_peripheral/Mutasi_peripheral_create.vue'),
    },
    {
        name: 'Edit Mutasi Peripheral',
        path: '/Edit-mutasi-peripheral/:code',
        component: ()=> import('./pages/ICT-Inventory/mutasi_peripheral/Mutasi_peripheral_edit.vue'),
    },
    //cash advance
    {
        name: 'Cash Advance ',
        path: '/cash-advance',
        component: ()=> import('./pages/ICT-Request/cash_advance/Cash_advance.vue'),
    },
    {
        name: 'Add Cash Advance',
        path: '/Add-cash-advance',
        component: ()=> import('./pages/ICT-Request/cash_advance/Cash_advance_create.vue'),
    },
    {
        name: 'add Cash Advance',
        path: '/add-cash-advance/:code/:dtl',
        component: ()=> import('./pages/ICT-Request/cash_advance/Cash_advance_create_from_ict.vue'),
    },
    {
        name: 'Edit Cash Advance',
        path: '/Edit-cash-advance/:code',
        component: ()=> import('./pages/ICT-Request/cash_advance/Cash_advance_edit.vue'),
    },
    //payment request
    {
        name: 'Payment Request',
        path: '/payment-request',
        component: ()=> import('./pages/ICT-Request/payment_request/Payment_request.vue'),
    },
    {
        name: 'Add Payment Request',
        path: '/Add-payment-request',
        component: ()=> import('./pages/ICT-Request/payment_request/Payment_request_create.vue'),
    },
    {
        name: 'add Payment Request',
        path: '/add-payment-request/:code/:dtl',
        component: ()=> import('./pages/ICT-Request/payment_request/Payment_request_create_from_request.vue'),
    },
    {
        name: 'Edit Payment Request',
        path: '/Edit-payment-request/:code',
        component: ()=> import('./pages/ICT-Request/payment_request/Payment_request_edit.vue'),
    },
    //supervisor
    {
        name: 'Spv Refs',
        path: '/supervisor-refs',
        component: ()=> import('./pages/Admin/referensi_supervisor/v_spv.vue'),
    },
    {
        name: 'Add Spv',
        path: '/Add-supervisor-refs',
        component: ()=> import('./pages/Admin/referensi_supervisor/v_add_spv.vue'),
    },
    {
        name: 'Edit Spv',
        path: '/edit-supervisor-refs/:code',
        component: ()=> import('./pages/Admin/referensi_supervisor/v_edit_spv.vue'),
    },
    //ict request
    {
        name: 'Ict Request',
        path: '/ict-request',
        component: ()=> import('./pages/ICT-Request/ict_request/Ict_request.vue'),
    },
    {
        name: 'Add Ict Request',
        path: '/Add-ict-request',
        component: ()=> import('./pages/ICT-Request/ict_request/Ict_request_create.vue'),
    },
    {
        name: 'Edit Ict Request',
        path: '/Edit-ict-request/:code',
        component: ()=> import('./pages/ICT-Request/ict_request/Ict_request_edit.vue'),
    },
    //ict request admin
    {
        name: 'Ict Request Admin',
        path: '/ict-request-admin',
        component: ()=> import('./pages/ICT-Request/ict_request_admin/Ict_request.vue'),
    },
    {
        name: 'Edit Ict Request Admin',
        path: '/Edit-ict-request-admin/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_admin/Ict_request_edit.vue'),
    },
    {
        name: 'Ict Request Admin Detail',
        path: '/ict-request-admin-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_admin/Ict_request_detail.vue'),
    },
    {
        name: 'Add Ict Request Admin Detail',
        path: '/Add-ict-request-admin-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_admin/Ict_request_detail_create.vue'),
    },
    {
        name: 'Edit Ict Request Admin Detail',
        path: '/Edit-ict-request-admin-detail/:code/:ireq',
        component: ()=> import('./pages/ICT-Request/ict_request_admin/Ict_request_detail_edit.vue'),
    },
    //ict_request_divisi1
    {
        name: 'Ict Request Higher Level',
        path: '/ict-request-higher-level',
        component: ()=> import('./pages/ICT-Request/Approval_atasan/Ict_request_divisi1.vue'),
    },
    {
        name: 'Ict Request Higher Level Detail',
        path: '/ict-request-higher-level-detail/:code',
        component: ()=> import('./pages/ICT-Request/Approval_atasan/Ict_request_divisi1_detail.vue'),
    },
    {
        name: 'Ict Request Verifikasi From Email',
        path: '/verifikasi-request/:code/:status',
        component: ()=> import('./pages/ICT-Request/Approval_atasan/Ict_request_verifikasi_from_email.vue'),
    },
    //status change request
    {
        name: 'Ict Request Divisi 3',
        path: '/ict-request-personnel',
        component: ()=> import('./pages/ICT-Request/Status_change_request/Ict_request_divisi3.vue'),
    },
    {
        name: 'Ict Request Divisi 3 Detail',
        path: '/ict-request-personnel-detail/:code',
        component: ()=> import('./pages/ICT-Request/Status_change_request/Ict_request_divisi3_detail.vue'),
    },
    //ict request (detail)
    {
        name: 'Ict Request Detail',
        path: '/ict-request-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_detail/Ict_request_detail.vue'),
    },
    {
        name: 'Add Ict Request Detail',
        path: '/Add-ict-request-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_detail/Ict_request_detail_create.vue'),
    },
    {
        name: 'Edit Ict Request Detail',
        path: '/Edit-ict-request-detail/:code/:ireq',
        component: ()=> import('./pages/ICT-Request/ict_request_detail/Ict_request_detail_edit.vue'),
    },
    //pembelian peripheral
    {
        name: 'Pembelian Peripheral',
        path: '/pembelian-peripheral',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral/Pembelian_peripheral.vue'),
    },
    {
        name: 'Add Pembelian Peripheral',
        path: '/Add-pembelian-peripheral',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral/Pembelian_peripheral_create.vue'),
    },
    {
        name: 'Edit Pembelian Peripheral',
        path: '/Edit-pembelian-peripheral/:code',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral/Pembelian_peripheral_edit.vue'),
    },
     //detail pembelian peripheral
     {
        name: 'Pembelian Peripheral Detail',
        path: '/pembelian-peripheral-detail/:code',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral_detail/Pembelian_peripheral_detail.vue'),
    },
    {
        name: 'Add Pembelian Peripheral Detail',
        path: '/Add-pembelian-peripheral-detail/:code',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral_detail/Pembelian_peripheral_detail_create.vue'),
    },
    {
        name: 'Edit Pembelian Peripheral Detail',
        path: '/Edit-pembelian-peripheral-detail/:code/:purchase',
        component: ()=> import('./pages/ICT-Inventory/pembelian_peripheral_detail/Pembelian_peripheral_detail_edit.vue'),
    },
    //mng_user
    {
        name: 'Management User',
        path: '/mng-user',
        component: ()=> import('./pages/Admin/mng_user/mng_user.vue'),
    },
    {
        name: 'Add Management User',
        path: '/add-mng-user',
        component: ()=> import('./pages/Admin/mng_user/mng_user_create.vue'),
    },
    {
        name: 'Edit Management User',
        path: '/Edit-mng-user/:code',
        component: ()=> import('./pages/Admin/mng_user/mng_user_edit.vue'),
    },
     //mng_role
     {
        name: 'Management Role',
        path: '/mng-role',
        component: ()=> import('./pages/Admin/mng_role/Mng_role.vue'),
    },
    {
        name: 'Add Management Role',
        path: '/add-mng-role',
        component: ()=> import('./pages/Admin/mng_role/Mng_role_create.vue'),
    },
    {
        name: 'Edit Management Role',
        path: '/edit-mng-role/:code',
        component: ()=> import('./pages/Admin/mng_role/Mng_role_edit.vue'),
    },
    //mng_module
    {
        name: 'Management Module',
        path: '/mng-module',
        component: ()=> import('./pages/Admin/mng_module/Mng_module.vue'),
    },
    {
        name: 'Add Management Module',
        path: '/Add-mng-module',
        component: ()=> import('./pages/Admin/mng_module/Mng_module_create.vue'),
    },
    {
        name: 'Edit Management Module',
        path: '/edit-mng-module/:code',
        component: ()=> import('./pages/Admin/mng_module/Mng_module_edit.vue'),
    },
    //mng_menu
    {
        name: 'Management Menu',
        path: '/mng-menu',
        component: ()=> import('./pages/Admin/mng_menu/Mng_menu.vue'),
    },
    {
        name: 'Add Management Menu',
        path: '/add-mng-menu',
        component: ()=> import('./pages/Admin/mng_menu/Mng_menu_create.vue'),
    },
    {
        name: 'Edit Management Menu',
        path: '/edit-mng-menu/:code',
        component: ()=> import('./pages/Admin/mng_menu/Mng_menu_edit.vue'),
    },
    //divisi_refs
    {
        name: 'Divisi Refs',
        path: '/divisi-refs',
        component: ()=> import('./pages/Admin/divisi_refs/Divisi_refs.vue'),
    },
    {
        name: 'Add Divisi Refs',
        path: '/add-divisi-refs',
        component: ()=> import('./pages/Admin/divisi_refs/Divisi_refs_create.vue'),
    },
    {
        name: 'Edit Divisi Refs',
        path: '/edit-divisi-refs/:code',
        component: ()=> import('./pages/Admin/divisi_refs/Divisi_refs_edit.vue'),
    },
    //catalog_refs
    {
        name: 'Catalog Refs',
        path: '/catalog-refs',
        component: ()=> import('./pages/Admin/catalog_ref/Catalog_refs.vue'),
    },
    {
        name: 'Add Catalog Refs',
        path: '/add-catalog-refs',
        component: ()=> import('./pages/Admin/catalog_ref/Catalog_refs_create.vue'),
    },
    {
        name: 'Edit Catalog Refs',
        path: '/edit-catalog-refs/:code',
        component: ()=> import('./pages/Admin/catalog_ref/Catalog_refs_edit.vue'),
    },
    //grafik
    {
        name: 'Statistik Permintaan User Per Status',
        path: '/req-per-status',
        component: ()=> import('./pages/ICT-Request/grafik/JmlRqstPerStatus.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi User Per Status',
        path: '/req-per-divisi-per-status',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiUserPerStatus.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi Req Per Status',
        path: '/req-per-divisi-req-per-status',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiReqPerStatus.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi User Per Tahun',
        path: '/req-per-divisi-user-per-tahun',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiUserPerTahun.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi Req Per Tahun',
        path: '/req-per-divisi-req-per-tahun',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiReqPerTahun.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi User Per Bulan',
        path: '/req-per-divisi-user-per-bulan',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiUserPerBulan.vue'),
    },
    {
        name: 'Statistik Permintaan Divisi Req Per Bulan',
        path: '/req-per-divisi-req-per-bulan',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstDivisiReqPerBulan.vue'),
    },
    {
        name: 'Statistik Permintaan Per Status Per Personnel',
        path: '/req-per-status-per-personnel',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstPerStatusPerPersonnel.vue'),
    },
    {
        name: 'Statistik Permintaan Per Personnel',
        path: '/req-per-personnel',
        component: ()=> import('./pages/ICT-Request/grafik/JmlhRqstPerPersonnel.vue'),
    },
    //laporan
    {
        name: 'Laporan Permintaan Per status',
        path: '/report-per-status',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsPerStatus.vue'),
    },
    {
        name: 'Laporan ICT Request',
        path: '/report-ict-request',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsBtwnDate.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi Requestor Per Bulan',
        path: '/report-div-req-per-bulan',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiPerBulan.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi User Per Bulan',
        path: '/report-div-user-per-bulan',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiUserPerBulan.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi Requestor Per Tahun',
        path: '/report-div-req-per-tahun',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiReqPerTahun.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi User Per Tahun',
        path: '/report-div-user-per-tahun',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiUserPerTahun.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi Requestor Per Status',
        path: '/report-div-req-per-status',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiReqPerStatus.vue'),
    },
    {
        name: 'Laporan Permintaan Divisi User Per Status',
        path: '/report-div-user-per-status',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsDivisiUserPerStatus.vue'),
    },
    {
        name: 'Laporan Permintaan Per Personnel',
        path: '/report-per-personnel',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsPerPersonnel.vue'),
    },
    {
        name: 'Laporan Permintaan Per Status Per Personnel',
        path: '/report-per-status-per-personnel',
        component: ()=> import('./pages/ICT-Request/laporan/LaporanRqsPerStatusPerPersonnel.vue'),
    },

    //ict request reviewer
    {
        name: 'Ict Request Reviewer',
        path: '/ict-request-reviewer',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer.vue'),
    },
    {
        name: 'Ict Request Reviewer Detail Permohonan',
        path: '/ict-request-reviewer/detail-permohonan/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer_detail_permohonan.vue'),
    },
    {
        name: 'Ict Request Reviewer Edit Detail Permohonan',
        path: '/ict-request-reviewer/edit-detail-permohonan/:ireq/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer_edit_detail_permohonan.vue'),
    },
    {
        name: 'Ict Request Reviewer Detail',
        path: '/ict-request-reviewer/detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer_detail.vue'),
    },
    {
        name: 'Ict Request Reviewer Assign Per Detail',
        path: '/ict-request-reviewer/Assign-per-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer_assign_per_detail.vue'),
    },
    {
        name: 'Ict Request Reviewer Detail Penugasan',
        path: '/ict-request-reviewer/detail-penugasan/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_reviewer/Ict_request_reviewer_detail_penugasan.vue'),
    },
    {
        name: 'Ict Request Verifikasi Reviewer',
        path: '/ict-request-verifikasi-reviewer/:code',
        component: ()=> import('./pages/Views/CheckVerifReviewer.vue'),
    },
    
    //legality_qr_code
    {
        name: 'Check legality Qr-code Higher Level',
        path: '/check-higher-level/:code',
        component: ()=> import('./pages/ICT-Request/legality_qr_code/legality_approval_atasan.vue'),
    },
    {
        name: 'Check legality Qr-code ICT Manager',
        path: '/check-ict-manager/:code',
        component: ()=> import('./pages/ICT-Request/legality_qr_code/legality_approval_ict_manager.vue'),
    },
    {
        name: 'Check legality Qr-code Requestor',
        path: '/check-requester/:code',
        component: ()=> import('./pages/ICT-Request/legality_qr_code/legality_request.vue'),
    },
    {
        name: 'Scan Legality',
        path: '/scan-qr-code',
        component: ()=> import('./pages/ICT-Request/legality_qr_code/Scan_legality.vue'),
    },

    //ict request manager
    {
        name: 'Ict Request Manager',
        path: '/ict-request-manager',
        component: ()=> import('./pages/ICT-Request/ict_request_manager/Ict_request_manager.vue'),
    },
    {
        name: 'Ict Request Manager Detail',
        path: '/ict-request-manager-detail/:code',
        component: ()=> import('./pages/ICT-Request/ict_request_manager/Ict_request_manager_detail.vue'),
    },
    {
        name: 'Ict Request Verifikasi From Email ICT Manager',
        path: '/verifikasi-request-mng/:code/:status',
        component: ()=> import('./pages/ICT-Request/ict_request_manager/Ict_request_verifikasi_from_email.vue'),
    },
    {
        path: '/error/:stat',
        name: 'error',
        component: () => import('./pages/Layout/Error.vue')
    },
    {
        path: '/notfound',
        name: 'notfound',
        component: () => import('./pages/Layout/NotFound.vue')
    },
    {
        path: '/:catchAll(.*)',
        name: 'Page Not Found',
        component: () => import('./pages/Layout/NotFound.vue')
    },
    {
        path: '/access',
        name: 'access',
        component: () => import('./pages/Layout/Access.vue')
    },
    {
        path: '/ictinv_resp/:code/:status',
        name: 'Check Verif',
        component: () => import('./pages/Views/checkVerif.vue')
    },
    {
        path: '/check/:status/:code',
        name: 'Check Legality',
        component: () => import('./pages/Views/CheckLegality.vue')
    }
];

const router = createRouter({
    history: createWebHistory('/invenict2/public'),
    routes,
});


export default router;
