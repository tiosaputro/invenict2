<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Pembelian Peripheral</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="purch"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Pembelian Peripheral"
          responsiveLayout="scroll"
        >
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised mr-2"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-pembelian-peripheral')"
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
            Loading Pembelian Peripheral data. Please wait.
          </template>
          <Column field="suplier_code" header="Suplier" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ slotProps.data.suplier_code }}
            </template>
          </Column>
          <Column field="purchase_date" header="Tgl. Pembelian" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.purchase_date) }}
            </template>
          </Column>
          <Column field="purchase_total" header="Total Pembelian" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{slotProps.data.valuta_code}} {{ formatPrice(slotProps.data.purchase_total) }}
            </template>
          </Column>
          <!-- <Column field="purchase_status" header="Status" :sortable="true">
            <template #body="slotProps">
              {{ slotProps.data.purchase_status }}
            </template>
          </Column> -->
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Pembelian Peripheral',
                    params: { code: slotProps.data.purchase_id },})"
              />
              <Button
                class="p-button-rounded p-button-danger mr-2"
                icon="pi pi-trash"
                @click="DeleteMut(slotProps.data.purchase_id)"
                v-tooltip.bottom="'Delete'"
              />
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.Right="'Detail'"
                @click="$router.push({
                    name: 'Pembelian Peripheral Detail',
                    params: { code: slotProps.data.purchase_id }, })"
              />
            </template>
          </Column>
          <template #footer>
               <div class="grid dir-col">
			        <div class="col">
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
                    icon="pi pi-print"
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

export default {
  data() {
    return {
        loading: true,
        token: localStorage.getItem('token'),
        purch: [],
        purchase_id:null,
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        checkname : [],
        checkto : [],
        divisi: [],
    };
  },
  created() {
    this.getPurchase();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY")
    },
    formatPrice(value) {
        let val = (value/1).toFixed(2).replace('.', ',')
         return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")
    },
    getPurchase(){
      this.axios.get('api/pem',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.purch = response.data;
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
           if(error.response.status == 403){
             this.$router.push('/access');
           }
        });
    },
    DeleteMut(purchase_id){
       this.$confirm.require({
        message: "Are you sure to delete this record data?",
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
          this.axios.delete('api/delete-pem/' +purchase_id,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.loading = true;
            this.getPurchase();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('api/report-pem-pdf',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcel(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-pem-excel',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'PURCHASE PERIPHERAL REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
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
@media screen and (max-width: 960px) {
	::v-deep(.p-toolbar) {
		flex-wrap: wrap;
        
		.p-button {
            margin-bottom: 0.25rem;
        }
	}
}
</style>