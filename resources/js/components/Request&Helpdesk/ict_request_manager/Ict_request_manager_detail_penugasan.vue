<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
			      <h4>ICT Request (Detail) </h4>
          </template>
          <template v-slot:end>
              <label style="width:140px">No. Request: {{kode.noreq}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="25"
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
                <span class=" p-input-icon-left">
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
            Loading data. Please wait.
          </template>
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Peripheral" :sortable="true" style="min-width:4rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:4rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:4rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
               <div class="p-grid p-dir-col">
			        <div class="p-col">
				        <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Manager'})"
                  />
                  <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    v-tooltip.bottom="'Click to print out (PDF)'"
                    @click="CetakPdf()"
                  />
                  <!-- <Button
                    v-if="this.status == 'T'" 
                    label="Excel"
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcelSedangDikerjakan()" 
                  /> -->
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
        kode:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        status:''
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
        if(this.checkname.includes("Approval Manager") || this.checkto.includes("/ict-request-manager")){ 
          this.getIctDetail();
          this.getNoreq();
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
      this.axios.get('/api/ict-detail-penugasan/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
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
        this.kode = response.data;
        this.status = response.data.cekstatus;
      });
    },
    CetakPdf(){
     this.loading = true;
       this.axios.get('/api/print-out-ict-request/' +this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    // CetakExcelSedangDikerjakan(){
    //   window.open('/api/report-ict-detail-excel-tab-sedang-dikerjakan/'+this.$route.params.code);
    // }
  },
};
</script>