<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Referensi Brand</h4>
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Referensi Brand"
          responsiveLayout="scroll">
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-referensi-brand')"
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
          <!-- <Column field="lookup_type" header="Tipe" :sortable="true" style="min-width:10rem"/> -->
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
                    name: 'Edit Referensi Brand',
                    params: { code: slotProps.data.lookup_code, type: slotProps.data.lookup_type },})"/>
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                v-tooltip.Right="'Delete'"
                @click="DeleteRef(slotProps.data.lookup_code, slotProps.data.lookup_type)"/>
            </template>
          </Column>
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
        ref: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getRef();
  },
  methods: {
    getRef(){
      this.axios.get('api/ref-lookup-brand').then((response)=> {
        this.ref = response.data.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
           else if (error.response.status == 403){
            this.$router.push('/access');
           }
      });
    },
    DeleteRef(lookup_code,lookup_type){
       this.$confirm.require({
        message: "Are you sure to delete this record?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-brand/' +lookup_code + "/" + lookup_type).then(()=>{  
            this.getRef();
          });
        },
        reject: () => {},
      });
    },
  },
};
</script>