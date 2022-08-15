<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="p-mb-4">
          <template v-slot:start>
				        <h4>ICT Request (Verification) </h4>
          </template>
        </Toolbar>
        <DataTable
          :value="verif"
          :paginator="true"
          :rows="25"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Verification)"
          responsiveLayout="scroll"
        >
        
          <template #header>
              <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
               <label style="width:140px">No. Request: {{kode.noreq}}</label>
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
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Peripheral" :sortable="true" style="min-width:12rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <template #footer>
            <div class="grid p-dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Divisi 1'})"
                  />
                  <Button
                    label="Approve"
                    class="p-button-raised p-button-success mr-2"
                    icon="pi pi-check-square"
                    @click="Approve()"
                  />
                  <Button 
                    label="Reject"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-times-circle"
                    @click="this.dialogReject = true" 
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>   
        <Dialog
        v-model:visible="dialogReject"
        :style="{ width: '400px' }"
        header="ICT Request"
        :modal="true"
        class="field grid"
      >
        <div class="field"> 
          <div class="field grid">
            <label class="col-fixed w-9rem">Reason</label>
              <div class="col-fixed w-9rem">
                <Textarea
                  :autoResize="true"
                  type="text"
                  v-model="reason.ket"
                  rows="5" 
                  placeholder="Give a reason"
                  :class="{ 'p-invalid': submitted && !reason.ket }"
                />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Reason not filled
                  </small>
              </div>
            </div>
          </div>
        <template #footer>
            <Button label="Save" @click="updateReject()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelReject()" class="p-button-text" />
        </template>
      </Dialog>
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
        dialogReject: false,
        submitted:false,
        verif: [],
        kode:[],
        reason:{
            ket:null,
        },
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
        if(this.checkname.includes("Atasan Requestor Divisi") || this.checkto.includes("/ict-request-divisi1")){ 
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
      Approve(){
      this.$confirm.require({
        message: "Are you sure you agree to this request?",
        header: "Confirmation Approval",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Successfully approved this request",
          });
          this.axios.get('/api/updateStatusPermohonan/' +this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}});
          setTimeout( () =>  this.$router.push('/ict-request-divisi1'),1000);
        },
        reject: () => {},
      });
      },
      updateReject(){
          this.submitted = true;
           if(this.reason.ket != null){
            this.axios.put('/api/updateStatusReject/'+this.$route.params.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.dialogReject = false;
              this.$toast.add({
                severity: "info",
                summary: "Success Message",
                detail: "Successfully rejected this request",
              });
              setTimeout( () => this.$router.push('/ict-request-divisi1'),1000);
            });
          }
      },
      cancelReject(){
        this.dialogReject = false;
        this.reason.ket = null;
        this.submitted = false;
      },
    getIctDetail(){
      this.axios.get('/api/get-verif/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.verif = response.data;
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
        this.kode = response.data;
        this.loading = false;
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
  },
};
</script>