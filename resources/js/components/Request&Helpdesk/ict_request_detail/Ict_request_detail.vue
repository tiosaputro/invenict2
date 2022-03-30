<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
						<div class="my-2">
				        <h4>ICT Request (Detail) </h4>
            </div>
          </template>
          <template v-slot:end>
              <label style="width:130px">No. Request: {{this.kode}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Detail)"
          responsiveLayout="scroll"
        >
        
       <template #header>
         <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center" v-if="this.status == null">
            <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push({
                name: 'Add Ict Request Detail',
                params: { code: code },
              })"
            />
              <span class="block mt-2 md:mt-0 p-input-icon-left">
                <i class="pi pi-search" />
                <InputText
                  v-model="filters['global'].value"
                  placeholder="Search. . ."
                />
              </span>
            </div>
            <div class="table-header text-right" v-else-if="this.status != null">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                  <InputText
                    v-model="filters['global'].value"
                    placeholder="Search. . ."
                  />
              </span>
            </div>
          </template>
           <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading ICT Request (Detail) data. Please wait.
          </template>
          <Column field="ireq_type" header="Tipe Request" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem" v-if="this.ireq.length"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                v-if=" slotProps.data.ireq_status == null"
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Ict Request Detail',
                    params: { ireq: slotProps.data.ireqd_id },
                  })"/>
              <Button
                v-if=" slotProps.data.ireq_status == null"
                icon="pi pi-trash"
                v-tooltip.right="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteIct(slotProps.data.ireqd_id)"
              />
            </template>
          </Column>
          <template #footer>
               <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request'})"
                  />
                  <Button
                    v-if="this.status == 'RR' || this.status == 'RA1' || this.status == 'RA2'"
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdfReject()"
                  />
                  <Button
                    v-if="this.status == 'RR' || this.status == 'RA1' || this.status == 'RA2'" 
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcelReject()" 
                  />
                  <Button
                    v-if="this.status == 'P'" 
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf()"
                  />
                  <Button
                    v-if="this.status == 'P'" 
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcel()" 
                  />
                  <Button
                    v-if="this.status == 'NA1' || this.status == 'NA2'" 
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdfTabReviewer()"
                  />
                  <Button
                    v-if="this.status == 'NA1' || this.status == 'NA2'"
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcelTabReviewer()" 
                  />
                  <Button
                    v-if="this.status == 'A1' || this.status == 'A2'" 
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdfTabVerifikasi()"
                  />
                  <Button
                    v-if="this.status == 'A1' || this.status == 'A2'"
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcelTabVerifikasi()" 
                  />
                  <Button
                    v-if="this.status =='T'" 
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdfTabSedangDikerjakan()"
                  />
                  <Button
                    v-if="this.status == 'T'"
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcelTabSedangDikerjakan()" 
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        loading: true,
        detail: [],
        status:'',
        kode:'',
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        tes:[],
        ireq:[]
    };
  },
  mounted() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
           this.getIctDetail();
           this.getNoreq()
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.tes = response.data.map((x)=>x.ireq_assigned_to);
        if(this.tes.length > 0 && this.tes[0] != null){
          this.ireq = this.tes
        }
        else{}
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
           }
      });
    },
    getNoreq(){
      this.axios.get('/api/get-noreq/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.kode = response.data.noreq;
        this.status = response.data.cekstatus;
      });
    },
    DeleteIct(ireqd_id){
       this.$confirm.require({
        message: "Data ini benar-benar akan dihapus?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('/api/delete-ict-detail/' +ireqd_id, {headers: {'Authorization': 'Bearer '+this.token}});
        this.getIctDetail();
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open('/api/report-ict-detail-pdf/' +this.code);
    },
    CetakExcel(){
      window.open('/api/report-ict-detail-excel/' +this.code);
    },
    CetakPdfReject(){
      window.open('/api/report-ict-detail-pdf-tab-reject/' +this.code);
    },
    CetakExcelReject(){
      window.open('/api/report-ict-detail-excel-tab-reject/' +this.code);
    },
    CetakPdfTabReviewer(){
      window.open('/api/report-ict-detail-pdf-tab-reviewer/' +this.code);
    },
    CetakExcelTabReviewer(){
      window.open('/api/report-ict-detail-excel-tab-reviewer/' +this.code);
    },
    CetakPdfTabVerifikasi(){
      window.open('/api/report-ict-detail-pdf-tab-verifikasi/' +this.code);
    },
    CetakExcelTabVerifikasi(){
      window.open('/api/report-ict-detail-excel-tab-verifikasi/' +this.code);
    },
    CetakPdfTabSedangDikerjakan(){
      window.open('/api/report-ict-detail-pdf-tab-sedang-dikerjakan/' +this.code);
    },
    CetakExcelTabSedangDikerjakan(){
      window.open('/api/report-ict-detail-excel-tab-sedang-dikerjakan/' +this.code);
    },
  },
};
</script>