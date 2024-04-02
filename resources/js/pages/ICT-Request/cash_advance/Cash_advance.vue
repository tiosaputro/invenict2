<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Cash Advance</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="cash"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Cash Advance"
          responsiveLayout="scroll"
        >
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-cash-advance')"
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
            Loading Cash Advance data. Please wait.
          </template>
          <Column field="ireq_id" header="No.Request" :sortable="true" style="min-width:6rem">
            <template #body="slotProps">
              <p @click="detailRequest(slotProps.data.ireq_id)" style="cursor:pointer;"> {{slotProps.data.ireq_no}}
              </p> 
            </template>
          </Column>
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:10rem"/>
          <Column field="ca_pic_name" header="Jumlah" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatPrice(slotProps.data.ca_pic_name) }}
            </template>
          </Column>
          <Column field="ca_submit_date" header="Tgl. Submit" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ca_submit_date) }}
            </template>
          </Column>
          <Column headerStyle="min-width:8rem;">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Cash Advance',
                    params: { code: slotProps.data.ca_id },
                  })
                "
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mt-2"
                @click="DeleteCash(slotProps.data.ca_id)"
                v-tooltip.Right="'Delete'"
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
                    class="p-button-raised p-button-success mt-2"
                    icon="pi pi-print"
                    @click="CetakExcel()" 
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>
        <Dialog
          v-model:visible="displayRequest"
          :style="{ width: '1200px' }"
          header="Detail Request"
          :modal="true"
        >
        <Toolbar class="mb-4">
          <template v-slot:end>
              <label style="width:130px">No. Request: {{this.ireq_id}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
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
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_type" header="Tipe Request" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Keterangan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem" v-if="this.ireq.length"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
        </DataTable>
        </Dialog>   
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
         displayRequest:false,
         detail:[],
         loading: true,
         cash: [],
         filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
         token: localStorage.getItem('token'),
         checkname : [],
         checkto : [],
         tes:[],
         ireq:[],
         ireq_id:''
    };
  },
  created() {
    this.getCash();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:24")
    },
    formatPrice(value) {
        var formatter = new Intl.NumberFormat('id', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        });
        return formatter.format(value);
    },
    getCash(){
        this.axios.get('/api/cash').then((response)=> {
          this.cash = response.data.data.cash;
          this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
           else if(error.response.status == 403){
             this.$router.push('/access');
           }
        });
    },
    DeleteCash(ca_id){
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
          this.axios.delete('api/delete-cash/'+ca_id);
          this.getCash();
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open('api/report-cash-pdf');
    },
    CetakExcel(){
      window.open('api/report-cash-excel');
    },
    detailRequest(ireq_id){
      this.axios.get('api/detail-request/' + ireq_id).then((response)=> {
        this.detail = response.data;
        this.ireq_id = response.data[0].ireq_no
        this.tes = response.data.map((x)=>x.ireq_assigned_to);
        if(this.tes.length > 0 && this.tes[0] != null){
          this.ireq = this.tes
        }
        else{}
      });
      this.displayRequest = true;
    }
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