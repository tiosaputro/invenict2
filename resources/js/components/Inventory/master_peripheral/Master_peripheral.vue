<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
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
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Master Peripheral ICT"
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
          <Column field="invent_code" header="Kode" :sortable="true">
           <template #body="slotProps">
              <p @click="detailKode(slotProps.data.invent_code)" style="cursor:pointer;"> {{slotProps.data.invent_code}}
              </p> 
            </template>
          </Column>
          <Column field="invent_desc" header="Nama" :sortable="true"/>
          <Column field="invent_brand" header="Merk" :sortable="true"/>
          <Column field="invent_bu" header="Bisnis Unit" :sortable="true"/>
          <Column headerStyle="min-width:14rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Master Peripheral',
                    params: { code: slotProps.data.invent_code },
                  })
                "
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteMas(slotProps.data.invent_code)"
                v-tooltip.top="'Delete'"
              />
              <Button
                icon="pi pi-qrcode"
                class="p-button-rounded p-button-success mt-2"
                @click="previewBarcode(slotProps.data.invent_code)"
                v-tooltip.right="'Print QR-Code'"
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
        <Dialog
          id="qrcode"
          v-model:visible="displayBarcode"
          :style="{ width: '400px' }"
          header="Preview QR-Code"
          :modal="true"
          class="p-fluid"
        >
        <qrcode-vue :value="barcode" ref="qr" :size="300" level="L" /> 
          <template #footer>
            <Button label="Pdf" icon="pi pi-download" @click="downloadBarcodePdf()" class="p-button-danger" />
          </template>
        </Dialog>
        <Dialog
          v-model:visible="displayKode"
          :style="{ width: '1200px' }"
          :header="this.header"
          :modal="true"
          class="fluid"
        >
        <div class="row">
          <div class="col-sm-6">
            <div class="field grid">
              <label style="width:155px">Kode</label>
                <div class="col-4">
                  <InputText
                    type="text"
                    v-model="detail.invent_code"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Merk</label>
                  <div class="col-4">
                    <InputText
                      v-model="detail.invent_brand"
                      disabled
                    />
                  </div>
                </div>
                <div class="field grid">
                  <label style="width:155px">Tipe</label>
                    <div class="col-4">
                      <InputText
                        disabled
                        v-model= "detail.invent_type"
                      />
                    </div>
                  </div>
                  <div class="field grid">
                    <label style="width:155px">S/N</label>
                      <div class="col-4">
                        <InputText
                          v-model="detail.invent_sn"
                          disabled
                        />
                    </div>
                  </div>
                  <div class="field grid">
                    <label style="width:155px">Tgl. Perolehan</label>
                      <div class="col-4">
                        <InputText
                          v-model="detail.invent_tgl_perolehan"
                          disabled
                        />
                      </div>
                  </div>
                  <!-- <div class="field grid">
                    <label style="width:155px">Lama Garansi</label>
                      <div class="col-3">
                        <div class="p-inputgroup">
                          <InputText
                            v-model="detail.invent_lama_garansi"
                            disabled
                          />
                            <span class="p-inputgroup-addon"> Tahun </span> 
                        </div>
                    </div>
                  </div> -->
                  <div class="field grid">
                    <label style="width:155px">Kondisi</label>
                      <div class="col-4">
                        <InputText
                          v-model="detail.invent_kondisi"
                          disabled
                        />
                      </div>
                  </div>
                  <div class="field grid">
                    <label for="notlp2" style="width:155px">Bisnis Unit</label>
                      <div class="col-4">
                        <InputText
                          v-model="detail.invent_bu"
                          disabled
                        />
                    </div>
                  </div>
                  <div class="field grid">
                    <label style="width:155px">Lokasi Terakhir</label>
                      <div class="col-6">
                        <InputText
                          type="text"
                          v-model="detail.invent_lokasi_update"
                          disabled
                        />
                      </div>
                  </div>
                  <div class="field grid">
                    <label style="width:155px">Pengguna Terakhir</label>
                      <div class="col-6">
                        <InputText
                          type="text"
                          v-model="detail.invent_pengguna_update"
                          disabled
                        />
                      </div>
                  </div> 
                </div>
                  <div class="col-sm-6">
                    <div class="field grid">
                      <label style="width:155px">Nama</label>
                        <div class="col-4">
                          <InputText
                            v-model="detail.invent_desc"
                            disabled
                          />
                        </div>
                    </div> 
                    <div class="field grid">
                      <label style="width:155px"></label>
                        <div class="col-10 md-6">
                          <div class="card" style="height: 16 rem;">
                            <img :src="'/master_peripheral/' +detail.invent_photo" class="master-image" />
                          </div>
                        </div>
                    </div>
                    <div class="field grid">
                      <label style="width:155px">Lokasi Sebelumnya</label>
                        <div class="col-6">
                          <InputText
                            v-model="detail.invent_lokasi_previous"
                            disabled
                          />
                        </div>
                    </div>
                    <div class="field grid">
                      <label style="width:155px">Penguna Sebelumnya</label>
                        <div class="col-6">
                          <InputText
                            v-model="detail.invent_pengguna_previous"
                            disabled
                          />
                        </div>
                    </div>
                  </div>
                  </div>
          </Dialog>  
      </div>
    </div>
  </div>    
