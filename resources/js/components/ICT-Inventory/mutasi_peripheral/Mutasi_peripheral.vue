<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Mutasi Peripheral</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="mutasi"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Mutasi Peripheral ICT"
          responsiveLayout="scroll"
        >
       <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-mutasi-peripheral')"
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
          <Column field="invent_desc" header="Peripheral" :sortable="true" style="min-width:11em">
            <template #body="slotProps">
              <p @click="detailKode(slotProps.data.invent_code_dtl)" style="cursor:pointer;"> {{slotProps.data.invent_desc}}
              </p> 
            </template>
          </Column>
          <Column field="invent_sn" header="S/N" :sortable="true" style="min-width:10rem"/>
          <Column field="imutasi_tgl_dari" header="From Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{formatDate(slotProps.data.imutasi_tgl_dari)}}
            </template>  
          </Column>
          <Column field="imutasi_tgl_sd" header="To Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.imutasi_tgl_sd">
                {{formatDate(slotProps.data.imutasi_tgl_sd)}}
              </p>
            </template>  
          </Column>
          <Column field="imutasi_pengguna" header="User" :sortable="true" style="min-width:10rem"/>
          <Column field="imutasi_lokasi" header="Location" :sortable="true" style="min-width:10rem"/>
          <Column headerStyle="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                @click="
                  $router.push({
                    name: 'Edit Mutasi Peripheral',
                    params: { code: slotProps.data.imutasi_id },
                  })"
                  v-tooltip.bottom="'Click to edit'"
              />
              <Button
                icon="pi pi-trash"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteMut(slotProps.data.imutasi_id)"
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
                    icon="pi pi-print"
                    @click="CetakExcel()"
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>
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
            <label style="width:155px">Brand</label>
              <div class="col-4">
                <InputText
                  v-model="detail.invent_brand"
                  disabled
                />
              </div>
            </div>
            <div class="field grid">
              <label style="width:155px">Type</label>
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
                <label style="width:155px">Date Received</label>
                  <div class="col-4">
                     <InputText
                      v-model="detail.invent_tgl_perolehan"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Condition</label>
                  <div class="col-4">
                    <InputText
                      v-model="detail.invent_kondisi"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid">
                <label for="notlp2" style="width:155px">Unit Business</label>
                  <div class="col-4">
                    <InputText
                      v-model="detail.invent_bu"
                      disabled
                    />
                </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Last Location</label>
                  <div class="col-6">
                    <InputText
                      type="text"
                      v-model="detail.invent_lokasi_update"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Last User</label>
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
                  <label style="width:155px">Items</label>
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
                      <div class="card">
                        <img :src="'/master_peripheral/' +detail.invent_photo" class="master-image" />
                      </div>
                    </div>
                 </div>
                <div class="field grid">
                  <label style="width:155px">Previous Location</label>
                    <div class="col-6">
                      <InputText
                        v-model="detail.invent_lokasi_previous"
                        disabled
                      />
                    </div>
                </div>
                <div class="field grid">
                  <label style="width:155px">Previous User</label>
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
export default {
  data() {
    return {
        header:'',
        loading: true,
        mutasi: [],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        detail:[],
        displayKode : false
    };
  },
  created() {
    this.getMutasi();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY")
    },
    getMutasi(){
      this.axios.get('api/mut').then((response)=> {
        this.mutasi = response.data.data;
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
    DeleteMut(imutasi_id){
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
          this.axios.delete('api/delete-mut/' +imutasi_id).then(()=>{
            this.getMutasi();
          });  
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      this.loading = true;
       this.axios.get('api/report-mutasi-pdf').then((response)=>{
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
       this.axios.get('api/report-mutasi-excel',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'MUTASI PERIPHERAL REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    detailKode(invent_code){
      this.displayKode = true;
      this.axios.get('api/detail-peripheral/' +invent_code).then((response)=>{
        this.detail = response.data;
        this.header = 'Detail Peripheral '+this.detail.name;
      });
    },
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:150pt;
  object-fit:contain;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>