<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Management Module</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="modul"
          :paginator="true"
          :rows="10"
          v-model:filters="filters"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5,10,25]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Management Module">
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-mng-module')"
            />
              <span class="p-input-icon-left">
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
          <Column field="mod_id" header="Module ID" :sortable="true" style="min-width:10rem"/>
          <Column field="mod_name" header="Module Name" :sortable="true" style="min-width:10rem"/>
          <Column field="mod_desc" header="Module Description" :sortable="true" style="min-width:10rem"/>
          <Column field="mod_stat" header="Module Status" :sortable="true" style="min-width:10rem"/>
          <Column style="min-width:10rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Management Module',
                    params: { code: slotProps.data.mod_id},
                    })"/>
              <Button
                icon="pi pi-trash"
                v-tooltip.right="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteModule(slotProps.data.mod_id)"/>
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
        modul: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getModule();
  },
  methods: {
    getModule(){
      this.axios.get('api/module').then((response)=> {
        this.modul = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 403) {
           this.$toast.add({
            severity:'error', summary: 'Error', detail:'Cannot Access This Page'
          });
          setTimeout( () => this.$router.push('/dashboard'),2000);
          }
           else if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
      });
    },
    DeleteModule(mod_id){
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
          this.axios.delete('api/delete-module/' +mod_id );
          this.getModule();
        },
        reject: () => {},
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    @media screen and (max-width: 960px) {
        align-items: start;
	}
}
</style>