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
      <Column header="Action" headerStyle="min-width:10rem">
        <template #body="slotProps">
          <div class="button-group">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="TabToDetail(slotProps.data.ireq_id, Active)"
            />
            <Button
              v-if="slotProps.data.status == 'NA2'"
              class="p-button-rounded p-button-success mr-2"
              icon="pi pi-check-square"
              v-tooltip.bottom="'Click to verification'"
              @click="RequestVerification(slotProps.data.ireq_id)"
            />
          </div>
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
            </div>
          </div>
        </div>
      </template>
    </DataTable>
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
        style="min-width: 9rem"
      />
      <Column
        field="ireq_date"
        header="Request Date"
        :sortable="true"
        style="min-width: 10rem"
      >
        <template #body="slotProps">
          {{ formatDate(slotProps.data.ireq_date) }}
        </template>
      </Column>
      <Column
        field="ireq_requestor"
        header="Requestor"
        :sortable="true"
        style="min-width: 8rem"
      />
      <Column
        field="ireq_user"
        header="User"
        :sortable="true"
        style="min-width: 8rem"
      />
      <Column
        field="usr_division"
        header="User Division"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="spv"
        header="User Supervisor"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showSpvColumn"
      />
      <Column
        field="ireq_reason"
        header="Reason"
        :sortable="true"
        style="min-width: 10rem"
        v-if="showReason == '1'"
      />
      <Column
        field="ireq_verificator_remark"
        header="Remark Reviewer"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showRemarkReviewerColumn"
      />
      <Column
        field="ireq_approver2_remark"
        header="Remark ICT manager"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showRemarkManagerColumn"
      />
      <Column
        field="ireq_assigned_to"
        header="ICT Personnel"
        :sortable="true"
        style="min-width: 10rem"
        v-if="showPersonnel1Column"
      />
      <Column
        field="ireq_assigned_to1_reason"
        header="Rejected Reason"
        :sortable="true"
        style="min-width: 12rem"
        v-if="showReasonPersonnelColumn"
      />
      <Column
        field="ireq_status"
        header="Status"
        :sortable="true"
        style="min-width: 12rem"
      >
        <template #body="slotProps">
          <span
            :class="
              'status-bagde status-' + slotProps.data.status.toLowerCase()
            "
            >{{ slotProps.data.ireq_status }}</span
          >
        </template>
      </Column>
      <Column headerStyle="min-width:10rem">
        <template #body="slotProps">
          <div class="button-group">
            <Button
              class="p-button-rounded p-button-secondary mr-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="TabToDetail(slotProps.data.ireq_id, Active)"
            />
            <Button
              v-if="slotProps.data.status == 'NA1'"
              class="p-button-rounded p-button-success mr-2"
              icon="pi pi-check-square"
              v-tooltip.bottom="'Click to verification'"
              @click="RequestVerification(slotProps.data.ireq_id)"
            />
          </div>
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
            </div>
          </div>
        </div>
      </template>
    </DataTable>
  </div>

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
  <Dialog
    v-model:visible="dialogReject"
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
            Reason Not Filled
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
    v-model:visible="dialogApprove"
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
      <Button label="Yes" @click="approve()" class="p-button" autofocus />
      <Button label="No" @click="cancelApprove()" class="p-button-text" />
    </template>
  </Dialog>
  <Dialog
    header="Confirmation"
    v-model:visible="ConfirmationVerifikasi"
    :style="{ width: '350px' }"
    :modal="true"
  >
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span>Verification Request</span>
    </div>
    <template #footer>
      <Button
        label="Reject"
        icon="pi pi-times"
        @click="rejectRequest"
        class="p-button-raised p-button-danger p-button-text"
      />
      <Button
        label="Approve"
        icon="pi pi-check"
        @click="approveRequest"
        class="p-button-raised p-button-text"
        autofocus
      />
    </template>
  </Dialog>
</template>

