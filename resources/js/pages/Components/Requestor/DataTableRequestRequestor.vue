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
        <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:10rem" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="spv" header="Supervisor" :sortable="true" style="min-width:12rem" v-if="showSpvColumn" />
        <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" v-if="showRemarkColumn" />
        <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem" v-if="showReason == '1'"/>
        <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:10rem" v-if="showPersonnel1 == '1'"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
            <template #body= "slotProps">
                <span :class="'user-request status-' + slotProps.data.status.toLowerCase()" v-if="slotProps.data.status">{{slotProps.data.ireq_status}}</span>
            </template>
        </Column>
        <Column headerStyle="min-width:13rem">
            <template #body="slotProps">
                <Button v-if="slotProps.data.status == 'P'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.bottom="'Click for request details'" @click="detailTabRequest(slotProps.data.ireq_id)" />
                <Button v-if="slotProps.data.ireq_status == null" class="p-button-rounded p-button-info mr-2" icon="pi pi-pencil" v-tooltip.bottom="'Click to edit request'" @click=" $router.push({ name: 'Edit Ict Request', params: { code: slotProps.data.ireq_id },})" />
                <Button v-if="slotProps.data.ireq_status == null" icon="pi pi-trash" class="p-button-rounded p-button-danger mr-2" @click="DeleteIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to delete the request'" />
                <Button v-if="slotProps.data.count > 0 && slotProps.data.ireq_status == null" class="p-button-rounded p-button-success mt-2" icon="pi pi-check" @click="SubmitIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to submit request'" />
                <Button v-if="slotProps.data.status == 'NA1' || slotProps.data.status == 'NA1'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.bottom="'Click for request details'" @click="detailTabReviewer(slotProps.data.ireq_id)"/>
                <Button v-if="slotProps.data.status == 'A1' || slotProps.data.status == 'A2'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.left="'Click for request details'" @click="detailTabVerified(slotProps.data.ireq_id)"/>
                <Button v-if="slotProps.data.status == 'RR' || slotProps.data.status == 'RA1' || slotProps.data.status == 'RA2'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.left="'Click for request details'" @click="detailTabRejected(slotProps.data.ireq_id)"/>
                <Button v-if="slotProps.data.status == 'NT' || slotProps.data.status == 'RT'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.right="'Click for request details'" @click="detailTabRequestAssignment(slotProps.data.ireq_id)"/>
                <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle" v-tooltip.left="'Click for request details'" @click="detailTabInProgress(slotProps.data.ireq_id)"  />
            </template>
        </Column>
        <template #footer>
            <div class="grid p-dir-col">
                <div class="col">
                    <div class="box">
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="PrintRequestPdf(printPdf)" />
                        <!-- <Button label="Excel" class="p-button-raised p-button-success mr-2" icon="pi pi-print"
                            @click="CetakExcelPermohonan()" /> -->
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
            showPersonnel1: String
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
            }
        },
        data() {
            return {
                localLoading: this.loading,
                Type:{
                    'report_type':''
                }
            };
        },
        methods: {
            DeleteIct(code){
                this.$emit('delete-data-ict', code);
            },
            SubmitIct(code){
                this.$confirm.require({
                    message: "Are you sure you want to submit this request?",
                    header: "Confirmation Submit",
                    icon: "pi pi-info-circle",
                    acceptClass: "p-button",
                    acceptLabel: "Yes",
                    rejectLabel: "No",
                    accept: () => {
                        this.localLoading = true;
                        this.axios.get('api/updateStatusSubmit/' +code).then(()=>{
                            this.$emit('get-ict');
                            this.$toast.add({
                            severity: "info",
                            summary: "Confirmed",
                            detail: "Successfully Submit",
                            life: 3000,
                            });
                        });
                    },
                    reject: () => {},
                })
            },
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
            detailTabRequest(ireq_id) {
                localStorage.setItem('active', 0);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
            detailTabReviewer(ireq_id) {
                localStorage.setItem('active', 1);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
            detailTabVerified(ireq_id) {
                localStorage.setItem('active', 2);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
            detailTabRejected(ireq_id) {
                localStorage.setItem('active', 3);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
            detailTabRequestAssignment(ireq_id) {
                localStorage.setItem('active', 4);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
            detailTabInProgress(ireq_id) {
                localStorage.setItem('active', 5);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
        }
    };

</script>
