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
            <div v-if="this.kode.ireq_date">
              <label style="width:110px">No. Request </label>
              <label>: {{this.kode.noreq}} </label>
              <br>
              <label style="width:110px">Request Date</label>
              <label>: {{formatDate(this.kode.ireq_date)}}</label>
            </div>
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
          <Column field="ireq_type" header="Request Type" :sortable="true"  style="min-width:10rem"/>
          <Column field="name" header="Items" :sortable="true"  style="min-width:8rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true"  style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_assigned_to1" header="ICT Personnel (1)" :sortable="true" style="min-width:10rem" v-if="this.showPersonnel1.some(el=> el > 0)"/>
          <Column field="ireq_assigned_to1_reason" header="Reason" :sortable="true"  style="min-width:8rem" v-if="this.showReason.some(el=> el > 0)"/>
          <Column field="ireq_assigned_to2" header="ICT Personnel (2)" :sortable="true"  style="min-width:12rem" v-if="this.showPersonnel2.some(el=> el > 0)"/>
          <Column field="ireq_status" header="Status" :sortable="true"  style="min-width:10rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:17rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.cekstatus == 'NT' || slotProps.data.cekstatus == 'RT'"
                class="p-button-raised p-button-text mr-2 mt-2"
                icon="pi pi-user-edit"
                label="Assign"
                @click="AssignPerDetail(slotProps.data.ireqd_id)"
              />
              <Button
                v-if="slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'RT' || slotProps.data.ireq_assigned_to2 && slotProps.data.cekstatus == 'NT'"
                class="p-button-raised p-button-text mr-2 mt-2"
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
                  v-model="assign.request_type"
                  disabled
                />
              </div>
          </div>
          <div class="field grid">
            <label class="col-fixed w-9rem">Items</label>
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
                  v-model="assign.ireq_assigned_to2"
                  :options="petugas"
                  optionValue="code"
                  optionLabel="name"
                  placeholder="Select One"
                  :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to2 }"
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
import html2pdf from 'html2pdf.js';

export default {
  data() {
    return {
        showPersonnel1:[],
        showPersonnel2:[],
        showReason:[],
        submitted:false,
        dialogAssign:false,
        assign:[],
        petugas:[],
        kode:'',
        show:false,
        status:'',
        loading: true,
        detail: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        code:null
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    formatDate(date){
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    AssignPerDetail(ireqd_id){
      this.axios.get('/api/detail/'+ ireqd_id+'/'+ this.$route.params.code).then((response)=>{
        this.assign = response.data.data.dtl;
        this.petugas = response.data.data.pekerja;
      });
      this.dialogAssign = true;
    },
    updateAssign(){
      this.submitted = true;
      this.code = this.assign.ireqd_id
      if(this.assign.status =='RT'){
        if(this.assign.ireq_assigned_to1 != null){
          this.axios.put('/api/updateAssignPerDetailFromReject/'+ this.code ,this.assign).then(()=>{
            this.assign = [];
            this.dialogAssign = false;
            this.submitted = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000,
            });
            this.loading = true;
            this.getIctDetail();
          });
        }
      }else{
        if(this.assign.ireq_assigned_to1 != null && this.assign.ireq_assigned_to1_reason != null){
          this.axios.put('/api/updateAssignPerDetailFromReject/'+ this.code ,this.assign).then(()=>{
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
        header: "Confirmation",
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
            this.axios.get('/api/appd/' +ireqd_id + '/' +this.$route.params.code);
            
            this.getIctDetail();
            this.loading = true;
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
      this.axios.get('/api/ict-detail-reviewer/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.showPersonnel1 = response.data.data.detail.map((x)=>x.ireq_count_status);
        this.showPersonnel2 = response.data.data.detail.map((x)=>x.ireq_count_personnel2);
        this.showReason = response.data.data.detail.map((x)=>x.ireq_count_reason);
        this.kode = response.data.data.request;
        this.status = response.data.data.request.Ccekstatus;
        if(this.status =='NT' || this.status == 'RT'){ 
          this.show = true
        }
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
          if(error.response.status == 403){
            this.$router.push('/access');
          }
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('/api/print-out-ict-request/' +this.$route.params.code).then((response)=>{
        let htmlContent = response.data.htmlContent;
         let RequestNo = response.data.norequest;
         const options = {
            filename: 'Form ICT Request No. '+RequestNo+'.pdf',
            jsPDF: { 
              unit: 'mm', 
              format: 'a4',
              orientation: 'landscape',
              width: 210,
              height: 297
            }
          };

          this.$html2pdf().set(options).from(htmlContent).save();
          this.loading = false;
       });
    },
  },
};
</script>