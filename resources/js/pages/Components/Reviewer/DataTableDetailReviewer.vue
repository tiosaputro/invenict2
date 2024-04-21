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
        <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
                <p @click="detailRequestNo(slotProps.data.ireq_id)" style="cursor:pointer;"> {{slotProps.data.ireq_no}}
                </p>
            </template>
        </Column>
        <Column field="ireqd_id" header="No. Detail" style="min-width:10rem" :sortable="true" />
        <Column field="ireq_type" header="Request Type" style="min-width:10rem" :sortable="true" />
        <Column field="kategori" header="Items" style="min-width:8rem" :sortable="true" />
        <Column field="ireq_qty" header="Qty" style="min-width:8rem" :sortable="true" />
        <Column field="ireq_remark" header="Remark" style="min-width:16rem" :sortable="true" />
        <Column field="ireq_date" header="Request Date" style="min-width:10rem" :sortable="true">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
                <p v-if="slotProps.data.ireq_attachment == null"></p>
                <p
                    v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                    <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                        v-tooltip.bottom="'Click to detail attachment'">
                        <i class="pi pi-images px-2"></i>
                        <span class="px-3">IMAGE</span>
                    </Button>
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
        <Column field="ireq_requestor" header="Requestor" style="min-width:8rem" :sortable="true" />
        <Column field="ireq_user" header="User" style="min-width:8rem" :sortable="true" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_assigned_to" header="ICT Personnel" style="min-width:12rem" :sortable="true" />
        <Column field="ireq_status" header="Status" style="min-width:10rem" :sortable="true">
            <template #body="slotProps">
                <span
                    :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
        </Column>
        <Column style="min-width:15rem">
            <template #body="slotProps">
                <Button v-if="slotProps.data.status == 'D'" class="p-button-raised mr-2" label="Closing"
                    v-tooltip.bottom="'Click to closing request'"
                    @click="ClosingPerDetail(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
                <Button label="Pdf" class="p-button-raised p-button-danger mt-2"
                    v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf"
                    @click="PrintOutFormIctRequest(slotProps.data.ireq_id)" />
            </template>
        </Column>
        <template #footer>
            <div class="grid p-dir-col">
                <div class="col">
                    <div class="box">
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="PrintRequestListByStatusPdf(printPdf)" />
                    </div>
                </div>
            </div>
        </template>
    </DataTable>
    <Dialog v-model:visible="displayDetailRequest" :style="{ width: '1200px' }" header="Detail Request" :modal="true">
        <Toolbar class="mb-4">
            <template v-slot:end>
                <label style="width:130px">No. Request: {{this.ireq_no}}</label>
            </template>
        </Toolbar>
        <DataTable :value="detail" :paginator="true" :rows="10" :filters="filters" :loading="loadingDetail"
            :rowHover="true" paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport
                RowsPerPageDropdown" :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Detail)"
            responsiveLayout="scroll">
            <template #header>
                <div class="table-header text-right">
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Search. . ." />
                    </span>
                </div>
            </template>
            <template #loading>
                Loading data. Please wait
            </template>
            <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:6rem" />
            <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem" />
            <Column field="kategori" header="Items" :sortable="true" style="min-width:12rem" />
            <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem" />
            <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem" />
            <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:12rem" />
            <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem" />
        </DataTable>
    </Dialog>
</template>

<script>
    export default {
        emits: ['show-loading', 'hide-loading','get-data'],
        props: {
            value: Array,
            loading: Boolean,
            printPdf: String,
            Active: String
        },
        data() {
            return {
                Type: {
                    'report_type': ''
                },
                filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
                detail:[],
                loadingDetail: false,
                ireq_no :'',
                displayDetailRequest: false
            };
        },
        methods: {
            ClosingPerDetail(ireqd_id,ireq_id){
                this.$confirm.require({
                message: "Are you sure to close this request?",
                header: "Closing Per Detail",
                icon: "pi pi-info-circle",
                acceptClass: "p-button",
                acceptLabel: "Yes",
                rejectLabel: "No",
                accept: () => {
                    this.$emit('show-loading');
                    this.$toast.add({
                        severity: "info",
                        summary: "Success",
                        detail: "Closing request successful",
                        life: 3000,
                    });
                    this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_id).then(()=>{
                    this.$emit('get-data');
                    this.$emit('show-loading');
                    });
                },
                reject: () => {},
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
            PrintOutFormIctRequest(ireq_id){
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
            detailRequestNo(ireq_id){
                this.displayDetailRequest = true;
                this.$emit('show-loading');
                this.axios.get('/api/detail-request-reviewer/' + ireq_id).then((response)=> {
                    this.detail = response.data;
                    this.ireq_no = response.data[0].ireq_no;
                    this.$emit('hide-loading');
                });
            },
            getDetail(ireq_attachment){
                var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
                var myWindow = window.open(page, "_blank");
                myWindow.focus();
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
