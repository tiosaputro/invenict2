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
            <Column header="Description" :sortable="true" style="min-width:9rem">
                <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireqd_id)">
                        This request pertains to a <b>{{ slotProps.data.ireq_type }}</b> requirement for <b>{{ slotProps.data.kategori }}</b>.
                        <i class="pi pi-info-circle"></i>
                    </p>
                </template>
            </Column>
            <Column header="Action">
                <template #body="slotProps">
                    <Button label="Pdf" class="p-button-raised p-button-danger p-button-sm mt-2"
                        v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf"
                        @click="CetakPdf(slotProps.data.ireq_id)" />
                </template>
            </Column>
            <template #footer>
                <div class="grid p-dir-col">
                    <div class="col">
                        <div class="box">
                            <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2"
                                icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard' })" />
                            <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                                @click="PrintRequestListByStatusPdf(printPdf)" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
        <Dialog v-model:visible="dialogDetailRequest" :breakpoints="{ '960px': '95vw' }" :style="{ width: '600px' }"
            :header="this.header" :modal="true" class="fluid">
            <table>
                <tr>
                    <th>No Request</th>
                    <td><InputText type="text" v-model="detail.ireq_no" readonly /></td>
                </tr>
                <tr>
                    <th>No. Detail</th>
                    <td><InputText :value="detail.ireqd_id" readonly /></td>
                </tr>
                <tr>
                    <th>Request Type</th>
                    <td><InputText :value="detail.ireq_type" readonly /></td>
                </tr>
                <tr>
                    <th>Items</th>
                    <td><InputText :value="detail.kategori" readonly /></td>
                </tr>
                <tr>
                    <th>Qty</th>
                    <td><InputText :value="detail.ireq_qty" readonly /></td>
                </tr>
                <tr>
                    <th>Request Date</th>
                    <td><InputText :value="formattedRequestDate" readonly /></td>
                </tr>
                <tr>
                    <th>Requestor</th>
                    <td><InputText v-model="detail.ireq_requestor" readonly /></td>
                </tr>
                <tr>
                    <th>User</th>
                    <td><InputText readonly v-model="detail.ireq_user" /></td>
                </tr>
                <tr>
                    <th>User Division</th>
                    <td><InputText v-model="detail.usr_division" readonly /></td>
                </tr>
                <tr v-if="detail.countspv > 0">
                    <th>Supervisor</th>
                    <td><InputText v-model="detail.spv" readonly /></td>
                </tr>
                <tr v-if="detail.countremark_reviewer > 0">
                    <th>Remark Reviewer</th>
                    <td><InputText v-model="detail.ireq_verificator_remark" readonly /></td>
                </tr>
                <tr v-if="detail.ireq_reason">
                    <th>Reason</th>
                    <td><InputText v-model="detail.ireq_reason" readonly /></td>
                </tr>
                <tr v-if="detail.ireq_assigned_to">
                    <th>ICT Personnel</th>
                    <td><InputText type="text" v-model="detail.ireq_assigned_to" readonly /></td>
                </tr>
                <tr v-if="detail.ireq_status">
                    <th>Status</th>
                    <td><InputText type="text" v-model="detail.ireq_status" readonly /></td>
                </tr>
            </table>
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
            <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem" />
            <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem" />
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
            <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
            <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
            <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
            <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:10rem" />
            <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                <template #body="slotProps">
                    <span
                        :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button v-if="slotProps.data.ireq_value == null && slotProps.data.status == 'C'"
                        v-tooltip.bottom="'Click to give feedback'" class="p-button-raised p-button p-button-sm mr-2"
                        label="Give Feedback" @click="createFeedback(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
                    <Button label="Pdf" class="p-button-raised p-button-danger p-button-sm mt-2"
                        v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf"
                        @click="CetakPdf(slotProps.data.ireq_id)" />
                </template>
            </Column>
            <template #footer>
                <div class="grid p-dir-col">
                    <div class="col">
                        <div class="box">
                            <Button v-if="showForDashboardFooter" label="Back" class="p-button-raised p-button mr-2"
                                icon="pi pi-chevron-left" @click="$router.push({ name: 'Dashboard' })" />
                            <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                                @click="PrintRequestListByStatusPdf(printPdf)" />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
    </div>
    <Dialog v-model:visible="dialogEdit" :style="{ width: '400px' }" header="Give Feedback" class="field grid">
        <div class="fluid">
            <div class="field grid">
                <div class="col-fixed">
                    <star-rating v-bind:increment="1" v-bind:max-rating="5" v-bind:rating="rating" v-bind:animate="true"
                        v-bind:show-rating="true" v-bind:inline="true" v-bind:star-size="40" @hover:rating="checkRating"
                        @update:rating="setRating">
                    </star-rating>
                    <Message severity="error" icon="bi bi-emoji-frown" :closable="false" v-if="sangat_kurang">Very Less
                    </Message>
                    <Message severity="warn" icon="bi bi-emoji-frown" :closable="false" v-if="kurang"> Less</Message>
                    <Message severity="info" icon="bi bi-emoji-neutral" :closable="false" v-if="baik">Normal</Message>
                    <Message severity="info" icon="bi bi-emoji-laughing" :closable="false" v-if="bagus">Good</Message>
                    <Message severity="success" icon="bi bi-emoji-heart-eyes" :closable="false" v-if="sangat_bagus">Very
                        Good</Message>
                </div>
            </div>
        </div>
        <div class="field" v-if="must">
            <div class="field grid">
                <div class="col-5">
                    <Textarea :autoResize="true" v-if="must" type="text" rows="5" v-model="reason.ket"
                        placeholder="Tell us about your experience and we will improve the quality of our service"
                        :class="{ 'p-invalid': submitted && !reason.ket }" />
                    <small v-if="submitted && !reason.ket" class="p-error">
                        Ulasan Belum Diisi
                    </small>
                </div>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submitFeedback()" class="p-button" autofocus />
            <Button label="No" @click="cancelFeedback()" class="p-button-text" />
        </template>
    </Dialog>
