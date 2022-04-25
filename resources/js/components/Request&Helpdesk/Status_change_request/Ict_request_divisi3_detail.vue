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
            <label style="width:140px">No. Request: {{kode.noreq}}</label>
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
          <Column field="ireq_type" header="Tipe Request" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:12rem"/>
          <template #footer>
            <div class="p-grid p-dir-col">
			        <div class="p-col">
				        <div class="box">
                   <Button
                    label="Kembali"
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
import {FilterMatchMode} from 'primevue/api';
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
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        user:[],
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Status Change Request") || this.checkto.includes("/ict-request-divisi3")){ 
          this.getUser();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getUser(){
        this.axios.get('/api/user',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.user = response.data;
        this.getIctDetail();
        this.getNoreq();
        });
      },
    getStatus(){
      this.axios.get('/api/getStatusIct', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.status = response.data;
      });
    },
    submit(){
      this.submitted = true;
      if(this.editDetail.status != null){
        this.axios.put('/api/update-status-done/'+this.$route.params.code, this.editDetail, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success', detail:'Status Berhasil Dirubah', life: 3000
          });
          this.cancel();
          this.getIctDetail();
        });
      }
      
    },
    getIctDetail(){
      this.axios.get('/api/get-detail-done/' + this.$route.params.code + '/' + this.user.usr_fullname, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.loading = false;
      }).catch((error)=>{
      if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
           }
      });
    },
    getNoreq(){
      this.axios.get('/api/get-noreq/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.kode = response.data;
      });
    },
  },
};
</script>