<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
						<div class="my-2">
				        <h4>ICT Request (Detail) </h4>
            </div>
          </template>
          <template v-slot:end>
              <label style="width:200px">No. Request: {{this.kode}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
          :loading="loading"
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
           <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading ICT Request (Detail) data. Please wait.
          </template>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="name" header="Peripheral" :sortable="true" style="min-width:12rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem" v-if="this.ireq.length"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'user-request status-' + slotProps.data.cekstatus.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.ireq_status == null"
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Ict Request',
                    params: { code: slotProps.data.ireq_id },})"
              />
              <Button
                v-if="slotProps.data.ireq_status == null"
                icon="pi pi-trash"
                v-tooltip.right="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteIct(slotProps.data.ireq_id)"
              />
            </template>
          </Column>
          <template #footer>
               <div class="grid dir-col">
			        <div class="col">
				        <div class="box">
                   <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
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
        status:'',
        kode:'',
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        tes:[],
        ireq:[]
    };
  },
  mounted() {
    this.getIctDetail();
    this.getNoreq();
  },
  methods: {
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.tes = response.data.map((x)=>x.ireq_assigned_to);
        if(this.tes.length > 0 && this.tes[0] != null){
          this.ireq = this.tes
        }
        else{}
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
           else if (error.response.status == 401) {
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
        this.kode = response.data.noreq;
        this.status = response.data.ireq_status;
      });
    },
    DeleteIct(ireqd_id){
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
          this.axios.delete('/api/delete-ict-detail/' +ireqd_id, {headers: {'Authorization': 'Bearer '+this.token}});
        this.getIctDetail();
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open('/api/report-ict-detail-pdf/' +this.code);
    },
    CetakExcel(){
      window.open('/api/report-ict-detail-excel/' +this.code);
    },
    CetakPdfReject(){
      window.open('/api/report-ict-detail-pdf-reject/' +this.code);
    },
    CetakExcelReject(){
      window.open('/api/report-ict-detail-excel-reject/' +this.code);
    },
  },
};
</script>