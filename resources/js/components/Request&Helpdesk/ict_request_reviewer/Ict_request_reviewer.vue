<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
            <h4>ICT Request - Reviewer</h4>
          </template>
        </Toolbar>
            <TabView ref="tabView2" v-model:activeIndex="active1">
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="this.showRemarkPermohonan.some(el=> el > 0)"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelPermohonan.some(el=> el > 0)"/>
                  <Column field="ireq_count_id" header="Total Detail" :sortable="true" style="min-width:10rem"/>
                  <Column headerStyle="min-width:25rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabRequestDetailPermohonan(slotProps.data.ireq_id)"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabRequestDetail(slotProps.data.ireq_id)"
                      />
                      <Button                    
                        class="p-button-rounded p-button-warning mr-2 mt-2"
                        @click="SendEmail(slotProps.data.usr_email,slotProps.data.ireq_id)"
                        icon="bi bi-envelope-check-fill"
                        v-tooltip.bottom="'Click to send email to '+slotProps.data.usr_email+'@emp.id'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-rounded p-button-danger mr-2 mt-2"
                        @click="Reject(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to reject request'"
                        icon="bi bi-x-square"
                      />
                      <Button
                        icon="bi bi-chat-quote"
                        class="p-button-rounded p-button mr-2 mt-2"
                        @click="Remark(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to add remark'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-rounded mr-2 mt-2"
                        @click="ApproveAtasan(slotProps.data.ireq_id)"
                        icon="bi bi-file-earmark-arrow-up"
                        v-tooltip.bottom="'Click to higher level approval'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                        class="p-button-rounded mr-2 mt-2"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to ICT manager approval'"
                        icon="bi bi-file-earmark-arrow-up-fill"
                      />
                      <Button
                        class="p-button-rounded mr-2 mt-2" 
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        icon="bi bi-person-workspace"
                        v-tooltip.bottom="'Click to Assign Per Request'"
                      />
                      <Button
                        class="p-button-rounded mr-2 mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        icon="bi bi-people"
                        v-tooltip.bottom="'Click to Assign Per Detail'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id"
                        class="p-button-rounded p-button-success mr-2 mt-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        icon="bi bi-send-check"
                        v-tooltip.bottom="'Click to submit'"
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
                    Please wait
                  </template>
                 <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem">
                  <template #body="slotProps">
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.status == 'NA1'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:16rem">
                    <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelAtasanDivisi.some(el=> el > 0)"/>
                  <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="this.showRemarkAtasanDivisi.some(el=> el > 0)"/>
                  <Column headerStyle="min-width:20rem">
                    <template #body="slotProps">
                       <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabHigherLevelDetailPermohonan(slotProps.data.ireq_id)"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabHigherLevelDetail(slotProps.data.ireq_id)"
                      />
                      <Button
                        icon="bi bi-chat-quote"
                        class="p-button-rounded p-button mr-2 mt-2"
                        @click="Remark(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to add remark'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.status == 'A1'"
                        class="p-button-rounded mr-2 mt-2"
                        @click="ApproveManager(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to ICT manager approval'"
                        icon="bi bi-file-earmark-arrow-up-fill"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A1'"
                        class="p-button-rounded mr-2 mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        icon="bi bi-person-workspace"
                        v-tooltip.bottom="'Click to Assign Per Request'"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A1'"
                        class="p-button-rounded mr-2 mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        icon="bi bi-people"
                        v-tooltip.bottom="'Click to Assign Per Detail'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status == 'A1'"
                        class="p-button-rounded p-button-success mr-2 mt-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        icon="bi bi-send-check"
                        v-tooltip.bottom="'Click to submit'"
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
                    Please wait
                  </template>
                 <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:7rem">
                  <template #body="slotProps">
                      <p style="color:orangered" class="pi pi-times text-xl" v-if="slotProps.data.status == 'NA2'">{{slotProps.data.ireq_no}}</p>
                      <p style="color:limegreen" class="pi pi-check text-xl" v-else>{{slotProps.data.ireq_no}}</p>
                  </template>
                 </Column>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:7rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:7rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:18rem">
                    <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelmanager.some(el=> el > 0)"/>
                  <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="this.showRemarkManager.some(el=> el > 0)"/>
                  <Column field="ireq_approver2_remark" header="Remark ICT Manager" :sortable="true" style="min-width:14rem" v-if="this.showRemarkApprover2Manager.some(el=> el > 0)"/>
                  <Column headerStyle="min-width:15rem">
                    <template #body="slotProps">
                       <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status <= 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabIctManagerDetailPermohonan(slotProps.data.ireq_id)"
                      />
                      <Button
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-if="slotProps.data.ireq_count_status > 0"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabIctManagerDetail(slotProps.data.ireq_id)"
                      />
                      <Button
                        icon="bi bi-chat-quote"
                        class="p-button-rounded p-button mr-2 mt-2"
                        @click="Remark(slotProps.data.ireq_id)"
                        v-tooltip.bottom="'Click to add remark'"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A2'"
                        class="p-button-rounded mr-2 mt-2"
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        icon="bi bi-person-workspace"
                        v-tooltip.bottom="'Click to Assign Per Request'"
                      />
                      <Button
                        v-if="slotProps.data.status == 'A2'"
                        class="p-button-rounded mr-2 mt-2"
                        @click="$router.push({
                            name: 'Ict Request Reviewer Assign Per Detail',
                            params : {code: slotProps.data.ireq_id},})"
                        icon="bi bi-people"
                        v-tooltip.bottom="'Click to Assign Per Detail'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status == 'A2'"
                        class="p-button-rounded p-button-success mr-2 mt-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        icon="bi bi-send-check"
                        v-tooltip.bottom="'Click to submit'"
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
                            @click="CetakPdfIctManager()"
                          />
                          <Button 
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
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
                        class="p-button-rounded p-button-secondary mr-2 mt-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabRejectedDetail(slotProps.data.ireq_id)"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem" v-if="this.showRemarkPenugasan.some(el=> el > 0)"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column field="ireq_assigned_to1_reason" header="Rejected Reason" :sortable="true" style="min-width:12rem" v-if="this.showReasonPersonnel.some(el=> el > 0)"/>
                  <Column style="min-width:15rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'Click for request details'"
                        @click="detailTabRequestAssignmentDetail(slotProps.data.ireq_id)"
                      />
                       <Button
                        v-if="slotProps.data.status == 'RT'"
                        class="p-button-rounded mr-2 mt-2" 
                        @click="AssignPerRequest(slotProps.data.ireq_id)"
                        icon="bi bi-person-workspace"
                        v-tooltip.bottom="'Click to Assign Per Request'"
                      />
                      <Button
                        v-if="slotProps.data.ireq_assigned_to2 && slotProps.data.status == 'RT'"
                        class="p-button-rounded p-button-success mr-2 mt-2"
                        @click="Submit(slotProps.data.ireq_id)"
                        icon="bi bi-send-check"
                        v-tooltip.bottom="'Click to submit'"
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
                        v-tooltip.bottom="'Click for request details'"
                        class="p-button-rounded p-button-secondary"
                        icon="pi pi-info-circle"
                        @click="detailTabInProgressDetail(slotProps.data.ireq_id)"
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
                  <Column field="ireq_no" header="No. Request" style="min-width:10rem" :sortable="true"/>
                  <Column field="ireqd_id" header="No. Detail" style="min-width:10rem" :sortable="true"/>
                  <Column field="ireq_type" header="Request Type" style="min-width:10rem" :sortable="true"/>
                  <Column field="kategori" header="Items" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_qty" header="Qty" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_remark" header="Remark" style="min-width:16rem" :sortable="true"/>
                  <Column field="ireq_date" header="Request Date" style="min-width:10rem" :sortable="true">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
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
                  <Column field="ireq_requestor" header="Requestor" style="min-width:8rem" :sortable="true"/>
                  <Column field="ireq_user" header="User" style="min-width:8rem" :sortable="true"/>
                  <Column field="div_name" header="Division User" style="min-width:10rem" :sortable="true"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" style="min-width:12rem" :sortable="true"/>
                  <Column field="ireq_status" header="Status" style="min-width:10rem" :sortable="true">
                  <template #body= "slotProps">
                      <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                    </template>
                  </Column>
                  <Column style="min-width:15rem">
                    <template #body="slotProps">
                     <Button
                      v-if="slotProps.data.status == 'D'"
                      class="p-button-raised mr-2"
                      label="Closing"
                      v-tooltip.bottom="'Click to closing request'"
                      @click="ClosingPerDetail(slotProps.data.ireqd_id, slotProps.data.ireq_id)"
                      />
                     <Button
                      label="Pdf"
                      class="p-button-raised p-button-danger mt-2"
                      v-tooltip.bottom="'Click to print out (PDF)'"
                      icon="pi pi-file-pdf"
                      @click="CetakPdf(slotProps.data.ireq_id)"
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
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem">
                   <template #body="slotProps">
                    <p @click="detailRequest(slotProps.data.ireq_id)" style="cursor:pointer;"> {{slotProps.data.ireq_no}}
                    </p> 
                   </template>
                  </Column>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
                  <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:16rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
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
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Division User" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:12rem"/>
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
            <Dialog v-model:visible="dialogReject"
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
            <Dialog v-model:visible="dialogAssign"
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
                            :filter="true"
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
            <Dialog v-model:visible="displayDetailRequest"
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
                :loading="loadingDetail"
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
                <template #loading>
                  Loading data. Please wait
                </template>
                <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:6rem"/>
                <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:12rem"/>
                <Column field="kategori" header="Items" :sortable="true" style="min-width:12rem"/>
                <!-- <Column field="ireq_desc" header="Deskripsi" :sortable="true" style="min-width:12rem"/> -->
                <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:6rem"/>
                <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:12rem"/>
                <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:12rem"/>
                <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
              </DataTable>
            </Dialog>  
            <Dialog v-model:visible="dialogRemark"
                :style="{ width: '400px' }"
                header="Form Dialog Remark"
                :modal="true"
                class="fluid grid"
            >
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Remark</label>
                     <div class="col">
                          <Textarea
                            :autoResize="true"
                            type="text"
                            v-model="remark.remark"
                            rows="5" 
                            placeholder="Enter Remark"
                          />
                            <!-- <small v-if="submitted && !rbr.ket" class="p-error">
                            Reason not filled
                            </small> -->
                     </div>
                   </div>
                </div>
                <template #footer>
                    <Button label="Save" @click="updateRemark()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelRemark()" class="p-button-text" />
                </template>
            </Dialog>
            <Dialog v-model:visible="dialogSendMail"
                :style="{ width: '400px' }"
                header="Dialog Send Mail"
                :modal="true"
                class="fluid grid"
            >
              <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Subjek</label>
                     <div class="col">
                          <InputText
                            type="text"
                            v-model="mail.subject"
                            disabled
                          />
                     </div>
                   </div>
                </div>
              <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">From Email</label>
                     <div class="col">
                          <InputText
                            type="text"
                            v-model="mail.from"
                            disabled
                          />
                     </div>
                   </div>
                </div>
                <div class="p-fluid">
                    <div class="field grid">
                      <label class="col-fixed w-9rem" style="width:100px">To Email</label>
                      <div class="col">
                            <InputText
                              v-model="mail.to"
                              disabled
                            />
                      </div>
                    </div>
                  </div>
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Body Email</label>
                     <div class="col">
                          <Textarea
                            v-model="mail.body"
                            rows="5" 
                            placeholder=""
                            style="white-space: pre"
                            :class="{ 'p-invalid': submitted && !mail.body }"
                          />
                            <small v-if="submitted && !mail.body" class="p-error">
                            Reason not filled
                            </small>
                     </div>
                   </div>
                </div>
                <div class="p-fluid">
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:100px">Footer Mail</label>
                     <div class="col" style="white-space: pre;">
                          <Textarea
                            v-model="mail.footer"
                            rows="4" 
                            :autoResize="true"
                            disabled
                          />
                     </div>
                   </div>
                </div>
                <template #footer>
                    <Button label="Send" icon="bi bi-envelope-check-fill" @click="updateMail()" class="p-button" autofocus />
                    <Button label="Cancel" @click="cancelMail()" class="p-button-text" />
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
        mail:{
          body:'',
          footer:'',
          subject:'',
          from:'',
          to:'',
          noreq:'',
          ireq_id:''
        },
        dialogSendMail:false,
        active1:JSON.parse(localStorage.getItem('active1')),
        dialogAssign:false,
        dialogRemark:false,
        remark:{
          remark:'',
          id:''
        },
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
        loadingDetail: false,
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
        checkname : [],
        checkto : [],
        showPersonelPermohonan:[],
        showPersonelAtasanDivisi:[],
        showPersonelmanager:[],
        showRemarkPermohonan:[],
        showRemarkAtasanDivisi:[],
        showRemarkManager:[],
        showRemarkPenugasan:[],
        showRemarkApprover2Manager:[],
        showReasonPersonnel:[]
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    SendEmail(usr_email,ireq_id){
      this.axios.get('/api/detailrequest-tomail/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        // this.dialogSendMail = true;
        // var frommail = usr_email + "@emp.id";
        // this.mail.from = res.data.fromemail;
        // this.mail.to = frommail;
        // this.mail.ireq_id = ireq_id;
        // this.mail.subject = res.data.noreq;
        // this.mail.footer = "Terimakasih, \n\n\n"+res.data.usr_fullname;
        // this.mail.body = "Dear Mr/Mrs, "+res.data.requestor+"\n\n";
        window.open("mailto:"+usr_email+"?subject="+res.data.noreq);
      });
    },
    cancelMail(){
      this.dialogSendMail = false;
      this.mail = {
          body:'',
          footer:'',
          subject:'',
          from:'',
          to:'',
          noreq:'',
          ireq_id:''
      };
    },
    updateMail(){
      this.axios.post('/api/sendMailtoRequestor',this.mail,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.cancelMail();
      });
    },
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    detailTabRequestDetailPermohonan(ireq_id){
      localStorage.setItem('active1',0);
      this.$router.push('/ict-request-reviewer/detail-permohonan/'+ireq_id)
    },
    detailTabRequestDetail(ireq_id){
      localStorage.setItem('active1',0);
      this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
    },
    detailTabHigherLevelDetailPermohonan(ireq_id){
      localStorage.setItem('active1',1);
      this.$router.push('/ict-request-reviewer/detail-permohonan/'+ireq_id)
    },
    detailTabHigherLevelDetail(ireq_id){
      localStorage.setItem('active1',1);
      this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
    },
    detailTabIctManagerDetailPermohonan(ireq_id){
      localStorage.setItem('active1',2);
      this.$router.push('/ict-request-reviewer/detail-permohonan/'+ireq_id)
    },
    detailTabIctManagerDetail(ireq_id){
      localStorage.setItem('active1',2);
      this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
    },
    detailTabRejectedDetail(ireq_id){
      localStorage.setItem('active1',3);
      this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
    },
    detailTabRequestAssignmentDetail(ireq_id){
      localStorage.setItem('active1',4);
      this.$router.push('/ict-request-reviewer/detail/'+ireq_id)
    },
    detailTabInProgressDetail(ireq_id){
      localStorage.setItem('active1',5);
      this.$router.push('/ict-request-reviewer/detail-penugasan/'+ireq_id)
    },
    getIct(){
      this.axios.get('api/get-data-reviewer',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.permohonan = response.data.ict;
          this.showPersonelPermohonan = this.permohonan.map((x)=>x.ireq_count_status);
          this.showRemarkPermohonan = this.permohonan.map((x)=>x.count_remark);
          this.loading = false;
          this.atasandivisi = response.data.ict1;
          this.showPersonelAtasanDivisi = this.atasandivisi.map((x)=>x.ireq_count_status);
          this.showRemarkAtasanDivisi = this.atasandivisi.map((x)=>x.count_remark);
          this.manager = response.data.ict2;
          this.showPersonelmanager = this.manager.map((x)=>x.ireq_count_status);
          this.showRemarkManager = this.manager.map((x)=>x.count_remark);
          this.showRemarkApprover2Manager = this.manager.map((x)=>x.count_remark_approver2);
          this.reject = response.data.ict3;
          this.penugasan = response.data.ict7;
          this.showRemarkPenugasan = this.penugasan.map((x)=>x.count_remark);
          this.showReasonPersonnel = this.penugasan.map((x)=>x.countreason);
          this.sedangDikerjakan = response.data.ict4;
          this.sudahDikerjakan = response.data.ict5;
          this.selesai = response.data.ict6;
          localStorage.setItem('active1',0);
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
    formatDate(date){
      return moment(date).format("DD MMM YYYY HH:mm")
    },
    Submit(ireq_id){
      this.$confirm.require({
        message: "Are you sure to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Submit",
            life: 3000,
          });
          this.axios.get('api/sapr/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.getIct();
          });
        },
        reject: () => {},
      })
    },
    Remark(ireq_id){
      this.loading = true;
      this.remark.id = ireq_id;
      this.axios.get('api/get-remark-reviewer/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        this.remark.remark = res.data.ireq_verificator_remark;
        this.dialogRemark = true;
        this.loading = false;
      });
    },
    cancelRemark(){
      this.remark.id = '';
      this.remark.remark = '';
      this.dialogRemark = false;
    },
    updateRemark(){
      this.dialogRemark = false;
      this.loading = true;
      this.axios.post('api/save-remark-reviewer',this.remark, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "info",
          summary: "Success",
          detail: "successfully added a remark",
          life: 2000,
        });
        this.remark = {id:'',remark:''};
        this.getIct();
      });
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
          this.axios.put('/api/reject-by-reviewer/'+this.rbr.id, this.rbr, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
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
              this.loading = true;
              this.getIct();
        });
        }
    },
    ApproveAtasan(ireq_id){
    this.$confirm.require({
        message: "Are you sure this request need approval from higher level?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.axios.get('/api/naa/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.getIct();
          });
        },
        reject: () => {},
      });
    },
    ApproveManager(ireq_id){
        this.$confirm.require({
        message: "Are you sure this request need approval from ICT Manager?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.axios.get('/api/nam/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.getIct();
          });
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
              summary: "Success Message",
              detail: "Successfully Assignment",
              life: 2000,
            });
            this.loading = true;
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
            this.loading = true;
            this.$toast.add({
              severity: "info",
              summary: "Success",
              detail: "Closing request successful",
              life: 3000,
            });
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.getIct();
            });
          },
          reject: () => {},
        });
    },
    detailRequest(ireq_id){
      this.displayDetailRequest = true;
      this.loadingDetail = true;
      this.axios.get('/api/detail-request-reviewer/' + ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.ireq_no = response.data[0].ireq_no;
        this.loadingDetail = false;
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
    CetakPdfPermohonan(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-reviewer-permohonan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelPermohonan(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-permohonan',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfAtasanDivisi(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-reviewer-atasan-divisi',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=yes");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelAtasanDivisi(){
      const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-atasan-divisi',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfIctManager(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-reviewer-ict-manager',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
         let responseHtml = response.data;
          var myWindow = window.open("", "response", "resizable=no","target=_blank");
          myWindow.document.write(responseHtml);
          this.loading = false;
       });
    },
    CetakExcelIctManager(){
       const date = new Date();
      const today = moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-ict-manager',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-pdf-reviewer-reject',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-reject',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakPdfAssignmentRequest(){
      this.loading = true;
       this.axios.get('api/report-ict-pdf-reviewer-assignment-request',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-assignment-request',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-pdf-reviewer-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-pdf-reviewer-sudah-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-sudah-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-pdf-reviewer-selesai',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-selesai',{headers: {'Authorization': 'Bearer '+this.token, 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
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