<script>
export default {
  emits: ["show-loading", "hide-loading", "get-data"],
  props: {
    value: Array,
    loading: Boolean,
    printPdf: String,
    showSpv: Array,
    showRemarkReviewer: Array,
    showRemarkManager: Array,
    showReason: String,
    showPersonnel1: Array,
    showReasonPersonnel: Array,
    Active: String,
    showForDashboard: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    formattedRequestDate() {
      return this.formatDate(this.detail.ireq_date);
    },
    showForDashboardFooter() {
      return this.showForDashboard == true;
    },
    showSpvColumn() {
      return this.showSpv && this.showSpv.some((el) => el > 0);
    },
    showRemarkReviewerColumn() {
      return (
        this.showRemarkReviewer && this.showRemarkReviewer.some((el) => el > 0)
      );
    },
    showRemarkManagerColumn() {
      return (
        this.showRemarkManager && this.showRemarkManager.some((el) => el > 0)
      );
    },
    showPersonnel1Column() {
      return this.showPersonnel1 && this.showPersonnel1.some((el) => el > 0);
    },
    showReasonPersonnelColumn() {
      return (
        this.showReasonPersonnel &&
        this.showReasonPersonnel.some((el) => el > 0)
      );
    },
  },
  mounted() {
    this.isMobile = window.innerWidth <= 768;
    window.addEventListener("resize", this.updateIsMobile);
  },
  data() {
    return {
      dialogReject: false,
      dialogApprove: false,
      ConfirmationVerifikasi: false,
      isMobile: false,
      reason: {
        ket: null,
        remark: null,
      },
      filters: {
        global: { value: null, matchMode: this.$FilterMatchMode.CONTAINS },
      },
      Type: {
        report_type: "",
      },
      submitted: false,
      code: null,
      dialogDetailRequest: false,
      detail: [],
      header: null,
    };
  },
  methods: {
    detailRequest(ireq_id = null) {
      const requestData = this.value.find((item) => item.ireq_id === ireq_id);
      this.header = " Detail Request No. " + requestData.ireq_no;
      this.detail = requestData;
      this.dialogDetailRequest = true;
    },
    updateIsMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
    TabToDetail(ireq_id = null, Active) {
      localStorage.setItem("active2", Active);
      this.$router.push("/ict-request-higher-level-detail/" + ireq_id);
    },
    PrintRequestListByStatusPdf(type = null) {
      this.$emit("show-loading");
      this.Type.report_type = type;
      this.axios
        .post("api/print-out-pdf-higher-level", this.Type)
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
    RequestVerification(ireq_id = null) {
      this.code = ireq_id;
      this.ConfirmationVerifikasi = true;
    },
    approve() {
      this.$toast.add({
        severity: "info",
        summary: "Success Message",
        detail: "Successfully approved this request",
        life: 1000,
      });
      this.axios.put("/api/updateStatusPermohonan/" + this.code).then(() => {
        this.cancelApprove();
        this.$emit("show-loading");
        this.$emit("get-data");
      });
    },
    rejectRequest() {
      this.ConfirmationVerifikasi = false;
      this.dialogReject = true;
    },
    approveRequest() {
      this.ConfirmationVerifikasi = false;
      this.dialogApprove = true;
    },
    cancelReject() {
      this.dialogReject = false;
      this.code = null;
      this.reason.ket = null;
      this.submitted = false;
    },
    cancelApprove() {
      this.dialogApprove = false;
      this.code = null;
      this.reason.remark = null;
    },
    updateReject() {
      this.submitted = true;
      if (this.reason.ket != null) {
        this.axios
          .put("/api/updateStatusReject/" + this.code, this.reason)
          .then(() => {
            this.dialogReject = false;
            this.$toast.add({
              severity: "info",
              summary: "Success Message",
              detail: "Successfully rejected this request",
              life: 1000,
            });
            this.cancelReject();
            this.$emit("show-loading");
            this.$emit("get-data");
          });
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
  margin: 5px;
}
th {
  padding-right: 10px;
}
</style>
