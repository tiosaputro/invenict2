<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <Dialog/>
        <ConfirmDialog/>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Suplier</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="supp"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Referensi Suplier"
          responsiveLayout="scroll"
        >
          <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-referensi-supplier')"
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
            Loading Suplier data. Please wait.
            </template>
          <Column field="suplier_code" header="Kode" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <p @click="detailSupp(slotProps.data.suplier_code)"  style="cursor:pointer;"> {{slotProps.data.suplier_code}}
              </p> 
            </template>
          </Column>
          <Column field="suplier_name" header="Nama" :sortable="true" style="min-width:12rem"/>
          <Column field="suplier_contact" header="Contact Person" :sortable="true" style="min-width:12rem"/>
          <Column field="suplier_fax" header="No. Fax" :sortable="true" style="min-width:12rem"/>
          <Column headerStyle="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Referensi Supplier',
                    params: { code: slotProps.data.suplier_code }, })"
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteSupp(slotProps.data.suplier_code)"
                v-tooltip.bottom="'Delete'"
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
                    @click="cetakExcel()" 
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>
         <Dialog
          v-model:visible="displaySupp"
          :breakpoints="{'960px': '75vw'}"
          :style="{ width: '450px' }"
          :header="this.header"
          :modal="true"
          class="fluid"
        >
        <div class="field grid">
          <label class="col-fixed" style="width:100px">Kode </label>
          <InputText
            v-model="supps.suplier_code"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px">Nama </label>
          <InputText
            v-model="supps.suplier_name"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Contact  </label>
          <InputText
            v-model="supps.suplier_contact"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Alamat 1  </label>
          <Textarea
            :autoResize="true"
            v-model="supps.suplier_address1"
            disabled
          />  
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Alamat 2 </label>
          <Textarea
            :autoResize="true"
            v-model="supps.suplier_address2"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Kota </label>
          <InputText
            v-model="supps.suplier_city"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Provinsi</label>
          <InputText
            v-model="supps.suplier_name"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Email </label>
          <InputText
            v-model="supps.suplier_email"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> Fax </label>
          <InputText
            v-model="supps.suplier_fax"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> No.Tlp 1 </label>
          <InputText
            v-model="supps.suplier_tlp1"
            disabled
          />
        </div>
        <div class="field grid">
          <label class="col-fixed" style="width:100px"> No.Tlp 2 </label>
          <InputText
            v-model="supps.suplier_tlp2"
            disabled
          />
        </div>
        <template #footer>
          <Button label="Close" class="p-button-raised p-button-danger mr-2" icon="pi pi-times" @click="this.displaySupp = false" autofocus/>
        </template>
      </Dialog>
      </div>
    </div>
  </div>
</template>
<script>
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        header:null,
        loading:true,
        token: localStorage.getItem('token'),
        supp: [],
        supps:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        displaySupp: false,
        id : localStorage.getItem('id'),
        checkname : [],
        checkto : [],
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Suplier") || this.checkto.includes("/referensi-supplier")){
          this.getSupp();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    CetakPdf(){
       window.open("api/report-supplier-pdf");
    },
    cetakExcel(){
      window.open("api/report-supplier-excel");
    },
    detailSupp(suplier_code){
      this.displaySupp = true;
      this.axios.get('api/show-supp/' +suplier_code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.supps = response.data;
        this.header = 'Detail Suplier '+this.supps.suplier_name;
      });
    },
    getSupp(){
      this.axios.get('api/supp', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.supp = response.data;
        this.loading =  false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
    },
    DeleteSupp(suplier_code){
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
          this.axios.delete('api/delete-supp/' + suplier_code, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getSupp();
        },
        reject: () => {},
      });
    },
  },
};
</script>