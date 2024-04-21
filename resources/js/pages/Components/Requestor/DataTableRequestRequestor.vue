<template>
    <div v-if="isMobile">
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
            <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:9rem">
                <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireq_id)" style="cursor:pointer;"> {{slotProps.data.ireq_no}}
                    </p>
                </template>
            </Column>
            <Column header="Action" style="min-width:9rem">
                <template #body="slotProps">
                    <div class="button-group">
                        <Button class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-info-circle" v-tooltip.bottom="'Click for request details'" @click="detailTab(slotProps.data.ireq_id, Active)" />
                        <Button v-if="slotProps.data.ireq_status == null" class="p-button-rounded p-button-info mr-2 mt-2" icon="pi pi-pencil" v-tooltip.bottom="'Click to edit request'" @click=" $router.push({ name: 'Edit Ict Request', params: { code: slotProps.data.ireq_id },})" />
                        <Button v-if="slotProps.data.ireq_status == null" icon="pi pi-trash" class="p-button-rounded p-button-danger mr-2 mt-2" @click="DeleteIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to delete the request'" />
                        <Button v-if="slotProps.data.count > 0 && slotProps.data.ireq_status == null" class="p-button-rounded p-button-success mr-2 mt-2" icon="pi pi-check" @click="SubmitIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to submit request'" />
                    </div>                
                </template>
            </Column>
        </DataTable>
        <Dialog
          v-model:visible="dialogDetailRequest"
          :breakpoints="{'960px': '75vw'}"
          :style="{ width: '600px' }"
          :header="this.header"
          :modal="true"
          class="fluid"
        >
            <div class="field grid">
                <label class="col-fixed" style="width:100px">No Request</label>
                  <InputText
                    type="text"
                    v-model="detail.ireq_no"
                    disabled
                  />
            </div>
            <div class="field grid">
                <label class="col-fixed" style="width:100px">Request Date</label>
                  <InputText
                    :value="formattedRequestDate"
                    disabled
                  />
            </div> 
            <div class="field grid">
                <label class="col-fixed" style="width:100px">Requestor</label>
                    <InputText
                      v-model="detail.ireq_requestor"
                      disabled
                    />
            </div>
            <div class="field grid">
                <label class="col-fixed" style="width:100px">User</label>
                  <InputText
                    disabled
                    v-model= "detail.ireq_user"
                  />
            </div>
            <div class="field grid">
                <label class="col-fixed" style="width:100px">User Division</label>
                  <InputText
                      v-model="detail.usr_division"
                      disabled
                  />
            </div>
            <div class="field grid" v-if="detail.countspv > 0">
                <label class="col-fixed" style="width:100px">Supervisor</label>
                  <InputText
                    v-model="detail.spv"
                    disabled
                  />
            </div>
            <div class="field grid" v-if="detail.countremark_reviewer > 0">
                <label class="col-fixed" style="width:100px">Remark Reviewer</label>
                  <InputText
                    v-model="detail.ireq_verificator_remark"
                    disabled
                  />
            </div>
            <div class="field grid" v-if="detail.ireq_reason">
                <label class="col-fixed" style="width:100px">Reason</label>
                  <InputText
                    v-model="detail.ireq_reason"
                    disabled
                  />
            </div>
            <div class="field grid" v-if="detail.ireq_assigned_to">
                <label class="col-fixed" style="width:100px">ICT Personnel</label>
                  <InputText
                    type="text"
                    v-model="detail.ireq_assigned_to"
                    disabled
                  />
            </div>
            <div class="field grid" v-if="detail.ireq_status">
                <label class="col-fixed" style="width:100px">Status</label>
                  <InputText
                    type="text"
                    v-model="detail.ireq_status"
                    disabled
                  />
            </div> 
        </Dialog> 
    </div>
    <div v-else>
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
            <Column field="ireq_status" header="Status" :sortable="true" style="min-width:9rem">
                <template #body= "slotProps">
                    <span :class="'user-request status-' + slotProps.data.status.toLowerCase()" v-if="slotProps.data.status">{{slotProps.data.ireq_status}}</span>
                </template>
            </Column>
            <Column header="Action">
                <template #body="slotProps">
                    <Button class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-info-circle" v-tooltip.bottom="'Click for request details'" @click="detailTab(slotProps.data.ireq_id, Active)" />
                    <Button v-if="slotProps.data.ireq_status == null" class="p-button-rounded p-button-info mr-2 mt-2" icon="pi pi-pencil" v-tooltip.bottom="'Click to edit request'" @click=" $router.push({ name: 'Edit Ict Request', params: { code: slotProps.data.ireq_id }})" />
                    <Button v-if="slotProps.data.ireq_status == null" icon="pi pi-trash" class="p-button-rounded p-button-danger mr-2 mt-2" @click="DeleteIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to delete the request'" />
                    <Button v-if="slotProps.data.count > 0 && slotProps.data.ireq_status == null" class="p-button-rounded p-button-success mt-2 mr-2" icon="pi pi-check" @click="SubmitIct(slotProps.data.ireq_id)" v-tooltip.bottom="'Click to submit request'" />
                </template>
            </Column>
            <template #footer>
                <div class="grid p-dir-col">
                    <div class="col">
                        <div class="box">
                            <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard'})"/>
                            <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                                @click="PrintRequestListByStatusPdf(printPdf)" />
                            <!-- <Button label="Excel" class="p-button-raised p-button-success mr-2" icon="pi pi-print"
                                @click="CetakExcelPermohonan()" /> -->
                                
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
    </div>
</template>

<script>
    export default {
        emits: ['show-loading', 'hide-loading','get-data'],
        props: {
            value: Array,
            loading: Boolean,
            filters: Object,
            printPdf: String,
            showSpv: Array,
            showRemark: Array,
            showReason: String,
            showPersonnel1: String,
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
                header:null,
                dialogDetailRequest: false,
                Type:{
                    'report_type':''
                },
                isMobile: false,
                detail:[]
            };
        },
        methods: {
            detailRequest(ireq_id){
                const requestData = this.value.find(item => item.ireq_id === ireq_id);
                this.header = " Detail Request No. "+ requestData.ireq_no
                this.detail = requestData;
                this.dialogDetailRequest = true;
            },
            updateIsMobile() {
                this.isMobile = window.innerWidth <= 768;
            },
            DeleteIct(code){
                this.$confirm.require({
                    message: "Are you sure you want to delete this record data?",
                    header: "Delete Confirmation",
                    icon: "pi pi-info-circle",
                    acceptClass: "p-button-danger",
                    acceptLabel: "Yes",
                    rejectLabel: "No",
                    accept: () => {
                    this.axios.delete('api/delete-ict/' +code).then(()=>{
                        this.$toast.add({
                        severity: "info",
                        summary: "Confirmed",
                        detail: "Record deleted",
                        life: 3000,
                        });
                        this.loading = true;
                        this.$emit('get-ict');
                    });
                    },
                    reject: () => {},
                });
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
                        this.$emit('show-loading');
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
            detailTab(ireq_id, Active) {
                localStorage.setItem('active', Active);
                this.$router.push('/ict-request-detail/' + ireq_id)
            },
        }
    };

</script>
<style lang="scss" scoped>
  .button-group {
    display: flex;
    flex-wrap: wrap;
  }

  .button-group .p-button {
    margin: 5px; /* Ruang antara tombol */
  }
</style>
