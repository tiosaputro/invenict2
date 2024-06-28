<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <DataTableDetail :value="detail" @show-loading="showLoading" @hide-loading="hideLoading" :kode="kode" :showPersonnel="showPersonnel" :loading="loading" :filters="filters" />
      </div>
    </div>
  </div>
</template>
<script>
import DataTableDetail from '../../Components/Change_request/detail/DataTableDetailRequest.vue';
export default {
  components: {
    DataTableDetail
  },
  data() {
    return {
      showForDashboard: false,
      loading: true,
      detail: [],
      kode: [],
      filters: { 'global': { value: null, matchMode: this.$FilterMatchMode.CONTAINS } },
      showPersonnel: []
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    getIctDetail() {
      if (this.$route.query.showForDashboard === 'true') {
          this.showForDashboard = true;
      }
      this.axios.get('/api/get-detail-done-personnel/' + this.$route.params.code).then((response) => {
        this.detail = response.data.data.detail;
        this.kode = response.data.data.request;
        this.loading = false;
      }).catch((error) => {
        if (error.response.status == 401) {
          this.$toast.add({
            severity: 'error', summary: 'Error', detail: 'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired', 'true')
          setTimeout(() => this.$router.push('/login'), 2000);
        }
        if (error.response.status == 403) {
          this.$router.push('access');
        }
      });
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
</style>