<template>
  <ConfirmDialog> </ConfirmDialog>
  <Dialog></Dialog>
    <Dialog
        v-model:visible="displayDetailRequest"
        :style="{ width: '1400px' }"
        header="Detail Request"
        :modal="true"
    >
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
          responsiveLayout="scroll"
        >
          <Column field="ireq_no" header="No. Request" style="min-width:6rem"/>
          <Column field="ireq_date" header="Request Date" style="min-width:12rem">
          <template #body="slotProps">
            {{formatDate(slotProps.data.ireq_date)}}
          </template>
          </Column>
          <Column field="ireq_requestor" header="Requester" style="min-width:6rem"/>
          <Column field="ireq_user" header="User" style="min-width:12rem"/>
          <Column field="div_name" header="Division User" style="min-width:12rem"/>
          <Column field="ireq_bu" header="Business Unit" style="min-width:12rem"/>
        </DataTable>
        </Dialog>  
</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
        displayDetailRequest: false,
        header:null,
        detail:[],
        token: localStorage.getItem('token'),
        kode:null,
        date:null,
    };
  },
  created() {
    this.checkLogin();
  },
  methods: {
    formatDate(date) {
      return moment(date).format("DD MMM YYYY HH:mm")
    },
    checkLogin(){
        var loggedIn = localStorage.getItem('loggedIn');
        if(loggedIn){
            this.cekUser();
        }
        else{
            var status = 'requester';
            this.$router.push('/loginn/'+status+'/'+this.$route.params.code)
        }
    },
    cekUser(){
        var id = localStorage.getItem('id');
      this.axios.get('/api/cek-user/'+ id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
            if(this.checkname.includes("Scan Legality") || this.checkto.includes("/scan-qr-code")){
           this.getIctDetail();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getIctDetail(){
      this.axios.get('/api/detail-norequest/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.displayDetailRequest = true;
      }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
           }
      });
    },
    getNoreq(){
      this.axios.get('/api/get-noreq/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.kode = response.data.noreq;
      });
    },
  },
};
</script>