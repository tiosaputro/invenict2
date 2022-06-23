<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="mb-4">
              <template v-slot:start>
                <h4>ICT Request</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1">
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
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
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
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column style="min-width:20rem">
                  <template #body="slotProps">
                    <Button
                      class="p-button-rounded p-button-info mr-2"
                      icon="pi pi-info-circle"
                      @click="$router.push({
                            name: 'Ict Request Divisi 3 Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                    />
                      <Button
                        class="p-button-raised p-button-info p-button-text mr-2"
                        label="Accept"
                        @click="acceptRequest(slotProps.data.ireq_id)"
                      />
                      <Button
                        class="p-button-raised p-button-danger p-button-text mt-2"
                        label="Reject"
                        @click="rejectRequest(slotProps.data.ireq_id)"
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
              <TabPanel header="Yang Direject">
                <DataTable
                  :value="reject"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
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
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to1_reason" header="Alasan" :sortable="true" style="min-width:10rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                   <Button
                      class="p-button-rounded p-button-info mr-2"
                      icon="pi pi-info-circle"
                      @click="$router.push({
                            name: 'Ict Request Divisi 3 Detail',
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
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
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
                  <Column field="ireqd_id" header="No. Detail" style="min-width:8rem"/>
                  <Column field="ireq_type" header="Tipe Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                    <Button
                      v-if="slotProps.data.ireq_status == 'Penugasan'"
                      class="p-button-rounded p-button-info mr-2"
                      v-tooltip.left="'Ubah Status'"
                      icon="pi pi-pencil"
                      @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                    <Button
                      v-if="slotProps.data.ireq_status == 'Penugasan'"
                      class="p-button-rounded p-button-help mr-2"
                      icon="bi bi-journal-text"
                      v-tooltip.bottom="'Note'"
                      @click="createNote(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                      <!-- <Button
                        class="p-button-raised p-button-info p-button-text mr-2"
                        label="CA"
                        @click="$router.push({
                            name: 'add Cash Advance',
                            params: { code: slotProps.data.ireq_id, dtl:slotProps.data.ireqd_id } })"
                      />
                      <Button
                        class="p-button-raised p-button-success p-button-text mt-2"
                        label="PR"
                        @click="$router.push({
                            name: 'add Payment Request',
                            params: { code: slotProps.data.ireq_id, dtl:slotProps.data.ireqd_id } })"
                      /> -->
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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
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
                  <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                  <template #footer>
                    <div class="p-grid p-dir-col">
                      <div class="p-col">
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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
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
                  <Column field="name" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                <template #footer>
                    <div class="p-grid p-dir-col">
                      <div class="p-col">
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
            v-model:visible="dialogEdit"
            :style="{ width: '500px' }"
            header="Dialog Reject Request"
            :modal="true"
            class="fluid"
          >
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Alasan</label>
                  <div class="col-fixed w-9rem">
                    <Textarea
                      v-model="editDetail.ireq_reason"
                      :autoResize="true" 
                        rows="5" 
                        cols="20"
                        placeholder="Masukan Alasan"
                        :class="{ 'p-invalid': submitted && !editDetail.ireq_reason }"
                    />
                    <small v-if="submitted && !editDetail.ireq_reason" class="p-error">
                      Alasan Belum Diisi
                    </small>
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitReject()" class="p-button" autofocus />
                <Button label="No" @click="cancel()" class="p-button-text" />
            </template>
          </Dialog>  
          <Dialog
            v-model:visible="dialogChangeStatus"
            :style="{ width: '500px' }"
            header="Dialog Ubah Status"
            :modal="true"
            class="fluid"
          >
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="editStatus.ireq_no"
                    disabled
                    />
                  </div>
              </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem"> No Detail </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="editStatus.ireqd_id"
                    disabled
                    />
                  </div> 
              </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem">Status</label>
                  <div class="col-fixed w-9rem">
                    <Dropdown 
                    v-model="editStatus.status"
                    :filter="true"
                    optionLabel="name"
                    optionValue="code"
                    :options="status"
                    placeholder="Pilih Status"
                    :class="{ 'p-invalid': submitted && !editStatus.status }"
                    />
                    <small v-if="submitted && !editStatus.status" class="p-error">
                      Status Belum Diisi
                    </small>
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitStatus()" class="p-button" autofocus />
                <Button label="No" @click="cancelStatus()" class="p-button-text" />
            </template>
          </Dialog>   
          <Dialog
            v-model:visible="dialogNote"
            :style="{ width: '500px' }"
            header="Dialog Keterangan"
            :modal="true"
            class="fluid"
          >
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="note.ireq_no"
                    disabled
                    />
                  </div>
              </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem"> No Detail </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="note.ireqd_id"
                    disabled
                    />
                  </div> 
              </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Keterangan</label>
                  <div class="col-fixed w-9rem">
                   <Textarea 
                    v-model="note.ireq_reason" 
                    placeholder="Masukan Keterangan"
                    :class="{ 'p-invalid': submitted && !note.ireq_reason }"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                  />
                    <small v-if="submitted && !note.ireq_reason" class="p-error">
                      Keterangan Belum Diisi
                    </small>
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitNote()" class="p-button" autofocus />
                <Button label="No" @click="cancelNote()" class="p-button-text" />
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
        dialogEdit:false,
        dialogNote:false,
        dialogChangeStatus:false,
        loading: true,
        submitted:false,
        selesai: [],
        penugasan:[],
        reject:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        user:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        editDetail:{ ireq_reason :''},
        editStatus:[],
        note:[],
        code:null,
        status:[],
    };
  },
  mounted() {
    this.getUser();
  },
  methods: {
    getUser(){
      this.axios.get('api/user',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.user = response.data;
        this.getData();
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
    acceptRequest(ireq_id){
      this.$confirm.require({
        message: "Apakah anda yakin?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
          accept: () => {
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Accept Request Success",
              life : 1000
            });
            this.axios.get('/api/acceptPersonnel/' +ireq_id + '/' + this.user.usr_fullname, {headers: {'Authorization': 'Bearer '+this.token}});
            
            this.getData();
        },
        reject: () => {},
      });
      },
    rejectRequest(ireq_id){
      this.code = ireq_id;
      this.dialogEdit = true;
    },
    cancel(){
      this.dialogEdit = false;
      this.editDetail = [];
      this.code = null;
      this.submitted = false;
    },
    submitReject(){
      this.submitted = true;
      if(this.editDetail.ireq_reason != ''){
        this.axios.put('/api/rejectPersonnel/'+this.code + '/' + this.user.usr_fullname, this.editDetail, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success', detail:'Success Update', life: 3000
          });
          this.cancel();
          this.getData();
        });
      }
    },
    createNote(ireqd_id,ireq_id){
      this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.note = response.data;
        this.code = ireqd_id;
      });
      this.dialogNote = true;
    },
    submitNote(){
      this.submitted = true;
      if(this.note.ireq_reason != null){
        this.axios.put('/api/update-note/'+this.code,this.note,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{ 
         this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
          this.note = [];
          this.code = null;
          this.dialogNote = false;
          this.submitted = false;
        });
        this.getData();
      }
    },
    cancelNote(){
      this.note = [];
      this.code = null;
      this.dialogNote = false;
    },
    edit(ireqd_id,ireq_id){
      this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.editStatus = response.data;
        this.code = ireq_id;
        this.getStatus();
      });
      this.dialogChangeStatus = true;
    },
    submitStatus(){
      this.submitted = true;
        this.axios.put('/api/update-status-done/'+this.code,this.editStatus,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.editStatus = [];
          this.code = null;
          this.status=[];
          this.dialogChangeStatus = false;
          this.submitted = false;
          this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
        });
        this.getData();
    },
    cancelStatus(){
      this.editStatus = [];
      this.code = null;
      this.status=[];
      this.dialogChangeStatus = false;
    },
    getData(){
      this.axios.get('api/get-sedang-dikerjakan/'+this.user.usr_fullname,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.penugasan = response.data.ict3;
        this.reject = response.data.ict4;
        this.sedangDikerjakan = response.data.ict;
        this.sudahDikerjakan = response.data.ict1;
        this.selesai = response.data.ict2;
        this.loading = false;
      }).catch(error=>{
          if(error.response.status == 403){
             this.$router.push('/access');
          }
        });
    },
    formatDate(date) {
      return moment(date).format("DD MMM YYYY HH:mm")
    },
    getStatus(){
      this.axios.get('/api/getStatusIct', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.status = response.data;
      });
    },
    CetakPdfSedangDikerjakan(){
      window.open('/api/report-ict-pdf-personnel-sedang-dikerjakan/'+this.user.usr_fullname);
    },
    CetakExcelSedangDikerjakan(){
      window.open('/api/report-ict-excel-personnel-sedang-dikerjakan/'+this.user.usr_fullname);
    },
    CetakPdfSudahDikerjakan(){
      window.open('/api/report-ict-pdf-personnel-sudah-dikerjakan/'+this.user.usr_fullname);
    },
    CetakExcelSudahDikerjakan(){
      window.open('/api/report-ict-excel-personnel-sudah-dikerjakan/'+this.user.usr_fullname);
    },
    CetakPdfSelesai(){
      window.open('/api/report-ict-pdf-personnel-selesai/'+this.user.usr_fullname);
    },
    CetakExcelSelesai(){
      window.open('/api/report-ict-excel-personnel-selesai/'+this.user.usr_fullname);
    },
  },
};
</script>