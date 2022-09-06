<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>ICT Request (Detail) </h4>
          </template>
          <template v-slot:end>
              <label style="width:200px">No. Request: {{kode.noreq}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="25"
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
            Loading data. Please wait.
          </template>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:9rem"/>
          <Column field="name" header="Items" :sortable="true" style="min-width:9rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:9rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:9rem"/>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <img :src="'/attachment_request/' +slotProps.data.ireq_attachment" class="attachment-image" style="cursor:pointer;" @click="getDetail(slotProps.data.ireq_attachment)"/>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Pdf :src="'/attachment_request/' +slotProps.data.ireq_attachment" class="attachment-image" style="cursor:pointer;" @click="getDetail(slotProps.data.ireq_attachment)" />
              </p>
            </template>  
          </Column>
          <Column field="ireq_assigned_to1" header="Personnel (ICT)" :sortable="true" style="min-width:9rem"/>
          <Column style="min-width:9rem">
            <template #body="slotProps">
                <Button
                    class="p-button-raised p-button-text p-mr-2 p-mb-2"
                    label="Assign"
                    @click="AssignPerDetail(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
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
                    name: 'Ict Request Reviewer'})"
                  />
                </div>
			        </div>
            </div>
           </template>
        </DataTable>   
        <Dialog
                v-model:visible="dialogAssign"
                :style="{ width: '500px' }"
                header="Assign Per-Detail"
                :modal="true"
                :closable="false"
                class="p-fluid"
            >
            <div class="p-fluid">
                <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">No Request</label>
                 <div class="col-fixed">
                     <InputText
                        v-model="assign.ireq_no"
                        disabled
                     />
                  </div>
                </div>
              </div>
              <div class="p-fluid">
                <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Request Type</label>
                 <div class="col-fixed">
                     <InputText
                        v-model="assign.ireq_type"
                        disabled
                     />
                  </div>
                </div>
              </div>
              <div class="p-fluid">
                <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Items</label>
                 <div class="col-fixed">
                     <InputText
                        v-model="assign.name"
                        disabled
                     />
                  </div>
                </div>
              </div>
              <div class="p-fluid">
                <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                 <div class="col-fixed">
                     <InputText
                        v-model="assign.ireq_remark"
                        disabled
                     />
                  </div>
                </div>
              </div>
                <div class="p-fluid">
                <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Petugas (ICT)</label>
                    <div class="col-fixed">
                        <Dropdown
                            v-model="assign.ireq_assigned_to1"
                            :options="petugas"
                            optionValue="name"
                            optionLabel="name"
                            placeholder="Pilih Petugas (ICT)"
                            :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to1 }"
                        />
                        <small v-if="submitted && !assign.ireq_assigned_to1" class="p-error">
                            Petugas(ICT) Harus Diisi
                        </small>
                    </div>
                </div>
                </div>
                <template #footer>
                    <Button label="Simpan" @click="updateAssign()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
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
        submitted: false,
        loading: true,
        dialogAssign: false,
        detail: [],
        petugas: [],
        assign:[],
        kode:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        code : this.$route.params.code,
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
    };
  },
  mounted() {
    this.cekUser();
  },
  methods: {
    getDetail(ireq_attachment){
      var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
        var myWindow = window.open(page, "_blank");
        myWindow.focus();
    },
    cekUser(){
      this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Reviewer") || this.checkto.includes("/ict-request-reviewer")){ 
         this.getIctDetail();
         this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      cancelAssign(){
          this.assign = [];
          this.petugas = [];
          this.dialogAssign = false;
      },
    AssignPerDetail(ireqd_id){
          this.axios.get('/api/detail/'+ ireqd_id+'/'+this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.assign = response.data;
          });
          this.axios.get('/api/get-pekerja', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.petugas = response.data;
          });
          this.dialogAssign = true; 
    },
    cancelAssign(){
        this.submitted = false;
        this.assign = [];
        this.dialogAssign = false;
    },
     updateAssign(){
        this.submitted = true;
        if(this.assign.ireq_assigned_to1 != null){
          this.axios.put('/api/updateAssignPerDetail/'+ this.$route.params.code ,this.assign, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.assign = [];
            this.dialogAssign = false;
            this.submitted = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 3000,
            });
            this.getIctDetail();
          });
        }
      },
    getIctDetail(){
      this.axios.get('/api/ict-detail/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
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
      });
    },
    getNoreq(){
      this.axios.get('/api/get-noreq/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.kode = response.data;
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.attachment-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>