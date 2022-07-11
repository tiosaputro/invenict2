<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toolbar class="mb-4">
            <template v-slot:start>
                <h4>ICT Request</h4>
            </template>
        </Toolbar>
        <DataTable
          :value="detail"
          :paginator="true"
          :rows="10"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5, 10, 15, 20, 25, 30, 35, 40, 45, 50]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} ICT Request"
          responsiveLayout="scroll"
        >
          <template #loading>
            Loading ICT Request data. Please wait.
          </template>
          <Column field="ireq_no" header="No. Request" style="min-width:8rem"/>
          <Column field="ireq_date" header="Request Date" style="min-width:10rem">
          <template #body="slotProps">
            {{formatDate(slotProps.data.ireq_date)}}
          </template>
          </Column>
          <Column field="ireq_requestor" header="Requester" style="min-width:10rem"/>
          <Column field="ireq_user" header="User" style="min-width:10rem"/>
          <Column field="div_name" header="Division User" style="min-width:12rem"/>
          <Column field="ireq_bu" header="Business Unit" style="min-width:12rem"/>
        </DataTable>
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
        loading:true,
        detail:[],
        token: localStorage.getItem('token'),
        verif:[]
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
      this.axios.get('/api/cek-verif-id/'+this.$route.params.code).then((res)=>{
        this.verif = res.data;
          if(!this.verif) {
             this.$router.push({ name: 'error', params: { stat: 'notvalid' } }) }
          else{
            var loggedIn = localStorage.getItem('loggedIn');
            if(loggedIn){
                this.cekUser();
            }
            else{
                var status = 'requester';
                this.$router.push('/loginn/'+status+'/'+this.$route.params.code)
            }
           }
        });
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
      this.axios.get('/api/detail-norequest/' + this.verif.ireq_id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.loading = false;
      });
    },
  },
};
</script>