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
          <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <label style="width:110px">No. Request: {{kode.noreq}}</label>
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
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
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
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.ireq_status == 'Penugasan'"
                class="p-button-rounded p-button-info p-mr-2 p-mb-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="edit(slotProps.data.ireqd_id)"
              />
            </template>
          </Column>
          <template #footer>
            <div class="p-grid p-dir-col">
			        <div class="col">
				        <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button p-mr-2 p-mb-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                        name: 'Desc'})"
                    />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>
        <Dialog
            v-model:visible="dialogEdit"
            :style="{ width: '400px' }"
            header="ICT Request"
            :modal="true"
            class="field grid"
        >
        <div class="field grid">
            <label style="width:100px">No. Request</label>
              <div class="col-3 md-6">
                <InputText
                  v-model="editDetail.ireq_no"
                  disabled
                />
            </div>
        </div>
        <div class="field grid">
            <label style="width:100px">Nama Items</label>
              <div class="col-3 md-6">
                <InputText
                  v-model="editDetail.name"
                  disabled
                />
            </div>
        </div>
        <div class="field grid">
            <label style="width:100px">Status</label>
              <div class="col-3 md-6">
                <Dropdown
                  v-model="editDetail.status"
                  :options="status"
                  optionLabel="name"
                  optionValue="code"
                  :showClear="true"
                  :class="{ 'p-invalid': submitted && !editDetail.status }"
                />
                <small class="p-error" v-if="submitted && !editDetail.status"
                  >Status Belum Diisi.
                </small>
            </div>
        </div>
        <template #footer>
            <Button label="Yes" @click="submit()" class="p-button" autofocus />
            <Button label="No" @click="cancel()" class="p-button-text" />
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
        submitted : false,
        loading: true,
        dialogEdit: false,
        detail: [],
        status:[],
        editDetail:[],
        kode:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        user:[],
    };
  },
  created() {
      this.getIctDetail();
  },
  methods: {
    edit(ireqd_id){
      this.dialogEdit = true;
      this.axios.get('/api/detail/'+ ireqd_id).then((response)=>{
        this.editDetail = response.data;
      });
      this.getStatus();
    },
    getStatus(){
      this.axios.get('/api/getStatusIct').then((response)=>{
        this.status = response.data;
      });
    },
    cancel(){
      this.dialogEdit = false;
      this.status = [];
      this.editDetail = [];
      this.submitted = false;
    },
    submit(){
      this.submitted = true;
      if(this.editDetail.status != null){
        this.axios.put('/api/update-status-done/'+this.$route.params.code, this.editDetail).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success', detail:'Status Berhasil Dirubah', life: 3000
          });
          this.cancel();
          this.create();
        });
      }
      
    },
    getIctDetail(){
      this.axios.get('/api/get-detail-done-personnel/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.kode = response.data.request;
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