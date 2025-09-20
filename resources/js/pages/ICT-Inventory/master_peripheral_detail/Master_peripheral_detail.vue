<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <Dialog></Dialog>
        <ConfirmDialog> </ConfirmDialog>
        <DynamicDialog />
        <Toolbar class="mb-4">
          <template v-slot:start>
			      <h4>Master Peripheral (Detail) </h4>
          </template>
          <template v-slot:end>
			      <h4>{{this.detailPeripheral.name}}</h4>
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Master Peripheral Detail"
          responsiveLayout="scroll"
        >
          <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
                label="Add"
                class="p-button-raised"
                icon="bi bi-file-earmark-plus"
                @click="$router.push({
                  name: 'Add Master Peripheral Detail',
                  params: { code: this.$route.params.code },
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
            Loading Master Peripheral data. Please wait.
          </template>
          <Column field="invent_sn" header="Serial Number" :sortable="true">
           <template #body="slotProps">
              <p @click="detailKode(slotProps.data.invent_code_dtl)" style="cursor:pointer;"> 
                {{slotProps.data.invent_sn}}
              </p> 
            </template>
          </Column>
          <Column field="invent_lokasi_previous" header="Previous Location" :sortable="true"/>
          <Column field="invent_lokasi_update" header="Last Location" :sortable="true"/>
          <Column field="invent_pengguna_previous" header="Previous User" :sortable="true"/>
          <Column field="invent_pengguna_update" header="Last User" :sortable="true"/>
          <Column headerStyle="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.bottom="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Master Peripheral Detail',
                    params: { code: slotProps.data.invent_code_dtl, kode:slotProps.data.invent_code },
                  })
                "
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteMas(slotProps.data.invent_code_dtl)"
                v-tooltip.top="'Delete'"
              />
              <Button
                icon="pi pi-qrcode"
                class="p-button-rounded p-button-success mt-2"
                @click="previewBarcode(slotProps.data.invent_code_dtl)"
                v-tooltip.right="'Print QR-Code'"
              />
            </template>
          </Column>
          <template #footer>
            <div class="p-grid p-dir-col">
              <div class="p-col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Master Peripheral'})"
                  />
                </div>
			        </div>
            </div>
          </template>
        </DataTable>   
        <Dialog
          v-model:visible="displayKode"
          :breakpoints="{'960px': '75vw'}"
          :style="{ width: '600px' }"
          :header="this.header"
          :modal="true"
          class="fluid"
        >
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Kode</label>
                  <InputText
                    type="text"
                    v-model="detail.invent_code"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Nama</label>
                  <InputText
                    v-model="detail.invent_desc"
                    disabled
                  />
              </div> 
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Merk</label>
                    <InputText
                      v-model="detail.invent_brand"
                      disabled
                    />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Tipe</label>
                  <InputText
                    disabled
                    v-model= "detail.invent_type"
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">S/N</label>
                  <InputText
                      v-model="detail.invent_sn"
                      disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Tgl. Perolehan</label>
                  <InputText
                    v-model="detail.invent_tgl_perolehan"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Kondisi</label>
                  <InputText
                    v-model="detail.invent_kondisi"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Bisnis Unit</label>
                  <InputText
                    v-model="detail.invent_bu"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Lokasi Terakhir</label>
                  <InputText
                    type="text"
                    v-model="detail.invent_lokasi_update"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Pengguna Terakhir</label>
                  <InputText
                    type="text"
                    v-model="detail.invent_pengguna_update"
                    disabled
                  />
              </div> 
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Lokasi Sebelumnya</label>
                  <InputText
                    v-model="detail.invent_lokasi_previous"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Penguna Sebelumnya</label>
                  <InputText
                    v-model="detail.invent_pengguna_previous"
                    disabled
                  />
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:100px"></label>
                  <div class="card">
                    <img v-if="this.detail.invent_photo" :src="`${$baseUrl}/master_peripheral/` + detail.invent_photo" class="master-image" />
                  </div>
              </div>
        </Dialog>  
      </div>
    </div>
  </div>    
</template>
<script>
import qrcode from '../../Components/QRCode.vue'; 
export default {
  data() {
    return {
        displayKode: false,
        header: '',
        detail:[],
        detailPeripheral:[],
        loading: true,
        displayBarcode: false,
        master: [],
        mas: [],
        barcode:'',
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        text:'',
    };
  },
  created() {
    this.getMaster();
  },
  methods: {
    previewBarcode(invent_code_dtl){
      localStorage.setItem('code',invent_code_dtl);
      this.$dialog.open(qrcode, {
        props: {
          header: 'Preview Barcode',
          style: { width: '20vw'},
          breakpoints: {'760px': '75vw','440px': '90vw'},
          modal: true
        },
      });
    },
    detailKode(invent_code_dtl){
      this.displayKode = true;
      this.axios.get('/api/detail-peripherall/' +invent_code_dtl).then((response)=>{
        this.detail = response.data;
        this.header = 'Detail Peripheral '+this.detail.invent_code +' '+ this.detail.invent_type;
      });
    },
    getMaster(){
      this.axios.get('/api/master-detail/'+this.$route.params.code).then((response)=> {
        this.master = response.data.data.dtl;
        this.detailPeripheral = response.data.data.mas;
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
    DeleteMas(invent_code_dtl){
       this.$confirm.require({
        message: "Are you sure you want to delete this data?",
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
          this.axios.delete('/api/delete-master-detail/' +invent_code_dtl).then(()=>{
            this.loading = true;
            this.getMaster();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open('api/report-master-pdf');
    },
    CetakExcel(){
      window.open('api/report-master-excel');
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