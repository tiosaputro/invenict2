<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast/>
        <ConfirmDialog group="positionDialog"></ConfirmDialog>
        <Toolbar class="p-mb-4">
          <template v-slot:start>
				    <h4>ICT Request (Verification) </h4>
          </template>
          <template v-slot:end>
            No. Request: {{kode.noreq}}
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
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Items" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                 <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
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
                      name: 'Ict Request Higher Level'})"
                  />
                  <Button
                    label="Approve"
                    v-if="this.kode.cekstatus == 'NA1'"
                    class="p-button-raised p-button-success mr-2"
                    icon="pi pi-check-square"
                    @click="Approve()"
                  />
                  <Button 
                    label="Reject"
                    v-if="this.kode.cekstatus == 'NA1'"
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
          header="Form Dialog Reject"
          :modal="true"
          position="top"
          class="field grid"
        >
        <div class="field grid">
            <label style="width:100px">Reason</label>
              <div class="col-3 md-6">
                <Textarea
                    :autoResize="true"
                    type="text"
                    rows="5" 
                    cols="20"
                    v-model="reason.ket"
                    placeholder="Give a reason"
                    :class="{ 'p-invalid': submitted && !reason.ket }"
                  />
                    <small v-if="submitted && !reason.ket" class="p-error">
                    Reason not filled
                    </small>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="updateReject()" class="p-button" autofocus />
            <Button label="No" @click="cancelReject()" class="p-button-text" />
        </template>
        </Dialog>
        <Dialog header="Confirmation" v-model:visible="confirmationVerifikasi" :style="{width: '350px'}" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Request Verification</span>
            </div>
            <template #footer>
                <Button label="Reject" icon="pi pi-times" @click="rejectRequest" class="p-button-raised p-button-danger p-button-text"/>
                <Button label="Approve" icon="pi pi-check" @click="Approve" class="p-button-raised p-button-text" autofocus />
            </template>
        </Dialog>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        loading: true,
        reason: {
          ket : null
        },
        dialogReject: false,
        confirmationVerifikasi:false,
        submitted:false,
        verif: [],
        kode:[],
        detail:{
          'code' : null,
          'status' : null,
          'ket' : null
        },
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        status : null,
    };
  },
  mounted() {
    this.getNoreq();
  },
  methods: {
      getDetail(ireq_attachment){
        var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
          var myWindow = window.open(page, "_blank");
          myWindow.focus();
      },
      VerifikasiRequest(){
        this.confirmationVerifikasi = true;
      },
      cekStatus(){
        this.status = this.$route.params.status;
          if(this.status == 'approve'){
              this.Approve();
          }
          if(this.status == 'reject'){
              this.dialogReject = true;
          }
      },
      Approve(){
      this.confirmationVerifikasi = false;
      this.$confirm.require({
        group: 'positionDialog',
        message: "Are you sure you approve to this request?",
        header: "Confirmation Approval",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        position: 'top',
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Successfully approved the request",
          });
          this.detail = { 
            'code' : this.$route.params.code,
            'status' : 'Approve'
          }
          this.ApprovalRequest(this.detail);
          // this.axios.get('/api/updateStatusPermohonan/' +this.$route.params.code);
          setTimeout( () =>  this.$router.push('/dashboard'),1000);
        },
        reject: () => {},
      });
      },
      rejectRequest(){
        this.confirmationVerifikasi = false;
        this.dialogReject = true;
      },
      ApprovalRequest(detail){
        this.axios.post('/api/approval-for-verification', this.detail).then(()=>{
          this.detail = {
            'code'    : null,
            'status'  : null,
            'ket'  : null
          }
        });
      },
      updateReject(){
        this.confirmationVerifikasi = false;
          this.submitted = true;
           if(this.reason.ket != null){
            // this.axios.put('/api/updateStatusReject/'+this.$route.params.code, this.reason).then(()=>{
              this.detail = { 
                'code' : this.$route.params.code,
                'status' : 'Reject',
                'ket'  : this.reason.ket
              }
              this.ApprovalRequest(this.detail);
              this.dialogReject = false;
              this.$toast.add({
                severity: "info",
                summary: "Success Message",
                detail: "Successfully rejected the request",
              });
              setTimeout( () => this.$router.push('/dashboard'),1000);
            // });
          }
      },
      cancelReject(){
        this.dialogReject = false;
        this.reason.ket = null;
        this.submitted = false;
      },
      getNoreq(){
        this.axios.get('/api/ict-detail-higher-level-verification/'+ this.$route.params.code).then((response)=>{
        this.kode = response.data.data.request;
        if(this.kode.cekstatus =='NA1'){
          this.verif = response.data.data.detail;
          this.cekStatus();
          this.loading = false;
        }
        else{
          this.$toast.add({
            severity: "error",
            summary: "Error Message",
            detail: "This request has been verified",
          });
          setTimeout( () =>  this.$router.push('/ict-request-higher-level'),2000);
        }
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.attachment-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

.p-button.youtube {
    cursor:pointer;
    background: linear-gradient(to left, var(--pink-600) 50%, var(--pink-700) 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: background-position 0.5s ease-out;
    color: #fff;
    border-color: var(--pink-700);
}
.p-button.youtube:hover {
    background-position: left bottom;
}
.p-button.youtube {
    cursor:pointer;
    background: linear-gradient(to left, var(--pink-600) 50%, var(--pink-700) 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: background-position 0.5s ease-out;
    color: #fff;
    border-color: var(--pink-700);
}
.p-button.youtube:hover {
    background-position: left bottom;
}
.template .p-button.twitter {
    background: linear-gradient(to left, var(--blue-400) 50%, var(--blue-500) 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: background-position 0.5s ease-out;
    color: #fff;
    border-color: var(--blue-500);
}
.template .p-button.twitter:hover {
    background-position: left bottom;
}
.template .p-button.twitter i {
    background-color: var(--blue-500);
}
.template .p-button.twitter:focus {
    box-shadow: 0 0 0 1px var(--blue-200);
}
</style>