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
      <Column headerStyle="min-width:8rem">
        <template #body="slotProps">
          <SplitButton label="Action" class="p-button p-button-success p-button-small" :model="GetItems(slotProps)" />
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
        style="min-width: 10rem"
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
        v-if="showRemarkColumn"
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
        field="ireq_count_id"
        header="Total Detail"
        :sortable="true"
        style="min-width: 10rem"
        v-if="showTotalDetail == '1'"
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
      <Column headerStyle="min-width:9rem">
        <template #body="slotProps">
          <SplitButton label="Action" class="p-button p-button-success p-button-small" :model="GetItems(slotProps)" />
          <!-- <div class="button-group">
            <Button
              class="p-button-rounded p-button-secondary mr-2 mt-2"
              icon="pi pi-info-circle"
              v-tooltip.bottom="'Click for request details'"
              @click="TabToDetail(slotProps.data.ireq_id, Active)"
            />
            <Button
              v-if="slotProps.data.status == 'P'"
              class="p-button-rounded p-button-secondary mr-2 mt-2"
              icon="pi pi-pencil"
              v-tooltip.bottom="'Click for Edit Supervisor'"
              @click="editDataSpv(slotProps.data.ireq_id)"
            />
            <Button
              v-if="slotProps.data.status == 'P'"
              class="p-button-rounded p-button-warning mr-2 mt-2"
              @click="
                SendEmail(slotProps.data.usr_email, slotProps.data.ireq_id)
              "
              icon="bi bi-envelope-check-fill"
              v-tooltip.bottom="
                'Click to send email to ' + slotProps.data.usr_email
              "
            />
            <Button
              v-if="slotProps.data.status == 'P'"
              class="p-button-rounded p-button-danger mr-2 mt-2"
              @click="Reject(slotProps.data.ireq_id)"
              v-tooltip.bottom="'Click to reject request'"
              icon="bi bi-x-square"
            />
            <Button
              v-if="
                slotProps.data.status !== 'RR' &&
                slotProps.data.status !== 'RA1' &&
                slotProps.data.status !== 'RA2' &&
                slotProps.data.status !== 'D' &&
                slotProps.data.status !== 'C'
              "
              icon="bi bi-chat-quote"
              class="p-button-rounded p-button mr-2 mt-2"
              @click="Remark(slotProps.data.ireq_id)"
              v-tooltip.bottom="'Click to add remark'"
            />
            <Button
              v-if="
                slotProps.data.ireq_count_status !=
                  slotProps.data.ireq_count_id &&
                slotProps.data.ireq_count_spv > 0 &&
                slotProps.data.status == 'P'
              "
              class="p-button-rounded mr-2 mt-2"
              @click="ApproveAtasan(slotProps.data.ireq_id)"
              icon="bi bi-file-earmark-arrow-up"
              v-tooltip.bottom="'Click to higher level approval'"
            />
            <Button
              v-if="
                (slotProps.data.ireq_count_status !=
                  slotProps.data.ireq_count_id &&
                  slotProps.data.ireq_count_spv > 0 &&
                  slotProps.data.status == 'P') ||
                slotProps.data.status === 'A1'
              "
              class="p-button-rounded mr-2 mt-2"
              @click="ApproveManager(slotProps.data.ireq_id)"
              v-tooltip.bottom="'Click to ICT manager approval'"
              icon="bi bi-file-earmark-arrow-up-fill"
            />
            <Button
              v-if="
                slotProps.data.status === 'P' ||
                slotProps.data.status === 'A1' ||
                slotProps.data.status === 'A2' ||
                slotProps.data.status == 'RT'
              "
              class="p-button-rounded mr-2 mt-2"
              @click="
                AssignPerRequest(
                  slotProps.data.ireq_id,
                  slotProps.data.ireq_assigned_to1
                )
              "
              icon="bi bi-person-workspace"
              v-tooltip.bottom="'Click to Assign Per Request'"
            />
            <Button
              v-if="
                slotProps.data.status === 'P' ||
                slotProps.data.status === 'A1' ||
                slotProps.data.status === 'A2'
              "
              class="p-button-rounded mr-2 mt-2"
              @click="
                $router.push({
                  name: 'Ict Request Reviewer Assign Per Detail',
                  params: { code: slotProps.data.ireq_id },
                })
              "
              icon="bi bi-people"
              v-tooltip.bottom="'Click to Assign Per Detail'"
            />
            <Button
              v-if="
                slotProps.data.ireq_count_status ==
                  slotProps.data.ireq_count_id &&
                slotProps.data.status !== 'NT' &&
                slotProps.data.status !== 'RT' &&
                slotProps.data.status !== 'T' &&
                slotProps.data.status !== 'C'
              "
              class="p-button-rounded p-button-success mr-2 mt-2"
              @click="Submit(slotProps.data.ireq_id)"
              icon="bi bi-send-check"
              v-tooltip.bottom="'Click to submit'"
            />
            <Button
              v-if="
                slotProps.data.count_assigned2 > 0 &&
                slotProps.data.status == 'RT'
              "
              class="p-button-rounded p-button-success mr-2 mt-2"
              @click="Submit(slotProps.data.ireq_id)"
              icon="bi bi-send-check"
              v-tooltip.bottom="'Click to submit'"
            />
          </div> -->
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
              <!-- <Button
                label="Excel"
                class="p-button-raised p-button-success mr-2"
                icon="pi pi-file-excel"
                @click="PrintRequestListByStatusExcel(printExcel)"
              /> -->
            </div>
          </div>
        </div>
      </template>
    </DataTable>
  </div>

  <Dialog v-model:visible="dialogEditSpv"
    :style="{ width: '400px' }"
    header="Form Dialog Edit SPV"
    :modal="true"
    class="fluid grid"
  >
    <div class="p-fluid">
      <div class="field grid">
        <label class="col-fixed w-9rem" style="width: 100px">Supervisor</label>
        <div class="col">
          <Dropdown
            v-model="editSpv.ireq_spv"
            :options="listSpv"
            optionValue="usr_domain"
            :filter="true"
            optionLabel="name"
            placeholder="Choose One"
            :showClear="true"
          />
        </div>
      </div>
    </div>
    <div class="p-fluid">
      <div class="field grid">
        <div class="col">
          <Checkbox v-model="editSpv.ireq_spv_acting" binary variant="filled" />
        <label for="ingredient1" class="ml-2"> Acting </label>
        </div>
      </div>
    </div>
    <template #footer>
      <Button label="Save" @click="submitSpv()" class="p-button" autofocus />
      <Button label="Cancel" @click="cancelSpv()" class="p-button-text" />
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
        <label class="col-fixed w-9rem" style="width: 100px">Reason</label>
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
            optionValue="code"
            :filter="true"
            optionLabel="name"
            placeholder="Choose One"
            :showClear="true"
          />
        </div>
      </div>
    </div>
    <template #footer>
      <Button label="Save" @click="updateAssign()" class="p-button" autofocus />
      <Button label="Cancel" @click="cancelAssign()" class="p-button-text" />
    </template>
  </Dialog>
  <Dialog v-model:visible="dialogRemark"
    :style="{ width: '400px' }"
    header="Form Dialog Remark"
    :modal="true"
    class="fluid grid"
  >
    <div class="p-fluid">
      <div class="field grid">
        <label class="col-fixed w-9rem" style="width: 100px">Remark</label>
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
  <Dialog v-model:visible="dialogDetailRequest"
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
          <th>Request Date</th>
          <td><InputText :value="formattedRequestDate" readonly/></td>
        </tr>
        <tr>
          <th>Requestor</th>
          <td><InputText v-model="detail.ireq_requestor" readonly/></td>
        </tr>
        <tr>
          <th>User</th>
          <td><InputText readonly v-model="detail.ireq_user"/></td>
        </tr>
        <tr>
          <th>User Division</th>
          <td><InputText v-model="detail.usr_division" readonly/></td>
        </tr>
        <tr v-if="detail.ireq_count_spv > 0">
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
</template>

