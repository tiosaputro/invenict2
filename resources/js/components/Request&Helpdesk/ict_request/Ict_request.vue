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
            <TabView ref="tabView2" v-model:activeIndex="active1">
              <TabPanel header="Request">
                <DataTable
                  :value="ict"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:10rem"/>
                  <Column headerStyle="min-width:13rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == null"
                        class="p-button-rounded p-button-info mr-2"
                        icon="pi pi-pencil"
                        v-tooltip.bottom="'Edit'"
                        @click="
                          $router.push({
                            name: 'Edit Ict Request',
                            params: { code: slotProps.data.ireq_id },})"
                      />
                      <Button
                        v-if="slotProps.data.ireq_status == null"
                        icon="pi pi-trash"
                        class="p-button-rounded p-button-danger mr-2"
                        @click="DeleteIct(slotProps.data.ireq_id)"
                        v-tooltip.top="'Delete'"
                      />
                      <Button
                        v-if="slotProps.data.count > 0 && slotProps.data.ireq_status == null"
                        class="p-button-rounded p-button-success mt-2"
                        icon="pi pi-check"
                        @click="SubmitIct(slotProps.data.ireq_id)"
                        v-tooltip.Right="'Submit'"
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
              <TabPanel header="Reviewer">
                <DataTable
                  :value="reviewer"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                    <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column headerStyle="min-width:10rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.bottom="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
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
                            @click="CetakPdfReviewer()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelReviewer()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>   
              </TabPanel>
                <TabPanel header="Verified">
                  <DataTable
                    :value="verif"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                    <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column style="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
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
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                    <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
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
                            @click="CetakPdfTabReject()"
                          />
                          <Button 
                            label="Excel"
                            class="p-button-raised p-button-success mr-2"
                            icon="pi pi-print"
                            @click="CetakExcelTabReject()" 
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </DataTable>  
                </TabPanel>
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
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.right="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
                      />
                    </template>
                  </Column>
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
                  <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:12rem"/>
                  <Column style="min-width:8rem">
                    <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'View Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail',
                            params: { code: slotProps.data.ireq_id }, })"
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
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
                            @click="CetakExcelSudahgDikerjakan()" 
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
                  <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
                  <Column field="invent_code" header="Peripheral" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem"/>
                  <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:14rem">
                  <template #body= "slotProps">
                    <span :class="'user-request status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
                  </template>
                  </Column>
                  <Column style="min-width:10rem">
                    <template #body="slotProps">
                      <Button
                        v-if="slotProps.data.ireq_value == null"
                        class="p-button-rounded p-button-info mr-2"
                        label = "Rating"
                        @click="tes(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
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
            <Dialog v-model:visible="dialogEdit"
              :style="{ width: '400px' }"
              header="ICT Request"
              class="field grid"
            >
            <div class="fluid">
              <div class="field grid">
                <div class="col-fixed">
                  <star-rating v-bind:increment="1"
                    v-bind:max-rating="5"
                    v-bind:rating="rating"
                    v-bind:animate="true"
                    v-bind:show-rating="true"
                    v-bind:inline="true"
                    v-bind:star-size="40"
                    @hover:rating="check"
                    @update:rating ="setRating">
                  </star-rating> 
                  <Message severity="error" icon="bi bi-emoji-frown" :closable="false" v-if="sangat_kurang">Sangat Kurang</Message>
                  <Message severity="warn" icon="bi bi-emoji-frown" :closable="false" v-if="kurang"> Kurang</Message>
                  <Message severity="info" icon="bi bi-emoji-neutral" :closable="false" v-if="baik">Biasa</Message>
                  <Message severity="info" icon="bi bi-emoji-laughing" :closable="false" v-if="bagus">bagus</Message>
                  <Message severity="success" icon="bi bi-emoji-heart-eyes" :closable="false" v-if="sangat_bagus">Sangat Bagus</Message>
                  </div>
                </div>
            </div>
            <div class="field" v-if="must">
              <div class="field grid">
                <div class="col-5">
                  <Textarea
                    :autoResize="true"
                    v-if="must"
                    type="text"
                    v-model="reason.ket"
                    placeholder="Berikan Ulasan "
                    :class="{ 'p-invalid': submitted && !reason.ket }"
                  />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Ulasan Belum Diisi
                  </small>
                </div>
              </div>
            </div>
            <template #footer>
              <Button label="Yes" @click="Update()" class="p-button" autofocus />
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
      reason:{ id :null, ket:null,ireq_id:null},
      must:false,
      submitted:false,
      sangat_kurang:false,
      kurang:false,
      baik:false,
      bagus:false,
      sangat_bagus:false,
      dialogEdit:false,
      rating: 0,
      active1:0,
      loading: true,
      ict: [],
      verif:[],
      reject:[],
      penugasan:[],
      reviewer:[],
      sedangDikerjakan:[],
      sudahDikerjakan:[],
      selesai:[],
      filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
      token: localStorage.getItem('token'),
      usr_name: localStorage.getItem('usr_name'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    setRating(rating){
      if(rating <= 2){
        this.must = true;
      }
      else{
        this.submitted = false;
        this.must = false;
      }
      this.rating= rating;
    },
    Update(){
      if(this.rating <= '2'){
        this.submitted = true;
        if(this.reason.ket != null){
        const data = new FormData();
        data.append("rating", this.rating);
        data.append("ireq_id", this.reason.ireq_id);
        data.append("id", this.reason.id);
        data.append("ket", this.reason.ket);
        this.axios.post('/api/submit-rating',data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.reason = {id:null, ket :null, ireq_id:null};
          this.sangat_bagus = false;
          this.bagus=false;
          this.baik = false;
          this.kurang = false;
          this.sangat_kurang = false;
          this.must = false;
          this.rating = 0;
          this.submitted = false;
          this.dialogEdit = false;
          this.$toast.add({
            severity:'info', summary: 'Success Submit', detail:'Ulasan Berhasil Disubmit',life:2000
          });
          this.getIct();
        });
        }
      }
      else{
        const data = new FormData();
        data.append("rating", this.rating);
        data.append("id", this.reason.id);
        data.append("ireq_id", this.reason.ireq_id);
        this.axios.post('/api/submit-rating',data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.rating = null;
          this.sangat_bagus = false;
          this.bagus=false;
          this.baik = false;
          this.kurang = false;
          this.sangat_kurang = false;
          this.must = false;
          this.dialogEdit = false;
          this.$toast.add({
            severity:'info', summary: 'Success Submit', detail:'Ulasan Berhasil Disubmit',life:2000
          });
          this.getIct();
        });
      }
    },
    cancel(){
      this.dialogEdit = false;
      this.reason = {id : null, ket : null};
    },
    tes(ireqd_id,ireq_id){
      this.reason.id = ireqd_id;
      this.reason.ireq_id = ireq_id;
      this.dialogEdit = true;
    },
    check(rating){
      if(rating == 1){
        this.sangat_bagus = false;
        this.bagus=false;
        this.baik = false;
        this.kurang = false;
        this.sangat_kurang = true;
        // this.must = true;
      }
      if(rating == 2){
        this.sangat_bagus = false;
        this.bagus=false;
        this.baik = false;
        this.kurang = true;
        this.sangat_kurang = false;
        // this.must = true;
      }
      if(rating == 3){
        this.sangat_bagus = false;
        this.bagus=false;
        this.baik = true;
        this.kurang = false;
        this.sangat_kurang = false;
      }
      if(rating == 4){
        this.sangat_bagus = false;
        this.bagus=true;
        this.baik = false;
        this.kurang = false;
        this.sangat_kurang = false;
        // this.must = false;
      }
      if(rating == 5){
        this.sangat_bagus = true;
        this.bagus=false;
        this.baik = false;
        this.kurang = false;
        this.sangat_kurang = false;
        // this.must = false;
      }
    },
    getIct(){
      this.axios.get('api/get-ict/'+this.usr_name,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.ict = response.data.ict;
          this.loading = false;
          this.verif = response.data.ict1;
          this.reject = response.data.ict2
          this.penugasan = response.data.ict8;
          this.sedangDikerjakan = response.data.ict3
          this.sudahDikerjakan = response.data.ict4
          this.selesai = response.data.ict5
          this.reviewer = response.data.ict6
        }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
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
    SubmitIct(ireq_id){
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Successfully Submit",
            life: 3000,
          });
          this.axios.get('api/updateStatusSubmit/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}});
          this.getIct();
        },
        reject: () => {},
      })
    },
    DeleteIct(ireq_id){
       this.$confirm.require({
        message: "Are you sure you want to delete this record data?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-ict/' +ireq_id,{headers: {'Authorization': 'Bearer '+this.token}});
          this.getIct();
        },
        reject: () => {},
      });
    },
    CetakPdfPermohonan(){
      window.open('api/report-ict-pdf-permohonan/'+this.usr_name);
    },
    CetakExcelPermohonan(){
      window.open('api/report-ict-excel-permohonan/'+this.usr_name);
    },
    CetakPdfReviewer(){
      window.open('api/report-ict-pdf-tab-reviewer/'+this.usr_name);
    },
    CetakExcelReviewer(){
      window.open('api/report-ict-excel-tab-reviewer/'+this.usr_name);
    },
    CetakPdfVerifikasi(){
      window.open('api/report-ict-pdf-verifikasi/'+this.usr_name);
    },
    CetakExcelVerifikasi(){
      window.open('api/report-ict-excel-verifikasi/'+this.usr_name);
    },
    CetakPdfTabReject(){
      window.open('api/report-ict-pdf-reject/'+this.usr_name);
    },
    CetakExcelTabReject(){
      window.open('api/report-ict-excel-reject/'+this.usr_name);
    },
    CetakPdfSedangDikerjakan(){
      window.open('api/report-ict-pdf-sedang-dikerjakan/'+this.usr_name);
    },
    CetakExcelSedangDikerjakan(){
      window.open('api/report-ict-excel-sedang-dikerjakan/'+this.usr_name);
    },
    CetakPdfSudahDikerjakan(){
      window.open('api/report-ict-pdf-tab-sudah-dikerjakan/'+this.usr_name);
    },
    CetakExcelSudahgDikerjakan(){
      window.open('api/report-ict-excel-tab-sudah-dikerjakan/'+this.usr_name);
    },
    CetakPdfSelesai(){
      window.open('api/report-ict-pdf-selesai/'+this.usr_name);
    },
    CetakExcelSelesai(){
      window.open('api/report-ict-excel-selesai/'+this.usr_name);
    },
  },
};
</script>