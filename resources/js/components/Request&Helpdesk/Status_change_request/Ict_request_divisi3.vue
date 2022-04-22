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
            <TabView ref="tabview1" v-model:activeIndex="active1">
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
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
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
                  <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                    <Button
                      v-if="slotProps.data.ireq_status == 'Penugasan'"
                      class="p-button-rounded p-button-info mr-2"
                      icon="pi pi-pencil"
                      @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                      <Button
                        class="p-button-raised p-button-info p-button-text mr-2"
                        label="CA"
                        @click="$router.push({
                            name: 'add Cash Advance',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-raised p-button-text mt-2"
                        label="PR"
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
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
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
                  <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                    <Button
                      v-if="slotProps.data.ireq_status == 'Penugasan'"
                      class="p-button-rounded p-button-info mr-2"
                      icon="pi pi-pencil"
                      @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                      <Button
                        class="p-button-raised p-button-info p-button-text mr-2"
                        label="CA"
                        @click="$router.push({
                            name: 'add Cash Advance',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-raised p-button-text mt-2"
                        label="PR"
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
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
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
                  <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                    <Button
                      v-if="slotProps.data.ireq_status == 'Penugasan'"
                      class="p-button-rounded p-button-info mr-2"
                      icon="pi pi-pencil"
                      @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                      <Button
                        class="p-button-raised p-button-info p-button-text mr-2"
                        label="CA"
                        @click="$router.push({
                            name: 'add Cash Advance',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-raised p-button-text mt-2"
                        label="PR"
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
                  <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
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
                  <Column field="ireq_assigned_to1" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
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
            header="ICT Request"
            :modal="true"
            class="fluid"
          >
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">No. Request</label>
                  <div class="col-fixed">
                    <InputText
                      v-model="editDetail.ireq_no"
                      disabled
                    />
                  </div>
                </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Nama Peripheral</label>
                  <div class="col-fixed">
                    <InputText
                      v-model="editDetail.name"
                      disabled
                    />
                  </div>
                </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Status</label>
                  <div class="col-fixed">
                    <Dropdown
                      v-model="editDetail.status"
                      :options="status"
                      optionLabel="name"
                      optionValue="code"
                      :showClear="true"
                      :class="{ 'p-invalid': submitted && !editDetail.status }"
                    />
                    <small class="p-error" v-if="submitted && !editDetail.status"
                      >Status Belum Diisi.
                    </small>
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submit()" class="p-button" autofocus />
                <Button label="No" @click="cancel()" class="p-button-text" />
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
        active1:0,
        loading: true,
        submitted:false,
        selesai: [],
        penugasan:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        user:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        editDetail:[],
        status:[],
        code:null
    };
  },
  mounted() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Status Change Request") || this.checkto.includes("/ict-request-divisi3")){ 
          this.getUser();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getUser(){
      this.axios.get('api/user',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.user = response.data;
        this.getData();
      })
    },
    edit(ireqd_id,ireq_id){
      this.code = ireq_id;
      this.dialogEdit = true;
      this.axios.get('/api/detail/'+ ireqd_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.editDetail = response.data;
      });
      this.getStatus();
    },
    cancel(){
      this.code=null;
      this.dialogEdit = false;
      this.status = [];
      this.editDetail = [];
      this.submitted = false;
    },
    submit(){
      this.submitted = true;
      if(this.editDetail.status != null){
        this.axios.put('/api/update-status-done/'+this.code, this.editDetail, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success', detail:'Status Berhasil Dirubah', life: 3000
          });
          this.cancel();
          this.getData();
        });
      }
    },
    getData(){
      this.axios.get('api/get-sedang-dikerjakan/'+this.user.usr_fullname,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.sedangDikerjakan = response.data.ict;
        this.sudahDikerjakan = response.data.ict1;
        this.selesai = response.data.ict2;
        this.loading = false;
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
    formatDate(date) {
      return moment(date).format("DD MMM YYYY")
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