<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>  
			      <h4>Catalog Request</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="catalog"
          :paginator="true"
          :rows="10"
          v-model:filters="filters"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Catalog Request"
          responsiveLayout="scroll">
          <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-catalog-refs')"
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
            Loading data. Please wait.
          </template>
          <Column field="catalog_id" header="Catalog ID" :sortable="true"/>
          <Column field="catalog_name" header="Catalog Name" :sortable="true" style="min-width:10rem"/>
          <Column field="catalog_request_type" header="Catalog Type" :sortable="true" style="min-width:10rem"/>
          <Column field="catalog_desc" header="Catalog Desc" :sortable="true" style="min-width:10rem"/>
          <Column headerStyle="min-width:8rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="$router.push({name: 'Edit Catalog Refs',params: { code: slotProps.data.catalog_id},})"
              />
              <Button
                icon="pi pi-trash"
                v-tooltip.right="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteCatalog(slotProps.data.catalog_id)"
              />
            </template>
          </Column>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        loading: true,
        catalog: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getCatalog();
  },
  methods: {
    getCatalog(){
      this.axios.get('api/get-catalog').then((response)=> {
        this.catalog = response.data.data;
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
    DeleteCatalog(catalog_id){
       this.$confirm.require({
        message: "Are you sure to delete this record?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-catalog/' +catalog_id ).then(()=>{  
            this.loading = true;
            this.getCatalog();
          });
        },
        reject: () => {},
      });
    },
  },
};
</script>