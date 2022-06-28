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
              <label style="width:130px">No. Request: {{this.kode}}</label>
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
            <div class="table-header text-left">
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
          <Column field="ireq_type" header="Tipe Request" :sortable="true"  style="min-width:11rem"/>
          <Column field="name" header="Nama Peripheral" :sortable="true"  style="min-width:11rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true"  style="min-width:11rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true"  style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:11rem"/>
          <Column field="ireq_status" header="Status" :sortable="true"  style="min-width:11rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Ict Request Reviewer'})"
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
        submitted:false,
        dialogAssign:false,
        assign:[],
        petugas:[],
        kode:'',
        status:'',
        loading: true,
        detail: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        code:null
    };
  },
  mounted() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Reviewer") || this.checkto.includes("/ict-request/reviewer")){ 
           this.getIctDetail();
           this.getNoreq()
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.loading = false;
      }).catch(error=>{
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
        this.kode = response.data.noreq;
        this.status = response.data.cekstatus;
      });
    },
  },
};
</script>