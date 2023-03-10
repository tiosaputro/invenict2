<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4" v-if="this.desc == 1">
          <template v-slot:start>
                <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 1"
          :value="ict"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Waiting for verification)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:10rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.ireq_status == null"
                class="p-button-rounded p-button-info mr-2"
                v-tooltip.left="'Edit'"
                icon="pi pi-pencil"
                @click="
                      $router.push({
                      name: 'Edit Ict Request',
                      params: { code: slotProps.data.ireq_id },})"
                />
              <Button
                v-if="slotProps.data.ireq_status == null"
                icon="pi pi-trash"
                v-tooltip.bottom="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteIct(slotProps.data.ireq_id)"
              />
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                
                @click="$router.push({
                      name: 'Ict Request Detail Desc Requestor',
                      params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 2">
          <template v-slot:start>
                <h4>ICT Request (Already verified)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 2"
          :value="sdhDiverifikasi"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc Requestor',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 3">
          <template v-slot:start>
                <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 3"
          :value="diReject"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
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
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc Requestor',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 4">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 4"
          :value="sdgDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Desc Detail Penugasan',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 5">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 5"
          :value="sdhDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
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
        <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 6">
          <template v-slot:start>
			      <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 6"
          :value="sdhSelesai"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Close)"
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
        <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>    
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 7">
          <template v-slot:start>
			      <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 7"
          :value="permohonan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Waiting for verification"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
                />
              <Button
                v-if="slotProps.data.ireq_statuss == 'NA1'"
                class="p-button-rounded p-button-success mr-2"
                icon="pi pi-check-square"
                v-tooltip.bottom="'Click to Verification'"
                @click="VerifikasiRequestAtasan(slotProps.data.ireq_id)"
              />
            </template>
          </Column>
           <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 8">
          <template v-slot:start>
                <h4>ICT Request (Already verified)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 8"
          :value="verif"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Already verified)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
           <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 9">
          <template v-slot:start>
                <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 9"
          :value="reject"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
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
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 10">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 10"
          :value="sedangDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 11">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 11"
          :value="sudahDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
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
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
         </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable> 
        <Toolbar class="mb-4" v-if="this.desc == 12">
          <template v-slot:start>
                <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 12"
          :value="selesai"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Close)"
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
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 13">
          <template v-slot:start>
              <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 13"
          :value="blmDiverifikasi"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Waiting for verification)"
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
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelblmDiverifikasi.some(el=> el > 0)"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column field="ireq_count_id" header="Total Detail" :sortable="true" style="min-width:10rem"/>
          <Column style="min-width:25rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2 mt-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
              />
              <Button
                v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                class="p-button-rounded p-button-danger mr-2 mt-2"
                @click="Reject(slotProps.data.ireq_id)"
                v-tooltip.bottom="'Click to reject request'"
                icon="bi bi-x-square"
              />
              <Button
                v-tooltip.bottom="'Click to add remark'"
                @click="RemarkReviewer(slotProps.data.ireq_id)"
                icon="bi bi-chat-quote"
                class="p-button-rounded p-button mr-2 mt-2"
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
                  name: 'Ict Request Desc Assign Per Detail',
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
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 14">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 14"
          :value="sudahDiassign"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
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
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:4rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:4rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                  name: 'Ict Request Desc Detail Penugasan',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 15">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 15"
          :value="sudahDikerjakann"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
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
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
          <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
              </p>
            </template>  
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
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
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable> 
        <Toolbar class="mb-4" v-if="this.desc == 16">
          <template v-slot:start>
                <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 16"
          :value="sudahslsi"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
          <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>   
        <Toolbar class="mb-4" v-if="this.desc == 46">
          <template v-slot:start>
                <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 46"
          :value="assignmentRequest3"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Assignment Request)"
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
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>  
        <Toolbar class="mb-4" v-if="this.desc == 47">
          <template v-slot:start>
                <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 47"
          :value="rejected3"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
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
        <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to1_reason" header="Reason" :sortable="true" style="min-width:10rem"/>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>  
        <Toolbar class="mb-4" v-if="this.desc == 17">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 17"
          :value="sedngDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
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
        <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:12rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem"/>
        <Column style="min-width:15rem">
          <template #body="slotProps">
            <Button
              v-if="slotProps.data.status == 'T'"
              class="p-button-rounded p-button-info mr-2"
              icon="pi pi-pencil"
              v-tooltip.bottom="'Click to change status'"
              @click="edit(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
            />
            <Button
              v-if="slotProps.data.status == 'T'"
              class="p-button-rounded p-button-help mr-2"
              icon="bi bi-journal-text"
              v-tooltip.bottom="'Click to create note'"
              @click="createNoteAssigned(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
            />
            <Button
              v-if="slotProps.data.status == 'T'"
              class="p-button-rounded p-button-danger mr-2"
              icon="bi bi-journals"
              v-tooltip.bottom="'Click to create remark'"
              @click="createRemarkAssigned(slotProps.data.ireqd_id,slotProps.data.ireq_id)"
            />
          </template>
        </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 18">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 18"
          :value="sudhDikerjakan"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
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
        <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 19">
          <template v-slot:start>
                <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 19"
          :value="selesaiii"
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
            Please wait
        </template>
        <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
        <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>     
        <Toolbar class="mb-4" v-if="this.desc == 20">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 20"
          :value="sdHDikerjakan4"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
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
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem"/>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
              </p>
            </template>  
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem"/>
          <Column>
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.ireq_status != 'Close'"
                class="p-button-raised p-button-text mr-2"
                label="Closing"
                @click="ClosingPerDetail(slotProps.data.ireqd_id, slotProps.data.ireq_no)"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 21">
          <template v-slot:start>
                <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 21"
          :value="selesai4"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem"/>
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
          <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
              </p>
            </template>  
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 22">
          <template v-slot:start>
                <h4>ICT Request (Overall Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 22"
          :value="total"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 23">
          <template v-slot:start>
                <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 23"
          :value="ictAdmin"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Waiting for verification)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:10rem">
            <template #body="slotProps">
              <Button
                v-if="slotProps.data.ireq_status == null"
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                      $router.push({
                      name: 'Edit Ict Request',
                      params: { code: slotProps.data.ireq_id },})"
                />
              <Button
                v-if="slotProps.data.ireq_status == null"
                icon="pi pi-trash"
                v-tooltip.bottom="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteIct(slotProps.data.ireq_id)"
              />
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 24">
          <template v-slot:start>
                <h4>ICT Request (Already verified)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.desc == 24"
          :value="sdhDiverifikasiAdmin"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Already verified)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 25">
          <template v-slot:start>
            <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 25"
          :value="diRejectAdmin"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 26">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 26"
          :value="sdgDikerjakanAdmin"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 27">
          <template v-slot:start>
                <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 27"
          :value="sdhDikerjakanAdmin"
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
            Please wait
        </template>
        <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
        <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 28">
          <template v-slot:start>
			      <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 28"
          :value="sdhSelesaiAdmin"
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
          Please wait
        </template>
        <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem"/>
        <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem"/>
        <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.ireq_date) }}
          </template>
        </Column>
        <Column header="Attachment" style="min-width:10rem">
          <template #body="slotProps">
            <p v-if="slotProps.data.ireq_attachment == null"></p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='jpeg'|| slotProps.data.ireq_attachment.split('.').pop()=='jpg' || slotProps.data.ireq_attachment.split('.').pop()=='png'">
              <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter" v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                   <span class="px-3">IMAGE</span>
                </Button>
            </p>
            <p v-else-if="slotProps.data.ireq_attachment.split('.').pop()=='pdf'">
              <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube" v-tooltip.bottom="'Click to detail attachment'">
                <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
              </Button>
            </p>
          </template>  
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 29">
          <template v-slot:start>
                <h4>ICT Request (Overall Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 29"
          :value="totalAdmin"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 30">
          <template v-slot:start>
                <h4>ICT Request (Higher Level)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 30"
          :value="atasanDivisi"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Higher Level)"
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
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelatasanDivisi.some(el=> el > 0)"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column headerStyle="min-width:20rem">
          <template #body="slotProps">
            <Button
              class="p-button-rounded p-button-secondary mr-2 mt-2"
              icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
              @click="$router.push({
                name: 'Ict Request Detail Desc',
                params: { code: slotProps.data.ireq_id }, })"
            />
            <Button
                icon="bi bi-chat-quote"
                class="p-button-rounded p-button mr-2 mt-2"
                @click="RemarkReviewer(slotProps.data.ireq_id)"
                v-tooltip.bottom="'Click to add remark'"
              />
            <Button
              v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.ireq_status == 'Sudah Diapprove Atasan'"
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
                name: 'Ict Request Desc Assign Per Detail',
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
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 31">
          <template v-slot:start>
                <h4>ICT Request (ICT Manager)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 31"
          :value="ictManager"
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
            Please wait
        </template>
        <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem">
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
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem"/>
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem"/>
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel ICT" :sortable="true" style="min-width:10rem" v-if="this.showPersonelictManager.some(el=> el > 0)"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <Column headerStyle="min-width:30rem">
          <template #body="slotProps">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="$router.push({
                name: 'Ict Request Detail Desc',
                params: { code: slotProps.data.ireq_id }, })"
            />
            <Button
              icon="bi bi-chat-quote"
              class="p-button-rounded p-button mr-2 mt-2"
              @click="RemarkReviewer(slotProps.data.ireq_id)"
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
              v-if="slotProps.data.ireq_status == 'A2'"
              class="p-button-rounded mr-2 mt-2"
              @click="$router.push({
                name: 'Ict Request Desc Assign Per Detail',
                params : {code: slotProps.data.ireq_id},})"
              icon="bi bi-people"
              v-tooltip.bottom="'Click to Assign Per Detail'"
            />
            <Button
              v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.ireq_status == 'A2'"
              class="p-button-rounded p-button-success mr-2 mt-2"
              @click="Submit(slotProps.data.ireq_id)"
              icon="bi bi-send-check"
              v-tooltip.bottom="'Click to submit'"
            />
          </template>
         </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 32">
          <template v-slot:start>
            <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 32"
          :value="direject2"
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
            Please wait
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
         <Toolbar class="mb-4" v-if="this.desc == 33">
          <template v-slot:start>
            <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 33"
          :value="blmDiverifikasi4"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Waiting for verification"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_statuss" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
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
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 34">
          <template v-slot:start>
                <h4>ICT Request (Already Verified)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 34"
          :value="sdhDiverifikasi4"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Already Verified"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 35">
          <template v-slot:start>
                <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 35"
          :value="direject4"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 36">
          <template v-slot:start>
                <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 36"
          :value="sdgDikerjakan4"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Desc Detail Penugasan',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 37">
          <template v-slot:start>
                <h4>ICT Request (Overhall Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 37"
          :value="totalRequest2"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Overhall Request)"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
         <Toolbar class="mb-4" v-if="this.desc == 38">
          <template v-slot:start>
                <h4>ICT Request (Overhall Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 38"
          :value="totalRequest1"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
            </template>
        </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
         <Toolbar class="mb-4" v-if="this.desc == 39">
          <template v-slot:start>
            <h4>ICT Request (Overhall Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 39"
          :value="totalRequest4"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Overhall Request"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
              <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
          </Column>
          <Column style="min-width:8rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 40">
          <template v-slot:start>
            <h4>ICT Request (Under review)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 40"
          :value="sedangDireview"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Under review)"
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
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 41">
          <template v-slot:start>
              <h4>ICT Request (Under review)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 41"
          :value="sedangDireview1"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Under review)"
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
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.ireq_statuss.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 42">
          <template v-slot:start>
                <h4>ICT Request (Under review)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 42"
          :value="sedangDireview2"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Under review"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:12rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'"
                @click="$router.push({
                      name: 'Ict Request Detail Desc',
                      params: { code: slotProps.data.ireq_id }, })"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 43">
          <template v-slot:start>
                <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 43"
          :value="penugasanRequest2"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :filters="filters"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Assignment Request)"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <Column style="min-width:12rem">
          <template #body="slotProps">
            <Button
              class="p-button-rounded mr-2 mt-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="$router.push({
                name: 'Ict Request Reviewer Detail',
                params: { code: slotProps.data.ireq_id }, })"
            />
            <Button
              v-if="slotProps.data.status == 'RT'"
              icon="bi bi-person-workspace"
              class="p-button-rounded mr-2 mt-2"
              @click="AssignPerRequest(slotProps.data.ireq_id)"
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
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 44">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 44"
          :value="penugasanRequest4"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <Column>
          <template #body="slotProps">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="$router.push({
                name: 'Ict Request Detail Desc',
                params: { code: slotProps.data.ireq_id }, })"
            />
          </template>
        </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.desc == 45">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.desc == 45"
          :value="penugasanRequest1"
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
        <Column field="div_name" header="User Division" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem"/>
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
          <template #body= "slotProps">
            <span :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.ireq_status}}</span>
          </template>
        </Column>
        <Column>
          <template #body="slotProps">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="$router.push({
                name: 'Ict Request Detail Desc',
                params: { code: slotProps.data.ireq_id }, })"
            />
          </template>
        </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Back"
                    class="p-button-raised p-button mr-2"
                    icon="pi pi-chevron-left"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Dialog v-model:visible="dialogAssign"
          :style="{ width: '450px' }"
          header="Assign Per-Request"
          :modal="true"
          :closable="false"
          class="field grid"
        >
        <div class="field grid">
            <label style="width:100px">Personnel (ICT)</label>
              <div class="col-3 md-6">
                <Dropdown
                  v-model="assign.name"
                    :options="petugas"
                    optionValue="name"
                    optionLabel="name"
                    placeholder="Select One"
                    :class="{ 'p-invalid': submitted && !assign.name }"
                />
                <small v-if="submitted && !assign.name" class="p-error">
                  Personnel (ICT) not filled
                </small>
          </div>
        </div>
        <template #footer>
          <Button label="Simpan" @click="updateAssign()" class="p-button" autofocus />
          <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
        </template>
        </Dialog>
        <Dialog v-model:visible="dialogReject"
          :style="{ width: '400px' }"
          header="Form Dialog Reject"
          :modal="true"
          class="fluid grid"
        >
          <div class="p-fluid">
            <div class="field grid">
              <label class="col-fixed w-9rem" style="width:100px">Reason</label>
                <div class="col-fixed w-9rem">
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
              <Button label="Yes" @click="updateReject()" class="p-button" autofocus />
              <Button label="No" @click="cancelReject()" class="p-button-text" />
            </template>
        </Dialog>
        <Dialog v-model:visible="confirmationVerifikasi" 
          header="Confirmation"
          :style="{width: '350px'}" 
          :modal="true"
        >
          <div class="confirmation-content">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
              <span>Verifikasi Request</span>
          </div>
          <template #footer>
            <Button label="Reject" icon="pi pi-times" @click="rejectRequestAtasan" class="p-button-raised p-button-danger p-button-text"/>
            <Button label="Approve" icon="pi pi-check" @click="approveAtasan" class="p-button-raised p-button-text" autofocus />
          </template>
        </Dialog>
        <Dialog  v-model:visible="ConfirmationVerifikasiManager"
          header="Confirmation" 
          :style="{width: '350px'}" 
          :modal="true"
        >
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>Request Verification</span>
            </div>
            <template #footer>
                <Button label="Reject" icon="pi pi-times" @click="rejectManager" class="p-button-raised p-button-danger p-button-text"/>
                <Button label="Approve" icon="pi pi-check" @click="approveManager" class="p-button-raised p-button-text" autofocus />
            </template>
        </Dialog>
        <Dialog v-model:visible="dialogRejectAtasan" 
          :breakpoints="{'960px': '75vw'}" 
          :style="{ width: '400px' }" 
          header="Form Dialog Reject" 
          :modal="true" 
          class="field grid"
        >
         <div class="field"> 
          <div class="field grid">
            <label class="col-fixed w-9rem">Reason</label>
              <div class="col-fixed w-9rem">
                <Textarea
                  :autoResize="true"
                  type="text"
                  v-model="reason.ket"
                  rows="5" 
                  placeholder="Enter Reason"
                  :class="{ 'p-invalid': submitted && !reason.ket }"
                />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Reason not filled
                  </small>
              </div>
            </div>
          </div>
          <template #footer>
            <Button label="Yes" @click="updateRejectAtasan()" class="p-button" autofocus />
            <Button label="No" @click="cancelRejectAtasan()" class="p-button-text" />
          </template>
        </Dialog>
        <Dialog v-model:visible="dialogApproveManager"
          :style="{ width: '400px' }"
          header="ICT Request"
          :modal="true"
          class="field"
          >
            <div class="field">
              <div class="field grid">
                <label class="col-fixed w-9rem">Remark</label>
                  <div class="co-fixed w-9rem">
                    <Textarea
                    :autoResize="true"
                    type="text"
                    v-model="reason.remark"
                    rows="5"
                    placeholder="IF Required"
                  />
                </div>
              </div>
            </div>
        <template #footer>
            <Button label="Yes" @click="updateApproveManager()" class="p-button" autofocus />
            <Button label="No" @click="cancelApproveManager()" class="p-button-text" />
        </template>
        </Dialog>
        <Dialog v-model:visible="dialogRejectManager"
          :style="{ width: '400px' }"
          header="ICT Request"
          :modal="true"
          class="field"
          >
            <div class="field">
              <div class="field grid">
                <label class="col-fixed w-9rem">Reason</label>
                  <div class="co-fixed w-9rem">
                    <Textarea
                    :autoResize="true"
                    type="text"
                    v-model="reason.ket"
                    rows="5"
                    placeholder="Enter Reason"
                    :class="{ 'p-invalid': submitted && !reason.ket }"
                  />
                    <small v-if="submitted && !reason.ket" class="p-error">
                    Reason not filled
                    </small>
                </div>
              </div>
            </div>
        <template #footer>
            <Button label="Yes" @click="updateRejectManager()" class="p-button" autofocus />
            <Button label="No" @click="cancelRejectManager()" class="p-button-text" />
        </template>
        </Dialog>
        <Dialog v-model:visible="dialogEdit"
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
                <label class="col-fixed w-9rem"> No Detail </label>
                  <div class="col-fixed">
                    <InputText 
                    v-model="editDetail.ireqd_id"
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
                <Button label="Yes" @click="submitt()" class="p-button" autofocus />
                <Button label="No" @click="cancel()" class="p-button-text" />
            </template>
        </Dialog>  
        <Dialog v-model:visible="dialogRemarkReviewer"
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
                    v-model="remarkreviewer.remark"
                    rows="5" 
                    placeholder="Enter Remark"
                  />
                </div>
            </div>
          </div>
          <template #footer>
            <Button label="Save" @click="updateRemarkReviewer()" class="p-button" autofocus />
            <Button label="Cancel" @click="cancelRemarkReviewer()" class="p-button-text" />
          </template>
        </Dialog>
        <Dialog v-model:visible="dialogNoteAssigned"
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
                    v-model="noteAssigned.ireq_no"
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
                      v-model="noteAssigned.ireqd_id"
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
                      v-model="noteAssigned.ireq_reason" 
                      placeholder="If required"
                      :autoResize="true" 
                      rows="5" 
                      cols="20"
                    />
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitNoteAssigned()" class="p-button" autofocus />
                <Button label="No" @click="cancelNoteAssigned()" class="p-button-text" />
            </template>
        </Dialog>   
        <Dialog v-model:visible="dialogRemarkAssigned"
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
                      v-model="remarkAssigned.ireq_no"
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
                    v-model="remarkAssigned.ireqd_id"
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
                      v-model="remarkAssigned.ireq_assigned_remark" 
                      placeholder="If required"
                      :autoResize="true" 
                      rows="5" 
                      cols="20"
                  />
                  </div>
                </div>
            </div>
            <template #footer>
                <Button label="Yes" @click="submitRemarkAssigned()" class="p-button" autofocus />
                <Button label="No" @click="cancelRemarkAssigned()" class="p-button-text" />
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
        showPersonelblmDiverifikasi:[],
        showPersonelatasanDivisi:[],
        showPersonelictManager:[],
        sedangDireview:[],
        sedangDireview1:[],
        sedangDireview2:[],
        dialogEdit:false,
        atasanDivisi:[],
        ictManager:[],
        penugasanRequest1:[],
        penugasanRequest2:[],
        direject2:[],
        dialogReject: false,
        desc : 1,
        loading: true,
        token: localStorage.getItem('token'),
        ict: [],
        sdhDiverifikasi:[],
        diReject:[],
        sdgDikerjakan:[],
        sdhDikerjakan:[],
        sdhSelesai:[],
        ictAdmin: [],
        sdhDiverifikasiAdmin:[],
        diRejectAdmin:[],
        sdgDikerjakanAdmin:[],
        sdhDikerjakanAdmin:[],
        sdhSelesaiAdmin:[],
        totalAdmin:[],
        permohonan:[],
        verif:[],
        reject:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        sudahslsi:[],
        sudahDikerjakann:[],
        sudahDiassign:[],
        blmDiverifikasi:[],
        assignmentRequest3:[],
        rejected3:[],
        sedngDikerjakan:[],
        sudhDikerjakan: [],
        selesaiii: [],
        blmDiverifikasi4:[],
        sdhDiverifikasi4:[],
        direject4:[],
        penugasanRequest4:[],
        sdgDikerjakan4:[],
        sdHDikerjakan4:[],
        selesai4:[],
        assign:{ id:null, name: null },
        total:[],
        petugas:[],
        submitted: false,
        dialogAssign: false,
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        user:[],
        rbr:{ ket:'', id:'' },
        confirmationVerifikasi:false,
        ConfirmationVerifikasiManager:false,
        dialogRejectAtasan:false,
        dialogRejectManager:false,
        dialogApproveManager:false,
        dialogRemarkReviewer:false,
        dialogRemarkAssigned:false,
        dialogNoteAssigned: false,
        remarkreviewer:{
          id:'',
          remark:''
        },
        noteAssigned:[],
        remarkAssigned:[],
        code:null,
        reason:{ ket:null, remark:null },
        totalRequest2:[],
        totalRequest1:[],
        totalRequest4:[],
        editDetail:[],
        status:[],
    };
  },
  mounted() {
    this.getActive();
  },
  methods: {
      getDetail(ireq_attachment){
        var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
          var myWindow = window.open(page, "_blank");
          myWindow.focus();
      },
      formatDate(date) {
        return moment(date).format("DD MMM YYYY HH:mm")
      },
      getActive(){
        if(localStorage.getItem('desc')){
          this.desc = localStorage.getItem('desc');
          if (this.desc <= 6 || this.desc == 22 ||this.desc == 40){
              this.getIct();
          }
          else if (this.desc >= 7 && this.desc <= 12 || this.desc == 38 || this.desc == 41 || this.desc == 45){
            this.getIct2();
          } 
          else if (this.desc > 12 && this.desc <=16 || this.desc == 37|| this.desc == 43){
            this.getIct3();
          }
          else if (this.desc > 16 && this.desc <=19 || this.desc == 46 || this.desc == 47){
            this.getIct4();
          }
          else if (this.desc > 19 && this.desc <=21 || this.desc == 39 || this.desc == 42){
            this.getIct5();
          }
          else if (this.desc > 22 && this.desc <= 29){
            this.getIct7();
          }
          else if (this.desc > 29 && this.desc <= 32){
            this.getIct3();
          }
          else if (this.desc > 32 && this.desc <= 36 || this.desc == 44){
            this.getIct5();
          }
        }
      },
      getIct(){
        this.axios.get('api/get-ict',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.ict = response.data.ict6;
          this.sdhDiverifikasi = response.data.ict1;
          this.diReject = response.data.ict2;
          this.sdgDikerjakan = response.data.ict9;
          this.sdhDikerjakan = response.data.ict4;
          this.sdhSelesai = response.data.ict5;
          this.sedangDireview = response.data.ict7;
          this.total = response.data.ict10;
          this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
        });
      },
      getIct2(){
        this.axios.get('/api/get-permohonan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.permohonan = response.data.ict7;
        this.sedangDireview1 = response.data.ict8;
        this.verif = response.data.ict1;
        this.reject = response.data.ict2;
        this.penugasanRequest1 = response.data.ict9;
        this.sedangDikerjakan = response.data.ict3;
        this.sudahDikerjakan = response.data.ict4;
        this.selesai = response.data.ict5;
        this.totalRequest1 = response.data.ict6;
        this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
        });
      },
      getIct3(){
        this.axios.get('api/get-data-reviewer',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.blmDiverifikasi = response.data.ict;
          this.showPersonelblmDiverifikasi = this.blmDiverifikasi.map((x)=>x.ireq_count_status);
          this.atasanDivisi = response.data.ict1;
          this.showPersonelatasanDivisi = this.atasanDivisi.map((x)=>x.ireq_count_status);
          this.ictManager = response.data.ict2;
          this.showPersonelictManager = this.ictManager.map((x)=>x.ireq_count_status);
          this.direject2 = response.data.ict3;
          this.sudahDiassign = response.data.ict4;
          this.sudahDikerjakann = response.data.ict5;
          this.sudahslsi = response.data.ict6;
          this.totalRequest2 = response.data.ict8;
          this.penugasanRequest2 = response.data.ict7;
          this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
        });
      },
      getIct4(){
        this.axios.get('api/get-sedang-dikerjakan',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.sedngDikerjakan = response.data.ict;
        this.sudhDikerjakan = response.data.ict1;
        this.selesaiii = response.data.ict2;
        this.assignmentRequest3 = response.data.ict3;
        this.rejected3 = response.data.ict4;
        this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
        });
      },
      getIct5(){
      this.axios.get('api/get-divisi-4',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.blmDiverifikasi4 = response.data.ict;
        this.sdhDiverifikasi4 = response.data.ict1;
        this.direject4 = response.data.ict2;
        this.sdgDikerjakan4 = response.data.ict3;
        this.sdHDikerjakan4 = response.data.ict4
        this.selesai4 = response.data.ict5;
        this.totalRequest4 = response.data.ict6;
        this.sedangDireview2 = response.data.ict7;
        this.penugasanRequest4 = response.data.ict8;
        this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
        });
      },
      getIct7(){
        this.axios.get('api/get-ict-admin',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.ictAdmin = response.data.ict;
          this.sdhDiverifikasiAdmin = response.data.ict1;
          this.diRejectAdmin = response.data.ict2;
          this.sdgDikerjakanAdmin = response.data.ict3;
          this.sdhDikerjakanAdmin = response.data.ict4;
          this.sdhSelesaiAdmin = response.data.ict5;
          this.totalAdmin = response.data.ict6;
          this.loading = false;
        }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
           this.$router.push('/access');
          }
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
            this.getActive();
          });
        }
      },
      ClosingPerDetail(ireqd_id,ireq_no){
        this.$confirm.require({
          message: "Are you sure to close this request?",
          header: "Closing Per Detail",
          icon: "pi pi-info-circle",
          acceptClass: "p-button",
          acceptLabel: "Yes",
          rejectLabel: "No",
          accept: () => {
            this.loading = true;
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_no, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.getActive();
              this.$toast.add({
              severity: "info",
              summary: "Success",
              detail: "Closing request successful",
              life: 3000,
            });
            });
          },
          reject: () => {},
        });
      },
      cancelAssign(){
          this.assign =
          {
              id : null,
              name : null
            };
          this.petugas = [];
          this.dialogAssign = false;
          this.submitted = false;
      }, 
      Submit(ireq_id){
      this.$confirm.require({
        message: "Are you sure to submit this request?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.axios.get('/api/sapr/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Submitted Success",
            life: 3000,
          });
          this.getActive();
          });
        },
        reject: () => {},
       });
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
          this.axios.get('/api/naa/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.getActive();
          });
        },
        reject: () => {},
      });
      },
      ApproveManager(ireq_id){
        this.$confirm.require({
        message: "Apakah Anda Yakin?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.axios.get('/api/nam/' +ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.loading = true;
            this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life:2000
          });
          this.getActive();
          });
        },
        reject: () => {},
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
        this.submitted = false;
      },
      updateReject(){
        this.submitted = true;
        if(this.rbr.ket){
          this.axios.put('/api/reject-by-reviewer/'+this.rbr.id, this.rbr, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
            this.dialogReject = false;    
            this.rbr.id = '' ;
            this.rbr.ket = '' ;
            this.submitted = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
                life: 2000,
              });
              this.getActive();
        });
        }
      },
      Verifikasi(ireq_id){
      this.code = ireq_id;
      this.ConfirmationVerifikasiManager = true;
      },
      VerifikasiRequestAtasan(ireq_id){
      this.code = ireq_id;
      this.confirmationVerifikasi = true;
      },
      approveAtasan(){
        this.confirmationVerifikasi = false;
         this.$confirm.require({
            message: "Are you sure you approve to this request?",
            header: "Confirmation Approval",
            icon: "pi pi-info-circle",
            acceptClass: "p-button",
            acceptLabel: "Yes",
            rejectLabel: "No",
            accept: () => {
              this.loading = true;
                this.axios.get('/api/updateStatusPermohonan/' +this.code, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
                  this.$toast.add({
                    severity: "info",
                    summary: "Success Message",
                    detail: "Successfully approved this request",
                    life : 1000
                  });
                this.code = null;
                this.getActive();
                });
          },
          reject: () => {},
        });
      },
      rejectRequestAtasan(){
        this.confirmationVerifikasi = false;
        this.dialogRejectAtasan = true;
      },
      cancelRejectAtasan(){
        this.dialogRejectAtasan = false;
        this.code = null;
        this.reason.ket = null;
        this.submitted = false;
      },
      updateRejectAtasan(){
          this.submitted = true;
           if(this.reason.ket != null){
            this.axios.put('/api/updateStatusReject/'+ this.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
              this.dialogRejectAtasan = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
                life: 1000
              });
               this.code = null;
               this.getActive();
            });
          }
      },
      cancel(){
      this.code=null;
      this.dialogEdit = false;
      this.status = [];
      this.editDetail = [];
      this.submitted = false;
      },
      submitt(){
        this.submitted = true;
        if(this.editDetail.status != null){
          this.axios.put('/api/update-status-done/'+this.code, this.editDetail, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
            this.$toast.add({
              severity:'success', summary: 'Success', detail:'Status Berhasil Dirubah', life: 3000
            });
            this.cancel();
            this.getIct4();
          });
        }
      },
      edit(ireqd_id,ireq_id){
        this.code = ireq_id;
        this.dialogEdit = true;
        this.axios.get('/api/detail/'+ ireqd_id+'/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.editDetail = response.data;
        });
        this.getStatus();
      },
      getStatus(){
        this.axios.get('/api/getStatusIct', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.status = response.data;
        });
      },
      approveManager(){
        this.ConfirmationVerifikasiManager = false;
        this.dialogApproveManager = true;
      },
      cancelApproveManager(){
        this.dialogApproveManager = false;
        this.code = null;
        this.reason.remark = null;
      },
      updateApproveManager(){
        this.ConfirmationVerifikasiManager = false;
        this.$toast.add({
          severity: "info",
          summary: "Confirmed",
          detail: "Permohonan Dilanjutkan",
          life : 1000
        });
        this.axios.put('/api/abm/' +this.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.cancelApproveManager();
          this.getActive();
        });
      },
      rejectManager(){
        this.ConfirmationVerifikasiManager = false;
        this.dialogRejectManager = true;
      },
      cancelRejectManager(){
        this.dialogRejectManager = false;
        this.code = null;
        this.reason.ket = null;
        this.submitted = false;
      },
      updateRejectManager(){
            this.submitted = true;
            if(this.reason.ket != null){
              this.axios.put('/api/rbm/'+ this.code, this.reason, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
                this.$toast.add({
                  severity: "info",
                  summary: "Confirmed",
                  detail: "Berhasil Direject",
                  life: 1000
                });
                this.cancelRejectManager();
                this.getActive();
              });
            }
      },
      RemarkReviewer(ireq_id){
      this.loading = true;
      this.remarkreviewer.id = ireq_id;
      this.axios.get('api/get-remark-reviewer/'+ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
        this.remarkreviewer.remark = res.data.ireq_verificator_remark;
        this.dialogRemarkReviewer = true;
        this.loading = false;
      });
      },
      cancelRemarkReviewer(){
        this.remarkreviewer.id = '';
        this.remarkreviewer.remark = '';
        this.dialogRemarkReviewer = false;
      },
      updateRemarkReviewer(){
        this.dialogRemarkReviewer = false;
        this.axios.post('api/save-remark-reviewer',this.remarkreviewer, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
         this.loading = true;
          this.$toast.add({
            severity: "info",
            summary: "Success",
            detail: "successfully added a remark",
            life: 2000,
          });
          this.remarkreviewer = {id:'',remark:''};
          this.getActive();
        });
      },
      createRemarkAssigned(ireqd_id,ireq_id){
        this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.remarkAssigned = response.data;
          this.dialogRemarkAssigned = true;
        });
          this.code = ireqd_id;
      },
      createNoteAssigned(ireqd_id,ireq_id){
        this.axios.get('api/detail/'+ireqd_id+'/'+ireq_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.noteAssigned = response.data;
          this.dialogNoteAssigned = true;
        });
          this.code = ireqd_id;
      },
      submitRemarkAssigned(){
          this.axios.put('/api/save-remark-assigned/'+this.code,this.remarkAssigned,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{ 
          this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
            this.noteAssigned = [];
            this.code = null;
            this.dialogRemarkAssigned = false;
          });
          this.loading = true;
          this.getIct4();
      },
      submitNoteAssigned(){
          this.axios.put('/api/update-note/'+this.code,this.noteAssigned,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{ 
          this.$toast.add({ severity:'success', summary: 'Success', detail:'Success Update', life: 2000 });
            this.noteAssigned = [];
            this.code = null;
            this.dialogNoteAssigned = false;
          });
          this.loading = true;
          this.getIct4();
      },
      cancelRemarkAssigned(){
        this.remarkAssigned = [];
        this.code = null;
        this.dialogRemarkAssigned = false;
      },
      cancelNoteAssigned(){
        this.noteAssigned = [];
        this.code = null;
        this.dialogNoteAssigned = false;
      },
  },
};
</script>
<style lang="scss" scoped>
.attachment-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
.p-button.youtube {
    cursor:pointer;
    background: linear-gradient(to left, var(--pink-600) 50%, var(--pink-700) 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: background-position 0.5s ease-out;
    color: #fff;
    border-color: var(--pink-700);
}
.p-button.youtube:hover {
    background-position: left bottom;
}
.template .p-button.twitter {
    background: linear-gradient(to left, var(--blue-400) 50%, var(--blue-500) 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: background-position 0.5s ease-out;
    color: #fff;
    border-color: var(--blue-500);
}
.template .p-button.twitter:hover {
    background-position: left bottom;
}
.template .p-button.twitter i {
    background-color: var(--blue-500);
}
.template .p-button.twitter:focus {
    box-shadow: 0 0 0 1px var(--blue-200);
}
</style>