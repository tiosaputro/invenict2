<template>
  <ConfirmDialog> </ConfirmDialog>
  <div v-if="isMobile">
    <Toolbar class="mb-4">
      <template v-slot:start>
        <div class="my-2">
          <h4>ICT Request (Detail)</h4>
        </div>
      </template>
      <template v-slot:end>
        <div v-if="this.kode.ireq_date">
          <label style="width: 110px">No. Request </label>
          <label>: {{ this.kode.noreq }} </label>
          <br />
          <label style="width: 110px">Request Date</label>
          <label>: {{ formatDateWithOutSecond(this.kode.ireq_date) }}</label>
        </div>
      </template>
    </Toolbar>
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
              placeholder="Search. . ."
            />
          </span>
        </div>
      </template>
      <template #empty> Not Found </template>
      <template #loading> Please wait </template>
      <Column header="Description" :sortable="true" style="min-width: 9rem">
        <template #body="slotProps">
          <p @click="detailRequest(slotProps.data.ireqd_id)">
            This request pertains to a
            <b>{{ slotProps.data.ireq_type }}</b> requirement for
            <b>{{ slotProps.data.name }}</b
            >.
            <i class="pi pi-info-circle"></i>
          </p>
        </template>
      </Column>
      <Column header="Action" style="min-width: 6rem">
        <template #body="slotProps">
          <Button
            v-if="
              slotProps.data.status == 'NT' || slotProps.data.status == 'RT'
            "
            class="p-button-raised p-button-text mr-2 mt-2"
            icon="pi pi-user-edit"
            label="Assign"
            @click="AssignPerDetail(slotProps.data.ireqd_id)"
          />
          <Button
            v-if="
              (slotProps.data.ireq_assigned_to2 &&
                slotProps.data.status == 'RT') ||
              (slotProps.data.ireq_assigned_to2 &&
                slotProps.data.status == 'NT')
            "
            class="p-button-raised p-button-text mr-2 mt-2"
            icon="pi pi-check"
            label="Submit"
            @click="Submit(slotProps.data.ireqd_id)"
          />
          <Button
            v-if="slotProps.data.status == 'P'"
            class="p-button-rounded p-button-info mr-2"
            icon="pi pi-pencil"
            v-tooltip.bottom="'Click to edit request'"
            @click="
              $router.push({
                name: 'Ict Request Reviewer Edit Detail Permohonan',
                params: {
                  code: this.$route.params.code,
                  ireq: slotProps.data.ireqd_id,
                },
              })
            "
          />
        </template>
      </Column>
      <template #footer>
        <div class="grid dir-col">
          <div class="col">
            <div class="box">
              <Button
                v-if="showForDashboardFooter"
                label="Back"
                class="p-button-raised p-button mr-2"
                icon="pi pi-chevron-left"
                @click="
                  $router.push({
                    name: 'Dashboard',
                  })
                "
              />
              <Button
                v-if="showForDashboardFooter == false"
                label="Back"
                class="p-button-raised p-button mr-2"
                v-tooltip.bottom="'Click to back'"
                icon="pi pi-chevron-left"
                @click="
                  $router.push({
                    name: 'Ict Request Reviewer',
                  })
                "
              />
              <Button
                label="Pdf"
                class="p-button-raised p-button-danger mr-2"
                v-tooltip.bottom="'Click to print out (PDF)'"
                icon="pi pi-file-pdf"
                @click="CetakPdf()"
              />
              <Button
                class="p-button-raised p-button-success mr-2 mt-2"
                icon="pi pi-calendar"
                label="Add Calendar"
                @click="AddToCalendar()"
                v-if="kode.cekstatus == 'P'"
                v-tooltip.bottom="'Click to Add Calendar'"
              />
            </div>
          </div>
        </div>
      </template>
    </DataTable>
  </div>
  <div v-else>
    <Toolbar class="mb-4">
      <template v-slot:start>
        <div class="my-2">
          <h4>ICT Request (Detail)</h4>
        </div>
      </template>
      <template v-slot:end>
        <div v-if="this.kode.ireq_date">
          <label style="width: 110px">No. Request </label>
          <label>: {{ this.kode.noreq }} </label>
          <br />
          <label style="width: 110px">Request Date</label>
          <label>: {{ formatDate(this.kode.ireq_date) }}</label>
        </div>
      </template>
    </Toolbar>
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
              placeholder="Search. . ."
            />
          </span>
        </div>
      </template>
      <template #empty> Not Found </template>
      <template #loading>
        Loading ICT Request (Detail) data. Please wait.
      </template>
      <Column
        field="ireq_type"
        header="Request Type"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="name"
        header="Items"
        :sortable="true"
        style="min-width: 8rem"
      />
      <Column
        field="ireq_qty"
        header="Qty"
        :sortable="true"
        style="min-width: 6rem"
      />
      <Column
        field="ireq_remark"
        header="Remark"
        :sortable="true"
        style="min-width: 10rem"
      />
      <Column
        field="ireq_assigned_to1"
        header="ICT Personnel (1)"
        :sortable="true"
        style="min-width: 10rem"
        v-if="showPersonnel1Column"
      />
      <Column
        field="ireq_assigned_to1_reason"
        header="Reason"
        :sortable="true"
        style="min-width: 8rem"
        v-if="showPersonnel1ReasonColumn"
      />
      <Column
        field="ireq_assigned_to2"
        header="ICT Personnel (2)"
        :sortable="true"
        style="min-width: 8rem"
        v-if="showPersonnel2Column"
      />
      <Column
        field="ireq_status"
        header="Status"
        :sortable="true"
        style="min-width: 10rem"
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
      <Column style="min-width: 15rem">
        <template #body="slotProps">
          <Button
            v-if="
              slotProps.data.status == 'NT' || slotProps.data.status == 'RT'
            "
            class="p-button-raised p-button-text mr-2 mt-2"
            icon="pi pi-user-edit"
            label="Assign"
            @click="AssignPerDetail(slotProps.data.ireqd_id)"
          />
          <Button
            v-if="
              (slotProps.data.ireq_assigned_to2 &&
                slotProps.data.status == 'RT') ||
              (slotProps.data.ireq_assigned_to2 &&
                slotProps.data.status == 'NT')
            "
            class="p-button-raised p-button-text mr-2 mt-2"
            icon="pi pi-check"
            label="Submit"
            @click="Submit(slotProps.data.ireqd_id)"
          />
          <Button
            v-if="slotProps.data.status == 'P'"
            class="p-button-rounded p-button-info mr-2"
            icon="pi pi-pencil"
            v-tooltip.bottom="'Click to edit request'"
            @click="
              $router.push({
                name: 'Ict Request Reviewer Edit Detail Permohonan',
                params: {
                  code: this.$route.params.code,
                  ireq: slotProps.data.ireqd_id,
                },
              })
            "
          />
        </template>
      </Column>
      <template #footer>
        <div class="grid dir-col">
          <div class="col">
            <div class="box">
              <Button
                v-if="showForDashboardFooter"
                label="Back"
                class="p-button-raised p-button mr-2"
                icon="pi pi-chevron-left"
                @click="
                  $router.push({
                    name: 'Dashboard',
                  })
                "
              />
              <Button
                v-if="showForDashboardFooter == false"
                label="Back"
                class="p-button-raised p-button mr-2"
                v-tooltip.bottom="'Click to back'"
                icon="pi pi-chevron-left"
                @click="
                  $router.push({
                    name: 'Ict Request Reviewer',
                  })
                "
              />
              <Button
                label="Pdf"
                class="p-button-raised p-button-danger mr-2"
                v-tooltip.bottom="'Click to print out (PDF)'"
                icon="pi pi-file-pdf"
                @click="CetakPdf()"
              />
              <Button
                class="p-button-raised p-button-success mr-2 mt-2"
                icon="pi pi-calendar"
                label="Add Calendar"
                @click="AddToCalendar()"
                v-if="kode.cekstatus == 'P'"
                v-tooltip.bottom="'Click to Add Calendar'"
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
      <tbody>
      <tr>
        <th>No Request</th>
        <td><InputText type="text" v-model="detail.ireq_no" readonly /></td>
      </tr>
      <tr>
        <th>No. Detail</th>
        <td><InputText :value="detail.ireqd_id" readonly /></td>
      </tr>
      <tr>
        <th>Request Type</th>
        <td><InputText :value="detail.ireq_type" readonly /></td>
      </tr>
      <tr>
        <th>Items</th>
        <td><InputText :value="detail.name" readonly /></td>
      </tr>
      <tr>
        <th>Qty</th>
        <td><InputText :value="detail.ireq_qty" readonly /></td>
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
        <td><InputText v-model="detail.ireq_verificator_remark" readonly /></td>
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
        <td><InputText type="text" v-model="detail.ireq_status" readonly /></td>
      </tr>
    </tbody>
    </table>
  </Dialog>
  <Dialog
    v-model:visible="dialogAssign"
    :style="{ width: '500px' }"
    header="Assign Per-Request"
    :modal="true"
    :closable="false"
    class="fluid"
  >
    <div class="field grid">
      <label class="col-fixed w-9rem">No Request</label>
      <div class="col-fixed">
        <InputText v-model="assign.ireq_no" disabled />
      </div>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">No Detail</label>
      <div class="col-fixed">
        <InputText v-model="assign.ireqd_id" disabled />
      </div>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">Request Type</label>
      <div class="col-fixed">
        <InputText v-model="assign.request_type" disabled />
      </div>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">Items</label>
      <div class="col-fixed">
        <InputText v-model="assign.name" disabled />
      </div>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">Qty</label>
      <div class="col-fixed">
        <InputText v-model="assign.ireq_qty" disabled />
      </div>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">Remark</label>
      <div class="col-fixed">
        <Textarea v-model="assign.ireq_remark" disabled autoResize />
      </div>
    </div>
    <div class="field grid" v-if="assign.status == 'NT'">
      <label class="col-fixed w-9rem">Reason</label>
      <div class="col-fixed">
        <Textarea
          v-model="assign.ireq_assigned_to1_reason"
          :autoResize="true"
          rows="5"
          cols="20"
          placeholder="Enter Reason"
          :class="{
            'p-invalid': submitted && !assign.ireq_assigned_to1_reason,
          }"
        />
      </div>
      <small
        v-if="submitted && !assign.ireq_assigned_to1_reason"
        class="p-error"
      >
        Reason not filled
      </small>
    </div>
    <div class="field grid">
      <label class="col-fixed w-9rem">Petugas (ICT)</label>
      <div class="col-fixed">
        <Dropdown
          v-model="assign.ireq_assigned_to2"
          :options="petugas"
          optionValue="code"
          optionLabel="name"
          placeholder="Select One"
          :class="{ 'p-invalid': submitted && !assign.ireq_assigned_to2 }"
        />
        <small v-if="submitted && !assign.ireq_assigned_to1" class="p-error">
          Petugas(ICT) not filled
        </small>
      </div>
    </div>
    <template #footer>
      <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
      <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
    </template>
  </Dialog>
