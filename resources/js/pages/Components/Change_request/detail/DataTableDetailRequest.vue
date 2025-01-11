<template>
    <div v-if="isMobile">
        <Toolbar class="mb-4">
            <template v-slot:start>
                <div class="my-2">
                    <h4>ICT Request (Detail) </h4>
                </div>
            </template>
            <template v-slot:end>
                <div v-if="this.kode.ireq_date">
                    <label style="width:110px">No. Request </label>
                    <label>: {{ kode.noreq }} </label>
                    <br>
                    <label style="width:110px">Request Date</label>
                    <label>: {{ formatDateWithOutSecond(kode.ireq_date) }}</label>
                </div>
            </template>
        </Toolbar>
        <DataTable :value="value" :paginator="true" :rows="25" :loading="loading" :filters="filters" :rowHover="true"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
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
            <template #empty>
                Not Found
            </template>
            <template #loading>
                Loading data. Please wait.
            </template>
            <Column header="Description" :sortable="true" style="min-width:9rem">
                <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireqd_id)">
                        This request pertains to a <b>{{ slotProps.data.ireq_type }}</b> requirement for <b>{{
                            slotProps.data.name }}</b>.
                        <i class="pi pi-info-circle" />
                    </p>
                </template>
            </Column>
            <template #footer>
                <div class="p-grid p-dir-col">
                    <div class="p-col">
                        <div class="box">
                            <Button label="Back" class="p-button-raised p-button p-mr-2 p-mb-2"
                                icon="pi pi-chevron-left" @click="$router.push({
                                    name: 'Ict Request Divisi 3'
                                })" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
        <Dialog v-model:visible="dialogDetailRequest" :breakpoints="{ '960px': '75vw' }" :style="{ width: '600px' }"
            :header="this.header" :modal="true" class="fluid">
            <table>
                <tbody>
                    <tr>
                        <th>Request Type</th>
                        <td>
                            <InputText type="text" v-model="detail.ireq_type" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Items</th>
                        <td>
                            <InputText :value="detail.name" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Qty</th>
                        <td>
                            <InputText v-model="detail.ireq_qty" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td>
                            <InputText readonly v-model="detail.ireq_remark" />
                        </td>
                    </tr>
                    <tr v-if="detail.ireq_assigned_to">
                        <th>ICT Personnel</th>
                        <td>
                            <InputText v-model="detail.ireq_assigned_to" readonly />
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <InputText v-model="detail.ireq_status" readonly />
                        </td>
                    </tr>
                </tbody>
            </table>
        </Dialog>
    </div>
    <div v-else>
        <Toolbar class="mb-4">
            <template v-slot:start>
                <div class="my-2">
                    <h4>ICT Request (Detail) </h4>
                </div>
            </template>
            <template v-slot:end>
                <div v-if="this.kode.ireq_date">
                    <label style="width:110px">No. Request </label>
                    <label>: {{ this.kode.noreq }} </label>
                    <br>
                    <label style="width:110px">Request Date</label>
                    <label>: {{ formatDate(kode.ireq_date) }}</label>
                </div>
            </template>
        </Toolbar>

        <DataTable :value="value" :paginator="true" :rows="25" :loading="loading" :filters="filters" :rowHover="true"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
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
            <template #empty>
                Not Found
            </template>
            <template #loading>
                Loading data. Please wait.
            </template>
            <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:5rem" />
            <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:8rem" />
            <Column field="name" header="Items" :sortable="true" style="min-width:12rem" />
            <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem" />
            <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:14rem" />
            <Column header="Attachment" style="min-width:10rem">
                <template #body="slotProps">
                    <p v-if="slotProps.data.ireq_attachment == null"></p>
                    <p
                        v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                        <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)"
                            aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                            <i class="pi pi-images px-2"></i>
                            <span class="px-3">IMAGE</span>
                        </Button>
                    </p>
                    <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                        <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)"
                            aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                            <i class="pi pi-file-pdf px-2"></i>
                            <span class="px-4">PDF</span>
                        </Button>
                    </p>
                </template>
            </Column>
            <Column field="ireq_assigned_to1" header="ICT Personnel" :sortable="true" style="min-width:12rem" />
            <template #footer>
                <div class="p-grid p-dir-col">
                    <div class="p-col">
                        <div class="box">
                            <Button label="Back" class="p-button-raised p-button p-mr-2 p-mb-2"
                                icon="pi pi-chevron-left" @click="$router.push({
                                    name: 'Ict Request Divisi 3'
                                })" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
    </div>
</template>
<script>
export default {
    emits: ['show-loading', 'hide-loading', 'get-data'],
    props: {
        value: Array,
        loading: Boolean,
        filters: Object,
        kode: Object,
        showPersonnel: Array,
        showForDashboard: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        this.isMobile = window.innerWidth <= 768;
        window.addEventListener('resize', this.updateIsMobile);
    },
    computed: {
        showPersonnelColumn() {
            return (
                (this.showPersonnel && this.showPersonnel.some(el => el > 0))
            );
        },
        showForDashboardFooter() {
            return (
                (this.showForDashboard === true)
            );
        },
        formattedRequestDate() {
            return this.formatDate(this.detail.ireq_date);
        }
    },
    data() {
        return {
            dialogDetailRequest: false,
            detail: [],
            isMobile: false,
            header: null
        };
    },
    methods: {
        getDetail(ireq_attachment = null) {
            var page = process.env.MIX_APP_URL + '/attachment_request/' + ireq_attachment;
            var myWindow = window.open(page, "_blank");
            myWindow.focus();
        },
        getStatus() {
            this.axios.get('/api/getStatusIct').then((response) => {
                this.status = response.data;
            });
        },
        detailRequest(ireqd_id = null) {
            const requestData = this.value.find(item => item.ireqd_id === ireqd_id);
            this.header = " Detail Request No. " + requestData.ireqd_id
            this.detail = requestData;
            this.dialogDetailRequest = true;
        },
        updateIsMobile() {
            this.isMobile = window.innerWidth <= 768;
        },
        formatDate(date) {
            return this.$moment(date).format("DD MMM YYYY HH:mm");
        },
        formatDateWithOutSecond(date) {
            return this.$moment(date).format("DD MMM YYYY");
        },
        CetakPdf() {
            this.$emit("show-loading");
            this.axios.get('/api/print-out-ict-request/' + this.$route.params.code, { headers: { 'Authorization': 'Bearer ' + this.token } }).then((response) => {
                let htmlContent = response.data.htmlContent;
                let RequestNo = response.data.norequest;
                const options = {
                    filename: 'Form ICT Request No. ' + RequestNo + '.pdf',
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'landscape',
                        width: 210,
                        height: 297
                    }
                };

                this.$html2pdf().set(options).from(htmlContent).save();
                this.$emit("hide-loading")
            });
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
    cursor: pointer;
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
