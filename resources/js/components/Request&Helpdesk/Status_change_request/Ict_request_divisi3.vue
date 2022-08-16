<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="mb-4">
              <template v-slot:start>
                <h4>Status Change Request</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1">
              <TabPanel header="Assignment Request">
                <DataTable
                  :value="penugasan"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Assignment Request"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column style="min-width:20rem">
                  <template #body="slotProps">
                    <Button
                      class="p-button-rounded p-button-secondary mr-2"
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
                            @click="CetakPdfAssignmentRequest()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelAssignmentRequest()" 
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to1_reason" header="Reason" :sortable="true" style="min-width:10rem"/>
                  <template #footer>
                    <div class="p-grid p-dir-col">
                      <div class="p-col">
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
              <TabPanel header="On Progress">
                <DataTable
                  :value="sedangDikerjakan"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} On Progress"
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
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:15rem">
                  <template #body="slotProps">
                    <Button
                      v-if="slotProps.data.status == 'T'"
                      class="p-button-rounded p-button-secondary mr-2"
                      v-tooltip.bottom="'Click to change status'"
                      icon="pi pi-pencil"
                      @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                    <Button
                      v-if="slotProps.data.status == 'T'"
                      class="p-button-rounded p-button-help mr-2"
                      icon="bi bi-journal-text"
                      v-tooltip.bottom="'Click to create note'"
                      @click="createNote(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
                    />
                    <Button
                      v-if="slotProps.data.status == 'T'"
                      class="p-button-rounded p-button-danger mr-2"
                      icon="bi bi-journals"
                      v-tooltip.bottom="'Click to create remark'"
                      @click="createRemark(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem"/>
                  <Column>
                    <template #body="slotProps">
                      <Button
                        label="Pdf"
                        class="p-button-raised p-button-danger mr-2"
                        v-tooltip.bottom="'Click to print out (PDF)'"
                        icon="pi pi-file-pdf"
                        @click="CetakPdf(slotProps.data.ireq_id)"
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
                    Loading ICT Request data. Please wait.
                  </template>
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem"/>
                  <Column>
                    <template #body="slotProps">
                      <Button
                        label="Pdf"
                        class="p-button-raised p-button-danger mr-2"
                        v-tooltip.bottom="'Click to print out (PDF)'"
                        icon="pi pi-file-pdf"
                        @click="CetakPdf(slotProps.data.ireq_id)"
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
            header="Dialog Create Note"
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
                <label class="col-fixed w-9rem" style="width:100px">Note</label>
                  <div class="col-fixed w-9rem">
                   <Textarea 
                    v-model="note.ireq_reason" 
                    placeholder="If required"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                  />
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitNote()" class="p-button" autofocus />
                <Button label="No" @click="cancelNote()" class="p-button-text" />
            </template>
          </Dialog>   
          <Dialog
            v-model:visible="dialogRemark"
            :style="{ width: '500px' }"
            header="Dialog Create Remark"
            :modal="true"
            class="fluid"
          >
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem"> No Request </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="remark.ireq_no"
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
                    v-model="remark.ireqd_id"
                    disabled
                    />
                  </div> 
              </div>
            </div>
            <div class="fluid">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                  <div class="col-fixed w-9rem">
                   <Textarea 
                    v-model="remark.ireq_assigned_remark" 
                    placeholder="If required"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                  />
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitRemark()" class="p-button" autofocus />
                <Button label="No" @click="cancelRemark()" class="p-button-text" />
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
        dialogRemark:false,
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
        remark:[],
        code:null,
        status:[],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
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
            this.axios.get('/api/acceptPersonnel/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
            
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
        this.axios.put('/api/rejectPersonnel/'+this.code, this.editDetail, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity:'success', summary: 'Success', detail:'Success Update', life: 3000
          });
          this.cancel();
          this.getData();
        });
      }
    },
    createRemark(ireqd_id,ireq_id){
      this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.remark = response.data;
        this.dialogRemark = true;
      });
        this.code = ireqd_id;
    },
    createNote(ireqd_id,ireq_id){
      this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.note = response.data;
        this.dialogNote = true;
      });
        this.code = ireqd_id;
    },
    submitRemark(){
        this.axios.put('/api/save-remark-assigned/'+this.code,this.remark,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{ 
         this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
          this.note = [];
          this.code = null;
          this.dialogRemark = false;
        });
        this.loading = true;
        this.getData();
    },
    submitNote(){
        this.axios.put('/api/update-note/'+this.code,this.note,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{ 
         this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
          this.note = [];
          this.code = null;
          this.dialogNote = false;
        });
        this.loading = true;
        this.getData();
    },
    cancelRemark(){
      this.remark = [];
      this.code = null;
      this.dialogRemark = false;
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
      this.axios.get('api/get-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
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
    CetakPdfAssignmentRequest(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-personnel-assignment-request',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelAssignmentRequest(){
    const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-personnel-assignment-request',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfReject(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-personnel-reject',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelReject(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-personnel-reject',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfSedangDikerjakan(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-personnel-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelSedangDikerjakan(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-personnel-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfSudahDikerjakan(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-personnel-sudah-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelSudahDikerjakan(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-personnel-sudah-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfSelesai(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-personnel-selesai',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelSelesai(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-personnel-selesai',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdf(ireq_id){
      this.loading = true;
       this.axios.get('api/print-out-ict-request/' +ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
  },
};
</script>