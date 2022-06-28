<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Approval Manager (Verifikasi) </h4>
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Approval Manager (Verifikasi)"
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
          <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="invent_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <template #footer>
            <div class="grid p-dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Manager'})"
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
        <div class="p-field">
          <div class="field grid">
            <label class="col-fixed w-9rem" style="width:100px">Reason</label>
              <div class="co-fixed">
                <Textarea
                    :autoResize="true"
                    type="text"
                    v-model="reason.ket"
                    rows="5" 
                    cols="30"
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
        if(this.checkname.includes("Approval Manager") || this.checkto.includes("/ict-request-manager")){ 
          this.getVerifikasi();
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
        message: "Approval Permohonan Dilanjutkan?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Permohonan Dilanjutkan",
          });
          this.axios.get('/api/abm/' +this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}});
          setTimeout( () =>  this.$router.push('/ict-request-manager'),1000);
        },
        reject: () => {},
      });
      },
      updateReject(){
          this.submitted = true;
           if(this.reason.ket != null){
            this.axios.put('/api/rbm/'+this.$route.params.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.dialogReject = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
              });
              setTimeout( () => this.$router.push('/ict-request-manager'),1000);
            });
          }
      },
      cancelReject(){
        this.dialogReject = false;
        this.reason.ket = null;
        this.submitted = false;
      },
    getVerifikasi(){
      this.axios.get('/api/get-data-manager-verifikasi/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.verif = response.data;
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
      });
    },
  },
};
</script>