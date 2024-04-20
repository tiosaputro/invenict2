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
                    <label>: {{kode.noreq}} </label>
                    <br>
                    <label style="width:110px">Request Date</label>
                    <label>: {{formatDate(kode.ireq_date)}}</label>
                </div>
            </template>
        </Toolbar>
        <DataTable :value="value" :kode="kode" :paginator="true" :rows="10" :loading="loading" :filters="filters" :rowHover="true"
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
            <Column field="ireqd_id" header="No. Detail" :sortable="true">
                <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireq_id)" style="cursor:pointer;">
                        {{slotProps.data.ireq_no}}
                    </p>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button label="Pdf" class="p-button-raised p-button-danger p-button-sm mt-2" v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf" @click="CetakPdf(slotProps.data.ireq_id)" />
                </template>
            </Column>
            <template #footer v-if="showForDashboardFooter">
                <div class="grid dir-col">
                    <div class="col">
                        <div class="box">
                        <Button
                            label="Back"
                            class="p-button-raised p-button mr-2"
                            icon="pi pi-chevron-left"
                            @click="$router.push({
                            name: 'Dashboard'})"
                        />
                        </div>
                    </div>
                </div>
            </template>
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
        <Toolbar class="mb-4">
            <template v-slot:start>
                <div class="my-2">
                    <h4>ICT Request (Detail) </h4>
                </div>
            </template>
            <template v-slot:end>
                <div v-if="this.kode.ireq_date">
                    <label style="width:110px">No. Request </label>
                    <label>: {{this.kode.noreq}} </label>
                    <br>
                    <label style="width:110px">Request Date</label>
                    <label>: {{formatDate(kode.ireq_date)}}</label>
                </div>
            </template>
        </Toolbar>
        <DataTable :value="value" :paginator="true" :rows="10" :loading="loading" :filters="filters" :rowHover="true"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
            :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Request" responsiveLayout="scroll">
            <template #header>
                <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center"
                    v-if="kode.status == null">
                    <Button label="Add" class="p-button-raised" v-tooltip.right="'Click to add new detail'"
                        icon="bi bi-file-earmark-plus" @click="$router.push({
                        name: 'Add Ict Request Detail',
                        params: { code: code },
                    })" />
                    <span class="block mt-2 md:mt-0 p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Search. . ." />
                    </span>
                </div>
                <div class="table-header text-right" v-else-if="kode.status != null">
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
                Please wait
            </template>
            <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
            <Column field="name" header="Items" :sortable="true" style="min-width:12rem" />
            <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem" />
            <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem" />
            <Column header="Attachment" style="min-width:10rem">
                <template #body="slotProps">
                    <p v-if="slotProps.data.ireq_attachment == null"></p>
                    <p
                        v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                        <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)"
                            aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                            <i class="pi pi-images px-2"></i>
                            <span class="px-3">IMAGE</span>
                        </Button>
                    </p>
                    <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                        <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)"
                            aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                            <i class="pi pi-file-pdf px-2"></i>
                            <span class="px-4">PDF</span>
                        </Button>
                    </p>
                </template>
            </Column>
            <Column field="ireq_assigned_to" header="ICT Personnel" :sortable="true" style="min-width:12rem" v-if="showPersonnelColumn"/>
            <Column field="ireq_status" header="Status" :sortable="true" style="min-width:6rem">
                <template #body="slotProps">
                    <span v-if="slotProps.data.status"
                        :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                </template>
            </Column>
            <Column style="min-width:12rem">
                <template #body="slotProps">
                    <Button v-if=" slotProps.data.ireq_status == null" class="p-button-rounded p-button-info mr-2"
                        icon="pi pi-pencil" v-tooltip.bottom="'Click to edit request'" @click="
                  $router.push({
                    name: 'Edit Ict Request Detail',
                    params: {code: this.$route.params.code, ireq: slotProps.data.ireqd_id},
                  })" />
                    <Button v-if=" slotProps.data.ireq_status == null" icon="pi pi-trash"
                        v-tooltip.bottom="'Click to delete request'" class="p-button-rounded p-button-danger mr-2"
                        @click="DeleteIct(slotProps.data.ireqd_id,this.$route.params.code)" />
                </template>
            </Column>
            <template #footer>
                <div class="grid dir-col">
                    <div class="col">
                        <div class="box">
                            <Button label="Back" class="p-button-raised p-button mr-2"
                                v-tooltip.bottom="'Click to back'" icon="pi pi-chevron-left" @click="$router.push({
                                name: 'Ict Request'})" 
                            />
                            <Button label="Pdf" class="p-button-raised p-button-danger mt-2"
                                v-tooltip.bottom="'Click to print out (PDF)'" icon="pi pi-file-pdf"
                                v-if="kode.cekstatus != null" @click="CetakPdf()" 
                            />
                            <Button class="p-button-raised p-button-success mr-2" icon="pi pi-check" label="Submit"
                                v-if="value.length && kode.cekstatus == null" @click="SubmitIct()"
                                v-tooltip.bottom="'Click to submit request'" 
                            />
                        </div>
                    </div>
                </div>
            </template>
        </DataTable>
    </div>
</template>
<script>
    export default {
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
        computed: {
            showPersonnelColumn() {
                return (
                    (this.showPersonnel && this.showPersonnel.some(el => el > 0))
                );
            },
            showForDashboardFooter() {
                return (
                    (this.showForDashboard == TRUE)
                );
            },
            formattedRequestDate() {
                return this.formatDate(this.detail.ireq_date);
            }
        },
        data() {
            return {
                dialogDetailRequest:false,
                detail:[],
                isMobile: false,
                header:null
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
            formatDate(date){
                return this.$moment(date).format("DD MMM YYYY HH:mm");
            },
            DeleteIct(id = null,code = null){
                this.$confirm.require({
                    message: "Are you sure you want to delete this record data?",
                    header: "Delete Confirmation",
                    icon: "pi pi-info-circle",
                    acceptClass: "p-button-danger",
                    acceptLabel: "Yes",
                    rejectLabel: "No",
                    accept: () => {
                    this.$toast.add({
                        severity: "info",
                        summary: "Confirmed",
                        detail: "Record deleted",
                        life: 3000,
                    });
                    this.axios.delete('/api/delete-ict-detail/' +id+'/'+code).then(()=>{
                        this.$emit("show-loading");
                        this.getIctDetail();
                    });
                    },
                    reject: () => {},
                });
            },
            SubmitIct(){
                this.$confirm.require({
                    message: "Are you sure you want to submit this request?",
                    header: "Confirmation Submit",
                    icon: "pi pi-info-circle",
                    acceptClass: "p-button-success",
                    rejectClass: "p-button-danger",
                    acceptLabel: "Yes",
                    rejectLabel: "No",
                    accept: () => {
                    this.$toast.add({
                        severity: "info",
                        summary: "Confirmed",
                        detail: "Successfully Submit",
                        life: 3000,
                    });
                    this.$emit("show-loading");
                    this.axios.get('/api/updateStatusSubmit/' +this.$route.params.code);
                    setTimeout( () => this.$router.push('/ict-request'),1000);
                    },
                    reject: () => {},
                })
            },
            CetakPdf(){
                this.$emit("show-loading");
                this.axios.get('/api/print-out-ict-request/' +this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                    let htmlContent = response.data.htmlContent;
                    let RequestNo = response.data.norequest;
                    const options = {
                        filename: 'Form ICT Request No. '+RequestNo+'.pdf',
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
</style>
