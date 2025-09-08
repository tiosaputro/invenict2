<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog />
        <Toolbar class="mb-4" v-if="this.desc == 1">
          <template v-slot:start>
            <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 1" :value="ict" :loading="loading"
          :showRemark="showRemarkRequestorWaiting" :showSpv="showSpvRequestorWaiting" :filters="filters"
          printPdf="tab_reviewer" @get-data="getIct" :showForDashboard=true Active="1" />
        <Toolbar class="mb-4" v-if="this.desc == 2">
          <template v-slot:start>
            <h4>ICT Request (Already verified)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 2" :value="sdhDiverifikasi" :loading="loading"
          :showRemark="showRemarkRequestorVerified" :showSpv="showSpvRequestorVerified" :filters="filters"
          printPdf="verifikasi" @get-data="getIct" :showForDashboard=true Active="2" />
        <Toolbar class="mb-4" v-if="this.desc == 3">
          <template v-slot:start>
            <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 3" :value="diReject" :loading="loading"
          :showRemark="showRemarkRequestorRejected" :showSpv="showSpvRequestorRejected" :filters="filters"
          printPdf="reject" @get-data="getIct" :showForDashboard=true Active="3" />
        <Toolbar class="mb-4" v-if="this.desc == 4">
          <template v-slot:start>
            <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 4" :value="sdgDikerjakan" :loading="loading"
          :showRemark="showRemarkRequestorInProgress" :showSpv="showSpvRequestorInProgress" :filters="filters"
          printPdf="sedang_dikerjakan" @get-data="getIct" :showForDashboard=true Active="5" />
        <Toolbar class="mb-4" v-if="this.desc == 5">
          <template v-slot:start>
            <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorDetail v-if="this.desc == 5" :value="sdhDikerjakan" :showForDashboard=true
          @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading"
          printPdf="sudah_dikerjakan" />
        <Toolbar class="mb-4" v-if="this.desc == 6">
          <template v-slot:start>
            <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorDetail v-if="this.desc == 6" :value="sdhSelesai" :showForDashboard=true
          @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading"
          printPdf="selesai" />
        <Toolbar class="mb-4" v-if="this.desc == 7">
          <template v-slot:start>
            <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 7" :value="permohonan" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Waiting for verification"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
              <Button v-if="slotProps.data.ireq_statuss == 'NA1'" class="p-button-rounded p-button-success mr-2"
                icon="pi pi-check-square" v-tooltip.bottom="'Click to Verification'"
                @click="VerifikasiRequestAtasan(slotProps.data.ireq_id)" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 8" :value="verif" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Already verified)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 9" :value="reject" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 10" :value="sedangDikerjakan" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 11" :value="sudahDikerjakan" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem" />
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 12" :value="selesai" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Close)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem" />
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTableReviewerRequest v-if="this.desc == 13" :value="blmDiverifikasi" :loading="loading"
          :showRemark="ForReviewer.showRemarkblmDiverifikasi" :showSpv="ForReviewer.showSpvblmDiverifikasi"
          printPdf="permohonan" :showPersonnel1="ForReviewer.showPersonelblmDiverifikasi" showTotalDetail="1"
          @get-data="getIct3" Active="0" @show-loading="showLoading" @hide-loading="hideLoading"
          :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 14">
          <template v-slot:start>
            <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerRequest v-if="this.desc == 14" :value="sudahDiassign" :loading="loading"
          :showRemark="ForReviewer.showRemarkSudahDiassign" :showSpv="ForReviewer.showSpvSudahDiassign"
          printPdf="sedang_dikerjakan" :showPersonnel1="ForReviewer.showPersonelSudahDiassign" @get-data="getIct3"
          Active="5" @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 15">
          <template v-slot:start>
            <h4>ICT Request (Done)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerDetail v-if="this.desc == 15" :value="sudahDikerjakann" @get-data="getIct" :loading="loading"
          @show-loading="showLoading" @hide-loading="hideLoading" printPdf="sudah_dikerjakan" />
        <Toolbar class="mb-4" v-if="this.desc == 16">
          <template v-slot:start>
            <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerDetail v-if="this.desc == 16" :value="sudahslsi" @get-data="getIct" :loading="loading"
          @show-loading="showLoading" @hide-loading="hideLoading" printPdf="selesai" />
        <Toolbar class="mb-4" v-if="this.desc == 46">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 46" :value="assignmentRequest3" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Assignment Request)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 47" :value="rejected3" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:8rem" />
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_to1_reason" header="Reason" :sortable="true" style="min-width:10rem" />
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 17" :value="sedngDikerjakan" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (In Progress)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_remark" header="Remark Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_remark" header="Remark Assigned" :sortable="true" style="min-width:12rem" />
          <Column style="min-width:15rem">
            <template #body="slotProps">
              <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil" v-tooltip.bottom="'Click to change status'"
                @click="edit(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
              <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-help mr-2"
                icon="bi bi-journal-text" v-tooltip.bottom="'Click to create note'"
                @click="createNoteAssigned(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
              <Button v-if="slotProps.data.status == 'T'" class="p-button-rounded p-button-danger mr-2"
                icon="bi bi-journals" v-tooltip.bottom="'Click to create remark'"
                @click="createRemarkAssigned(slotProps.data.ireqd_id, slotProps.data.ireq_id)" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 18" :value="sudhDikerjakan" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Done)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem" />
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 19" :value="selesaiii" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="invent_code" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_remark" header="Remark" :sortable="true" style="min-width:10rem" />
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTableManagerDetail v-if="this.desc == 20" :value="sdHDikerjakan4" @show-loading="showLoading"
          @hide-loading="hideLoading" @get-data="getIct5" :showForDashboard=true :loading="loading"
          printPdf="sudah_dikerjakan" />
        <Toolbar class="mb-4" v-if="this.desc == 21">
          <template v-slot:start>
            <h4>ICT Request (Close)</h4>
          </template>
        </Toolbar>
        <DataTableManagerDetail v-if="this.desc == 21" :value="selesai4" @show-loading="showLoading"
          @hide-loading="hideLoading" @get-data="getIct5" :showForDashboard=true :loading="loading"
          printPdf="selesai" />
        <Toolbar class="mb-4" v-if="this.desc == 22">
          <template v-slot:start>
            <h4>ICT Request (Overall Request)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 22" :value="total" :loading="loading"
          :showRemark="showRemarkRequestorTotal" :showSpv="showSpvRequestorTotal" :filters="filters"
          printPdf="verifikasi" @get-ict="getIct" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 23">
          <template v-slot:start>
            <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 23" :value="ictAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Waiting for verification)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:10rem">
            <template #body="slotProps">
              <Button v-if="slotProps.data.ireq_status == null" class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil" v-tooltip.left="'Edit'" @click="
                  $router.push({
                    name: 'Edit Ict Request',
                    params: { code: slotProps.data.ireq_id },
                  })" />
              <Button v-if="slotProps.data.ireq_status == null" icon="pi pi-trash" v-tooltip.bottom="'Delete'"
                class="p-button-rounded p-button-danger mr-2" @click="DeleteIct(slotProps.data.ireq_id)" />
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 24" :value="sdhDiverifikasiAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Already verified)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 25" :value="diRejectAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Rejected)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_reason" header="Reason" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 26" :value="sdgDikerjakanAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:12rem" />
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 27" :value="sdhDikerjakanAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 28" :value="sdhSelesaiAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireqd_id" header="No.Detail" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_type" header="Request Type" :sortable="true" style="min-width:10rem" />
          <Column field="kategori" header="Items" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_qty" header="Qty" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column header="Attachment" style="min-width:10rem">
            <template #body="slotProps">
              <p v-if="slotProps.data.ireq_attachment == null"></p>
              <p
                v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'jpeg' || slotProps.data.ireq_attachment.split('.').pop() == 'jpg' || slotProps.data.ireq_attachment.split('.').pop() == 'png'">
                <Button class="twitter p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Twitter"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-images px-2"></i>
                  <span class="px-3">IMAGE</span>
                </Button>
              </p>
              <p v-else-if="slotProps.data.ireq_attachment.split('.').pop() == 'pdf'">
                <Button class="youtube p-0" @click="getDetail(slotProps.data.ireq_attachment)" aria-label="Youtube"
                  v-tooltip.bottom="'Click to detail attachment'">
                  <i class="pi pi-file-pdf px-2"></i>
                  <span class="px-4">PDF</span>
                </Button>
              </p>
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTable v-if="this.desc == 29" :value="totalAdmin" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:12rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTableReviewerRequest v-if="this.desc == 30" :value="atasanDivisi" :loading="loading"
          :showRemark="ForReviewer.showRemarkAtasanDivisi" :showSpv="ForReviewer.showSpvAtasanDivisi"
          printPdf="tab_reviewer" :showPersonnel1="ForReviewer.showPersonelAtasanDivisi" showTotalDetail="1"
          @get-data="getIct3" Active="1" @show-loading="showLoading" @hide-loading="hideLoading"
          :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 31">
          <template v-slot:start>
            <h4>ICT Request (ICT Manager)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerRequest v-if="this.desc == 31" :value="ictManager" :loading="loading"
          :showRemark="ForReviewer.showRemarkIctManager" :showSpv="ForReviewer.showSpvIctManager" printPdf="verifikasi"
          :showPersonnel1="ForReviewer.showPersonelIctManager" showTotalDetail="1" @get-data="getIct3" Active="2"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 32">
          <template v-slot:start>
            <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerRequest v-if="this.desc == 32" :value="direject2" :loading="loading"
          :showRemark="ForReviewer.showRemarkDireject2" :showSpv="ForReviewer.showSpvDireject2" printPdf="reject"
          :showPersonnel1="ForReviewer.showPersonelDireject2" showTotalDetail="1" @get-data="getIct3" Active="3"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true showReason="1" />
        <Toolbar class="mb-4" v-if="this.desc == 33">
          <template v-slot:start>
            <h4>ICT Request (Waiting for verification)</h4>
          </template>
        </Toolbar>
        <DataTableManagerRequest v-if="this.desc == 33" :value="blmDiverifikasi4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewerblmDiverifikasi4"
          :showRemarkManager="ForIctManager.showRemarkManagerblmDiverifikasi4"
          :showSpv="ForIctManager.showSpvblmDiverifikasi4" printPdf="permohonan"
          :showPersonnel1="ForIctManager.showPersonnelblmDiverifikasi4" @get-data="getIct5" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 34">
          <template v-slot:start>
            <h4>ICT Request (Already Verified)</h4>
          </template>
        </Toolbar>
        <DataTableManagerRequest v-if="this.desc == 34" :value="sdhDiverifikasi4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewersdhDiverifikasi4"
          :showRemarkManager="ForIctManager.showRemarkManagersdhDiverifikasi4"
          :showSpv="ForIctManager.showSpvsdhDiverifikasi4" printPdf="verifikasi"
          :showPersonnel1="ForIctManager.showPersonnelsdhDiverifikasi4" @get-data="getIct5" Active="1"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 35">
          <template v-slot:start>
            <h4>ICT Request (Rejected)</h4>
          </template>
        </Toolbar>
        <DataTableManagerRequest v-if="this.desc == 35" :value="direject4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewerdireject4"
          :showRemarkManager="ForIctManager.showRemarkManagerdireject4" :showSpv="ForIctManager.showSpvdireject4"
          printPdf="reject" :showPersonnel1="ForIctManager.showPersonneldireject4" @get-data="getIct5" Active="2"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true showReason="1" />
        <Toolbar class="mb-4" v-if="this.desc == 36">
          <template v-slot:start>
            <h4>ICT Request (In Progress)</h4>
          </template>
        </Toolbar>
        <DataTableManagerRequest v-if="this.desc == 36" :value="sdgDikerjakan4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewersdgDikerjakan4"
          :showRemarkManager="ForIctManager.showRemarkManagerpenugasanRequest4"
          :showSpv="ForIctManager.showSpvsdgDikerjakan4" printPdf="assignment_request"
          :showPersonnel1="ForIctManager.showPersonnelsdgDikerjakan4" @get-data="getIct5" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 37">
          <template v-slot:start>
            <h4>ICT Request (Overhall Request)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerRequest v-if="this.desc == 37" :value="totalRequest2" :loading="loading"
          :showRemark="ForReviewer.showRemarkTotalRequest2" :showSpv="ForReviewer.showSpvTotalRequest2"
          printPdf="permohonan" :showPersonnel1="ForReviewer.showPersonelTotalRequest2" @get-data="getIct" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 38">
          <template v-slot:start>
            <h4>ICT Request (Overhall Request)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 38" :value="totalRequest1" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request" responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTableManagerRequest v-if="this.desc == 39" :value="totalRequest4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewertotalRequest4"
          :showRemarkManager="ForIctManager.showRemarkManagertotalRequest4"
          :showSpv="ForIctManager.showSpvtotalRequest4" printPdf="assignment_request"
          :showPersonnel1="ForIctManager.showPersonneltotalRequest4" @get-data="getIct5" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 40">
          <template v-slot:start>
            <h4>ICT Request (Under review)</h4>
          </template>
        </Toolbar>
        <DataTableRequestorRequest v-if="this.desc == 40" :value="sedangDireview" :loading="loading"
          :showRemark="showRemarkRequestorPermohonan" :showSpv="showSpvRequestorPermohonan" :filters="filters"
          printPdf="permohonan" @get-ict="getIct" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 41">
          <template v-slot:start>
            <h4>ICT Request (Under review)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 41" :value="sedangDireview1" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request (Under review)"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.ireq_statuss.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
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
        <DataTableManagerRequest v-if="this.desc == 42" :value="sedangDireview2" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewersedangDireview2"
          :showRemarkManager="ForIctManager.showRemarkManagerDireview2" :showSpv="ForIctManager.showSpvDireview2"
          printPdf="permohonan" :showPersonnel1="ForIctManager.showPersonnelDireview2" @get-data="getIct5" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 43">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTableReviewerRequest v-if="this.desc == 43" :value="penugasanRequest2" :loading="loading"
          :showRemark="ForReviewer.showRemarkPenugasanRequest2" :showSpv="ForReviewer.showSpvPenugasanRequest2"
          printPdf="assignment_request" :showPersonnel1="ForReviewer.showPersonelPenugasanRequest2" @get-data="getIct3"
          Active="4" @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 44">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTableManagerRequest v-if="this.desc == 44" :value="penugasanRequest4" :loading="loading"
          :showRemarkReviewer="ForIctManager.showRemarkReviewerpenugasanRequest4"
          :showRemarkManager="ForIctManager.showRemarkManagerpenugasanRequest4"
          :showSpv="ForIctManager.showSpvpenugasanRequest4" printPdf="assignment_request"
          :showPersonnel1="ForIctManager.showPersonnelpenugasanRequest4" @get-data="getIct5" Active="0"
          @show-loading="showLoading" @hide-loading="hideLoading" :showForDashboard=true />
        <Toolbar class="mb-4" v-if="this.desc == 45">
          <template v-slot:start>
            <h4>ICT Request (Assignment Request)</h4>
          </template>
        </Toolbar>
        <DataTable v-if="this.desc == 45" :value="penugasanRequest1" :paginator="true" :rows="10" :loading="loading"
          :filters="filters" :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Assignment Request"
          responsiveLayout="scroll">
          <template #header>
            <div class="table-header text-right">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Please wait
          </template>
          <Column field="ireq_no" header="No.Request" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
          <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
          <Column field="usr_division" header="Divison User" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_assigned_to" header="Personnel (ICT)" :sortable="true" style="min-width:10rem" />
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              <span
                :class="'status-bagde status-' + slotProps.data.status.toLowerCase()">{{ slotProps.data.ireq_status }}</span>
            </template>
          </Column>
          <Column>
            <template #body="slotProps">
              <Button class="p-button-rounded p-button-secondary mr-2" icon="pi pi-info-circle"
                v-tooltip.bottom="'Click for request details'" @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id },
                })" />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button label="Back" class="p-button-raised p-button mr-2" icon="pi pi-chevron-left" @click="$router.push({
                    name: 'Dashboard'
                  })" />
                </div>
              </div>
            </div>
          </template>
        </DataTable>
      </div>
    </div>
  </div>
