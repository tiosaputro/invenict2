<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="p-mb-4">
              <template v-slot:start>
                <h4>Higher Level Approval</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1" v-model:activeIndex="active1">
              <TabPanel header="Request">
                <DataTable
                  :value="permohonan"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Request"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                    <template #body= "slotProps">
                        <span :class="'user-request status-' + slotProps.data.ireq_statuss.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="$router.push({
                            name: 'Ict Request Divisi 1 Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        v-if="slotProps.data.ireq_statuss == 'NA1'"
                        class="p-button-rounded p-button-success mr-2"
                        icon="pi pi-check-square"
                        v-tooltip.bottom="'Click to Verification'"
                        @click="VerifikasiRequest(slotProps.data.ireq_id)"
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
                            @click="CetakPdfPermohonan()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelPermohonan()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>   
              </TabPanel>
                <TabPanel header="Approved">
                  <DataTable
                    :value="verif"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Approved"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                    <template #body= "slotProps">
                      <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="$router.push({
                            name: 'Ict Request Divisi 1 Detail',
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
                            @click="CetakPdfVerifikasi()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelVerifikasi()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>   
                </TabPanel>
                <TabPanel header="Rejected">
                   <DataTable
                    :value="reject"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Rejected"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                    <template #body= "slotProps">
                      <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="$router.push({
                            name: 'Ict Request Divisi 1 Detail',
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
                            @click="CetakPdfReject()"
                          />
                          <Button 
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
                <TabPanel header="Request Assignment">
                  <DataTable
                    :value="penugasan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Request Assignment"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                    <template #body= "slotProps">
                      <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column style="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="$router.push({
                            name: 'Ict Request Divisi 1 Detail',
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
                            class="p-button-raised p-button-success mr-2 mt-2"
                            icon="pi pi-print"
                            @click="CetakExcelReject()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>
                </TabPanel>
                <TabPanel header="In Progress">
                   <DataTable
                    :value="sedangDikerjakan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} In Progress"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:12rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="$router.push({
                            name: 'Ict Request Divisi 1 Detail',
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
                <TabPanel header="Done">
                    <DataTable
                    :value="sudahDikerjakan"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Done"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="kategori" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
                    <template #body= "slotProps">
                      <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
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
                <TabPanel header="Close">
                    <DataTable
                    :value="selesai"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Close"
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
                    Please wait
                  </template>
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="kategori" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
                    <template #body= "slotProps">
                      <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
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
            <Dialog header="Verification" v-model:visible="confirmationVerifikasi" :style="{width: '350px'}" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Request Verification</span>
            </div>
            <template #footer>
                <Button label="Reject" icon="pi pi-times" @click="rejectRequest" class="p-button-raised p-button-danger p-button-text"/>
                <Button label="Approve" icon="pi pi-check" @click="approve" class="p-button-raised p-button-text" autofocus />
            </template>
        </Dialog>
        <Dialog v-model:visible="dialogReject" :breakpoints="{'960px': '75vw'}" :style="{ width: '400px' }" header="Form Dialog Reject" :modal="true" class="field grid">
         <div class="field"> 
          <div class="field grid">
            <label class="col-fixed w-9rem">Reason</label>
              <div class="col-fixed">
                <Textarea
                  :autoResize="true"
                  type="text"
                  v-model="reason.ket"
                  rows="5" 
                  placeholder="Give a reason"
                  :class="{ 'p-invalid': submitted && !reason.ket }"
                />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Reason not filled
                  </small>
              </div>
            </div>
          </div>
        <template #footer>
            <Button label="Save" @click="updateReject()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelReject()" class="p-button-text" />
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
        confirmationVerifikasi:false,
        dialogReject:false,
        active1:0,
        loading: true,
        permohonan: [],
        penugasan:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        verif: [],
        reject:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        code:null,
        reason:{ ket:null },
        submitted:false
    };
  },
  created() {
    this.getPermohonan();
  },
  methods: {
    getPermohonan(){
      this.axios.get('api/get-permohonan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.permohonan = response.data.ict;
        this.verif = response.data.ict1;
        this.reject = response.data.ict2;
        this.penugasan = response.data.ict9;
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
          if(error.response.status == 403){
            this.$router.push('/access');
          }
        });
    },
    formatDate(date) {
      return moment(date).format("DD MMM YYYY")
    },
    VerifikasiRequest(ireq_id){
      this.code = ireq_id;
      this.confirmationVerifikasi = true;
    },
    approve(){
      this.confirmationVerifikasi = false;
      this.$confirm.require({
            message: "Are you sure you approve to this request?",
            header: "Confirmation Approval",
            icon: "pi pi-info-circle",
            acceptClass: "p-button",
            acceptLabel: "Yes",
            rejectLabel: "No",
            accept: () => {
              this.$toast.add({
                severity: "info",
                summary: "Success Message",
                detail: "Successfully approved this request",
                life : 1000
              });
              this.axios.get('/api/updateStatusPermohonan/' +this.code, {headers: {'Authorization': 'Bearer '+this.token}});
              this.code = null;
              this.loading = true;
              this.getPermohonan();
        },
        reject: () => {},
      });
    },
    rejectRequest(){
      this.confirmationVerifikasi = false;
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
            this.axios.put('/api/updateStatusReject/'+ this.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.dialogReject = false;
              this.$toast.add({
                severity: "info",
                summary: "Success Message",
                detail: "Successfully rejected this request",
                life: 1000
              });
               this.code = null;
               this.loading = true;
               this.getPermohonan();
            });
          }
    },
    CetakPdfPermohonan(){
      window.open('api/report-ict-pdf-atasan-permohonan/'+this.usr_name);
    },
    CetakExcelPermohonan(){
      window.open('api/report-ict-excel-atasan-permohonan/'+this.usr_name);
    },
    CetakPdfVerifikasi(){
      window.open('api/report-ict-pdf-atasan-verifikasi/'+this.usr_name);
    },
    CetakExcelVerifikasi(){
      window.open('api/report-ict-excel-atasan-verifikasi/'+this.usr_name);
    },
    CetakPdfReject(){
      window.open('api/report-ict-pdf-atasan-reject/'+this.usr_name);
    },
    CetakExcelReject(){
      window.open('api/report-ict-excel-atasan-reject/'+this.usr_name);
    },
    CetakPdfSedangDikerjakan(){
      window.open('api/report-ict-pdf-atasan-sedang-dikerjakan/'+this.usr_name);
    },
    CetakExcelSedangDikerjakan(){
      window.open('api/report-ict-excel-atasan-sedang-dikerjakan/'+this.usr_name);
    },
    CetakPdfSudahDikerjakan(){
      window.open('api/report-ict-pdf-atasan-sudah-dikerjakan/'+this.usr_name);
    },
    CetakExcelSudahDikerjakan(){
      window.open('api/report-ict-excel-atasan-sudah-dikerjakan/'+this.usr_name);
    },
    CetakPdfSelesai(){
      window.open('api/report-ict-atasan-pdf-selesai/'+this.usr_name);
    },
    CetakExcelSelesai(){
      window.open('api/report-ict-atasan-excel-selesai/'+this.usr_name);
    },
  },
};
</script>