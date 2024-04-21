<template>
    <DataTable :value="value" :paginator="true" :rows="10" :loading="loading" :filters="filters" :rowHover="true"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Request" responsiveLayout="scroll">
        <template #header>
            <div class="table-header text-right">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="filters['global'].value" placeholder="Search..." />
                </span>
            </div>
        </template>
        <template #empty>
            Not Found
        </template>
        <template #loading>
            Please wait
        </template>
        <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="spv" header="User Supervisor" :sortable="true" style="min-width:12rem" v-if="showSpvColumn" />
        <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:10rem" v-if="showReason == '1'"/>
        <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="showRemarkColumn" />
        <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:10rem" v-if="showPersonnel1Column" />
        <Column field="ireq_assigned_to1_reason" header="Rejected Reason" :sortable="true" style="min-width:12rem" v-if="showReasonPersonnelColumn"/>
        <Column field="ireq_count_id" header="Total Detail" :sortable="true" style="min-width:10rem" v-if="showTotalDetail == '1'" />
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
                <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
        </Column>
        <Column headerStyle="min-width:10rem">
            <template #body="slotProps">
                <div class="button-group">
                    <Button class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-info-circle" v-tooltip.bottom="'Click for request details'" @click="detailRequest(slotProps.data.ireq_id, Active)" />
                    <Button v-if="slotProps.data.status == 'P'" class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-pencil" v-tooltip.bottom="'Click for Edit Supervisor'" @click="editDataSpv(slotProps.data.ireq_id)" />
                    <Button v-if="slotProps.data.status == 'P'" class="p-button-rounded p-button-warning mr-2 mt-2" @click="SendEmail(slotProps.data.usr_email,slotProps.data.ireq_id)" icon="bi bi-envelope-check-fill" v-tooltip.bottom="'Click to send email to '+slotProps.data.usr_email" />
                    <Button v-if="slotProps.data.status == 'P'" class="p-button-rounded p-button-danger mr-2 mt-2" @click="Reject(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to reject request'" icon="bi bi-x-square" />
                    <Button v-if="slotProps.data.status !== 'RR' && slotProps.data.status !== 'RA1' && slotProps.data.status !== 'RA2'" icon="bi bi-chat-quote" class="p-button-rounded p-button mr-2 mt-2" @click="Remark(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to add remark'" />
                    <Button v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.ireq_count_spv > 0 && slotProps.data.status == 'P'" class="p-button-rounded mr-2 mt-2" @click="ApproveAtasan(slotProps.data.ireq_id)" icon="bi bi-file-earmark-arrow-up" v-tooltip.bottom="'Click to higher level approval'" />
                    <Button v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.ireq_count_spv > 0 && slotProps.data.status == 'P' || slotProps.data.status === 'A1'" class="p-button-rounded mr-2 mt-2" @click="ApproveManager(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to ICT manager approval'" icon="bi bi-file-earmark-arrow-up-fill" />
                    <Button v-if="slotProps.data.status === 'P' || slotProps.data.status === 'A1' || slotProps.data.status === 'A2' || slotProps.data.status == 'RT'" class="p-button-rounded mr-2 mt-2" @click="AssignPerRequest(slotProps.data.ireq_id, slotProps.data.ireq_assigned_to1)" icon="bi bi-person-workspace" v-tooltip.bottom="'Click to Assign Per Request'" />
                    <Button v-if="slotProps.data.status === 'P' || slotProps.data.status === 'A1' || slotProps.data.status === 'A2'" class="p-button-rounded mr-2 mt-2" @click="$router.push({ name: 'Ict Request Reviewer Assign Per Detail', params : {code: slotProps.data.ireq_id}})" icon="bi bi-people" v-tooltip.bottom="'Click to Assign Per Detail'" />
                    <Button v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status !== 'NT' && slotProps.data.status !== 'RT' && slotProps.data.status !== 'T'" class="p-button-rounded p-button-success mr-2 mt-2" @click="Submit(slotProps.data.ireq_id)" icon="bi bi-send-check" v-tooltip.bottom="'Click to submit'" />
                    <Button v-if="slotProps.data.count_assigned2 > 0 && slotProps.data.status == 'RT'" class="p-button-rounded p-button-success mr-2 mt-2" @click="Submit(slotProps.data.ireq_id)" icon="bi bi-send-check" v-tooltip.bottom="'Click to submit'" />
                </div>
            </template>
        </Column>
        <template #footer>
            <div class="grid p-dir-col">
                <div class="col">
                    <div class="box">
                        <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard'})"/>
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="PrintRequestListByStatusPdf(printPdf)" />
                    </div>
                </div>
            </div>
        </template>
    </DataTable>
    <Dialog v-model:visible="dialogEditSpv" :style="{ width: '400px' }" header="Form Dialog Edit SPV" :modal="true"
        class="fluid grid">
        <div class="p-fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Supervisor</label>
                <div class="col">
                    <Dropdown v-model="editSpv.ireq_spv" :options="listSpv" optionValue="spv_id" :filter="true"
                        optionLabel="spvnamejob" placeholder="Choose One" :showClear="true" />
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Save" @click="submitSpv()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelSpv()" class="p-button-text" />
        </template>
    </Dialog>
    <Dialog v-model:visible="dialogReject" :style="{ width: '400px' }" header="Form Dialog Reject" :modal="true" class="fluid grid">
        <div class="p-fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Reason</label>
                <div class="col">
                    <Textarea :autoResize="true" type="text" v-model="rbr.ket" rows="5" placeholder="Enter Reason"
                        :class="{ 'p-invalid': submitted && !rbr.ket }" />
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
    <Dialog v-model:visible="dialogAssign" :style="{ width: '500px' }" header="Assign Per-Request" :modal="true"
        :closable="false" class="fluid">
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem">Personnel (ICT)</label>
                <div class="col-fixed w-9rem">
                    <Dropdown v-model="assign.name" :options="petugas" optionValue="code" :filter="true"
                        optionLabel="name" placeholder="Choose One" :showClear="true" />
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
        </template>
    </Dialog>
    <Dialog v-model:visible="dialogRemark" :style="{ width: '400px' }" header="Form Dialog Remark" :modal="true"
        class="fluid grid">
        <div class="p-fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                <div class="col">
                    <Textarea :autoResize="true" type="text" v-model="remark.remark" rows="5"
                        placeholder="Enter Remark" />
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
</template>

<script>
    export default {
        emits: ['show-loading', 'hide-loading','get-data'],
        props: {
            value: Array,
            loading: Boolean,
            printPdf: String,
            showSpv: Array,
            showRemark: Array,
            showReason: String,
            showTotalDetail: String,
            showPersonnel1: Array,
            showPersonnel2: Array,
            showReason: String,
            showReasonPersonnel: Array,
            Active: String,
            showForDashboard: {
                type: Boolean,
                default: false
            },

        },
        computed: {
            showForDashboardFooter() {
                return (
                    (this.showForDashboard == true)
                );
            },
            showSpvColumn() {
                return (
                    (this.showSpv && this.showSpv.some(el => el > 0))
                );
            },
            showRemarkColumn() {
                return (
                    (this.showRemark && this.showRemark.some(el => el > 0))
                );
            },
            showPersonnel1Column() {
                return (
                    (this.showPersonnel1 && this.showPersonnel1.some(el => el > 0))
                );
            },
            showReasonPersonnelColumn() {
                return (
                    (this.showReasonPersonnel && this.showReasonPersonnel.some(el=> el > 0))
                );
            }
        },
        data() {
            return {
                filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
                Type: {
                    'report_type': ''
                },
                editSpv:[],
                listSpv:[],
                dialogSendMail:false,
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
                rbr:{
                    ket:null,
                    id:null
                },
                
            };
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
                    this.$emit('get-data');
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
            PrintRequestListByStatusPdf(type) {
                this.$emit('show-loading');
                this.Type.report_type = type;
                this.axios.post('api/print-out-pdf-requestor', this.Type).then((response) => {
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
                    this.$emit('hide-loading');
                });
            },
            formatDate(date) {
                return this.$moment(date).format("DD MMM YYYY HH:mm")
            },
            detailRequest(ireq_id, active){
                localStorage.setItem('active1',active);
                this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
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
                        this.$emit('show-loading');
                        this.$toast.add({
                            severity: "info",
                            summary: "Confirmed",
                            detail: "Success Submit",
                            life: 3000,
                        });
                        this.axios.get('api/sapr/'+ireq_id).then(()=>{
                            this.$emit('get-data');
                        });
                    },
                    reject: () => {},
                })
            },
            Remark(ireq_id){
                this.$emit('show-loading');
                this.remark.id = ireq_id;
                this.axios.get('api/get-remark-reviewer/'+ireq_id).then((res)=>{
                    this.remark.remark = res.data.ireq_verificator_remark;
                    this.dialogRemark = true;
                    this.$emit('hide-loading');
                });
            },
            cancelRemark(){
                this.remark.id = '';
                this.remark.remark = '';
                this.dialogRemark = false;
            },
            updateRemark(){
                this.dialogRemark = false;
                this.$emit('show-loading');
                this.axios.post('api/save-remark-reviewer',this.remark).then(()=>{
                    this.$toast.add({
                        severity: "info",
                        summary: "Success",
                        detail: "successfully added a remark",
                        life: 2000,
                    });
                    this.remark = {id:'',remark:''};
                    this.$emit('get-data');
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
                    this.$emit('show-loading');
                    this.$emit('get-data');
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
                        this.$emit('show-loading');
                        this.$toast.add({
                            severity: "info",
                            summary: "Confirmed",
                            detail: "Success Update Request",
                            life:2000
                        });
                        this.axios.get('/api/naa/' +ireq_id).then(()=>{
                            this.$emit('get-data');
                            this.$emit('show-loading');
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
                    this.$emit('show-loading');
                    this.$toast.add({
                        severity: "info",
                        summary: "Confirmed",
                        detail: "Success Update Request",
                        life:2000
                    });
                    this.axios.get('/api/nam/' +ireq_id).then(()=>{
                        this.$emit('get-data');
                        this.$emit('show-loading');
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
                    this.$emit('show-loading');
                    this.$emit('get-data');
                });
            },
            cancelAssign(){
                this.assign =
                { id : null, name : null };
                this.petugas = [];
                this.dialogAssign = false;
                this.submitted = false;
            }, 
        }
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
  .button-group {
    display: flex;
    flex-wrap: wrap;
  }

  .button-group .p-button {
    margin: 5px; /* Ruang antara tombol */
  }
</style>
