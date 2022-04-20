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
            <div class="table-header text-right">
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
          <Column field="invent_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <template #footer>
               <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Reviewer'})"
                  />
                  <Button
                    v-if="this.status != 'RR' && this.status != 'RA1' && this.status != 'RA2' &&  this.status != 'T' && this.status != 'D' && this.status != 'C'" 
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf()"
                  />
                  <Button
                    v-if="this.status != 'RR' && this.status != 'RA1' && this.status != 'RA2' &&  this.status != 'T' && this.status != 'D' && this.status != 'C'"
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcel()" 
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
        if(this.checkname.includes("Reviewer") || this.checkto.includes("/ict-request/reviewer")){ 
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
    CetakPdf(){
      window.open('/api/report-ict-detaill-pdf/' +this.code);
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
  },
};
</script>