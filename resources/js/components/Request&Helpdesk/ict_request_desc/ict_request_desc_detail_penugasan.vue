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
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:4rem"/>
          <Column field="invent_code" header="Kode" :sortable="true" style="min-width:4rem"/>
          <Column field="invent_desc" header="Deskripsi" :sortable="true" style="min-width:4rem"/>
          <Column field="ireq_assigned_to" header="Petugas(ICT)" :sortable="true" style="min-width:4rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:4rem"/>
          <template #footer>
            <div class="p-grid p-dir-col">
                <div class="p-col">
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
      </div>
    </div>
  </div>
</template>
<script>
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        loading: true,
        detail: [],
        kode:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
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
        if(this.checkname.includes("Reviewer") || this.checkname.includes("Request") || this.checkname.includes("Approval Manager") || this.checkto.includes("/ict-request-reviewer")){ 
          this.getIctDetail();
          this.getNoreq();
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
      this.axios.get('/api/ict-detail-penugasan/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
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
        this.kode = response.data;
      });
    },
  },
};
</script>