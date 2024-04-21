<template>
    <DataTable :value="value" :paginator="true" :rows="10" :loading="loading" :filters="filters" :rowHover="true"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
        :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Request"
        responsiveLayout="scroll">
        <template #header>
            <div class="table-header text-right">
                <span class="p-input-icon-left">
                    <i class="pi pi-search" />
                    <InputText v-model="filters['global'].value" placeholder="Search. . ." />
                </span>
            </div>
        </template>
        <template #empty>
            Not Found
        </template>
        <template #loading>
            Loading ICT Request data. Please wait.
        </template>
        <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
        <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
        <Column field="name" header="Items" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="showRemarkReviewerColumn"/>
        <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:10rem" v-if="showRemarkRequestorColumn"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
                <p v-if="slotProps.data.ireq_attachment == null"></p>
                <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                    <img :src="'/attachment_request/' +slotProps.data.ireq_attachment" class="attachment-image" style="cursor:pointer;" @click="getDetail(slotProps.data.ireq_attachment)" />
                </p>
                <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                    <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                        v-tooltip.bottom="'Click to detail attachment'">
                        <i class="pi pi-file-pdf px-2"></i>
                        <span class="px-4">PDF</span>
                    </Button>
                </p>
            </template>
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_assigned_to1_reason" header="Reason" :sortable="true" style="min-width:10rem" v-if="showReasonColumn" />
        <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem" v-if="showRemarkAssignedColumn"/>
        <Column field="ireq_note_personnel" header="Note Assigned" :sortable="true" style="min-width:12rem" v-if="showNoteAssignedColumn"/>
        <Column style="min-width:8rem">
            <template #body="slotProps">
                <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-secondary mr-2 mt-2"
                    v-tooltip.bottom="'Click to change status'" icon="pi pi-pencil"
                    @click="editStatusRequest(slotProps.data.ireqd_id,slotProps.data.ireq_id)" />
                <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-help mr-2 mt-2"
                    icon="bi bi-journal-text" v-tooltip.bottom="'Click to create note'"
                    @click="createNote(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
                <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-danger mr-2 mt-2"
                    icon="bi bi-journals" v-tooltip.bottom="'Click to create remark'"
                    @click="createRemark(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
                <Button v-if="slotProps.data.status == 'D' || slotProps.data.status == 'C' " label="Pdf" class="p-button-raised p-button-danger mr-2 mt-2"
                    v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf"
                    @click="PrintOutFormIctRequest(slotProps.data.ireq_id)" />
            </template>
        </Column>
        <template #footer>
            <div class="p-grid p-dir-col">
                <div class="p-col">
                    <div class="box">
                        <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard'})"/>
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="printPdfRequestListByStatus(printRequestListByStatus)" />
                        <!-- <Button label="Excel" class="p-button-raised p-button-success mr-2" icon="pi pi-print"
                            @click="CetakExcelAssignmentRequest()" /> -->
                    </div>
                </div>
            </div>
        </template>
    </DataTable>
    <Dialog v-model:visible="dialogEdit" :style="{ width: '500px' }" header="Dialog Reject Request" :modal="true" class="fluid">
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Reason</label>
                <div class="col-fixed w-9rem">
                    <Textarea v-model="editDetail.ireq_reason" :autoResize="true" rows="5" cols="20"
                        placeholder="Give a reason" :class="{ 'p-invalid': submitted && !editDetail.ireq_reason }" />
                    <small v-if="submitted && !editDetail.ireq_reason" class="p-error">
                      Reason not filled
                    </small>
                  </div>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submitReject()" class="p-button" autofocus />
            <Button label="No" @click="cancelReject()" class="p-button-text" />
        </template>
    </Dialog>  
    <Dialog v-model:visible="dialogRemark" :style="{ width: '500px' }" header="Dialog Create Remark" :modal="true"
        class="fluid">
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                <div class="col-fixed">
                    <InputText v-model="remark.ireq_no" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Detail </label>
                <div class="col-fixed">
                    <InputText v-model="remark.ireqd_id" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                <div class="col-fixed w-9rem">
                    <Textarea v-model="remark.ireq_assigned_remark" placeholder="If required" :autoResize="true"
                        rows="5" cols="20" />
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submitRemark()" class="p-button" autofocus />
            <Button label="No" @click="cancelRemark()" class="p-button-text" />
        </template>
    </Dialog>  
    <Dialog v-model:visible="dialogNote" :style="{ width: '500px' }" header="Dialog Create Note" :modal="true"
        class="fluid">
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                <div class="col-fixed">
                    <InputText v-model="note.ireq_no" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Detail </label>
                <div class="col-fixed">
                    <InputText v-model="note.ireqd_id" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Note</label>
                <div class="col-fixed w-9rem">
                    <Textarea v-model="note.ireq_reason" placeholder="If required" :autoResize="true" rows="5" cols="20" />
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submitNote()" class="p-button" autofocus />
            <Button label="No" @click="cancelNote()" class="p-button-text" />
        </template>
    </Dialog>
    <Dialog v-model:visible="dialogChangeStatus" :style="{ width: '500px' }" header="Dialog Form Change Status"
        :modal="true" class="fluid">
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                <div class="col-fixed">
                    <InputText v-model="editStatus.ireq_no" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> No Detail </label>
                <div class="col-fixed">
                    <InputText v-model="editStatus.ireqd_id" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem"> Items </label>
                <div class="col-fixed">
                    <InputText v-model="editStatus.name" disabled />
                </div>
            </div>
        </div>
        <div class="fluid">
            <div class="field grid">
                <label class="col-fixed w-9rem">Status</label>
                <div class="col-fixed w-9rem">
                    <Dropdown v-model="editStatus.status" :filter="true" optionLabel="name" optionValue="code"
                        :options="status" placeholder="Pilih Status"
                        :class="{ 'p-invalid': submitted && !editStatus.status }" />
                    <small v-if="submitted && !editStatus.status" class="p-error">
                        Status Belum Diisi
                    </small>
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submitStatus()" class="p-button" autofocus />
            <Button label="No" @click="cancelStatus()" class="p-button-text" />
        </template>
    </Dialog>
     
