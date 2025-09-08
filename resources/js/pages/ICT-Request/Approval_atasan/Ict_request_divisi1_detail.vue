<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <DataTableDetail
          :showForDashboard="showForDashboard"
          :value="detail"
          @show-loading="showLoading"
          @hide-loading="hideLoading"
          :kode="kode"
          :showPersonnel="showPersonnel"
          :loading="loading"
          :filters="filters"
        />
      </div>
    </div>
  </div>
</template>
<script>
import DataTableDetail from "../../Components/HigherLevel/Detail/DataTableDetailHigherLevel.vue";
export default {
  components: {
    DataTableDetail,
  },
  data() {
    return {
      showForDashboard: false,
      showPersonnel: [],
      loading: true,
      detail: [],
      kode: [],
      filters: {
        global: { value: null, matchMode: this.$FilterMatchMode.CONTAINS },
      },
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    getDetail(ireq_attachment) {
      var page =
        process.env.MIX_APP_URL + "/attachment_request/" + ireq_attachment;
      var myWindow = window.open(page, "_blank");
      myWindow.focus();
    },
    getIctDetail() {
      if (this.$route.query.showForDashboard === 'true') {
          this.showForDashboard = true;
      }
      this.axios
        .get("/api/ict-detail-higher-level/" + this.$route.params.code)
        .then((response) => {
          this.detail = response.data.data.detail;
          this.showPersonnel = this.detail.map((x)=>x.ireq_count_personnel1);
          this.kode = response.data.data.request;
          this.loading = false;
        })
        .catch((error) => {
          if (error.response.status == 401) {
            this.$toast.add({
              severity: "error",
              summary: "Error",
              detail: "Session login expired",
            });
            localStorage.clear();
            localStorage.setItem("Expired", "true");
            setTimeout(() => this.$router.push("/login"), 2000);
          }
          if (error.response.status == 403) {
            this.$router.push("/access");
          }
        });
    },
    CetakPdf() {
      this.loading = true
      const ireq_id = this.$route.params.code;
      this.axios.get('/api/print-out-ict-request/' + ireq_id, {
        headers: { 'Authorization': 'Bearer ' + this.token },
        responseType: 'blob'
      }).then((response) => {
        const file = new Blob([response.data], { type: 'application/pdf' });
        const fileURL = URL.createObjectURL(file);
        const link = document.createElement('a');
        link.href = fileURL;
        link.setAttribute('download', 'ICT_Request_' + ireq_id + '.pdf');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        this.loading = false
      }).catch(() => this.loading = false);
    },
    showLoading(){
      this.loading = true;
    },
    hideLoading(){
      this.loading = false;
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
</style>