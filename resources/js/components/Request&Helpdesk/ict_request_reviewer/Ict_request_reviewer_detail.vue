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
          <Column field="ireq_type" header="Tipe Request" :sortable="true"  style="min-width:8rem"/>
          <Column field="name" header="Peripheral" :sortable="true"  style="min-width:8rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true"  style="min-width:8rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true"  style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to1" header="Personnel ICT" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to1_reason" header="Alasan" :sortable="true"  style="min-width:8rem" v-if="this.show == true"/>
          <Column field="ireq_assigned_to2" header="Personnel ICT (2)" :sortable="true"  style="min-width:8rem" v-if="this.show == true"/>
          <Column field="ireq_status" header="Status" :sortable="true"  style="min-width:8rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:17rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.cekstatus == 'RT'"
                class="p-button-raised p-button-text mr-2 mt-2"
                label="Assign"
                icon="pi pi-user-edit"
                @click="AssignPerDetail(slotProps.data.ireqd_id)"
              />
              <Button
                v-if="slotProps.data.cekstatus == 'NT'"
                class="p-button-raised p-button-text mr-2 mt-2"
                icon="pi pi-user-edit"
                label="Assign"
                @click="AssignPerDetail(slotProps.data.ireqd_id)"
              />
              <Button
                v-if="slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'RT' || slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'NT'"
                class="p-button-raised p-button-success p-button-text mr-2 mt-2"
                icon="pi pi-check"
                label="Submit"
                @click="Submit(slotProps.data.ireqd_id)"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    v-tooltip.bottom="'Click to back'"
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Reviewer'})"
                  />
                  <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    v-tooltip.bottom="'Click to print out (PDF)'"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf()"
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>   
        <Dialog
          v-model:visible="dialogAssign"
          :style="{ width: '500px' }"
          header="Assign Per-Request"
          :modal="true"
          :closable="false"
          class="fluid"
        >
          <div class="field grid">
            <label class="col-fixed w-9rem">No Request</label>
              <div class="col-fixed">
                <InputText
                  v-model="assign.ireq_no"
                  disabled
                />
              </div>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">No Detail</label>
              <div class="col-fixed">
                <InputText
                  v-model="assign.ireqd_id"
                  disabled
                />
              </div>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">Request Type</label>
              <div class="col-fixed">
                <InputText
                  v-model="assign.ireq_type"
                  disabled
                />
              </div>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">Peripheral</label>
              <div class="col-fixed">
                <InputText
                  v-model="assign.name"
                  disabled
                />
              </div>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">Qty</label>
              <div class="col-fixed">
                <InputText
                  v-model="assign.ireq_qty"
                  disabled
                />
              </div>
          </div>
           <div class="field grid">
            <label class="col-fixed w-9rem">Remark</label>
              <div class="col-fixed">
                <Textarea
                  v-model="assign.ireq_remark"
                  disabled
                  autoResize
                />
              </div>
          </div>
          <div class="field grid" v-if="assign.status == 'NT'">
            <label class="col-fixed w-9rem">Reason</label>
              <div class="col-fixed">
                <Textarea
                  v-model="assign.ireq_assigned_to1_reason"
                  :autoResize="true" 
                  rows="5" 
                  cols="20"
                  placeholder="Enter Reason"
                  :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to1_reason }"
                  />
              </div>
              <small v-if="submitted && !assign.ireq_assigned_to1_reason" class="p-error">
                  Reason not filled
                </small>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">Petugas (ICT)</label>
              <div class="col-fixed">
                <Dropdown
                  v-model="assign.ireq_assigned_to1"
                  :options="petugas"
                  optionValue="name"
                  optionLabel="name"
                  placeholder="Select One"
                  :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to1 }"
                />
                <small v-if="submitted && !assign.ireq_assigned_to1" class="p-error">
                  Petugas(ICT) not filled
                </small>
              </div>
            </div>
          <template #footer>
            <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
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
        submitted:false,
        dialogAssign:false,
        assign:[],
        petugas:[],
        kode:'',
        show:false,
        status:'',
        loading: true,
        detail: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        code:null
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
    AssignPerDetail(ireqd_id){
      this.axios.get('/api/detail/'+ ireqd_id+'/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.assign = response.data;
          });
      this.axios.get('/api/get-pekerja', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.petugas = response.data;
        });
      this.dialogAssign = true;
    },
    updateAssign(){
      this.submitted = true;
      this.code = this.assign.ireqd_id
      if(this.assign.status =='RT'){
        if(this.assign.ireq_assigned_to1 != null){
          this.axios.put('/api/updateAssignPerDetailFromReject/'+ this.code ,this.assign, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.assign = [];
            this.dialogAssign = false;
            this.submitted = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000,
            });
            this.getIctDetail();
          });
        }
      }else{
        if(this.assign.ireq_assigned_to1 != null && this.assign.ireq_assigned_to1_reason != null){
          this.axios.put('/api/updateAssignPerDetailFromReject/'+ this.code ,this.assign, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.assign = [];
            this.dialogAssign = false;
            this.submitted = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000,
            });
            this.getIctDetail();
          });
        }
      }
    },
    Submit(ireqd_id){
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
          accept: () => {
            this.$toast.add({
              severity: "info",
              summary: "Success Message",
              detail: "Success Submit",
              life : 1000
            });
            this.axios.get('/api/appd/' +ireqd_id + '/' +this.$route.params.code ,{headers: {'Authorization': 'Bearer '+this.token}});
            
            this.getIctDetail();
        },
        reject: () => {},
      });
    },
    cancelAssign(){
      this.submitted = false;
      this.assign = [];
      this.dialogAssign = false;
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
        if(this.status =='NT' || this.status == 'RT'){ this.show = true}
      });
    },
    CetakPdf(){
      window.open('/api/print-out-ict-request/' +this.$route.params.code);
    },
    // CetakExcel(){
    //   window.open('/api/report-ict-detail-excel/' +this.code);
    // },
    // CetakPdfReject(){
    //  window.open('/api/report-ict-detail-pdf-tab-reject/' +this.code);
    // },
    // CetakExcelReject(){
    //   window.open('/api/report-ict-detail-excel-tab-reject/' +this.code);
    // },
  },
};
</script>