</template>
<script>
    export default {
        emits: ['show-loading', 'hide-loading','get-data'],
        props: {
            value: Array,
            loading: Boolean,
            showRemarkReviewer:Array,
            showForDashboard: {
                type: Boolean,
                default: false
            },
            showReason: Array,
            printRequestListByStatus: String,
            showRemarkAssigned: Array,
            showNoteAssigned: Array,
            showRemarkRequestor: {
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
            showRemarkRequestorColumn(){
                return (
                    (this.showRemarkRequestor == true)
                );
            },
            showNoteAssignedColumn(){
                return (
                    (this.showNoteAssigned && this.showNoteAssigned.some(el => el > 0))
                );
            },
            showReasonColumn(){
                return (
                    (this.showReason && this.showReason.some(el => el > 0))
                );
            },
            showRemarkReviewerColumn() {
                return (
                    (this.showRemarkReviewer && this.showRemarkReviewer.some(el => el > 0))
                );
            },
            showRemarkAssignedColumn(){
                return (
                    (this.showRemarkAssigned && this.showRemarkAssigned.some(el => el > 0))
                );
            },
            formattedRequestDate() {
                return this.formatDate(this.detail.ireq_date);
            }
        },
        mounted() {
            this.isMobile = window.innerWidth <= 768;
            window.addEventListener('resize', this.updateIsMobile);
        },
        data() {
            return {
                isMobile: false,
                editDetail:{ 
                    ireq_id:'',
                    ireq_reason :''
                },
                submitted:false,
                dialogEdit : false,
                dialogRemark : false,
                dialogNote:false,
                dialogChangeStatus: false,
                remark:[],
                note:[],
                editStatus:[],
                submitted : false,
                Type:{
                    'report_type': ''
                },
                status:[
                    {'code': 'T','name':'In Progress'},
                    {'code': 'D','name':'Done'},
                ],
                filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
            };
        },
        methods: {
            getDetail(ireq_attachment = null){
                var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
                var myWindow = window.open(page, "_blank");
                myWindow.focus();
            },
            updateIsMobile() {
                this.isMobile = window.innerWidth <= 768;
            },
            formatDate(date = null) {
                return this.$moment(date).format("DD MMM YYYY HH:mm")
            },
            acceptRequest(ireq_id = null){
                this.$confirm.require({
                    message: "Are you sure to accept this request?",
                    header: "Confirmation",
                    icon: "pi pi-info-circle",
                    acceptClass: "p-button",
                    acceptLabel: "Yes",
                    rejectLabel: "No",
                    accept: () => {
                        this.$emit('show-loading');
                        this.$toast.add({
                            severity: "info",
                            summary: "Message Succes",
                            detail: "Accept Request Success",
                            life : 1000
                        });
                        this.axios.get('/api/acceptPersonnel/' +ireq_id).then(()=>{
                            this.$emit('get-data');
                        });
                    },
                    reject: () => {},
            }   );
            },
            rejectRequest(ireq_id = null){
                this.editDetail.ireq_id = ireq_id;
                this.dialogEdit = true;
            },
            cancelReject(){
                this.dialogEdit = false;
                this.editDetail = [];
                this.submitted = false;
            },
            submitReject(){
                this.submitted = true;
                if(this.editDetail.ireq_reason != ''){
                    this.$emit('show-loading');
                    this.axios.post('/api/rejectPersonnel', this.editDetail).then(()=>{
                        this.$toast.add({
                            severity:'success', summary: 'Success Message', detail:'Successfully rejected request', life: 3000
                        });
                        this.cancelReject();
                        this.$emit('get-data');
                    });
            }   
            },
            printPdfRequestListByStatus(status = null){
                this.$emit('show-loading')
                this.Type.report_type = status;
                this.axios.post('api/print-out-pdf-personnel', this.Type).then((response)=>{
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
                    this.$emit('hide-loading')
                });
            },
            CetakExcelAssignmentRequest(){
                const date = new Date();
                const today = this.$moment(date).format("DD MMM YYYY")
                this.$emit('show-loading')
                this.axios.get('api/report-ict-excel-personnel-assignment-request',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    this.$emit('hide-loading')
                });
            },
            createRemark(id = null,code = null){
                this.axios.get('api/detail/'+id+'/'+code).then((response)=>{
                    this.remark = response.data.data.dtl;
                    this.dialogRemark = true;
                });
                this.code = id;
            },
            createNote(ireqd_id = null,ireq_id = null){
                this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id).then((response)=>{
                    this.note = response.data.data.dtl;
                    this.dialogNote = true;
                });
                this.code = ireqd_id;
            },
            submitRemark(){
                this.axios.put('/api/save-remark-assigned/'+this.code,this.remark).then(()=>{ 
                    this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
                    this.note = [];
                    this.code = null;
                    this.dialogRemark = false;
                });
                this.$emit('show-loading');
                this.$emit('get-data');
            },
            submitNote(){
                this.axios.put('/api/update-note/'+this.code,this.note).then(()=>{ 
                    this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
                    this.note = [];
                    this.code = null;
                    this.dialogNote = false;
                });
                this.$emit('show-loading');
                this.$emit('get-data');
            },
            cancelRemark(){
                this.remark = [];
                this.code = null;
                this.dialogRemark = false;
            },
            cancelNote(){
                this.note = [];
                this.code = null;
                this.dialogNote = false;
            },
            CetakExcelReject(){
                const date = new Date();
                const today = this.$moment(date).format("DD MMM YYYY")
                this.$emit('show-loading');
                this.axios.get('api/report-ict-excel-personnel-reject',{headers: { 'Content-Type':
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType:
                'arraybuffer',}).then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    this.$emit('hide-loading');
                });
            },
            CetakExcelSedangDikerjakan(){
                const date = new Date();
                const today = this.$moment(date).format("DD MMM YYYY")
                this.$emit('show-loading');
                this.axios.get('api/report-ict-excel-personnel-sedang-dikerjakan',{headers: { 'Content-Type':
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType:
                'arraybuffer',}).then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    this.$emit('hide-loading');
                });
            },
            CetakExcelSudahDikerjakan(){
                const date = new Date();
                const today = this.$moment(date).format("DD MMM YYYY")
                this.$emit('show-loading');
                this.axios.get('api/report-ict-excel-personnel-sudah-dikerjakan',{headers: { 'Content-Type':
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType:
                'arraybuffer',}).then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    this.$emit('hide-loading');
                });
            },
            CetakExcelSelesai(){
                const date = new Date();
                const today = this.$moment(date).format("DD MMM YYYY")
                this.$emit('show-loading');
                this.axios.get('api/report-ict-excel-personnel-selesai',{headers: { 'Content-Type':
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType:
                'arraybuffer',}).then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    this.$emit('hide-loading');
                });
            },
            PrintOutFormIctRequest(ireq_id = null){
                this.$emit('show-loading');
                this.axios.get('api/print-out-ict-request/' +ireq_id).then((response)=>{
                    let htmlContent = response.data.htmlContent;
                    let norequest = response.data.norequest;
                    const options = {
                        filename: 'Form ICT Request No.'+norequest+'.pdf',
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
            editStatusRequest(ireqd_id = null,ireq_id = null){
                this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id).then((response)=>{
                    this.editStatus = response.data.data.dtl;
                    this.code = ireq_id;
                });
                this.dialogChangeStatus = true;
            },
            submitStatus(){
                this.submitted = true;
                this.axios.put('/api/update-status-done/'+this.code,this.editStatus).then(()=>{
                    this.cancelStatus();
                    this.$emit('show-loading');
                    this.submitted = false;
                    this.$toast.add({ severity:'success', summary: 'Message Success', detail:'Success Update Status', life: 2000 });
                    this.$emit('get-data');
                });
            },
            cancelStatus(){
                this.editStatus = [];
                this.code = null;
                this.dialogChangeStatus = false;
            },
        }
    }
</script>
