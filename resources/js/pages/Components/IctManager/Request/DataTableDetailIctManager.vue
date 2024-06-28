

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
                    <p @click="detailRequest(slotProps.data.ireqd_id, slotProps.data.ireq_no)" style="cursor:pointer;"> 
                        This request pertains to a <b>{{ slotProps.data.ireq_type }}</b> requirement for <b>{{ slotProps.data.kategori }}</b>.
                        <i class="pi pi-info-circle" />
                    </p>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button label="Pdf" class="p-button-raised p-button-danger p-button-sm mt-2" v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf" @click="CetakPdf(slotProps.data.ireq_id)" />
                </template>
            </Column>
            <template #footer>
                <div class="grid p-dir-col">
                    <div class="col">
                        <div class="box">
                            <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard'})"/>
                            <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf" @click="PrintRequestListByStatusPdf(printPdf)" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
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
            <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:9rem" />
            <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:9rem" />
            <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
            <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem" />
            <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem" />
            <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem" />
            <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
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
            <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
            <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
            <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
            <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:10rem" />
            <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
                <template #body="slotProps">
                    <span
                        :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button label="Pdf" class="p-button-raised p-button-danger p-button-sm mr-2" v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf" @click="CetakPdf(slotProps.data.ireq_id)" />
                </template>
            </Column>
            <template #footer>
                <div class="grid p-dir-col">
                    <div class="col">
                        <div class="box">
                            <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard'})" />
                            <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf" @click="PrintRequestListByStatusPdf(printPdf)" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
    </div>
    
    <Dialog v-model:visible="dialogDetailRequest" :breakpoints="{ '960px': '95vw' }" :style="{ width: '600px' }"
      :header="this.header" :modal="true" class="fluid">
      <table>
        <tr>
          <th>No Request</th>
          <td>
            <InputText type="text" v-model="detail.ireq_no" readonly />
          </td>
        </tr>
        <tr>
          <th>No. Detail</th>
          <td>
            <InputText :value="detail.ireqd_id" readonly />
          </td>
        </tr>
        <tr>
          <th>Request Type</th>
          <td>
            <InputText :value="detail.ireq_type" readonly />
          </td>
        </tr>
        <tr>
          <th>Items</th>
          <td>
            <InputText :value="detail.kategori" readonly />
          </td>
        </tr>
        <tr>
          <th>Qty</th>
          <td>
            <InputText :value="detail.ireq_qty" readonly />
          </td>
        </tr>
        <tr>
          <th>Request Date</th>
          <td>
            <InputText :value="formattedRequestDate" readonly />
          </td>
        </tr>
        <tr>
          <th>Requestor</th>
          <td>
            <InputText v-model="detail.ireq_requestor" readonly />
          </td>
        </tr>
        <tr>
          <th>User</th>
          <td>
            <InputText readonly v-model="detail.ireq_user" />
          </td>
        </tr>
        <tr>
          <th>User Division</th>
          <td>
            <InputText v-model="detail.usr_division" readonly />
          </td>
        </tr>
        <tr v-if="detail.countspv > 0">
          <th>Supervisor</th>
          <td>
            <InputText v-model="detail.spv" readonly />
          </td>
        </tr>
        <tr v-if="detail.countremark_reviewer > 0">
          <th>Remark Reviewer</th>
          <td>
            <InputText v-model="detail.ireq_verificator_remark" readonly />
          </td>
        </tr>
        <tr v-if="detail.ireq_reason">
          <th>Reason</th>
          <td>
            <InputText v-model="detail.ireq_reason" readonly />
          </td>
        </tr>
        <tr v-if="detail.ireq_count_personnel1">
          <th>ICT Personnel</th>
          <td>
            <InputText type="text" v-model="detail.ireq_assigned_to" readonly />
          </td>
        </tr>
        <tr v-if="detail.ireq_status">
          <th>Status</th>
          <td>
            <InputText type="text" v-model="detail.ireq_status" readonly />
          </td>
        </tr>
      </table>
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
            showForDashboard: {
                type: Boolean,
                default: false
            },

        },
        computed: {
            formattedRequestDate() {
                return this.formatDate(this.detail.ireq_date);
            },
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
        },
        data() {
            return {
                filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
                Type: {
                    'report_type': ''
                },
                submitted:false,
                dialogDetailRequest: false,
                detail:[],
                header:null,
                isMobile : false
            };
        },
        mounted() {
            this.isMobile = window.innerWidth <= 768;
            window.addEventListener('resize', this.updateIsMobile);
        },
        methods: {
            updateIsMobile() {
                this.isMobile = window.innerWidth <= 768;
            },
            getDetail(ireq_attachment){
                var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
                var myWindow = window.open(page, "_blank");
                myWindow.focus();
            },
            TabToDetail(ireq_id, Active){
                localStorage.setItem('active2',Active);
                this.$router.push('/ict-request-manager-detail/'+ireq_id);
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
            getDetail(ireq_attachment){
                var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
                var myWindow = window.open(page, "_blank");
                myWindow.focus();
            },
            CetakPdf(code) {
                this.$emit('show-loading');
                this.axios.get('api/print-out-ict-request/' +code).then((response)=>{
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
            detailRequest(ireqd_id, ireq_no){
                const requestData = this.value.find(item => item.ireqd_id === ireqd_id && ireq_no === item.ireq_no);
                this.header = " Detail Request No. "+ ireq_no
                this.detail = requestData;
                this.dialogDetailRequest = true;
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
