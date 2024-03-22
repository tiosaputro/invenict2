<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog />
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>Laporan ICT Request</h4>
          </template>
          <template v-slot:end>
				    <Button
              label="Pdf"
              class="p-button-raised p-button-danger mr-2"
              icon="pi pi-file-pdf"
              @click="CetakPdf()"
              v-if="this.filterDate.start || this.filterDate.status"
            />
          </template>
        </Toolbar>
        <DataTable
          :value="ict"
          :paginator="true"
          :rows="25"
          :rowHover="true"
          responsiveLayout="scroll"
          :loading="loading"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          stripedRows
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
        >
         <template #loading>
          Loading data. Please wait.
         </template>
        <template #empty>
          Not Found
        </template>
         <template #header>
            <div class="table-header text-left">
              <Button label="Clear" icon="pi pi-filter-slash" class="p-button-raised p-button-danger mr-2" @click="resetFilter" v-if="this.filter == true" autofocus/>
              <Button icon="pi pi-filter" @click="this.dialogFilterDate = true"/>
            </div>
          </template>
          <Column field="ireq_no" header="No. Request" style="min-width:8rem" :sortable="true"/>
          <Column field="ireqd_id" header="No. Detail" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_type" header="Request Type" style="min-width:8rem" :sortable="true"/>
          <Column field="kategori" header="Peripheral" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_qty" header="Qty" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_remark" header="Remark" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_date" header="Request Date" style="min-width:8rem" :sortable="true">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_user" header="User" style="min-width:8rem" :sortable="true"/>
          <Column field="div_name" header="User Division" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_assigned_to" header="ICT Personnel" style="min-width:8rem" :sortable="true"/>
          <Column field="ireq_status" header="Status" style="min-width:8rem" :sortable="true">
            <template #body= "slotProps">
              <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
        </DataTable> 
        <Dialog
          v-model:visible="dialogFilterDate"
          :breakpoints="{'960px': '75vw'}"
          :style="{ width: '600px' }"
          header="Filter Data"
          :modal="true"
          class="fluid"
        >
        <div class="field grid">
           <label class="col-fixed w-9rem" style="width:180px">Pilih Tanggal</label>
           <div class="col-fixed w-9rem">
            <DatePicker v-model="filterDate" is-range />
           </div>
        </div>
        <div class="field grid">
           <label class="col-fixed w-9rem" style="width:180px">Pilih Status</label>
           <div class="col-fixed w-9rem">
            <Dropdown :filter="true" :showClear="true" v-model="filterDate.status" :options="status" optionValue="code" optionLabel="name" placeholder="Pilih Status" class="mr-2" />
           </div>
        </div>
        <template #footer>
          <Button label="Filter" icon="pi pi-filter" class="p-button-raised p-button mr-2" @click="submitFilter" autofocus/>
          <Button label="Close" class="p-button-raised p-button-danger mr-2" icon="pi pi-times" @click="this.dialogFilterDate = false"/>
        </template>
      </Dialog>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  data() {
    return {
         filter:false,
         mask:{
          input: 'DD MMM YYYY'
         },
         filterDate:{
           start:null,
           end:null,
           status:null,
         },
         dialogFilterDate :false,
         statusRequestor:null,
         status:[],
         statuss:'',
         ict:[],
         loading: false,
         req: [],
         token: localStorage.getItem('token'),
         checkname : [],
         checkto : [],
            items: [
                {
                    label: 'Pdf',
                    icon: 'bi bi-file-earmark-pdf text-danger',
                    command: () => {
                        window.open('api/req-div-req-per-status-pdf/'+this.statusRequestor);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-div-req-per-status-excel/'+this.statusRequestor);
                    }
                }
            ],
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm")
    },
    cekUser(){
      this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkto.includes("/report-ict-request")){
          this.getIct();
          this.getStatus();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getIct(){
      this.loading = true;
      this.axios.get('api/dataIct', {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        this.ict = res.data
        this.loading = false;
      });
    },
    getStatus(){
      this.axios.get('api/get-status', {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        this.status = res.data;
      });
    },
    submitFilter(){
      if (this.filterDate.start == null){ this.filterDate.start == ''};
      if (this.filterDate.start == null){ this.filterDate.start == ''};
      if (this.filterDate.status == null){ this.filterDate.status == ''};
    
      this.axios.post('api/filterByDate',this.filterDate,{headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        this.ict = res.data;
        this.filter = true;
        this.dialogFilterDate = false;
      })
    },
    resetFilter(){
      this.filterDate = 
      {
           start:null,
           end:null ,
           status:null, 
      }
      this.getIct();
      this.filter = false;
    },
    CetakPdf(){
      if(this.filterDate.start && this.filterDate.end){
        var start = moment(this.filterDate.start).format("DD MMM YYYY");
        var end = moment(this.filterDate.end).format("DD MMM YYYY");
        window.open('api/cetak-pdf-filter-ict/'+start + '/' +end + '/' + this.filterDate.status)
      }
      else{
        window.open('api/cetak-pdf-filter-ict/'+this.filterDate.start + '/' +this.filterDate.end + '/' + this.filterDate.status)
      }
    }
  },
};
</script>