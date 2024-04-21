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
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="showRemarkReviewerColumn"/>
        <Column style="min-width:10rem">
            <template #body="slotProps">
                <Button class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-info-circle"
                    v-tooltip.bottom="'Click for request details'" @click="$router.push({
                          name: 'Ict Request Divisi 3 Detail',
                          params: { code: slotProps.data.ireq_id }, })" />
                <Button class="p-button-rounded p-button-info mr-2 mt-2" icon="pi pi-check"
                    v-tooltip.bottom="'Click for accept request'" @click="acceptRequest(slotProps.data.ireq_id)" />
                <Button class="p-button-rounded p-button-danger mr-2 mt-2" icon="bi bi-x-square"
                    v-tooltip.bottom="'Click for reject request'" @click="rejectRequest(slotProps.data.ireq_id)" />
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
</template>
<script>
    export default {
        emits: ['show-loading', 'hide-loading','get-data'],
        props: {
            value: Array,
            loading: Boolean,
            showRemarkReviewer:Array,
            printRequestListByStatus: String,
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
            showRemarkReviewerColumn() {
                return (
                    (this.showRemarkReviewer && this.showRemarkReviewer.some(el => el > 0))
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
                filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
                isMobile: false,
                editDetail:{ 
                    ireq_id:'',
                    ireq_reason :''
                },
                dialogEdit : false,
                submitted : false
            };
        },
        methods: {
            updateIsMobile() {
                this.isMobile = window.innerWidth <= 768;
            },
            formatDate(date) {
                return this.$moment(date).format("DD MMM YYYY HH:mm")
            },
            acceptRequest(ireq_id){
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
            rejectRequest(ireq_id){
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
        }
    }
</script>
