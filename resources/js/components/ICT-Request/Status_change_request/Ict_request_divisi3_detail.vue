<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>ICT Request (Detail) </h4>
          </template>
          <template v-slot:end>
            <div v-if="this.kode.ireq_date">
              <label style="width:110px">No. Request </label>
              <label>: {{this.kode.noreq}} </label>
              <br>
              <label style="width:110px">Request Date</label>
              <label>: {{formatDate(this.kode.ireq_date)}}</label>
            </div>
          </template>

        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="25"
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
            Loading data. Please wait.
          </template>
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:5rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:8rem"/>
          <Column field="name" header="Items" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:14rem"/>
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
          <Column field="ireq_assigned_to1" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <template #footer>
            <div class="p-grid p-dir-col">
			        <div class="p-col">
				        <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button p-mr-2 p-mb-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Divisi 3'})"
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

export default {
  data() {
    return {
        submitted : false,
        loading: true,
        dialogEdit: false,
        detail: [],
        status:[],
        editDetail:[],
        kode:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getIctDetail();
  },
  methods: {
    formatDate(date){
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    getStatus(){
      this.axios.get('/api/getStatusIct').then((response)=>{
        this.status = response.data;
      });
    },
    submit(){
      this.submitted = true;
      if(this.editDetail.status != null){
        this.axios.put('/api/update-status-done/'+this.$route.params.code, this.editDetail).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success Message', detail:'Success Change Status', life: 3000
          });
          this.cancel();
          this.getIctDetail();
        });
      }
      
    },
    getIctDetail(){
      this.axios.get('/api/get-detail-done-personnel/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.kode = response.data.data.request;
        this.loading = false;
      }).catch((error)=>{
        if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
        }
        if(error.response.status == 403){
          this.$router.push('access');
        }
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
</style>