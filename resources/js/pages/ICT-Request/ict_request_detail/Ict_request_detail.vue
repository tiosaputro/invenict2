<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <DataTableDetail :showForDashboard="showForDashboard" :value="detail" @show-loading="showLoading" @hide-loading="hideLoading" :kode="kode" :showPersonnel="showPersonnel" :loading="loading" :filters="filters"/> 
      </div>
    </div>
  </div>
</template>
<script>
import DataTableDetail from '../../Components/Requestor/Detail/DataTableDetailRequest.vue';
export default {
  components:{
    DataTableDetail
  },
  data() {
    return {
        showForDashboard: false,
        loading: true,
        detail: [],
        kode:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        showPersonnel:[]
    };
  },
  mounted() {
    this.getIctDetail();
  },
  methods: {
    getIctDetail(){
      if (this.$route.query.showForDashboard === 'true') {
          this.showForDashboard = true;
      }
      this.axios.get('/api/ict-detail/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.showPersonnel = this.detail.map((x)=>x.ireq_count_personnel1);
        this.kode = response.data.data.request;
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
