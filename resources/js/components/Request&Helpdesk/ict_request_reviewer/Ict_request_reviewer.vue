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
            <TabView ref="tabView2">
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column columnKey="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem">
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
                        class="p-button-raised p-button-danger p-button-text mr-2"
                        @click="Reject(slotProps.data.ireq_id)"
                        label="Reject"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text"
                        @click="ApproveAtasan(slotProps.data.ireq_id)"
                        label="Higher Level Approval"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-raised p-button-text"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        label="ICT Manager Approval"
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
                <TabPanel header="Higher Level">
                  <DataTable
                    :value="atasandivisi"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :filters="filters"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Higher Level"
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
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.status == 'NA1'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:16rem">
                  <template #body= "slotProps">
                    <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
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
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.status == 'A1'"
                        class="p-button-raised p-button-text p-button-sm mr-2"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        label="ICT Manager Approval"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A1'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A1'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        label="Assign Per-Detail"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status == 'A1'"
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
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.status == 'NA2'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:7rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:7rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:18rem">
                    <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
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
                        v-if="slotProps.data.status == 'A2'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A2'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        label="Assign Per-Detail"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status == 'A2'"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                    <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
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
                        v-if="slotProps.data.status == 'RT'"
                        class="p-button-raised p-button-text p-button-sm mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        label="Assign Per-Request"
                      />
                      <Button
                        v-if="slotProps.data.ireq_assigned_to2 && slotProps.data.status == 'RT'"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:5rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:4rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:4rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
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
                  <Column field="ireq_no" header="No. Request" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireqd_id" header="No. Detail" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_type" header="Request Type" style="min-width:8rem" :sortable="true"/>
                  <Column field="kategori" header="Peripheral" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_qty" header="Qty" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_remark" header="Remark" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_date" header="Request Date" style="min-width:8rem" :sortable="true">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_user" header="User" style="min-width:8rem" :sortable="true"/>
                  <Column field="div_name" header="Division User" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_status" header="Status" style="min-width:8rem" :sortable="true">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column>
                    <template #body="slotProps">
                     <Button
                          v-if="slotProps.data.status == 'D'"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem">
                   <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireq_id)" style="cursor:pointer;"> {{slotProps.data.ireq_no}}
                    </p> 
                   </template>
                  </Column>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:8rem"/>
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
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
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
                  <!-- <template #footer>
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
                  </template> -->
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
                    <label class="col-fixed w-9rem" style="width:100px">Reason</label>
                     <div class="col">
                          <Textarea
                            :autoResize="true"
                            type="text"
                            v-model="rbr.ket"
                            rows="5" 
                            placeholder="Enter Reason"
                            :class="{ 'p-invalid': submitted && !rbr.ket }"
                          />
                            <small v-if="submitted && !rbr.ket" class="p-error">
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
                    <label class="col-fixed w-9rem">Personnel (ICT)</label>
                    <div class="col-fixed w-9rem">
                        <Dropdown
                            v-model="assign.name"
                            :options="petugas"
                            optionValue="name"
                            optionLabel="name"
                            placeholder="Choose One"
                            :class="{ 'p-invalid': submitted && !assign.name }"
                        />
                        <small v-if="submitted && !assign.name" class="p-error">
                            Personnel (ICT) not filled
                        </small>
                    </div>
                </div>
                </div>
                <template #footer>
                    <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
                </template>
            </Dialog>
            <Dialog
              v-model:visible="displayDetailRequest"
              :style="{ width: '1200px' }"
              header="Detail Request"
              :modal="true"
            >
        <Toolbar class="mb-4">
          <template v-slot:end>
              <label style="width:130px">No. Request: {{this.ireq_no}}</label>
          </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
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
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
          <Column field="kategori" header="Peripheral" :sortable="true" style="min-width:12rem"/>
          <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
        </DataTable>
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
        displayDetailRequest:false,
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
        detail:[],
        ireq_no :'',
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
                summary: "Success",
                detail: "Successfully rejected the request",
                life: 2000,
              });
              this.getIct();
        });
        }
    },
    ApproveAtasan(ireq_id){
    this.$confirm.require({
        message: "Are you sure this request need approval from higher level?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
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
        message: "Are you sure this request need approval from ICT Manager?",
        header: "ICT Request    ",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
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
              detail: "Assignment request successful",
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
          message: "Are you sure to close this request?",
          header: "Closing Per Detail",
          icon: "pi pi-info-circle",
          acceptClass: "p-button",
          acceptLabel: "Yes",
          rejectLabel: "No",
          accept: () => {
            this.$toast.add({
              severity: "info",
              summary: "Success",
              detail: "Closing request successful",
              life: 3000,
            });
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
           this.getIct();
          },
          reject: () => {},
        });
    },
    detailRequest(ireq_id){
      this.axios.get('/api/detail-request-reviewer/' + ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.ireq_no = response.data[0].ireq_no;
      });
      this.displayDetailRequest = true;
    },
    CetakPdf(ireq_id){
      window.open('/api/print-out-ict-request/' +ireq_id);
    },
    // CetakPdfPermohonan(){
    //   window.open('api/report-ict-pdf-reviewer-permohonan');
    // },
    // CetakExcelPermohonan(){
    //   window.open('api/report-ict-excel-reviewer-permohonan');
    // },
    // CetakPdfAtasanDivisi(){
    //   window.open('api/report-ict-pdf-reviewer-atasan-divisi');
    // },
    // CetakExcelAtasanDivisi(){
    //   window.open('api/report-ict-excel-reviewer-atasan-divisi');
    // },
    // CetakPdfIctManager(){
    //   window.open('api/report-ict-pdf-reviewer-ict-manager');
    // },
    // CetakExcelIctManager(){
    //   window.open('api/report-ict-excel-reviewer-ict-manager');
    // },
    // CetakPdfReject(){
    //   window.open('api/report-ict-pdf-reviewer-reject');
    // },
    // CetakExcelReject(){
    //   window.open('api/report-ict-excel-reviewer-reject');
    // },
    // CetakPdfSedangDikerjakan(){
    //   window.open('api/report-ict-pdf-reviewer-sedang-dikerjakan');
    // },
    // CetakExcelSedangDikerjakan(){
    //   window.open('api/report-ict-excel-reviewer-sedang-dikerjakan');
    // },
    // CetakPdfSudahDikerjakan(){
    //   window.open('api/report-ict-pdf-reviewer-sudah-dikerjakan');
    // },
    // CetakExcelSudahDikerjakan(){
    //   window.open('api/report-ict-excel-reviewer-sudah-dikerjakan');
    // },
    // CetakPdfSelesai(){
    //   window.open('api/report-ict-pdf-reviewer-selesai');
    // },
    // CetakExcelSelesai(){
    //   window.open('api/report-ict-excel-reviewer-selesai');
    // },
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
