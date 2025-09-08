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
import DataTableDetail from "../../Components/IctManager/Detail/DataTableDetailIctManager.vue"
export default {
  components:{
    DataTableDetail
  },
  data() {
    return {
        showForDashboard: false,
        showPersonnel:[],
        loading: true,
        detail: [],
        kode:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  mounted() {
   this.getIctDetail();
  },
  methods: {
    formatDate(date){
      return this.$moment(date).format("DD MMM YYYY HH:mm");
    },
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    getIctDetail(){
      if (this.$route.query.showForDashboard === 'true') {
          this.showForDashboard = true;
      }
      this.axios.get('/api/ict-detail-manager/' + this.$route.params.code).then((response)=> {
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
        if(error.response.status == 403){
          this.$router.push('/access');
        }
      });
    },
    CetakPdf(){
      this.loading = true;
        const code = this.$route.params.code;
        window.open('/api/print-out-ict-request/' + code, '_blank');
        this.loading = false;
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