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
            <label style="width:200px">No. Request : {{this.kode}}</label>
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
         <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button
              label="Add"
              class="p-button-raised"
              v-tooltip.right="'Click to add new detail'"
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
          </template>
           <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading ICT Request (Detail) data. Please wait.
          </template>
          <Column field="ireqd_id" header="No Detail" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
          <Column field="name" header="Peripheral" :sortable="true" style="min-width:12rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:12rem" v-if="this.ireq.length"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span v-if="slotProps.data.status" :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.bottom="'Click to edit request'"
                @click="
                  $router.push({
                    name: 'Edit Ict Request Admin Detail',
                    params: {code: this.code,ireq: slotProps.data.ireqd_id},
                  })"/>
              <Button
                icon="pi pi-trash"
                v-tooltip.bottom="'Click to delete request'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteIct(slotProps.data.ireqd_id,this.code)"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    v-tooltip.bottom="'Click to back'"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Admin'})"
                  />
                   <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mt-2"
                    v-tooltip.bottom="'Click to print out (PDF)'"
                    icon="pi pi-file-pdf"
                    v-if="this.status != null"
                    @click="CetakPdf()"
                  />
                  <Button
                    class="p-button-raised p-button-success mr-2"
                    icon="pi pi-check"
                    label="Submit"
                    v-if="this.detail.length && this.status == null"
                    @click="SubmitIct()"
                    v-tooltip.bottom="'Click to submit request'"
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
        status:null,
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
            severity:'error', summary: 'Error', detail:'Session login expired'
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
    DeleteIct(ireqd_id,code){
       this.$confirm.require({
        message: "Are you sure you want to delete this record data?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('/api/delete-ict-detail/' +ireqd_id+'/'+code, {headers: {'Authorization': 'Bearer '+this.token}});
        this.getIctDetail();
        },
        reject: () => {},
      });
    },
     SubmitIct(){
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Successfully Submit",
            life: 3000,
          });
          this.loading = true;
          this.axios.get('/api/updateStatusSubmit/' +this.code, {headers: {'Authorization': 'Bearer '+this.token}});
          setTimeout( () => this.$router.push('/ict-request-admin'),1000);
        },
        reject: () => {},
      })
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('/api/print-out-ict-request/' +this.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    // CetakExcel(){
    //   window.open('/api/report-ict-detail-excel/' +this.code);
    // },
    // CetakPdfReject(){
    //   window.open('/api/print-out-ict-request/' +this.code);
    // },
    // CetakExcelReject(){
    //   window.open('/api/report-ict-detail-excel-tab-reject/' +this.code);
    // },
    // CetakPdfTabReviewer(){
    //   window.open('/api/report-ict-detail-pdf-tab-reviewer/' +this.code);
    // },
    // CetakExcelTabReviewer(){
    //   window.open('/api/report-ict-detail-excel-tab-reviewer/' +this.code);
    // },
    // CetakPdfTabVerifikasi(){
    //   window.open('/api/report-ict-detail-pdf-tab-verifikasi/' +this.code);
    // },
    // CetakExcelTabVerifikasi(){
    //   window.open('/api/report-ict-detail-excel-tab-verifikasi/' +this.code);
    // },
    // CetakPdfTabSedangDikerjakan(){
    //   window.open('/api/report-ict-detail-pdf-tab-sedang-dikerjakan/' +this.code);
    // },
    // CetakExcelTabSedangDikerjakan(){
    //   window.open('/api/report-ict-detail-excel-tab-sedang-dikerjakan/' +this.code);
    // },
  },
};
</script>