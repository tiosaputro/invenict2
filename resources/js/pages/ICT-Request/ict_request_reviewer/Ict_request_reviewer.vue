<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
            <h4>ICT Request - Reviewer</h4>
          </template>
        </Toolbar>
            <TabView v-model:activeIndex="active1">
              <TabPanel header="Request">
                <DataTableRequest :value="permohonan" :loading="loading" :showRemark="showRemarkPermohonan" :showSpv="showSpvPermohonan"
                    :filters="filters" printPdf="permohonan" :showPersonnel1="showPersonelPermohonan" showTotalDetail ="1" @get-data="getIct" 
                    @approve-atasan="ApproveAtasan" @approve-manager="ApproveManager" @assign-per-request="AssignPerRequest" @send-email="SendEmail"
                    @submit-request="Submit" @edit-spv="editDataSpv" @add-remark="Remark" Active="0"
                />  
              </TabPanel>
              <TabPanel header="Higher Level">
                <DataTableRequest :value="atasandivisi" :loading="loading" :showRemark="showRemarkAtasanDivisi" :showSpv="showSpvAtasanDivisi"
                    :filters="filters" printPdf="atasan" :showPersonnel1="showPersonelAtasanDivisi" @get-data="getIct" 
                    @approve-manager="ApproveManager" @assign-per-request="AssignPerRequest" @submit-request="Submit" @add-remark="Remark" Active="1"
                />  
              </TabPanel>
              <TabPanel header="ICT Manager">
                <DataTableRequest :value="manager" :loading="loading" :showRemark="showRemarkManager" :showSpv="showSpvManager"
                    :filters="filters" printPdf="manager" :showPersonnel1="showPersonelmanager" @get-data="getIct" 
                     @assign-per-request="AssignPerRequest" @submit-request="Submit" @add-remark="Remark" Active="2"
                /> 
              </TabPanel>
              <TabPanel header="Rejected">
                <DataTableRequest :value="reject" :loading="loading" :showRemark="showRemarkManager" :showSpv="showSpvManager"
                    :filters="filters" printPdf="reject" @get-data="getIct" @add-remark="Remark" showReason="1" Active="3"
                />
              </TabPanel>
              <TabPanel header="Request Assignment">
                <DataTableRequest :value="penugasan" :loading="loading" :showRemark="showRemarkPenugasan" :showSpv="showSpvPenugasan"
                    :filters="filters" printPdf="assignment_request" :showPersonnel1="showPersonelPenugasan" @get-data="getIct" 
                    @add-remark="Remark" @assign-per-request="AssignPerRequest" @submit-request="Submit" :showReasonPersonnel="showReasonPersonnel" Active="4"
                />
              </TabPanel>
              <TabPanel header="In Progress">
                <DataTableRequest :value="sedangDikerjakan" :loading="loading" :showRemark="showRemarkSedangDikerjakan" :showSpv="showSpvSedangDikerjakan"
                    :filters="filters" printPdf="sedang_dikerjakan" :showPersonnel1="showPersonelSedangDikerjakan" Active="5"
                />
              </TabPanel>
              <TabPanel header="Done">
                <DataTableDetail :value="sudahDikerjakan" :loading="loading" @closing-request="ClosingPerDetail"
                  :filters="filters" printPdf="sudah_dikerjakan" @cetak-pdf="PrintOutFormIctRequest"/>
              </TabPanel>
              <TabPanel header="Close">
                <DataTableDetail :value="selesai" :loading="loading" :filters="filters" printPdf="selesai" 
                  @cetak-pdf="PrintOutFormIctRequest" @detail-request="detailRequestNo"/>
              </TabPanel>
            </TabView>
            <Dialog v-model:visible="dialogEditSpv"
                :style="{ width: '400px' }"
                header="Form Dialog Edit SPV"
                :modal="true"
                class="fluid grid"
            >
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Supervisor</label>
                     <div class="col">
                        <Dropdown
                            v-model="editSpv.ireq_spv"
                            :options="listSpv"
                            optionValue="spv_id"
                            :filter="true"
                            optionLabel="spvnamejob"
                            placeholder="Choose One"
                            :showClear="true"
                        />
                     </div>
                   </div>
                </div>
                <template #footer>
                    <Button label="Save" @click="submitSpv()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelSpv()" class="p-button-text" />
                </template>
            </Dialog>
            <Dialog v-model:visible="dialogReject"
                :style="{ width: '400px' }"
                header="Form Dialog Reject"
                :modal="true"
                class="fluid grid"
            >
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Reason</label>
                     <div class="col">
                          <Textarea
                            :autoResize="true"
                            type="text"
                            v-model="rbr.ket"
                            rows="5" 
                            placeholder="Enter Reason"
                            :class="{ 'p-invalid': submitted && !rbr.ket }"
                          />
                            <small v-if="submitted && !rbr.ket" class="p-error">
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
            <Dialog v-model:visible="dialogAssign"
                :style="{ width: '500px' }"
                header="Assign Per-Request"
                :modal="true"
                :closable="false"
                class="fluid"
            >
                <div class="fluid">
                <div class="field grid">
                    <label class="col-fixed w-9rem">Personnel (ICT)</label>
                    <div class="col-fixed w-9rem">
                        <Dropdown
                            v-model="assign.name"
                            :options="petugas"
                            optionValue="code"
                            :filter="true"
                            optionLabel="name"
                            placeholder="Choose One"
                            :showClear="true"
                        />
                    </div>
                </div>
                </div>
                <template #footer>
                    <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
                </template>
            </Dialog>
            <Dialog v-model:visible="displayDetailRequest"
              :style="{ width: '1200px' }"
              header="Detail Request"
              :modal="true"
            >
            <Toolbar class="mb-4">
              <template v-slot:end>
                  <label style="width:130px">No. Request: {{this.ireq_no}}</label>
              </template>
            </Toolbar>
              <DataTable
                :value="detail"
                :paginator="true"
                :rows="10"
                :filters="filters"
                :loading="loadingDetail"
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
                <template #loading>
                  Loading data. Please wait
                </template>
                <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:6rem"/>
                <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
                <Column field="kategori" header="Items" :sortable="true" style="min-width:12rem"/>
                <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
                <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
                <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:12rem"/>
                <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
              </DataTable>
            </Dialog>  
            <Dialog v-model:visible="dialogRemark"
                :style="{ width: '400px' }"
                header="Form Dialog Remark"
                :modal="true"
                class="fluid grid"
            >
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                     <div class="col">
                          <Textarea
                            :autoResize="true"
                            type="text"
                            v-model="remark.remark"
                            rows="5" 
                            placeholder="Enter Remark"
                          />
                            <!-- <small v-if="submitted && !rbr.ket" class="p-error">
                            Reason not filled
                            </small> -->
                     </div>
                   </div>
                </div>
                <template #footer>
                    <Button label="Save" @click="updateRemark()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelRemark()" class="p-button-text" />
                </template>
            </Dialog>
      </div>
    </div>
  </div>
