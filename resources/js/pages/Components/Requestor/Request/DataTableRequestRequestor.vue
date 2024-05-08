<template>
  <div v-if="isMobile">
    <DataTable
      :value="value"
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
              placeholder="Search..."
            />
          </span>
        </div>
      </template>
      <template #empty> Not Found </template>
      <template #loading> Please wait </template>
      <Column
        field="ireq_no"
        header="No. Request"
        :sortable="true"
        style="min-width: 9rem"
      >
        <template #body="slotProps">
          <p
            @click="detailRequest(slotProps.data.ireq_id)"
            style="cursor: pointer"
          >
            {{ slotProps.data.ireq_no }}
            <i class="pi pi-info-circle"></i>
          </p>
        </template>
      </Column>
      
      <Column header="Action" style="min-width: 9rem">
        <template #body="slotProps">
          <div class="button-group">
            <Button
              class="p-button-rounded p-button-secondary mr-2 mt-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="
                detailTab(slotProps.data.ireq_id, Active, showForDashboard)
              "
            />
            <Button
              v-if="slotProps.data.ireq_status == null"
              class="p-button-rounded p-button-info mr-2 mt-2"
              icon="pi pi-pencil"
              v-tooltip.bottom="'Click to edit request'"
              @click="
                $router.push({
                  name: 'Edit Ict Request',
                  params: { code: slotProps.data.ireq_id },
                })
              "
            />
            <Button
              v-if="slotProps.data.ireq_status == null"
              icon="pi pi-trash"
              class="p-button-rounded p-button-danger mr-2 mt-2"
              @click="DeleteIct(slotProps.data.ireq_id)"
              v-tooltip.bottom="'Click to delete the request'"
            />
            <Button
              v-if="
                slotProps.data.count > 0 && slotProps.data.ireq_status == null
              "
              class="p-button-rounded p-button-success mr-2 mt-2"
              icon="pi pi-check"
              @click="SubmitIct(slotProps.data.ireq_id)"
              v-tooltip.bottom="'Click to submit request'"
            />
          </div>
        </template>
      </Column>
    </DataTable>
    <Dialog
      v-model:visible="dialogDetailRequest"
      :breakpoints="{ '960px': '95vw' }"
      :style="{ width: '600px' }"
      :header="this.header"
      :modal="true"
      class="fluid"
    >
      <table>
        <tr>
          <th>No Request</th>
          <td><InputText type="text" v-model="detail.ireq_no" readonly /></td>
        </tr>
        <tr>
          <th>Request Date</th>
          <td><InputText :value="formattedRequestDate" readonly /></td>
        </tr>
        <tr>
          <th>Requestor</th>
          <td><InputText v-model="detail.ireq_requestor" readonly /></td>
        </tr>
        <tr>
          <th>User</th>
          <td><InputText readonly v-model="detail.ireq_user" /></td>
        </tr>
        <tr>
          <th>User Division</th>
          <td><InputText v-model="detail.usr_division" readonly /></td>
        </tr>
        <tr v-if="detail.countspv > 0">
          <th>Supervisor</th>
          <td><InputText v-model="detail.spv" readonly /></td>
        </tr>
        <tr v-if="detail.countremark_reviewer > 0">
          <th>Remark Reviewer</th>
          <td>
            <InputText v-model="detail.ireq_verificator_remark" readonly />
          </td>
        </tr>
        <tr v-if="detail.ireq_reason">
          <th>Reason</th>
          <td><InputText v-model="detail.ireq_reason" readonly /></td>
        </tr>
        <tr v-if="detail.ireq_assigned_to">
          <th>ICT Personnel</th>
          <td>
            <InputText type="text" v-model="detail.ireq_assigned_to" readonly />
          </td>
        </tr>
        <tr v-if="detail.ireq_status">
          <th>Status</th>
          <td>
            <InputText type="text" v-model="detail.ireq_status" readonly />
          </td>
        </tr>
      </table>
    </Dialog>
  </div>
  <div v-else>
    <DataTable
      :value="value"
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
              placeholder="Search..."
            />
          </span>
        </div>
      </template>
      <template #empty> Not Found </template>
      <template #loading> Please wait </template>
      <Column
        field="ireq_no"
        header="No. Request"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="ireq_date"
        header="Request Date"
        :sortable="true"
        style="min-width: 12rem"
      >
        <template #body="slotProps">
          {{ formatDate(slotProps.data.ireq_date) }}
        </template>
      </Column>
      <Column
        field="ireq_requestor"
        header="Requestor"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="ireq_user"
        header="User"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="usr_division"
        header="User Division"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="spv"
        header="Supervisor"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showSpvColumn"
      />
      <Column
        field="ireq_verificator_remark"
        header="Remark Reviewer"
        :sortable="true"
        v-if="showRemarkColumn"
      />
      <Column
        field="ireq_reason"
        header="Reason"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showReason == '1'"
      />
      <Column
        field="ireq_assigned_to"
        header="ICT Personnel"
        :sortable="true"
        style="min-width: 10rem"
        v-if="showPersonnel1 == '1'"
      />
      <Column
        field="ireq_status"
        header="Status"
        :sortable="true"
        style="min-width: 9rem"
      >
        <template #body="slotProps">
          <span
            :class="
              'user-request status-' + slotProps.data.status.toLowerCase()
            "
            v-if="slotProps.data.status"
            >{{ slotProps.data.ireq_status }}</span
          >
        </template>
      </Column>
      <Column header="Action">
        <template #body="slotProps">
          <Button
            class="p-button-rounded p-button-secondary mr-2 mt-2"
            icon="pi pi-info-circle"
            v-tooltip.bottom="'Click for request details'"
            @click="detailTab(slotProps.data.ireq_id, Active, showForDashboard)"
          />
          <Button
            v-if="slotProps.data.ireq_status == null"
            class="p-button-rounded p-button-info mr-2 mt-2"
            icon="pi pi-pencil"
            v-tooltip.bottom="'Click to edit request'"
            @click="
              $router.push({
                name: 'Edit Ict Request',
                params: { code: slotProps.data.ireq_id },
              })
            "
          />
          <Button
            v-if="slotProps.data.ireq_status == null"
            icon="pi pi-trash"
            class="p-button-rounded p-button-danger mr-2 mt-2"
            @click="DeleteIct(slotProps.data.ireq_id)"
            v-tooltip.bottom="'Click to delete the request'"
          />
          <Button
            v-if="
              slotProps.data.count > 0 && slotProps.data.ireq_status == null
            "
            class="p-button-rounded p-button-success mt-2 mr-2"
            icon="pi pi-check"
            @click="SubmitIct(slotProps.data.ireq_id)"
            v-tooltip.bottom="'Click to submit request'"
          />
        </template>
      </Column>
      <template #footer>
        <div class="grid p-dir-col">
          <div class="col">
            <div class="box">
              <Button
                v-if="showForDashboardFooter"
                label="Back"
                class="p-button-raised p-button mr-2"
                icon="pi pi-chevron-left"
                @click="$router.push({ name: 'Dashboard' })"
              />
              <Button
                label="Pdf"
                class="p-button-raised p-button-danger mr-2"
                icon="pi pi-file-pdf"
                @click="PrintRequestListByStatusPdf(printPdf)"
              />
              <!-- <Button label="Excel" class="p-button-raised p-button-success mr-2" icon="pi pi-print"
                                @click="CetakExcelPermohonan()" /> -->
            </div>
          </div>
        </div>
      </template>
    </DataTable>
  </div>
