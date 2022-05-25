<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Lookups</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="ref"
          :paginator="true"
          :rows="10"
          v-model:filters="filters"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5,10,25]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Referensi Lookups"
          responsiveLayout="scroll">
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-referensi-lookupss')"
            />
              <span class="block mt-2 md:mt-0 p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading Lookups data. Please wait.
          </template>
          <Column field="lookup_type" header="Tipe" :sortable="true" style="min-width:10rem"/>
          <Column field="lookup_code" header="Kode" :sortable="true" style="min-width:10rem"/>
          <Column field="lookup_desc" header="Deskripsi" :sortable="true" style="min-width:10rem"/>
          <Column field="lookup_status" header="Status" :sortable="true" style="min-width:10rem"/>
          <Column headerStyle="min-width:10rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Referensi Lookupss',
                    params: { code: slotProps.data.lookup_code, type: slotProps.data.lookup_type },})"/>
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                v-tooltip.Right="'Delete'"
                @click="DeleteRef(slotProps.data.lookup_code, slotProps.data.lookup_type)"/>
            </template>
          </Column>
          <template #footer>
            <div class="grid p-dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf"
                  />
                  <Button 
                    label="Excel"
                    class="p-button-raised p-button-success mr-2"
                    icon="pi pi-print" 
                    @click="CetakExcel"
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
        token: localStorage.getItem('token'),
        ref: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        id : localStorage.getItem('id'),
        checkname : [],
        checkto : [],
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        if(this.checkto.includes("/referensi-lookupss")){
          this.getRef();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getRef(){
      this.axios.get('api/ref-lookup-helpdesk', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.ref = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
      });
    },
    DeleteRef(lookup_code,lookup_type){
       this.$confirm.require({
        message: "Data ini benar-benar akan dihapus?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-ref/' +lookup_code + "/" + lookup_type, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getRef();
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open("api/report-lookups-pdf");
    },
    CetakExcel(){
      window.open('api/report-lookups-excel');
    },
  },
};
</script>