</template>
<script>

import DataTableRequest from '../../Components/Reviewer/DataTableRequestReviewer.vue';
import DataTableDetail from '../../Components/Reviewer/DataTableDetailReviewer.vue';
export default {
  components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
        editSpv:[],
        listSpv:[],
        dialogSendMail:false,
        active1:JSON.parse(localStorage.getItem('active1')),
        dialogAssign:false,
        dialogRemark:false,
        dialogEditSpv:false,
        remark:{
          remark:'',
          id:''
        },
        displayDetailRequest:false,
        submitted:false,
        assign:{
          id:null,
          name: null
        },
        petugas:[],
        dialogReject: false,
        submmited: false,
        rbr:{
          ket:null,
          id:null
        },
        loading: true,
        loadingDetail: false,
        permohonan: [],
        detail:[],
        ireq_no :'',
        atasandivisi:[],
        manager:[],
        reject:[],
        penugasan:[],
        sedangDikerjakan:[],
        showRemarkSedangDikerjakan:[],
        showSpvSedangDikerjakan:[],
        showPersonelSedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        showPersonelPermohonan:[],
        showPersonelAtasanDivisi:[],
        showSpvAtasanDivisi:[],
        showPersonelmanager:[],
        showRemarkPermohonan:[],
        showSpvPermohonan:[],
        showRemarkAtasanDivisi:[],
        showRemarkManager:[],
        showSpvManager:[],
        showRemarkPenugasan:[],
        showSpvPenugasan:[],
        showPersonelPenugasan:[],
        showRemarkApprover2Manager:[],
        showReasonPersonnel:[],
        Type:{
          'report_type': ''
        },
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    editDataSpv(ireq_id){
      this.axios.get('/api/edit-spv/'+ireq_id).then((res)=>{
        this.editSpv = res.data.data.request;
        this.listSpv = res.data.data.listSupervisor;
        this.dialogEditSpv = true;
      });
    },
    submitSpv(){
      this.axios.post('/api/save-spv',this.editSpv).then(()=>{
        this.cancelSpv();
        this.getIct();
      })
    },
    cancelSpv(){
      this.editSpv = [];
      this.listSpv = [];
      this.dialogEditSpv = false;
    },
    SendEmail(usr_email,ireq_id){
      this.axios.get('/api/edit-spv/'+ireq_id).then((res)=>{
        var frommail = usr_email;
        window.open("mailto:"+frommail+"?subject="+res.data.data.request.noreq);
      });
    },
    getIct(){
      this.axios.get('api/get-data-reviewer').then((response)=> {
          this.permohonan = response.data.ict;
          this.showPersonelPermohonan = this.permohonan.map((x)=>x.ireq_count_status);
          this.showRemarkPermohonan = this.permohonan.map((x)=>x.count_remark);
          this.showSpvPermohonan = this.permohonan.map((x)=>x.ireq_count_spv);
          this.loading = false;
          this.atasandivisi = response.data.ict1;
          this.showPersonelAtasanDivisi = this.atasandivisi.map((x)=>x.ireq_count_status);
          this.showRemarkAtasanDivisi = this.atasandivisi.map((x)=>x.count_remark);
          this.showSpvAtasanDivisi = this.atasandivisi.map((x)=>x.ireq_count_spv);
          this.manager = response.data.ict2;
          this.showPersonelmanager = this.manager.map((x)=>x.ireq_count_status);
          this.showRemarkManager = this.manager.map((x)=>x.count_remark);
          this.showSpvManager = this.manager.map((x)=>x.ireq_count_spv);
          this.showRemarkApprover2Manager = this.manager.map((x)=>x.count_remark_approver2);
          this.reject = response.data.ict3;
          this.penugasan = response.data.ict7;
          this.showSpvPenugasan = this.penugasan.map((x)=>x.ireq_count_spv);
          this.showPersonelPenugasan = this.penugasan.map((x)=>x.ireq_count_status);
          this.showRemarkPenugasan = this.penugasan.map((x)=>x.count_remark);
          this.showReasonPersonnel = this.penugasan.map((x)=>x.countreason);
          this.sedangDikerjakan = response.data.ict4;
          this.showRemarkSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.count_remark);
          this.showSpvSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.ireq_count_spv);
          this.showPersonelSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.ireq_count_status);
          this.sudahDikerjakan = response.data.ict5;
          this.selesai = response.data.ict6;
          localStorage.setItem('active1',0);
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
    formatDate(date){
      return this.$moment(date).format("DD MMM YYYY HH:mm")
    },
    Submit(ireq_id){
      this.$confirm.require({
        message: "Are you sure to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Submit",
            life: 3000,
          });
          this.axios.get('api/sapr/'+ireq_id).then(()=>{
            this.getIct();
          });
        },
        reject: () => {},
      })
    },
    Remark(ireq_id){
      this.loading = true;
      this.remark.id = ireq_id;
      this.axios.get('api/get-remark-reviewer/'+ireq_id).then((res)=>{
        this.remark.remark = res.data.ireq_verificator_remark;
        this.dialogRemark = true;
        this.loading = false;
      });
    },
    cancelRemark(){
      this.remark.id = '';
      this.remark.remark = '';
      this.dialogRemark = false;
    },
    updateRemark(){
      this.dialogRemark = false;
      this.loading = true;
      this.axios.post('api/save-remark-reviewer',this.remark).then(()=>{
        this.$toast.add({
          severity: "info",
          summary: "Success",
          detail: "successfully added a remark",
          life: 2000,
        });
        this.remark = {id:'',remark:''};
        this.getIct();
      });
    },
    AssignPerRequest(ireq_id, ireq_assigned_to1){
        this.assign.id = ireq_id;
        this.assign.name = ireq_assigned_to1;
        this.axios.get('api/get-pekerja').then((response)=>{
            this.petugas = response.data;
        });
        this.dialogAssign = true;
    },
    Reject(ireq_id){
        this.dialogReject = true;
        this.rbr.id = ireq_id;
    },
    cancelReject(){
        this.dialogReject = false;
        this.rbr.id = null;
        this.rbr.ket = null;
    },
    updateReject(){
        this.submitted = true;
        if(this.rbr.ket != null){
          this.axios.put('/api/reject-by-reviewer/'+this.rbr.id, this.rbr).then(()=>{
            this.dialogReject = false;    
            this.rbr.id = null;
            this.rbr.ket = null;
            this.submitted = false;
              this.$toast.add({
                severity: "info",
                summary: "Success",
                detail: "Successfully rejected the request",
                life: 2000,
              });
              this.loading = true;
              this.getIct();
        });
        }
    },
    ApproveAtasan(ireq_id){
    this.$confirm.require({
        message: "Are you sure this request need approval from higher level?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.axios.get('/api/naa/' +ireq_id).then(()=>{
            this.getIct();
            this.loading = true;
          });
        },
        reject: () => {},
      });
    },
    ApproveManager(ireq_id){
        this.$confirm.require({
        message: "Are you sure this request need approval from ICT Manager?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.axios.get('/api/nam/' +ireq_id).then(()=>{
            this.getIct();
            this.loading = true;
          });
        },
        reject: () => {},
      });
    },
    updateAssign(){
          this.axios.post('/api/aprr', this.assign).then(()=>{
            this.assign = {
              id : null,
              name : null
            };
            this.dialogAssign = false;
            this.$toast.add({
              severity: "info",
              summary: "Success Message",
              detail: "Successfully Assignment",
              life: 2000,
            });
            this.loading = true;
            this.getIct();
          });
    },
    cancelAssign(){
      this.assign =
      { id : null, name : null };
      this.petugas = [];
      this.dialogAssign = false;
      this.submitted = false;
    }, 
    ClosingPerDetail(ireqd_id,ireq_id){
        this.$confirm.require({
          message: "Are you sure to close this request?",
          header: "Closing Per Detail",
          icon: "pi pi-info-circle",
          acceptClass: "p-button",
          acceptLabel: "Yes",
          rejectLabel: "No",
          accept: () => {
            this.loading = true;
            this.$toast.add({
              severity: "info",
              summary: "Success",
              detail: "Closing request successful",
              life: 3000,
            });
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_id).then(()=>{
              this.getIct();
              this.loading = true;
            });
          },
          reject: () => {},
        });
    },
    detailRequestNo(ireq_id){
      this.displayDetailRequest = true;
      this.loadingDetail = true;
      this.axios.get('/api/detail-request-reviewer/' + ireq_id).then((response)=> {
        this.detail = response.data;
        this.ireq_no = response.data[0].ireq_no;
        this.loadingDetail = false;
      });
    },
    PrintOutFormIctRequest(ireq_id){
      this.loading = true;
       this.axios.get('api/print-out-ict-request/' +ireq_id).then((response)=>{
          let htmlContent = response.data.data.htmlContent;
          let norequest = response.data.data.norequest;
          const options = {
            filename: 'Form ICT Request No.'+norequest+'.pdf', 
            jsPDF: { 
              unit: 'mm', 
              format: 'a4', 
              orientation: 'landscape', 
            }
          };

          
          this.$html2pdf().set(options).from(htmlContent).save();
          this.loading = false;
       });
    },
    PrintRequestListByStatusPdf(type){
      this.loading = true;
      this.Type.report_type = type;
       this.axios.post('api/print-out-pdf-reviewer', this.Type).then((response)=>{
        let htmlContent = response.data.data.htmlContent;
        const options = {
          filename: 'ICT Request List.pdf', 
          jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'landscape', 
          }
        };
        
        this.$html2pdf().set(options).from(htmlContent).save();
        this.loading = false;
       });
    },
    CetakExcelPermohonan(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-permohonan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelAtasanDivisi(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-atasan-divisi',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelIctManager(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-ict-manager',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelReject(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-reject',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelAssignmentRequest(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-assignment-request',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSedangDikerjakan(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-sedang-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSudahDikerjakan(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-sudah-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSelesai(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-selesai',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
  },
};
</script>
