<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <DataTableDetail :showForDashboard="showForDashboard" :value="detail" 
        @show-loading="showLoading" @hide-loading="hideLoading" :kode="kode" 
        :showPersonnel1="showPersonnel1" :showPersonnel2="showPersonnel2" :showPersonnel1Reason="showPersonnel1Reason" 
        :loading="loading" :filters="filters" @get-data="getIct"/>  
      </div>
    </div>
  </div>
</template>
<script>
import DataTableDetail from "../../Components/Reviewer/Detail/DataTableDetailReviewer.vue"
export default {
  components:{
    DataTableDetail
  },
  data() {
    return {
        showForDashboard: false,
        showPersonnel1:[],
        showPersonnel1Reason:[],
        showPersonnel2:[],
        kode:[],
        detail: [],
        loading: true,
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  mounted() {
    this.getIct();
  },
  methods: {
    getIct(){
      
      this.axios.get('/api/ict-detail-reviewer/' + this.$route.params.code).then((response)=> {
        this.detail = response.data.data.detail;
        this.showPersonnel1 = response.data.data.detail.map((x)=>x.ireq_count_status);
        this.showPersonnel2 = response.data.data.detail.map((x)=>x.ireq_count_personnel2);
        this.showReason = response.data.data.detail.map((x)=>x.ireq_count_reason);
        this.kode = response.data.data.request;
        this.status = response.data.data.request.Ccekstatus;
        if(this.status =='NT' || this.status == 'RT'){ 
          this.show = true
        }
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
          if(error.response.status == 403){
            this.$router.push('/access');
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