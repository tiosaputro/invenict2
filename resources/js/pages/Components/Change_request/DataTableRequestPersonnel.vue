<template>
    <DataTable :value="value" :paginator="true" :rows="10" :loading="loading" :filters="filters" :rowHover="true"
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
            Loading ICT Request data. Please wait.
        </template>
        <Column field="ireq_no" header="No. Request" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_date" header="Request Date" :sortable="true" style="min-width:10rem">
            <template #body="slotProps">
                {{ formatDate(slotProps.data.ireq_date) }}
            </template>
        </Column>
        <Column field="ireq_requestor" header="Requestor" :sortable="true" style="min-width:8rem" />
        <Column field="ireq_user" header="User" :sortable="true" style="min-width:8rem" />
        <Column field="usr_division" header="User Division" :sortable="true" style="min-width:10rem" />
        <Column field="ireq_verificator_remark" header="Remark Reviewer" :sortable="true" style="min-width:12rem"
            v-if="this.countRemarkReviewerPenugasan.some(el=> el > 0)" />
        <Column style="min-width:20rem">
            <template #body="slotProps">
                <Button class="p-button-rounded p-button-secondary mr-2 mt-2" icon="pi pi-info-circle"
                    v-tooltip.bottom="'Click for request details'" @click="$router.push({
                          name: 'Ict Request Divisi 3 Detail',
                          params: { code: slotProps.data.ireq_id }, })" />
                <Button class="p-button-rounded p-button-info mr-2 mt-2" icon="pi pi-check"
                    v-tooltip.bottom="'Click for accept request'" @click="acceptRequest(slotProps.data.ireq_id)" />
                <Button class="p-button-rounded p-button-danger mr-2 mt-2" icon="bi bi-x-square"
                    v-tooltip.bottom="'Click for reject request'" @click="rejectRequest(slotProps.data.ireq_id)" />
            </template>
        </Column>
        <template #footer>
            <div class="p-grid p-dir-col">
                <div class="p-col">
                    <div class="box">
                        <Button label="Pdf" class="p-button-raised p-button-danger mr-2" icon="pi pi-file-pdf"
                            @click="CetakPdfAssignmentRequest()" />
                        <Button label="Excel" class="p-button-raised p-button-success mr-2" icon="pi pi-print"
                            @click="CetakExcelAssignmentRequest()" />
                    </div>
                </div>
            </div>
        </template>
    </DataTable>
</template>
<script>
    export default {
        props: {
            value: Array,
            loading: Boolean,
            filters: Object,
        },
        methods:{
            
        }
    }
</script>
