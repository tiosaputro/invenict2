<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>ICT Request (Detail) </h4>
          </template>
          <template v-slot:end>
            <label style="width:200px">No. Request: {{kode.noreq}}</label>
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
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Items" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
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
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
                <Button
                    class="p-button-raised p-button-text mr-2"
                    label="Assign"
                    @click="AssignPerDetail(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                />
            </template>  
          </Column>
          <template #footer>
            <div class="grid dir-col">
			        <div class="col">
                  <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Desc'})"
                  />
                 </div>
			        </div>
            </div>
           </template>
          </DataTable>   
          <Dialog
            v-model:visible="dialogAssign"
            :style="{ width: '500px' }"
            header="Assign Per-Detail Request"
            :modal="true"
            :closable="false"
            class="field grid"
          >
          <div class="field grid">
                <label style="width:100px">No Request</label>
                 <div class="col-3 md-6">
                     <InputText
                        v-model="assign.ireq_no"
                        disabled
                     />
                </div>
              </div>
              <div class="field grid">
                <label style="width:100px">Nama Items</label>
                 <div class="col-3 md-6">
                     <InputText
                        v-model="assign.name"
                        disabled
                     />
                </div>
              </div>
                <div class="field grid">
                    <label style="width:100px">Personnel (ICT)</label>
                    <div class="col-3 md-6">
                        <Dropdown
                            v-model="assign.ireq_assigned_to"
                            :options="petugas"
                            optionValue="name"
                            optionLabel="name"
                            placeholder="Pilih Personnel (ICT)"
                            :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to }"
                        />
                        <small v-if="submitted && !assign.ireq_assigned_to" class="p-error">
                            Personnel(ICT) Harus Diisi
                        </small>
                  </div>
                </div>
                <template #footer>
                    <Button label="Simpan" @click="updateAssign()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
                </template>
            </Dialog>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        submitted: false,
        loading: true,
        dialogAssign: false,
        detail: [],
        petugas: [],
        assign:[],
        kode:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
      cancelAssign(){
          this.assign = [];
          this.petugas = [];
          this.dialogAssign = false;
      },
    AssignPerDetail(ireqd_id,ireq_id){
          this.axios.get('/api/detail/'+ ireqd_id+'/'+ireq_id).then((response)=>{
            this.assign = response.data;
          });
          this.axios.get('/api/get-pekerja').then((response)=>{
            this.petugas = response.data;
          });
          this.dialogAssign = true; 
    },
    cancelAssign(){
        this.submitted = false;
        this.assign = [];
        this.dialogAssign = false;
    },
     updateAssign(){
        this.submitted = true;
        if(this.assign.name != null){
          this.axios.put('/api/updateAssignPerDetail/'+ this.$route.params.code ,this.assign).then(()=>{
            this.assign = [];
            this.dialogAssign = false;
            this.submitted = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000,
            });
            this.getIctDetail();
          });
        }
      },
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.kode = response.data.data.request;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
           else if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
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