<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
						<div class="my-2">
				      <h4>ICT Request (Detail) </h4>
            </div>
          </template>
          <template v-slot:end>
            <div v-if="this.detailRequest.request_date">
              <label style="width:110px">No. Request </label>
              <label>: {{this.detailRequest.noreq}} </label>
              <br>
              <label style="width:110px">Request Date</label>
              <label>: {{formatDate(this.detailRequest.request_date)}}</label>
            </div>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Detail)"
          responsiveLayout="scroll"
        >
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                  <InputText
                    v-model="filters['global'].value"
                    placeholder="Search. . ."
                  />
              </span>
            </div>
          </template>
           <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading ICT Request (Detail) data. Please wait.
          </template>
          <Column field="ireq_type" header="Request Type" :sortable="true"  style="min-width:11rem"/>
          <Column field="name" header="Items" :sortable="true"  style="min-width:11rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true"  style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:11rem"/>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                 <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>  
          </Column>
          <Column field="ireq_status" header="Status" :sortable="true"  style="min-width:11rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:8rem">
            <template #body= "slotProps">
              <Button v-if=" slotProps.data.status == 'P'" class="p-button-rounded p-button-info mr-2" icon="pi pi-pencil" v-tooltip.bottom="'Click to edit request'"
                @click=" $router.push({ name: 'Ict Request Reviewer Edit Detail Permohonan', params: {code: this.$route.params.code,ireq: slotProps.data.ireqd_id}})"/>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Back"
                    v-tooltip.bottom="'Click to back'"
                    class="p-button-raised p-button mr-2 mt-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Reviewer'})"
                  />
                   <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2 mt-2"
                    v-tooltip.bottom="'Click to print out (PDF)'"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf()"
                  />
                  <Button
                    class="p-button-raised p-button-success mr-2 mt-2"
                    icon="pi pi-calendar"
                    label="Add Calendar"
                    @click="AddToCalendar()"
                    v-if="this.status == 'P'"
                    v-tooltip.bottom="'Click to Add Calendar'"
                  />
                </div>
			        </div>
            </div>
          </template>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
import { ics } from "calendar-link";
export default {
  data() {
    return {
        submitted:false,
        dialogAssign:false,
        assign:[],
        petugas:[],
        kode:'',
        status:'',
        loading: true,
        detail: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        code:null,
        detailRequest:[],
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    formatDate(date){
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    AddToCalendar(){
      const remark = this.detail.map((x)=>x.ireq_remark);
      const event = {
          title: "Reminder Request A/n "+this.detailRequest.ireq_requestor +" No Request : "+ this.detailRequest.noreq,
          description: "Detail Request:\n" +remark,
          start: this.detailRequest.ireq_date,
          location: this.detailRequest.loc_desc,
          duration: [1, "day"],
        };
        window.location.assign(ics(event));
    },
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    getIctDetail(){
      this.axios.get('/api/ict-detail-reviewer/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.detailRequest = response.data.data.request;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403){
            this.$router.push('/access');
          }
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('/api/print-out-ict-request/' +this.$route.params.code).then((response)=>{
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
          this.loading = false;
       });
    },
  },
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