</template>
<script>
import DataTableRequestorRequest from '../../Components/Requestor/Request/DataTableRequestRequestor.vue';
import DataTableRequestorDetail from '../../Components/Requestor/Request/DataTableDetailRequestor.vue';
import DataTableReviewerRequest from '../../Components/Reviewer/Request/DataTableRequestReviewer.vue';
import DataTableReviewerDetail from '../../Components/Reviewer/Request/DataTableDetailReviewer.vue';
import DataTableManagerRequest from '../../Components/IctManager/Request/DataTableRequestIctManager.vue';
import DataTableManagerDetail from '../../Components/IctManager/Request/DataTableDetailIctManager.vue';

export default {
  components: {
    DataTableRequestorRequest,
    DataTableRequestorDetail,
    DataTableReviewerRequest,
    DataTableReviewerDetail,
    DataTableManagerRequest,
    DataTableManagerDetail
  },
  data() {
    return {
      ForReviewer: {
        showRemarkblmDiverifikasi: [],
        showSpvblmDiverifikasi: [],
        showPersonelblmDiverifikasi: [],
        showRemarkAtasanDivisi: [],
        showSpvAtasanDivisi: [],
        showPersonelAtasanDivisi: [],
        showRemarkIctManager: [],
        showSpvIctManager: [],
        showPersonelIctManager: [],
        showRemarkDireject2: [],
        showSpvDireject2: [],
        showPersonelDireject2: [],
        showRemarkPenugasanRequest2: [],
        showSpvPenugasanRequest2: [],
        showPersonelPenugasanRequest2: [],
        showRemarkSudahDiassign: [],
        showSpvSudahDiassign: [],
        showPersonelSudahDiassign: [],
        showRemarkTotalRequest2: [],
        showSpvTotalRequest2: [],
        showPersonelTotalRequest2: [],
      },
      ForIctManager: {
        showRemarkReviewersedangDireview2: [],
        showRemarkManagerDireview2: [],
        showSpvDireview2: [],
        showPersonnelDireview2: [],
        showRemarkReviewersedangsdhDiverifikasi4: [],
        showRemarkManagersdhDiverifikasi4: [],
        showSpvsdhDiverifikasi4: [],
        showPersonnelsdhDiverifikasi4: [],
        showRemarkReviewerdireject4: [],
        showRemarkManagerdireject4: [],
        showSpvdireject4: [],
        showPersonneldireject4: [],
        showRemarkReviewersdgDikerjakan4: [],
        showRemarkManagersdgDikerjakan4: [],
        showSpvsdgDikerjakan4: [],
        showPersonnelsdgDikerjakan4: [],
        showRemarkReviewersdHDikerjakan4: [],
        showRemarkManagersdHDikerjakan4: [],
        showSpvsdHDikerjakan4: [],
        showPersonnelsdHDikerjakan4: [],
        showRemarkReviewerselesai4: [],
        showRemarkManagerselesai4: [],
        showSpvselesai4: [],
        showPersonnelselesai4: [],
        showRemarkReviewertotalRequest4: [],
        showRemarkManagertotalRequest4: [],
        showSpvtotalRequest4: [],
        showPersonneltotalRequest4: [],
        showRemarkReviewerpenugasanRequest4: [],
        showRemarkManagerpenugasanRequest4: [],
        showSpvpenugasanRequest4: [],
        showPersonnelpenugasanRequest4: []
      },
      showRemarkRequestorPermohonan: [],
      showSpvRequestorPermohonan: [],
      showRemarkRequestorWaiting: [],
      showSpvRequestorWaiting: [],
      showRemarkRequestorVerified: [],
      showSpvRequestorVerified: [],
      showRemarkRequestorRejected: [],
      showSpvRequestorRejected: [],
      showRemarkRequestorTotal: [],
      showSpvRequestorTotal: [],
      showRemarkRequestorInProgress: [],
      showSpvRequestorInProgress: [],
      sedangDireview: [],
      sedangDireview1: [],
      sedangDireview2: [],
      dialogEdit: false,
      atasanDivisi: [],
      ictManager: [],
      penugasanRequest1: [],
      penugasanRequest2: [],
      direject2: [],
      desc: 1,
      loading: true,
      ict: [],
      sdhDiverifikasi: [],
      diReject: [],
      sdgDikerjakan: [],
      sdhDikerjakan: [],
      sdhSelesai: [],
      ictAdmin: [],
      sdhDiverifikasiAdmin: [],
      diRejectAdmin: [],
      sdgDikerjakanAdmin: [],
      sdhDikerjakanAdmin: [],
      sdhSelesaiAdmin: [],
      totalAdmin: [],
      permohonan: [],
      verif: [],
      reject: [],
      sedangDikerjakan: [],
      sudahDikerjakan: [],
      selesai: [],
      sudahslsi: [],
      sudahDikerjakann: [],
      sudahDiassign: [],
      blmDiverifikasi: [],
      assignmentRequest3: [],
      rejected3: [],
      sedngDikerjakan: [],
      sudhDikerjakan: [],
      selesaiii: [],
      blmDiverifikasi4: [],
      sdhDiverifikasi4: [],
      direject4: [],
      penugasanRequest4: [],
      sdgDikerjakan4: [],
      sdHDikerjakan4: [],
      selesai4: [],
      assign: { id: null, name: null },
      total: [],
      petugas: [],
      submitted: false,
      dialogAssign: false,
      filters: { 'global': { value: null, matchMode: this.$FilterMatchMode.CONTAINS } },
      user: [],
      rbr: { ket: '', id: '' },
      remarkreviewer: {
        id: '',
        remark: ''
      },
      code: null,
      reason: { ket: null, remark: null },
      totalRequest2: [],
      totalRequest1: [],
      totalRequest4: [],
    };
  },
  mounted() {
    this.getActive();
  },
  methods: {
    getDetail(ireq_attachment) {
      var page = process.env.MIX_APP_URL + '/attachment_request/' + ireq_attachment;
      var myWindow = window.open(page, "_blank");
      myWindow.focus();
    },
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm")
    },
    getActive() {
      if (localStorage.getItem('desc')) {
        const d = Number(localStorage.getItem('desc'));
        this.desc = d;
        if (!d) return;

        const inSet = (arr) => arr.includes(d);

        if (inSet([1, 2, 3, 4, 5, 6, 22, 40])) return this.getIct();                 // Requestor
        if (inSet([7, 8, 9, 10, 11, 12, 38, 41, 45])) return this.getIct2();          // Requestor Divisi
        if (inSet([13, 14, 15, 16, 30, 31, 32, 37, 43])) return this.getIct3();       // Reviewer
        if (inSet([17, 18, 19, 46, 47])) return this.getIct4();                    // Personnel
        if (inSet([20, 21, 33, 34, 35, 36, 39, 42, 44])) return this.getIct5();        // Manager
        if (inSet([23, 24, 25, 26, 27, 28, 29])) return this.getIct7();              // Admin
      }
    },
    getIct() {
      this.axios.get('api/get-ict').then((response) => {
        this.ict = response.data.ict6;
        this.showRemarkRequestorWaiting = this.ict.map((x) => x.countremark_reviewer);
        this.showSpvRequestorWaiting = this.ict.map((x) => x.countspv);
        this.sdhDiverifikasi = response.data.ict1;
        this.showRemarkRequestorVerified = this.sdhDiverifikasi.map((x) => x.countremark_reviewer);
        this.showSpvRequestorVerified = this.sdhDiverifikasi.map((x) => x.countspv);
        this.diReject = response.data.ict2;
        this.showRemarkRequestorRejected = this.diReject.map((x) => x.countremark_reviewer);
        this.showSpvRequestorRejected = this.diReject.map((x) => x.countspv);
        this.sdgDikerjakan = response.data.ict9;
        this.showRemarkRequestorInProgress = this.sdgDikerjakan.map((x) => x.countremark_reviewer);
        this.showSpvRequestorInProgress = this.sdgDikerjakan.map((x) => x.countspv);
        this.sdhDikerjakan = response.data.ict4;
        this.sdhSelesai = response.data.ict5;
        this.sedangDireview = response.data.ict7;
        this.showRemarkRequestorPermohonan = this.sedangDireview.map((x) => x.countremark_reviewer);
        this.showSpvRequestorPermohonan = this.sedangDireview.map((x) => x.countspv);
        this.total = response.data.ict10;
        this.showRemarkRequestorTotal = this.total.map((x) => x.countremark_reviewer);
        this.showSpvRequestorTotal = this.total.map((x) => x.countspv);
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    getIct2() {
      this.axios.get('/api/get-permohonan').then((response) => {
        this.permohonan = response.data.data.ict7;
        this.sedangDireview1 = response.data.data.ict8;
        this.verif = response.data.data.ict1;
        this.reject = response.data.data.ict2;
        this.penugasanRequest1 = response.data.data.ict9;
        this.sedangDikerjakan = response.data.data.ict3;
        this.sudahDikerjakan = response.data.data.ict4;
        this.selesai = response.data.data.ict5;
        this.totalRequest1 = response.data.data.ict6;
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    getIct3() {
      this.axios.get('api/get-data-reviewer').then((response) => {
        this.blmDiverifikasi = response.data.ict;
        this.ForReviewer.showPersonelblmDiverifikasi = this.blmDiverifikasi.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkblmDiverifikasi = this.blmDiverifikasi.map((x) => x.count_remark);
        this.ForReviewer.showSpvblmDiverifikasi = this.blmDiverifikasi.map((x) => x.ireq_count_spv);

        this.atasanDivisi = response.data.ict1;
        this.ForReviewer.showPersonelAtasanDivisi = this.atasanDivisi.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkAtasanDivisi = this.atasanDivisi.map((x) => x.count_remark);
        this.ForReviewer.showSpvAtasanDivisi = this.atasanDivisi.map((x) => x.ireq_count_spv);

        this.ictManager = response.data.ict2;
        this.ForReviewer.showPersonelIctManager = this.ictManager.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkIctManager = this.ictManager.map((x) => x.count_remark);
        this.ForReviewer.showSpvIctManager = this.ictManager.map((x) => x.ireq_count_spv);

        this.direject2 = response.data.ict3;
        this.ForReviewer.showPersonelDireject2 = this.direject2.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkDireject2 = this.direject2.map((x) => x.count_remark);
        this.ForReviewer.showSpvDireject2 = this.direject2.map((x) => x.ireq_count_spv);

        this.sudahDiassign = response.data.ict4;
        this.ForReviewer.showPersonelSudahDiassign = this.sudahDiassign.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkSudahDiassign = this.sudahDiassign.map((x) => x.count_remark);
        this.ForReviewer.showSpvSudahDiassign = this.sudahDiassign.map((x) => x.ireq_count_spv);

        this.sudahDikerjakann = response.data.ict5;
        this.sudahslsi = response.data.ict6;

        this.totalRequest2 = response.data.ict8;
        this.ForReviewer.showPersonelTotalRequest2 = this.totalRequest2.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkTotalRequest2 = this.totalRequest2.map((x) => x.count_remark);
        this.ForReviewer.showSpvTotalRequest2 = this.totalRequest2.map((x) => x.ireq_count_spv);

        this.penugasanRequest2 = response.data.ict7;
        this.ForReviewer.showPersonelPenugasanRequest2 = this.penugasanRequest2.map((x) => x.ireq_count_status);
        this.ForReviewer.showRemarkPenugasanRequest2 = this.penugasanRequest2.map((x) => x.count_remark);
        this.ForReviewer.showSpvPenugasanRequest2 = this.penugasanRequest2.map((x) => x.ireq_count_spv);
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    getIct4() {
      this.axios.get('api/get-sedang-dikerjakan').then((res) => {
        this.sedngDikerjakan = res.data.data.ict;
        this.sudhDikerjakan = res.data.data.ict1;
        this.selesaiii = res.data.data.ict2;
        this.assignmentRequest3 = res.data.data.ict3;
        this.rejected3 = res.data.data.ict4;
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    getIct5() {
      this.axios.get('api/get-data-manager').then((response) => {
        const data = response.data.data;
        this.updateProperty('ForIctManager', data.ict1, 'sdhDiverifikasi4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict2, 'direject4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict3, 'sdgDikerjakan4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict4, 'sdHDikerjakan4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict5, 'selesai4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict9, 'totalRequest4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict7, 'sedangDireview2', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict8, 'blmDiverifikasi4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');
        this.updateProperty('ForIctManager', data.ict6, 'penugasanRequest4', 'showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel');

        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    updateProperty(ArrayName, dataArray, propertyName, remarkKey, managerKey, spvKey, personnelKey) {
      this[propertyName] = dataArray;
      this[ArrayName][remarkKey + propertyName] = dataArray.map(x => x.count_remark_reviewer);
      this[ArrayName][managerKey + propertyName] = dataArray.map(x => x.count_remark_manager);
      this[ArrayName][spvKey + propertyName] = dataArray.map(x => x.count_spv);
      this[ArrayName][personnelKey + propertyName] = dataArray.map(x => x.count_personnel1);
    },
    getIct6() {
      this.axios.get('api/total-request').then((response) => {
        this.total = response.data;
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    getIct7() {
      this.axios.get('api/get-ict-admin').then((response) => {
        this.ictAdmin = response.data.data.ict;
        this.sdhDiverifikasiAdmin = response.data.data.ict1;
        this.diRejectAdmin = response.data.data.ict2;
        this.sdgDikerjakanAdmin = response.data.data.ict3;
        this.sdhDikerjakanAdmin = response.data.data.ict4;
        this.sdhSelesaiAdmin = response.data.data.ict5;
        this.totalAdmin = response.data.data.ict6;
        this.loading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
      });
    },
    showLoading() {
      this.loading = true;
    },
    hideLoading() {
      this.loading = false;
    },
  },
};
</script>