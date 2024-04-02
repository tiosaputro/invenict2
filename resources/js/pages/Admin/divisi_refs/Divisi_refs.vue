<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Referensi - Divisi</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="divisi"
          :paginator="true"
          :rows="10"
          v-model:filters="filters"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Divisi Refs"
          responsiveLayout="scroll">
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
          <Column field="div_code" header="Divisi Code" :sortable="true" style="min-width:10rem"/>
          <Column field="div_name" header="Divisi Name" :sortable="true" style="min-width:10rem"/>
          <Column field="div_verificator" header="Divisi Verificator" :sortable="true" style="min-width:10rem"/>
          <Column headerStyle="min-width:10rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Divisi Refs',
                    params: { code: slotProps.data.div_id},
                    })"/>
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteDivisi(slotProps.data.div_id)"
                v-tooltip.right="'Delete'"
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
        
        divisi: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getDivisi();
  },
  methods: {
    getDivisi(){
      this.axios.get('api/divisi').then((response)=> {
        this.divisi = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
              severity:'error', 
              summary: 'Error', 
              detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
          }
          else if (error.response.status == 403){
            this.$router.push('/access')
          }
      });
    },
    DeleteDivisi(div_id){
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
          this.axios.delete('api/delete-divisi/' +div_id ).then(()=>{
            this.loading = true;
            this.getDivisi();
          });
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