</template>

<script>
export default {
    emits: ['show-loading', 'hide-loading', 'get-data'],
    props: {
        value: Array,
        loading: Boolean,
        printPdf: String,
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
    data() {
        return {
            dialogDetailRequest: false,
            isMobile: false,
            Type: {
                'report_type': ''
            },
            detail: [],
            reason: [],
            header: null,
            must: false,
            submitted: false,
            sangat_kurang: false,
            kurang: false,
            baik: false,
            bagus: false,
            sangat_bagus: false,
            dialogEdit: false,
            rating: 0,
            filters: { 'global': { value: null, matchMode: this.$FilterMatchMode.CONTAINS } },
        };
    },
    mounted() {
        this.isMobile = window.innerWidth <= 768;
        window.addEventListener('resize', this.updateIsMobile);
    },
    methods: {
        detailRequest(ireq_id) {
            const requestData = this.value.find(item => item.ireqd_id === ireq_id);
            this.header = " Detail Request No. " + requestData.ireq_no
            this.detail = requestData;
            this.dialogDetailRequest = true;
        },
        updateIsMobile() {
            this.isMobile = window.innerWidth <= 768;
        },
        CetakPdf(code) {
            this.$emit('show-loading');
            this.axios.get('api/print-out-ict-request/' + code).then((response) => {
                let htmlContent = response.data.htmlContent;
                let norequest = response.data.norequest;
                const options = {
                    filename: 'Form ICT Request No.' + norequest + '.pdf',
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
        getDetail(code) {
            var page = process.env.MIX_APP_URL + '/attachment_request/' + code;
            var myWindow = window.open(page, "_blank");
            myWindow.focus();
        },
        createFeedback(ireqd_id, ireq_id) {
            this.reason.id = ireqd_id;
            this.reason.ireq_id = ireq_id;
            this.dialogEdit = true;
        },
        setRating(rating) {
            if (rating <= 2) {
                this.must = true;
            }
            else {
                this.submitted = false;
                this.must = false;
            }
            this.rating = rating;
        },
        checkRating(rating) {
            const messageMap = {
                1: { sangat_bagus: false, bagus: false, baik: false, kurang: false, sangat_kurang: true },
                2: { sangat_bagus: false, bagus: false, baik: false, kurang: true, sangat_kurang: false },
                3: { sangat_bagus: false, bagus: false, baik: true, kurang: false, sangat_kurang: false },
                4: { sangat_bagus: false, bagus: true, baik: false, kurang: false, sangat_kurang: false },
                5: { sangat_bagus: true, bagus: false, baik: false, kurang: false, sangat_kurang: false }
            };

            const messageValues = messageMap[rating];

            Object.assign(this.$data, messageValues);
        },
        cancelFeedback() {
            this.dialogEdit = false;
            this.reason = { id: null, ket: null };
        },
        submitFeedback() {
            if (this.rating <= '2') {
                this.submitted = true;
                if (this.reason.ket != null) {
                    const data = new FormData();
                    data.append("rating", this.rating);
                    data.append("ireq_id", this.reason.ireq_id);
                    data.append("id", this.reason.id);
                    data.append("ket", this.reason.ket);
                    this.axios.post('/api/submit-rating', data).then(() => {
                        this.reason = { id: null, ket: null, ireq_id: null };
                        this.dialogEdit = false;
                        this.sangat_bagus = false;
                        this.bagus = false;
                        this.baik = false;
                        this.kurang = false;
                        this.sangat_kurang = false;
                        this.must = false;
                        this.rating = 0;
                        this.submitted = false;
                        this.$toast.add({
                            severity: 'info', summary: 'Success Submit', detail: 'Thanks for you feedback', life: 2000
                        });
                        this.$emit('get-data');
                    });
                }
            }
            else {
                this.dialogEdit = false;
                const data = new FormData();
                data.append("rating", this.rating);
                data.append("id", this.reason.id);
                data.append("ireq_id", this.reason.ireq_id);
                this.axios.post('/api/submit-rating', data).then(() => {
                    this.rating = null;
                    this.sangat_bagus = false;
                    this.bagus = false;
                    this.baik = false;
                    this.kurang = false;
                    this.sangat_kurang = false;
                    this.must = false;
                    this.$toast.add({
                        severity: 'info', summary: 'Success Submit', detail: 'Thank for your feedback', life: 2000
                    });
                    this.$emit('get-data');
                });
            }
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
