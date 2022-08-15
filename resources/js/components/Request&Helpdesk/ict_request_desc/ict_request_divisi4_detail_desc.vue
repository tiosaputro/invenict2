<template>
  <div class="p-grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:left>
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
          <Column field="ireq_type" header="Tipe Request" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:12rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="keterangan" :sortable="true" style="min-width:12rem"/>
          <template #footer>
               <div class="grid dir-col">
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
    };
  },
  mounted() {
    this.getIctDetail();
    this.getNoreq();
  },
  methods: {
    cekUser(){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Closing Request") || this.checkto.includes("/ict-request-divisi4")){ 
          this.getIctDetail();
          this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
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