</template>
<script>
import {FilterMatchMode} from 'primevue/api';
import Jspdf from 'jspdf';
export default {
  data() {
    return {
        displayKode: false,
        header: '',
        detail:[],
        loading: true,
        displayBarcode: false,
        token: localStorage.getItem('token'),
        master: [],
        mas: [],
        barcode:'',
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
    };
  },
  created() {
    this.getMaster();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMaster();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    downloadBarcodePdf(){
      const doc = new Jspdf();
      const contentHtml = this.$refs.qr.$el;
      const image = contentHtml.toDataURL('image/jpeg', 0.8);
      doc.addImage(image, 'JPEG', 70, 30);
      doc.save('Barcode.pdf');
      this.barcode= '';
      this.displayBarcode = false;
    },
    previewBarcode(invent_code){
      // this.axios.get('api/getBarcode/'+invent_code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
      //   this.barcode = 'Kode Peripheral ' + ': ' + response.data.invent_code +', '+ 'Nama Peripheral ' + ': ' + response.data.invent_desc + ', '+ 'Merk '+': '+ response.data.invent_brand+', '
      //   + 'Tipe '+': '+ response.data.invent_type+', '+'S/N '+': '+response.data.invent_sn+', '+ 'Bisnis Unit '+': '+response.data.invent_bu +', '+'Lokasi Terakhir '+': '
      //   +response.data.invent_lokasi_previous+', '+'Pengguna Terakhir '+': '+response.data.invent_pengguna_previous+', '+'Lama Garansi '+': '+response.data.invent_lama_garansi+' Tahun'+', '+'Tanggal Perolehan '+': '+response.data.invent_tgl_perolehan; 
        
        
        this.barcode = 'http://localhost:8000/detPeri/' +invent_code
        this.displayBarcode = true;
      // });
    },
    detailKode(invent_code){
      this.displayKode = true;
      this.axios.get('api/detail-peripheral/' +invent_code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.detail = response.data;
        this.header = 'Detail Peripheral '+this.detail.name;
      });
    },
    getMaster(){
      this.axios.get('api/mas',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.master = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
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
          this.axios.delete('api/delete-mas/' +invent_code,{headers: {'Authorization': 'Bearer '+this.token}});
          this.getMaster();
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open('api/report-master-pdf');
    },
    CetakExcel(){
      window.open('api/report-master-excel');
      // ,{ headers: 
      //  { 'Authorization': 'Bearer '+this.token}, 
      //  'Accept': 'application/json'});
    },
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:200pt;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>