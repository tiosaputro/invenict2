<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="p-mb-4">
              <template v-slot:start>
                <h4>ICT Request</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1" v-model:activeIndex="active1">
              <TabPanel header="Menunggu Diverifikasi">
                <DataTable
                  :value="blmdiverifikasi"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Menunggu Diverifikasi"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Manager Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-rounded p-button-success mr-2"
                        icon="pi pi-check-square"
                        v-tooltip.right="'Verifikasi'"
                        @click="Verifikasi(slotProps.data.ireq_id)"
                      />
                    </template>
                  </Column>
                </DataTable>   
              </TabPanel>
                <TabPanel header="Yang Telah Diverifikasi">
                  <DataTable
                    :value="sdhdiverifikasi"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Yang Telah Diverifikasi"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Manager Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                    </template>
                  </Column>
                </DataTable>   
                </TabPanel>
                <TabPanel header="Yang Di Reject">
                   <DataTable
                    :value="reject"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Yang Di Reject"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Manager Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                    </template>
                  </Column>
                </DataTable>  
                </TabPanel>
                <TabPanel header="Sedang Dikerjakan">
                   <DataTable
                    :value="sedangDikerjakan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Sedang Dikerjakan"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:7rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:3rem"/>
                  <Column style="min-width:10rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.right="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Manager Detail Penugasan',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                    </template>
                  </Column>
                </DataTable>
                </TabPanel>
                <TabPanel header="Sudah Dikerjakan">
                    <DataTable
                    :value="sudahDikerjakan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Sudah Dikerjakan"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem"/>
                </DataTable>
                </TabPanel>
                <TabPanel header="Selesai">
                    <DataTable
                    :value="selesai"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Selesai"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem"/>
                </DataTable>
                </TabPanel>
            </TabView>
            <Dialog
              v-model:visible="dialogReject"
              :style="{ width: '400px' }"
              header="ICT Request"
              :modal="true"
              class="field"
            >
            <div class="field">
              <div class="field grid">
                <label class="col-fixed w-9rem">Alasan</label>
                  <div class="co-fixed">
                    <Textarea
                    :autoResize="true"
                    type="text"
                    v-model="reason.ket"
                    rows="5"
                    placeholder="Masukan Alasan"
                    :class="{ 'p-invalid': submitted && !reason.ket }"
                  />
                    <small v-if="submitted && !reason.ket" class="p-error">
                    Alasan Harus Diisi
                    </small>
                </div>
              </div>
            </div>
        <template #footer>
            <Button label="Yes" @click="updateReject()" class="p-button" autofocus />
            <Button label="No" @click="cancelReject()" class="p-button-text" />
        </template>
      </Dialog>
      <Dialog header="Confirmation" v-model:visible="ConfirmationVerifikasi" :style="{width: '350px'}" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Verifikasi Request</span>
            </div>
            <template #footer>
                <Button label="Reject" icon="pi pi-times" @click="rejectRequest" class="p-button-raised p-button-danger p-button-text"/>
                <Button label="Approve" icon="pi pi-check" @click="approve" class="p-button-raised p-button-text" autofocus />
            </template>
        </Dialog>
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment';
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        dialogReject:false,
        ConfirmationVerifikasi:false,
        reason :{
          ket:null,
          id:null,
        },
        submitted:false,
        loading: true,
        blmdiverifikasi: [],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        sdhdiverifikasi: [],
        reject:[],
        usr_name : localStorage.getItem('usr_name'),
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        code:null
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
        if(this.checkname.includes("Approval Manager") || this.checkto.includes("/ict-request-manager")){ 
          this.getPermohonan();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
     getPermohonan(){
      this.axios.get('api/get-data-manager',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.blmdiverifikasi = response.data.ict;
        this.sdhdiverifikasi = response.data.ict1;
        this.reject = response.data.ict2;
        this.sedangDikerjakan = response.data.ict3;
        this.sudahDikerjakan = response.data.ict4;
        this.selesai = response.data.ict5;
        this.loading = false;
      }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
           }
        });
    },
    formatDate(date) {
      return moment(date).format("DD MMM YYYY")
    },
    Verifikasi(ireq_id){
      this.code = ireq_id;
      this.ConfirmationVerifikasi = true;
    },
    approve(){
      this.ConfirmationVerifikasi = false;
      this.$confirm.require({
            message: "Approval Permohonan Dilanjutkan?",
            header: "ICT Request    ",
            icon: "pi pi-info-circle",
            acceptClass: "p-button",
            acceptLabel: "Ya",
            rejectLabel: "Tidak",
            accept: () => {
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Permohonan Dilanjutkan",
                life : 1000
              });
              this.axios.get('/api/abm/' +this.code, {headers: {'Authorization': 'Bearer '+this.token}});
              this.code = null;
              this.getPermohonan();
        },
        reject: () => {},
      });
    },
    rejectRequest(){
      this.ConfirmationVerifikasi = false;
      this.dialogReject = true;
    },
    cancelReject(){
      this.dialogReject = false;
      this.code = null;
      this.reason.ket = null;
      this.submitted = false;
    },
    updateReject(){
          this.submitted = true;
           if(this.reason.ket != null){
            this.axios.put('/api/rbm/'+ this.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.dialogReject = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
                life: 1000
              });
               this.code = null;
               this.getPermohonan();
            });
          }
      },
  },
};
</script>