<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Pembelian Peripheral(Detail) </h4>
          </template>
          <template v-slot:end>
            <div>
              <label style="width:110px">Suplier </label>
              <label>: {{details.suplier_code}} </label>
              <br>
              <label style="width:110px">Tgl. Pembelian </label>
              <label>: {{formatDate(details.purchase_date)}}</label>
            </div>
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Pembelian Peripheral(Detail)"
          responsiveLayout="scroll"
        >
        
       <template #header>
              <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                <Button
                  label="Add"
                  class="p-button-raised"
                  icon="bi bi-file-earmark-plus"
                  @click="$router.push({
                    name: 'Add Pembelian Peripheral Detail',
                    params: { code: code },
                  })"
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
            Loading data. Please wait.
          </template>
          <Column field="invent_code" header="Kode" :sortable="true"/>
          <Column field="invent_desc" header="Peripheral" :sortable="true"/>
          <Column field="invent_brand" header="Brand" :sortable="true"/>
          <Column field="invent_type" header="Type" :sortable="true"/>
          <Column header="Qty" :sortable="true">
          <template #body="slotProps">
          {{slotProps.data.dpurchase_qty}} {{slotProps.data.dpurchase_sat}}
          </template>  
          </Column>
          <Column field="dpurchase_prc" header="Total Harga" :sortable="true">
           <template #body="slotProps">
             {{slotProps.data.valuta_code}}{{formatPrice(slotProps.data.dpurchase_prc)}}
           </template>
          </Column>
          <Column headerStyle="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                @click="
                  $router.push({
                    name: 'Edit Pembelian Peripheral Detail',
                    params: { purchase: slotProps.data.dpurchase_id }, })"
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mt-2"
                @click="DeleteDetail(slotProps.data.dpurchase_id)"
              />
            </template>
          </Column>
          <template #footer>
               <div class="p-grid p-dir-col">
			        <div class="p-col">
				        <div class="box">
                   <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Pembelian Peripheral'})"
                  />
                 <Button
                    label="Pdf"
                    class="p-button-raised p-button-danger mr-2 mt-2"
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
        detail: [],
        purchase_id: this.$route.params.code,
        details:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        checkname : [],
        checkto : [],
        divisi: [],
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('/api/cek-user').then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
         if(this.checkname.includes("Pembelian Peripheral") || this.checkto.includes("/pembelian-peripheral")){
          this.getPembelianDetail();
          this.getDetails();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY")
    },
     formatPrice(value) {
         let val = (value/1).toFixed(2).replace('.', ',')
         return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g,".")
      },
    getPembelianDetail(){
      this.axios.get('/api/detail-pem/' + this.$route.params.code).then((response)=> {
        this.detail = response.data;
      });
    },
    getDetails(){
        this.axios.get('/api/getSuppDate/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.details = response.data;
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
        });
    },
    DeleteDetail(dpurchase_id){
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
          this.axios.delete('/api/delete-detail-pem/' +this.$route.params.code + '/' +dpurchase_id).then(()=>{
            this.loading = true;
            this.getPembelianDetail();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('api/report-pem-detail-pdf/' + this.purchase_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-pem-detail-excel/' + this.purchase_id,{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'PURCHASE DETAIL PERIPHERAL REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
  },
};
</script>