</template>

<script>
export default {
  emits: ["show-loading", "hide-loading", "get-data"],
  props: {
    value: Array,
    loading: Boolean,
    printPdf: String,
    showSpv: Array,
    showRemark: Array,
    showReason: String,
    showPersonnel1: String,
    Active: String,
    showForDashboard: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    showForDashboardFooter() {
      return this.showForDashboard == true;
    },
    showSpvColumn() {
      return this.showSpv && this.showSpv.some((el) => el > 0);
    },
    showRemarkColumn() {
      return this.showRemark && this.showRemark.some((el) => el > 0);
    },
    formattedRequestDate() {
      return this.formatDate(this.detail.ireq_date);
    },
  },
  mounted() {
    this.isMobile = window.innerWidth <= 768;
    window.addEventListener("resize", this.updateIsMobile);
  },
  data() {
    return {
      header: null,
      dialogDetailRequest: false,
      Type: {
        report_type: "",
      },
      isMobile: false,
      detail: [],
      filters: {
        global: { value: null, matchMode: this.$FilterMatchMode.CONTAINS },
      },
    };
  },
  methods: {
    detailRequest(ireq_id) {
      const requestData = this.value.find((item) => item.ireq_id === ireq_id);
      this.header = " Detail Request No. " + requestData.ireq_no;
      this.detail = requestData;
      this.dialogDetailRequest = true;
    },
    updateIsMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
    DeleteIct(code) {
      this.$confirm.require({
        message: "Are you sure you want to delete this record data?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.axios.delete("api/delete-ict/" + code).then(() => {
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Record deleted",
              life: 3000,
            });
            this.loading = true;
            this.$emit("get-data");
          });
        },
        reject: () => {},
      });
    },
    SubmitIct(code) {
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$emit("show-loading");
          this.axios.get("api/updateStatusSubmit/" + code).then(() => {
            this.$emit("get-data");
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Successfully Submit",
              life: 3000,
            });
          });
        },
        reject: () => {},
      });
    },
    PrintRequestListByStatusPdf(type) {
      this.$emit("show-loading");
      this.Type.report_type = type;
      this.axios
        .post("api/print-out-pdf-requestor", this.Type)
        .then((response) => {
          let htmlContent = response.data.data.htmlContent;
          const options = {
            filename: "ICT Request List.pdf",
            jsPDF: {
              unit: "mm",
              format: "a4",
              orientation: "landscape",
            },
          };

          this.$html2pdf().set(options).from(htmlContent).save();
          this.$emit("hide-loading");
        });
    },
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    detailTab(ireq_id, Active, showForDashboard) {
      if (showForDashboard === true) {
        this.$router.push({
          path: "/ict-request-detail/" + ireq_id,
          query: {
            showForDashboard: true,
          },
        });
      } else {
        localStorage.setItem("active", Active);
        this.$router.push("/ict-request-detail/" + ireq_id);
      }
    },
  },
};
</script>
<style lang="scss" scoped>
.button-group {
  display: flex;
  flex-wrap: wrap;
}

.button-group .p-button {
  margin: 5px; /* Ruang antara tombol */
}
th {
  padding-right: 10px; /* Adjust the value as needed */
}
</style>
