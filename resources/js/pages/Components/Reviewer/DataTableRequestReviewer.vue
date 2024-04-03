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
                    <Button v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status !== 'NT' && slotProps.data.status !== 'RT'" class="p-button-rounded p-button-success mr-2 mt-2" @click="Submit(slotProps.data.ireq_id)" icon="bi bi-send-check" v-tooltip.bottom="'Click to submit'" />
                    <Button v-if="slotProps.data.count_assigned2 > 0 && slotProps.data.status == 'RT'" class="p-button-rounded p-button-success mr-2 mt-2" @click="Submit(slotProps.data.ireq_id)" icon="bi bi-send-check" v-tooltip.bottom="'Click to submit'" />
                </div>
            </template>
        </Column>
        <template #footer>
            <div class="grid p-dir-col">
                <div class="col">
                    <div class="box">
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="PrintRequestPdf(printPdf)" />
                    </div>
                </div>
            </div>
        </template>
    </DataTable>
</template>

<script>
    export default {
        props: {
            value: Array,
            loading: Boolean,
            filters: Object,
            printPdf: String,
            showSpv: Array,
            showRemark: Array,
            showReason: String,
            showTotalDetail: String,
            showPersonnel1: Array,
            showPersonnel2: Array,
            showReason: String,
            showReasonPersonnel: Array,
            Active: String

        },
        computed: {
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
                localLoading: this.loading,
                Type: {
                    'report_type': ''
                }
            };
        },
        methods: {
            PrintRequestPdf(type) {
                this.localLoading = true;
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
                    this.localLoading = false;
                });
            },
            formatDate(date) {
                return this.$moment(date).format("DD MMM YYYY HH:mm")
            },
            detailRequest(ireq_id, active){
                localStorage.setItem('active1',active);
                this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
            },
            ApproveAtasan(ireq_id){
                this.$emit('approve-atasan', ireq_id);
            },
            ApproveManager(ireq_id){
                this.$emit('approve-manager', ireq_id);
            },
            AssignPerRequest(ireq_id, ireq_assigned_to1){
                this.$emit('assign-per-request', ireq_id, ireq_assigned_to1);
            },
            Submit(ireq_id){
                this.$emit('submit-request', ireq_id);
            },
            SendEmail(mail,ireq_id){
                this.$emit('send-email',  mail, ireq_id);
            },
            editDataSpv(ireq_id){
                this.$emit('edit-spv', ireq_id);
            },
            Remark(ireq_id){
                this.$emit('add-remark', ireq_id);
            },
            
        }
    };

</script>