</template>
<script>
export default {
  emits: ["show-loading", "hide-loading", "get-data"],
  props: {
    value: Array,
    loading: Boolean,
    filters: Object,
    kode: Object,
    showPersonnel1: Array,
    showPersonnel1Reason: Array,
    showPersonnel2: Array,
    showForDashboard: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      submitted: false,
      dialogAssign: false,
      assign: [],
      petugas: [],
      detail: [],
      detailCalendar: [],
      code: null,
      dialogDetailRequest: false,
      detail: [],
      isMobile: false,
      header: null,
    };
  },
  computed: {
    showPersonnel1Column() {
      return this.showPersonnel1 && this.showPersonnel1.some((el) => el > 0);
    },
    showPersonnel2Column() {
      return this.showPersonnel2 && this.showPersonnel2.some((el) => el > 0);
    },
    showPersonnel1ReasonColumn() {
      return (
        this.showPersonnel1Reason &&
        this.showPersonnel1Reason.some((el) => el > 0)
      );
    },
    showForDashboardFooter() {
      return this.showForDashboard === true;
    },
    formattedRequestDate() {
      return this.formatDate(this.detail.ireq_date);
    },
  },
  mounted() {
    this.isMobile = window.innerWidth <= 768;
    window.addEventListener("resize", this.updateIsMobile);
  },
  methods: {
    detailRequest(ireqd_id) {
      const requestData = this.value.find((item) => item.ireqd_id === ireqd_id);
      this.header = " Detail Request No. " + requestData.ireq_no;
      this.detail = requestData;
      this.dialogDetailRequest = true;
    },
    updateIsMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    formatDateWithOutSecond(date) {
      return this.$moment(date).format("DD MMM YYYY");
    },
    CetakPdf() {
      this.$emit("show-loading");
      this.axios
        .get("/api/print-out-ict-request/" + this.$route.params.code, {
          headers: { Authorization: "Bearer " + this.token },
        })
        .then((response) => {
          let htmlContent = response.data.htmlContent;
          let RequestNo = response.data.norequest;
          const options = {
            filename: "Form ICT Request No. " + RequestNo + ".pdf",
            jsPDF: {
              unit: "mm",
              format: "a4",
              orientation: "landscape",
              width: 210,
              height: 297,
            },
          };

          this.$html2pdf().set(options).from(htmlContent).save();
          this.$emit("hide-loading");
        });
    },
    AddToCalendar() {
      const remark = this.detail.map((x) => x.ireq_remark);
      const event = {
        title:
          "Reminder Request A/n " +
          this.detailCalendar.ireq_requestor +
          " No Request : " +
          this.detailCalendar.noreq,
        description: "Detail Request:\n" + remark,
        start: this.detailCalendar.ireq_date,
        location: this.detailCalendar.loc_desc,
        duration: [1, "day"],
      };
      window.location.assign(ics(event));
    },
    AssignPerDetail(ireqd_id) {
      this.axios
        .get("/api/detail/" + ireqd_id + "/" + this.$route.params.code)
        .then((response) => {
          this.assign = response.data.data.dtl;
          this.petugas = response.data.data.pekerja;
        });
      this.dialogAssign = true;
    },
    updateAssign() {
      this.submitted = true;
      this.code = this.assign.ireqd_id;
      if (this.assign.status == "RT") {
        if (this.assign.ireq_assigned_to1 != null) {
          this.axios
            .put(
              "/api/updateAssignPerDetailFromReject/" + this.code,
              this.assign
            )
            .then(() => {
              this.assign = [];
              this.dialogAssign = false;
              this.submitted = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Assign",
                life: 3000,
              });
              this.$emit("show-loading");
              this.$emit("get-data");
            });
        }
      } else {
        if (
          this.assign.ireq_assigned_to1 != null &&
          this.assign.ireq_assigned_to1_reason != null
        ) {
          this.axios
            .put(
              "/api/updateAssignPerDetailFromReject/" + this.code,
              this.assign
            )
            .then(() => {
              this.assign = [];
              this.dialogAssign = false;
              this.submitted = false;
              this.$toast.add({
                severity: "info",
                summary: "Confirmed",
                detail: "Berhasil Assign",
                life: 3000,
              });
              this.$emit("get-data");
            });
        }
      }
    },
    Submit(ireqd_id) {
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Success Message",
            detail: "Success Submit",
            life: 1000,
          });
          this.axios.get(
            "/api/appd/" + ireqd_id + "/" + this.$route.params.code
          );

          this.$emit("get-data");
          this.$emit("show-loading");
        },
        reject: () => {},
      });
    },
    cancelAssign() {
      this.submitted = false;
      this.assign = [];
      this.dialogAssign = false;
    },
  },
};
</script>