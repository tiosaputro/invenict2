<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <Dialog></Dialog>
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>Master Peripheral</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="master"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Master Peripheral"
          responsiveLayout="scroll"
        >
          <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
                label="Add"
                class="p-button-raised"
                icon="bi bi-file-earmark-plus"
                @click="$router.push('/Add-master-peripheral')"
              />
              <span class="block mt-2 md:mt-0 p-input-icon-left">
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
            Loading Master Peripheral data. Please wait.
          </template>
          <Column field="invent_code" header="Kode Peripheral" :sortable="true" style="min-width:8rem"/>
          <Column field="invent_desc" header="Items" :sortable="true" style="min-width:8rem"/>
          <Column field="invent_brand" header="Merk" :sortable="true" style="min-width:8rem"/>
          <Column field="invent_type" header="Type" :sortable="true" style="min-width:8rem"/>
          <Column field="countstok" header="Qty" :sortable="true" style="min-width:8rem"/>
          <Column headerStyle="min-width:6rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2 mt-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for detail peripheral'"
                @click="$router.push({
                  name: 'Master Peripheral Detail',
                  params: { code: slotProps.data.invent_code }, })"
                />
              <Button
                class="p-button-rounded p-button-info mr-2 mt-2"
                icon="pi pi-pencil"
                v-tooltip.bottom="'Click to edit'"
                @click="
                  $router.push({
                    name: 'Edit Master Peripheral',
                    params: { code: slotProps.data.invent_code },
                  })
                "
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2 mt-2"
                @click="DeleteMas(slotProps.data.invent_code)"
                v-tooltip.bottom="'Click to delete'"
              />
            </template>
          </Column>
          <template #footer>
            <div class="p-grid p-dir-col">
			        <div class="p-col">
				        <div class="box">
                  <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2"
                    icon="pi pi-file-pdf"
                    @click="CetakPdf()"
                  />
                  <Button 
                    label="Excel"
                    class="p-button-raised p-button-success mr-2"
                    icon="bi bi-file-earmark-spreadsheet"
                    @click="CetakExcel()" 
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
import moment from 'moment';
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        loading: true,
        token: localStorage.getItem('token'),
        master: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getMaster();
  },
  methods: {
    getMaster(){
      this.axios.get('api/mas',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.master = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
           else if(error.response.status == 403){
            this.$router.push('/access');
          }
        });
    },
    DeleteMas(invent_code){
       this.$confirm.require({
        message: "Are you sure to delete this data?",
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
          this.axios.delete('api/delete-mas/' +invent_code,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.loading = true;
            this.getMaster();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('api/report-master-pdf',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-master-excel',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'MASTER PERIPHERAL REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
  },
};
</script>
<style scoped lang="scss">
.master-image {
  width: 100%;
  height: auto;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>