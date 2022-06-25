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
            <TabView scrollable ref="tabview1">
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
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:18rem">
                  <template #body= "slotProps">
                    <span :class="'manager-belum-verifikasi status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column style="min-width:8rem">
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
                        v-if="slotProps.data.status == 'NA2'"
                        class="p-button-rounded p-button-success mr-2"
                        icon="pi pi-check-square"
                        v-tooltip.right="'Verifikasi'"
                        @click="Verifikasi(slotProps.data.ireq_id)"
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
                            @click="CetakPdfBlmDiverifikasi()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelBlmDiverifikasi()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:18rem">
                  <template #body= "slotProps">
                    <span :class="'manager-sudah-verifikasi status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column>
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
                  <template #footer>
                    <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfSudahDiverifikasi()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelSudahDiverifikasi()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
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
                  <template #footer>
                    <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfDireject()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelDireject()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>  
                </TabPanel>
                <TabPanel header="Penugasan Request">
                  <DataTable
                    :value="penugasan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Penugasan Request"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:20rem">
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
                  <template #footer>
                    <div class="p-grid p-dir-col">
                      <div class="p-col">
                        <div class="box">
                          <Button
                            v-if="this.reject.length"
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfReject()"
                          />
                          <Button 
                            v-if="this.reject.length"
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelReject()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <template #footer>
                    <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfSedangDikerjakan()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelSedangDikerjakan()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <template #footer>
                    <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfSudahDikerjakan()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelSudahDikerjakan()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <template #footer>
                    <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfSelesai()"
                          />
                          <Button
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelSelesai()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
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
                  <div class="co-fixed w-9rem">
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
        penugasan:[],
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
        this.penugasan = response.data.ict6;
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
      return moment(date).format("DD MMM YYYY HH:mm")
    },
    Verifikasi(ireq_id){
      this.code = ireq_id;
      this.ConfirmationVerifikasi = true;
    },
    approve(){
      this.ConfirmationVerifikasi = false;
      this.$confirm.require({
            message: "Are you sure you agree to this request?",
            header: "Confirmation Approval",
            icon: "pi pi-info-circle",
            acceptClass: "p-button",
            acceptLabel: "Yes",
            rejectLabel: "No",
            accept: () => {
              this.$toast.add({
                severity: "info",
                summary: "Success Message",
                detail: "Successfully approved the request",
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
                summary: "Success Message",
                detail: "Successfully rejected the request",
                life: 1000
              });
               this.code = null;
               this.getPermohonan();
            });
          }
    },
    CetakPdfBlmDiverifikasi(){
      window.open('api/report-ict-pdf-manager-permohonan');
    },
    CetakExcelBlmDiverifikasi(){
      window.open('api/report-ict-excel-manager-permohonan');
    },
    CetakPdfSudahDiverifikasi(){
      window.open('api/report-ict-pdf-manager-verifikasi');
    },
    CetakExcelSudahDiverifikasi(){
      window.open('api/report-ict-excel-manager-verifikasi');
    },
    CetakPdfDireject(){
      window.open('api/report-ict-pdf-manager-reject');
    },
    CetakExcelDireject(){
      window.open('api/report-ict-excel-manager-reject');
    },
    CetakPdfSedangDikerjakan(){
      window.open('api/report-ict-pdf-manager-sedang-dikerjakan');
    },
    CetakExcelSedangDikerjakan(){
      window.open('api/report-ict-excel-manager-sedang-dikerjakan');
    },
    CetakPdfSudahDikerjakan(){
      window.open('api/report-ict-pdf-manager-sudah-dikerjakan');
    },
    CetakExcelSudahDikerjakan(){
      window.open('api/report-ict-excel-manager-sudah-dikerjakan');
    },
    CetakPdfSelesai(){
      window.open('api/report-ict-pdf-manager-selesai');
    },
    CetakExcelSelesai(){
      window.open('api/report-ict-excel-manager-selesai');
    },
  },
};
</script>