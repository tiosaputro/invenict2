<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4" v-if="this.active == 1">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Belum Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 1"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
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
                v-tooltip.right="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 2">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Diverifikasi)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 2"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 3">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Direject)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 3"
          :value="diReject"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                v-tooltip.left="'Detail'"
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 4">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 4"
          :value="sdgDikerjakan"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 5">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 5"
          :value="sdhDikerjakan"
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
        <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 6">
          <template v-slot:start>
			      <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 6"
          :value="sdhSelesai"
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
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 7">
          <template v-slot:start>
			      <h4>ICT Request (Request Yang Menunggu Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 7"
          :value="permohonan"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
                />
              <Button
                class="p-button-rounded p-button-success mr-2"
                icon="pi pi-check-square"
                v-tooltip.right="'Verifikasi'"
                @click="VerifikasiRequestAtasan(slotProps.data.ireq_id)"
              />
            </template>
          </Column>
           <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 8">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 8"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>\
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 9">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Direject)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 9"
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
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 10">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 10"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 11">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 11"
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
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable> 
        <Toolbar class="mb-4" v-if="this.active == 12">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 12"
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
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 13">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Belum Diverifikasi)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 13"
          :value="blmDiverifikasi"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:8rem"/>
          <Column style="min-width:50rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-sm p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
                @click="$router.push({
                  name: 'Ict Request Detail Desc',
                  params: { code: slotProps.data.ireq_id }, })"
              />
              <Button
                v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                class="p-button-raised p-button-sm p-button-text mr-2"
                @click="Reject(slotProps.data.ireq_id)"
                label="Reject"
              />
              <Button
                v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                class="p-button-raised p-button-sm p-button-text"
                @click="ApproveAtasan(slotProps.data.ireq_id)"
                label="Persetujuan Atasannya"
              />
              <Button
                v-if="slotProps.data.ireq_count_status != slotProps.data.ireq_count_id"
                class="p-button-raised p-button-sm p-button-text"
                @click="ApproveManager(slotProps.data.ireq_id)"
                label="Persetujuan ICT Manager"
              />
              <Button
                class="p-button-raised p-button-sm p-button-text p-button-sm mt-2"
                @click="AssignPerRequest(slotProps.data.ireq_id)"
                label="Assign Per-Request"
              />
              <Button
                class="p-button-raised p-button-sm p-button-text p-button-sm mt-2"
                @click="$router.push({
                  name: 'Ict Request Desc Assign Per Detail',
                  params : {code: slotProps.data.ireq_id},})"
                label="Assign Per-Detail"
              />
              <Button
                v-if="slotProps.data.ireq_count_status == slotProps.data.ireq_count_id"
                class="p-button-raised p-button-text p-button-sm p-button-sm mr-2"
                @click="Submit(slotProps.data.ireq_id)"
                label="Submit"
              />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 14">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 14"
          :value="sudahDiassign"
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
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 15">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 15"
          :value="sudahDikerjakann"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable> 
        <Toolbar class="mb-4" v-if="this.active == 16">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 16"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>     
        <Toolbar class="mb-4" v-if="this.active == 17">
          <template v-slot:start>>
                <h4>ICT Request (Request Yang Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 17"
          :value="sedngDikerjakan"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Petugas (ICT)" :sortable="true" style="min-width:8rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
                @click="$router.push({
                  name: 'Ict Request Desc Detail Divisi 3',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 18">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 18"
          :value="sudhDikerjakan"
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
          <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
                @click="$router.push({
                  name: 'Ict Request Desc Detail Divisi 3',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template>
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 19">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 19"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:8rem"/>
          <Column field="ireqd_id" header="No. Detail" :sortable="true" style="min-width:8rem"/>
          <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column style="min-width:12rem">
            <!-- <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                @click="$router.push({
                  name: 'Ict Request Desc Detail Divisi 3',
                  params: { code: slotProps.data.ireq_id }, })"
                />
            </template> -->
          </Column>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>     
        <Toolbar class="mb-4" v-if="this.active == 20">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 20"
          :value="sdHDikerjakan4"
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
          <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Petugas(ICT)" :sortable="true" style="min-width:8rem"/>
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 21">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 21"
          :value="selesai4"
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
          <Column field="invent_code" header="Nama Peripheral" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
            <template #body="slotProps">
              {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
          <Column field="ireq_assigned_to" header="Petugas(ICT)" :sortable="true" style="min-width:8rem"/>
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 22">
          <template v-slot:start>
                <h4>ICT Request (Total Keseluruhan Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 22"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 23">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Belum Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 23"
          :value="ictAdmin"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
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
                v-tooltip.Right="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 24">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Diverifikasi)</h4>
          </template>
        </Toolbar>   
        <DataTable
          v-if="this.active == 24"
          :value="sdhDiverifikasiAdmin"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 25">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Direject)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 25"
          :value="diRejectAdmin"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 26">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 26"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
                v-tooltip.left="'Detail'"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 27">
          <template v-slot:start>
                <h4>ICT Request (Request Yang Sudah Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 27"
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
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 28">
          <template v-slot:start>
			      <h4>ICT Request (Request Yang Sudah Selesai)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 28"
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
          <template #footer>
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 29">
          <template v-slot:start>
                <h4>ICT Request (Total Keseluruhan Request)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 29"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 30">
          <template v-slot:start>
                <h4>ICT Request (Atasan Divisi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 30"
          :value="atasanDivisi"
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
                  <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:8rem">
                    <template #body="slotProps">
                      {{ formatDate(slotProps.data.ireq_date) }}
                    </template>
                  </Column>
                  <Column field="ireq_requestor" header="Pemohon" :sortable="true" style="min-width:8rem"/>
                  <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:8rem"/>
                  <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:10rem"/>
                  <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem"/>
                  <Column headerStyle="min-width:35rem">
                  <template #body="slotProps">
                      <Button
                        class="p-button-rounded p-button-secondary mr-2"
                        icon="pi pi-info-circle"
                        v-tooltip.left="'Detail'"
                        @click="$router.push({
                            name: 'Ict Request Detail Desc',
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
                            name: 'Ict Request Desc Assign Per Detail',
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
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 31">
          <template v-slot:start>
                <h4>ICT Request (ICT Manager)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 31"
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
        <Column field="ireq_status" header="Status" :sortable="true" style="min-width:10rem"/>
        <Column headerStyle="min-width:30rem">
          <template #body="slotProps">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.left="'Detail'"
              @click="$router.push({
                name: 'Ict Request Detail Desc',
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
                name: 'Ict Request Desc Assign Per Detail',
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
            <div class="grid dir-col">
              <div class="col">
                <div class="box">
                  <Button
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 32">
          <template v-slot:start>
                <h4>ICT Request (Direject)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 32"
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
            Loading ICT Request data. Please wait.
        </template>
          <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
         <Toolbar class="mb-4" v-if="this.active == 33">
          <template v-slot:start>
                <h4>ICT Request (Belum Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 33"
          :value="blmDiverifikasi4"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 34">
          <template v-slot:start>
                <h4>ICT Request (Sudah Diverifikasi)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 34"
          :value="sdhDiverifikasi4"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 35">
          <template v-slot:start>
                <h4>ICT Request (Direject)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 35"
          :value="direject4"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_reason" header="Alasan" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_status" header="Status" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Toolbar class="mb-4" v-if="this.active == 36">
          <template v-slot:start>
                <h4>ICT Request (Sedang Dikerjakan)</h4>
          </template>
        </Toolbar>
        <DataTable
          v-if="this.active == 36"
          :value="sdgDikerjakan4"
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
          <Column field="ireq_date" header="Tgl.Request" :sortable="true" style="min-width:12rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
          </Column>
          <Column field="ireq_user" header="Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="div_name" header="Divisi Pengguna" :sortable="true" style="min-width:12rem"/>
          <Column field="ireq_assigned_to" header="Petugas ICT" :sortable="true" style="min-width:12rem"/>
          <Column style="min-width:12rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-secondary mr-2"
                icon="pi pi-info-circle"
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
                    label="Kembali"
                    class="p-button-raised p-button mr-2"
                    icon="bi bi-skip-backward-fill"
                    @click="$router.push({
                    name: 'Dashboard'})"
                  />
                </div>
			      </div>
          </div>
        </template>
        </DataTable>
        <Dialog v-model:visible="dialogAssign"
          :style="{ width: '400px' }"
          header="Assign Per-Request"
          :modal="true"
          :closable="false"
          class="field grid"
        >
        <div class="field grid">
            <label style="width:100px">Petugas (ICT)</label>
              <div class="col-3 md-6">
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
        <Dialog header="Confirmation" v-model:visible="confirmationVerifikasi" :style="{width: '350px'}" :modal="true">
          <div class="confirmation-content">
            <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
              <span>Verifikasi Request</span>
          </div>
          <template #footer>
            <Button label="Reject" icon="pi pi-times" @click="rejectRequestAtasan" class="p-button-raised p-button-danger p-button-text"/>
            <Button label="Approve" icon="pi pi-check" @click="approveAtasan" class="p-button-raised p-button-text" autofocus />
          </template>
        </Dialog>
        <Dialog v-model:visible="dialogRejectAtasan" :breakpoints="{'960px': '75vw'}" :style="{ width: '400px' }" header="Form Dialog Reject" :modal="true" class="field grid">
         <div class="field"> 
          <div class="field grid">
            <label class="col-fixed w-9rem">Alasan</label>
              <div class="fol-fixed">
                <Textarea
                  :autoResize="true"
                  type="text"
                  v-model="reason.ket"
                  rows="5" 
                  placeholder="Masukan Alasan"
                  :class="{ 'p-invalid': submitted && !reason.ket }"
                />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Alasan Harus Diisi
                  </small>
              </div>
            </div>
          </div>
          <template #footer>
            <Button label="Yes" @click="updateRejectAtasan()" class="p-button" autofocus />
            <Button label="No" @click="cancelRejectAtasan()" class="p-button-text" />
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
        atasanDivisi:[],
        ictManager:[],
        direject2:[],
        dialogReject: false,
        active : 1,
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
        sedngDikerjakan:[],
        sudhDikerjakan: [],
        selesaiii: [],
        blmDiverifikasi4:[],
        sdhDiverifikasi4:[],
        direject4:[],
        sdgDikerjakan4:[],
        sdHDikerjakan4:[],
        selesai4:[],
        assign:{ id:null, name: null },
        total:[],
        petugas:[],
        submitted: false,
        dialogAssign: false,
        filters: { 'global': {value: null, matchMode: FilterMatchMode.CONTAINS} },
        usr_name: localStorage.getItem('usr_name'),
        user:[],
        rbr:{ ket:null, id:null },
        confirmationVerifikasi:false,
        dialogRejectAtasan:false,
        code:null,
        reason:{ ket:null },
    };
  },
  mounted() {
    this.getActive();
  },
  methods: {
    formatDate(date) {
      return moment(date).format("DD MMM YYYY")
    },
      getActive(){
        if(localStorage.getItem('active')){
          this.active = localStorage.getItem('active');
          if (this.active <= 6){
              this.getIct();
          }
          else if (this.active >= 7 && this.active <= 12){
            this.getIct2();
          } 
          else if (this.active > 12 && this.active <=16){
            this.getIct3();
          }
          else if (this.active > 16 && this.active <=19){
            this.getUser();
          }
          else if (this.active > 19 && this.active <=21){
            this.getIct5();
          }
          else if (this.active == 22){
            this.getIct6();
          }
          else if (this.active > 22 && this.active <= 29){
            this.getIct7();
          }
          else if (this.active > 29 && this.active <= 32){
            this.getIct3();
          }
          else if (this.active > 32 && this.active <= 36){
            this.getIct5();
          }
        }
      },
      getIct(){
        this.axios.get('api/get-ict/'+this.usr_name,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.ict = response.data.ict7;
          this.sdhDiverifikasi = response.data.ict1;
          this.diReject = response.data.ict2;
          this.sdgDikerjakan = response.data.ict3;
          this.sdhDikerjakan = response.data.ict4;
          this.sdhSelesai = response.data.ict5;
          this.loading = false;
        });
      },
      getIct2(){
        this.axios.get('/api/get-permohonan/'+this.usr_name,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.permohonan = response.data.ict;
        this.verif = response.data.ict1;
        this.reject = response.data.ict2;
        this.sedangDikerjakan = response.data.ict3;
        this.sudahDikerjakan = response.data.ict4;
        this.selesai = response.data.ict5;
        this.loading = false;
        });
      },
      getIct3(){
        this.axios.get('api/get-permohonan-divisi',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
          this.blmDiverifikasi = response.data.ict1;
          this.atasanDivisi = response.data.ict2;
          this.ictManager = response.data.ict3;
          this.direject2 = response.data.ict4;
          this.sudahDiassign = response.data.ict5;
          this.sudahDikerjakann = response.data.ict6;
          this.sudahslsi = response.data.ict7;
          this.loading = false;
        });
      },
      getUser(){
        this.axios.get('api/user',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.user = response.data;
          this.getIct4();
        });
      },
      getIct4(){
        this.axios.get('api/get-sedang-dikerjakan/'+this.user.usr_fullname,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.sedngDikerjakan = response.data.ict;
        this.sudhDikerjakan = response.data.ict1;
        this.selesaiii = response.data.ict2;
        this.loading = false;
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
        this.loading = false;
        });
      },
      getIct6(){
        this.axios.get('api/total-request/'+this.usr_name, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.total = response.data;
          this.loading = false;
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
            this.axios.get('/api/updateStatusClosingDetail/' +ireqd_id + '/' + ireq_no, {headers: {'Authorization': 'Bearer '+this.token}});
           this.getActive();
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
          this.getActive();
        },
        reject: () => {},
      })
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
          this.getActive();
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
          this.getActive();
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
      },
      updateReject(){
        this.submmited = true;
        this.axios.put('/api/reject-by-reviewer/'+this.rbr.id, this.rbr, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
            this.dialogReject = false;    
            this.rbr.id = null;
            this.rbr.ket = null;
            this.submmited = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Direject",
                life: 2000,
              });
              this.getActive();
        });
      },
      VerifikasiRequestAtasan(ireq_id){
      this.code = ireq_id;
      this.confirmationVerifikasi = true;
      },
      approveAtasan(){
        this.confirmationVerifikasi = false;
        this.$confirm.require({
              message: "Approval Permohonan Dilanjutkan?",
              header: "ICT Request    ",
              icon: "pi pi-info-circle",
              acceptClass: "p-button",
              acceptLabel: "Ya",
              rejectLabel: "Tidak",
              accept: () => {
                this.$toast.add({
                  severity: "info",
                  summary: "Confirmed",
                  detail: "Permohonan Dilanjutkan",
                  life : 1000
                });
                this.axios.get('/api/updateStatusPermohonan/' +this.code, {headers: {'Authorization': 'Bearer '+this.token}});
                this.code = null;
                this.getActive();
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
  },
};
</script>