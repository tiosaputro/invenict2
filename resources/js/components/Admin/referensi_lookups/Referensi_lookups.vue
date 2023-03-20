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
              @click="$router.push('/Add-referensi-lookups')"
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
                    name: 'Edit Referensi Lookups',
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
        ref: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getRef();
  },
  methods: {
    getRef(){
      this.axios.get('api/ref').then((response)=> {
        this.ref = response.data.data;
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
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-ref/' +lookup_code + "/" + lookup_type).then(()=>{
            this.loading = true;
            this.getRef();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('api/report-lookups-pdf').then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcel(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-lookups-excel',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'LOOKUPS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
  },
};
</script>