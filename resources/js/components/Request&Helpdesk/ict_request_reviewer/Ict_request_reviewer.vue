<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
            <template v-slot:start>
                <h4>ICT Request</h4>
            </template>
        </Toolbar>
            <TabView scrollable ref="tabView2">
              <TabPanel header="Permohonan Divisi">
                <DataTable
                  :value="permohonan"
                  :paginator="true"
                  :rows="10"
                  :loading="loading"
                  :filters="filters"
                  :rowHover="true"
                  paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                  :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Permohonan Divisi"
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
                  <Column columnKey="ireq_count_status" header="Personnel ICT" :sortable="true" style="min-width:8rem">
                  <template #body="slotProps">
                    {{slotProps.data.ireq_assigned_to}}
                  </template>
                  </Column>
                  <Column headerStyle="min-width:55rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail Permohonan',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text mr-2"
                        @click="Reject(slotProps.data.ireq_id)"
                        label="Reject"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text"
                        @click="ApproveAtasan(slotProps.data.ireq_id)"
                        label="Persetujuan Atasannya"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        label="Persetujuan ICT Manager"
                      />
                      <Button
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        label="Assign Per-Detail"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text p-button-sm mr-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        label="Submit"
                      />
                    </template>
                  </Column>
                  <template #footer>
                    <div class="grid p-dir-col">
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
                <TabPanel header="Atasan Divisi">
                  <DataTable
                    :value="atasandivisi"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Atasan Divisi"
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
                 <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem">
                  <template #body="slotProps">
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.ireq_status == 'Belum Diapprove Atasan'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:16rem">
                  <template #body= "slotProps">
                    <span :class="'reviewer-atasan status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column headerStyle="min-width:35rem">
                    <template #body="slotProps">
                       <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail Permohonan',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.ireq_status == 'Sudah Diapprove Atasan'"
                        class="p-button-raised p-button-text p-button-sm mr-2"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        label="Persetujuan ICT Manager"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == 'Sudah Diapprove Atasan'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == 'Sudah Diapprove Atasan'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        label="Assign Per-Detail"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.ireq_status == 'Sudah Diapprove Atasan'"
                        class="p-button-raised p-button-text p-button-sm mr-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        label="Submit"
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
                            @click="CetakPdfAtasanDivisi()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelAtasanDivisi()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>   
                </TabPanel>
                <TabPanel header="ICT Manager">
                   <DataTable
                    :value="manager"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Manager"
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
                 <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:7rem">
                  <template #body="slotProps">
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.ireq_status == 'Belum Diapprove ICT Manager'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:7rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:7rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:18rem">
                    <template #body= "slotProps">
                      <span :class="'reviewer-manager status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column headerStyle="min-width:30rem">
                    <template #body="slotProps">
                       <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail Permohonan',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == 'Sudah Diapprove ICT Manager'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == 'Sudah Diapprove ICT Manager'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        label="Assign Per-Detail"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.ireq_status == 'Sudah Diapprove ICT Manager'"
                        class="p-button-raised p-button-text p-button-sm mr-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        label="Submit"
                      />
                    </template>
                  </Column>
                  <template #footer>
                      <div class="p-grid p-dir-col">
                      <div class="p-col">
                        <div class="box">
                          <Button
                            v-if="this.manager.length"
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfIctManager()"
                          />
                          <Button 
                            v-if="this.manager.length"
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelIctManager()" 
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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Yang Direject"
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
                  <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
                  <Column headerStyle="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.right="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail',
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
                            name: 'Ict Request Reviewer Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                       <Button
                        v-if="slotProps.data.ireq_status == 'Reject By ICT Personnel'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.ireq_assigned_to2 && slotProps.data.ireq_status == 'Reject By ICT Personnel'"
                        class="p-button-raised p-button-text p-button-sm p-button-success mr-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        label="Submit"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:2rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:5rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:4rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:3rem"/>
                  <Column headerStyle="min-width:6rem">
                    <template #body="slotProps">
                      <Button
                        v-tooltip.right="' Detail '"
                        class="p-button-rounded p-button-secondary"
                        icon="pi pi-info-circle"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Detail Penugasan',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                    </template>
                  </Column>
                  <template #footer>
                      <div class="grid dir-col">
                      <div class="col">
                        <div class="box">
                          <Button
                            v-if="this.sedangDikerjakan.length"
                            label="Pdf"
                            class="p-button-raised p-button-danger mr-2"
                            icon="pi pi-file-pdf"
                            @click="CetakPdfSedangDikerjakan()"
                          />
                          <Button 
                            v-if="this.sedangDikerjakan.length"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:10rem"/>
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
                  <Column>
                    <template #body="slotProps">
                     <Button
                          v-if="slotProps.data.ireq_status == 'Done'"
                          class="p-button-raised p-button-text mr-2"
                          label="Closing"
                          @click="ClosingPerDetail(slotProps.data.ireqd_id, slotProps.data.ireq_id)"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem"/>
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
                header="Form Dialog Reject"
                :modal="true"
                class="fluid grid"
            >
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Alasan</label>
                     <div class="col">
                          <Textarea
                            :autoResize="true"
                            type="text"
                            v-model="rbr.ket"
                            rows="5" 
                            placeholder="Masukan Alasan"
                            :class="{ 'p-invalid': submitted && !rbr.ket }"
                          />
                            <small v-if="submitted && !rbr.ket" class="p-error">
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
            <Dialog
                v-model:visible="dialogAssign"
                :style="{ width: '500px' }"
                header="Assign Per-Request"
                :modal="true"
                :closable="false"
                class="fluid"
            >
                <div class="fluid">
                <div class="field grid">
                    <label class="col-fixed w-9rem">Petugas (ICT)</label>
                    <div class="col-fixed w-9rem">
                        <Dropdown
                            v-model="assign.name"
                            :options="petugas"
                            optionValue="name"
                            optionLabel="name"
                            placeholder="Pilih Petugas (ICT)"
                            :class="{ 'p-invalid': submitted && !assign.name }"
                        />
                        <small v-if="submitted && !assign.name" class="p-error">
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
import moment from 'moment';
import {FilterMatchMode} from 'primevue/api';
export default {
  data() {
    return {
        dialogAssign:false,
        submitted:false,
        assign:{
          id:null,
          name: null
        },
        petugas:[],
        dialogReject: false,
        submmited: false,
        rbr:{
          ket:null,
          id:null
        },
        loading: true,
        permohonan: [],
        atasandivisi:[],
        manager:[],
        reject:[],
        penugasan:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        token: localStorage.getItem('token'),
        usr_name: localStorage.getItem('usr_name'),
        checkname : [],
        checkto : [],
        id : localStorage.getItem('id'),
        show:false,
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    getIct(){
      this.axios.get('api/get-data-reviewer',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.permohonan = response.data.ict;
          this.loading = false;
          this.atasandivisi = response.data.ict1;
          this.manager = response.data.ict2;
          this.reject = response.data.ict3;
          this.penugasan = response.data.ict7;
          this.sedangDikerjakan = response.data.ict4
          this.sudahDikerjakan = response.data.ict5
          this.selesai = response.data.ict6;
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
      return moment(date).format("DD MMM YYYY HH:mm")
    },
    Submit(ireq_id){
      this.$confirm.require({
        message: "Apakah Anda Yakin Mensubmit?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Berhasil Submit",
            life: 3000,
          });
          this.axios.get('api/sapr/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getIct();
        },
        reject: () => {},
      })
    },
    Reject(ireq_id){
        this.dialogReject = true;
        this.rbr.id = ireq_id;
    },
    cancelReject(){
        this.dialogReject = false;
        this.rbr.id = null;
        this.rbr.ket = null;
    },
    updateReject(){
        this.submitted = true;
        if(this.rbr.ket != null){
          this.axios.put('/api/reject-by-reviewer/'+this.rbr.id, this.rbr, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
            this.dialogReject = false;    
            this.rbr.id = null;
            this.rbr.ket = null;
            this.submitted = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
                life: 2000,
              });
              this.getIct();
        });
        }
    },
    ApproveAtasan(ireq_id){
    this.$confirm.require({
        message: "Apakah Anda Yakin?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Berhasil Update",
            life:2000
          });
          this.axios.get('/api/naa/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getIct();
        },
        reject: () => {},
      });
    },
    ApproveManager(ireq_id){
        this.$confirm.require({
        message: "Apakah Anda Yakin?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Ya",
        rejectLabel: "Tidak",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Berhasil Update",
            life:2000
          });
          this.axios.get('/api/nam/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getIct();
        },
        reject: () => {},
      });
    },
    AssignPerRequest(ireq_id){
        this.assign.id = ireq_id;
        this.axios.get('api/get-pekerja', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.petugas = response.data;
        });
        this.dialogAssign = true;
    },
    updateAssign(){
        this.submitted = true;
        if(this.assign.name != null){
          this.axios.post('/api/aprr', this.assign, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.assign = {
              id : null,
              name : null
              };
            this.submitted = false;
            this.dialogAssign = false;
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Assign",
              life: 2000,
            });
            this.getIct();
          });
        }
    },
    cancelAssign(){
      this.assign =
      { id : null, name : null };
      this.petugas = [];
      this.dialogAssign = false;
      this.submitted = false;
    }, 
    ClosingPerDetail(ireqd_id,ireq_id){
        this.$confirm.require({
          message: "Closing Permohonan Dilanjutkan?",
          header: "Closing Per Detail",
          icon: "pi pi-info-circle",
          acceptClass: "p-button",
          acceptLabel: "Ya",
          rejectLabel: "Tidak",
          accept: () => {
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Berhasil Diclosing",
              life: 3000,
            });
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
           this.getIct();
          },
          reject: () => {},
        });
    },
    CetakPdfPermohonan(){
      window.open('api/report-ict-pdf-reviewer-permohonan');
    },
    CetakExcelPermohonan(){
      window.open('api/report-ict-excel-reviewer-permohonan');
    },
    CetakPdfAtasanDivisi(){
      window.open('api/report-ict-pdf-reviewer-atasan-divisi');
    },
    CetakExcelAtasanDivisi(){
      window.open('api/report-ict-excel-reviewer-atasan-divisi');
    },
    CetakPdfIctManager(){
      window.open('api/report-ict-pdf-reviewer-ict-manager');
    },
    CetakExcelIctManager(){
      window.open('api/report-ict-excel-reviewer-ict-manager');
    },
    CetakPdfReject(){
      window.open('api/report-ict-pdf-reviewer-reject');
    },
    CetakExcelReject(){
      window.open('api/report-ict-excel-reviewer-reject');
    },
    CetakPdfSedangDikerjakan(){
      window.open('api/report-ict-pdf-reviewer-sedang-dikerjakan');
    },
    CetakExcelSedangDikerjakan(){
      window.open('api/report-ict-excel-reviewer-sedang-dikerjakan');
    },
    CetakPdfSudahDikerjakan(){
      window.open('api/report-ict-pdf-reviewer-sudah-dikerjakan');
    },
    CetakExcelSudahDikerjakan(){
      window.open('api/report-ict-excel-reviewer-sudah-dikerjakan');
    },
    CetakPdfSelesai(){
      window.open('api/report-ict-pdf-reviewer-selesai');
    },
    CetakExcelSelesai(){
      window.open('api/report-ict-excel-reviewer-selesai');
    },
  },
};
</script>
<style scoped lang="scss">
    // .cheap {
    //     background-color: #54a90a !important;
    //     background-image: none !important;
    //     color: #ffffff !important;
    // }
</style>