<script>
export default {
  emits: ["show-loading", "hide-loading", "get-data"],
  props: {
    value: Array,
    loading: Boolean,
    printExcel: String,
    printPdf: String,
    showSpv: Array,
    showRemark: Array,
    showReason: String,
    showTotalDetail: String,
    showPersonnel1: Array,
    showPersonnel2: Array,
    showReason: String,
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
    showRemarkColumn() {
      return this.showRemark && this.showRemark.some((el) => el > 0);
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
  data() {
    return {
      filters: {
        global: { value: null, matchMode: this.$FilterMatchMode.CONTAINS },
      },
      Type: {
        report_type: "",
      },
      isMobile: false,
      editSpv: [],
      listSpv: [],
      dialogSendMail: false,
      dialogAssign: false,
      dialogRemark: false,
      dialogEditSpv: false,
      dialogDetailRequest: false,
      detail: [],
      header: null,
      remark: {
        remark: "",
        id: "",
      },
      displayDetailRequest: false,
      submitted: false,
      assign: {
        id: null,
        name: null,
      },
      petugas: [],
      dialogReject: false,
      rbr: {
        ket: null,
        id: null,
      },
    };
  },
  mounted() {
    this.isMobile = window.innerWidth <= 768;
    window.addEventListener("resize", this.updateIsMobile);
  },
  methods: {
    GetItems(slotProps){
      const items = [
        {
          label: 'Detail Request',
          icon: "pi pi-info-circle",
          command: () => {
            this.TabToDetail(slotProps.data.ireq_id, this.Active);
          }
        }
      ];

      if (slotProps.data.status === 'P') {
        items.push(
          {
            label: 'Update Supervisor',
            icon: "pi pi-pencil",
            command: () => {
              this.editDataSpv(slotProps.data.ireq_id);
            }
          },
          {
            label: 'Send Mail',
            icon: "bi bi-envelope-check-fill",
            command: () => {
              this.SendEmail(slotProps.data.usr_email, slotProps.data.ireq_id);
            }
          },
          {
            label: 'Reject Request',
            icon: "bi bi-x-square",
            command: () => {
              this.Reject(slotProps.data.ireq_id);
            }
          },
        );
      }

      if (!['RR', 'RA1', 'RA2', 'D', 'C'].includes(slotProps.data.status)) {
        items.push({
          label: 'Add Remark',
          icon: "bi bi-chat-quote",
          command: () => {
            this.Remark(slotProps.data.ireq_id);
          }
        });
      }

      if ( slotProps.data.ireq_count_status !== slotProps.data.ireq_count_id && slotProps.data.ireq_count_spv > 0 && slotProps.data.status === 'P') {
        items.push({
          label: 'Higher Level Approval',
          icon: "bi bi-file-earmark-arrow-up",
          command: () => {
            this.ApproveAtasan(slotProps.data.ireq_id);
          }
        });
      }

      if ( slotProps.data.ireq_count_status != slotProps.data.ireq_count_id && slotProps.data.status === 'A1'){
        items.push({
          label: 'ICT Manager Approval',
          icon: "bi bi-file-earmark-arrow-up-fill",
          command: () => {
            this.ApproveManager(slotProps.data.ireq_id);
          }
        });
      }

      if (slotProps.data.status === 'P' || slotProps.data.status === 'A1' || slotProps.data.status === 'A2' || slotProps.data.status == 'RT') {
        items.push({
          label: 'Assign Per Request',
          icon: "bi bi-person-workspace",
          command: () => {
            this.AssignPerRequest(slotProps.data.ireq_id, slotProps.data.ireq_assigned_to1);
          }
        });
      }

      if(slotProps.data.status === 'P' || slotProps.data.status === 'A1' || slotProps.data.status === 'A2'){
        items.push({
          label: 'Assign Per Detail',
          icon: "bi bi-people",
          command: () => {
            this.$router.push({ name: 'Ict Request Reviewer Assign Per Detail', params: { code: slotProps.data.ireq_id }})
          }
        });
      }

      if(slotProps.data.ireq_count_status == slotProps.data.ireq_count_id && slotProps.data.status !== 'NT' && slotProps.data.status !== 'RT' && slotProps.data.status !== 'T' && slotProps.data.status !== 'C' || slotProps.data.count_assigned2 > 0 && slotProps.data.status == 'RT' || slotProps.data.ireq_count_spv > 0 && slotProps.data.status == 'P' ){
        items.push({
          label: 'Submit',
          icon: "bi bi-send-check",
          command: () => {
            this.Submit(slotProps.data.ireq_id);
          }
        });
      }

      return items;
    },
    detailRequest(ireq_id = null) {
      const requestData = this.value.find((item) => item.ireq_id === ireq_id);
      this.header = " Detail Request No. " + requestData.ireq_no;
      this.detail = requestData;
      this.dialogDetailRequest = true;
    },
    updateIsMobile() {
      this.isMobile = window.innerWidth <= 768;
    },
    editDataSpv(ireq_id) {
      this.axios.get("/api/edit-spv/" + ireq_id).then((res) => {
        this.editSpv = res.data.data.request;
        this.listSpv = res.data.data.listSupervisor;
        this.dialogEditSpv = true;
      });
    },
    submitSpv() {
      this.axios.post("/api/save-spv", this.editSpv).then(() => {
        this.cancelSpv();
        this.$emit("get-data");
      });
    },
    cancelSpv() {
      this.editSpv = [];
      this.listSpv = [];
      this.dialogEditSpv = false;
    },
    SendEmail(usr_email, ireq_id) {
      this.axios.get("/api/edit-spv/" + ireq_id).then((res) => {
        var frommail = usr_email;
        window.open(
          "mailto:" + frommail + "?subject=" + res.data.data.request.noreq
        );
      });
    },
    PrintRequestListByStatusPdf(type) {
      this.$emit("show-loading");
      this.Type.report_type = type;
      this.axios
        .post("api/print-out-pdf-reviewer", this.Type)
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
          this.Type.report_type = null;
          this.$html2pdf().set(options).from(htmlContent).save();
          this.$emit("hide-loading");
        });
    },
    PrintRequestListByStatusExcel(type){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.$emit("show-loading");
      this.Type.report_type = type;
       this.axios.post('api/print-out-excel-reviewer', this.Type, {headers: { 'Content-Type': 'application/json'},responseType: 'blob',}).
        then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.$emit("hide-loading");
          this.Type.report_type = null;
       });
    },
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    TabToDetail(ireq_id, active) {
      localStorage.setItem("active1", active);
      this.$router.push("/ict-request-reviewer/detail/" + ireq_id);
    },
    Submit(ireq_id) {
      this.$confirm.require({
        message: "Are you sure to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$emit("show-loading");
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Submit",
            life: 3000,
          });
          this.axios.get("api/sapr/" + ireq_id).then(() => {
            this.$emit("get-data");
          });
        },
        reject: () => {},
      });
    },
    Remark(ireq_id) {
      this.$emit("show-loading");
      this.remark.id = ireq_id;
      this.axios.get("api/get-remark-reviewer/" + ireq_id).then((res) => {
        this.remark.remark = res.data.ireq_verificator_remark;
        this.dialogRemark = true;
        this.$emit("hide-loading");
      });
    },
    cancelRemark() {
      this.remark.id = "";
      this.remark.remark = "";
      this.dialogRemark = false;
    },
    updateRemark() {
      this.dialogRemark = false;
      this.$emit("show-loading");
      this.axios.post("api/save-remark-reviewer", this.remark).then(() => {
        this.$toast.add({
          severity: "info",
          summary: "Success",
          detail: "successfully added a remark",
          life: 2000,
        });
        this.remark = { id: "", remark: "" };
        this.$emit("get-data");
      });
    },
    AssignPerRequest(ireq_id, ireq_assigned_to1) {
      this.assign.id = ireq_id;
      this.assign.name = ireq_assigned_to1;
      this.axios.get("api/get-pekerja").then((response) => {
        this.petugas = response.data;
      });
      this.dialogAssign = true;
    },
    Reject(ireq_id) {
      this.dialogReject = true;
      this.rbr.id = ireq_id;
    },
    cancelReject() {
      this.dialogReject = false;
      this.rbr.id = null;
      this.rbr.ket = null;
    },
    updateReject() {
      this.submitted = true;
      if (this.rbr.ket != null) {
        this.axios
          .put("/api/reject-by-reviewer/" + this.rbr.id, this.rbr)
          .then(() => {
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
            this.$emit("show-loading");
            this.$emit("get-data");
          });
      }
    },
    ApproveAtasan(ireq_id) {
      this.$confirm.require({
        message: "Are you sure this request need approval from higher level?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$emit("show-loading");
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life: 2000,
          });
          this.axios.get("/api/naa/" + ireq_id).then(() => {
            this.$emit("get-data");
            this.$emit("show-loading");
          });
        },
        reject: () => {},
      });
    },
    ApproveManager(ireq_id) {
      this.$confirm.require({
        message: "Are you sure this request need approval from ICT Manager?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$emit("show-loading");
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Success Update Request",
            life: 2000,
          });
          this.axios.get("/api/nam/" + ireq_id).then(() => {
            this.$emit("get-data");
            this.$emit("show-loading");
          });
        },
        reject: () => {},
      });
    },
    updateAssign() {
      this.axios.post("/api/aprr", this.assign).then(() => {
        this.assign = {
          id: null,
          name: null,
        };
        this.dialogAssign = false;
        this.$toast.add({
          severity: "info",
          summary: "Success Message",
          detail: "Successfully Assignment",
          life: 2000,
        });
        this.$emit("show-loading");
        this.$emit("get-data");
      });
    },
    cancelAssign() {
      this.assign = { id: null, name: null };
      this.petugas = [];
      this.dialogAssign = false;
      this.submitted = false;
    },
  },
};
</script>
<style lang="scss" scoped>
.p-button-small {
  padding: 0.25em 0.5em;
  font-size: 0.875em;
}
.custom-split-button .p-splitbutton-menubutton {
  display: none;
}

.custom-split-button .p-button-label {
  display: none;
}
.centered-speed-dial {
  position: relative;
  left: auto;
  transform: none;
}

.custom-speed-dial {
  font-size: 14px;
  /* Adjust the font size */
  padding: 8px;
  /* Adjust the padding */
}

.attachment-image {
  width: 50px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

.p-button.youtube {
  cursor: pointer;
  background: linear-gradient(
    to left,
    var(--pink-600) 50%,
    var(--pink-700) 50%
  );
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
  background: linear-gradient(
    to left,
    var(--blue-400) 50%,
    var(--blue-500) 50%
  );
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

.button-group {
  display: flex;
  flex-wrap: wrap;
}

.button-group .p-button {
  margin: 5px;
  /* Ruang antara tombol */
}
th {
  padding-right: 10px; /* Adjust the value as needed */
}
</style>
