<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="p-mb-4">
              <template v-slot:start>
                <h4>ICT Request - Higher Level Approval</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1" v-model:activeIndex="active3">
              <TabPanel header="Request">
                <DataTableRequest :value="permohonan" :loading="loading" :showRemarkReviewer="showRemarkReviewerPermohonan" :showRemarkManager="showRemarkManagerPermohonan"
                  :showSpv="showSpvPermohonan" printPdf="permohonan" :showPersonnel1="showPersonnelPermohonan" @get-data="getIct" 
                  Active="0" @show-loading="showLoading" @hide-loading="hideLoading"
                /> 
              </TabPanel>
                <TabPanel header="Approved">
                  <DataTableRequest :value="verif" :loading="loading" :showRemarkReviewer="showRemarkReviewerverif" :showRemarkManager="showRemarkManagerverif"
                    :showSpv="showSpvverif" printPdf="verifikasi" :showPersonnel1="showPersonnelverif" @get-data="getIct" 
                    Active="1" @show-loading="showLoading" @hide-loading="hideLoading"
                  /> 
                </TabPanel>
                <TabPanel header="Rejected">
                  <DataTableRequest :value="reject" :loading="loading" :showRemarkReviewer="showRemarkReviewerreject" :showRemarkManager="showRemarkManagerreject"
                    :showSpv="showSpvreject" printPdf="reject" :showPersonnel1="showPersonnelreject" @get-data="getIct" 
                    Active="2" @show-loading="showLoading" @hide-loading="hideLoading" showReason = "1"
                  /> 
                </TabPanel>
                <TabPanel header="Request Assignment">
                  <DataTableRequest :value="penugasan" :loading="loading" :showRemarkReviewer="showRemarkReviewerpenugasan" :showRemarkManager="showRemarkManagerpenugasan"
                    :showSpv="showSpvpenugasan" printPdf="assignment_request" :showPersonnel1="showPersonnelpenugasan" @get-data="getIct" 
                    Active="3" @show-loading="showLoading" @hide-loading="hideLoading"
                  /> 
                </TabPanel>
                <TabPanel header="In Progress">
                  <DataTableRequest :value="sedangDikerjakan" :loading="loading" :showRemarkReviewer="showRemarkReviewersedangDikerjakan" :showRemarkManager="showRemarkManagersedangDikerjakan"
                    :showSpv="showSpvsedangDikerjakan" printPdf="sedang_dikerjakan" :showPersonnel1="showPersonnelsedangDikerjakan" @get-data="getIct" 
                    Active="3" @show-loading="showLoading" @hide-loading="hideLoading"
                  /> 
                </TabPanel>
                <TabPanel header="Done">
                  <DataTableDetail :value="sudahDikerjakan" @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading" printPdf="sudah_dikerjakan"/> 
                </TabPanel>
                <TabPanel header="Close">
                  <DataTableDetail :value="selesai" @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading" printPdf="selesai"/> 
                </TabPanel>
            </TabView>
      </div>
    </div>
  </div>
</template>
<script>

import DataTableRequest from "../../Components/HigherLevel/Request/DataTableRequestHigherLevel.vue";
import DataTableDetail from "../../Components/HigherLevel/Request/DataTableDetailHigherLevel.vue";

export default {
  components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
        showRemarkReviewerPermohonan:[],
        showRemarkManagerPermohonan:[],
        showSpvPermohonan:[],
        showPersonnelPermohonan:[],
        showRemarkReviewerpenugasan:[],
        showRemarkManagerpenugasan:[],
        showSpvpenugasan:[],
        showPersonnelpenugasan:[],
        showRemarkReviewerverif:[],
        showRemarkManagerverif:[],
        showSpvverif:[],
        showPersonnelverif:[],
        showRemarkReviewerreject:[],
        showRemarkManagerreject:[],
        showSpvreject:[],
        showPersonnelreject:[],
        showRemarkReviewersedangDikerjakan:[],
        showRemarkManagersedangDikerjakan:[],
        showSpvsedangDikerjakan:[],
        showPersonnelsedangDikerjakan:[],
        confirmationVerifikasi:false,
        dialogReject:false,
        active3:JSON.parse(localStorage.getItem('active3')),
        loading: true,
        permohonan: [],
        penugasan:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        verif: [],
        reject:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        code:null,
        reason:{ 
          ket: null 
        },
        Type:{
          'report_type': ''
        },
        submitted:false
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    showLoading(){
      this.loading = true;
    },
    hideLoading(){
      this.loading = false;
    },
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    getIct(){
      this.axios.get('api/get-permohonan').then((response)=> {
        
        this.permohonan = response.data.data.ict;
        this.verif = response.data.data.ict1;
        this.reject = response.data.data.ict2;
        this.penugasan = response.data.data.ict9;
        this.sedangDikerjakan = response.data.data.ict3;
        this.sudahDikerjakan = response.data.data.ict4;
        this.selesai = response.data.data.ict5;

        const propertiesToUpdate = ['showRemarkReviewer', 'showRemarkManager', 'showSpv', 'showPersonnel'];

        propertiesToUpdate.forEach(property => {
          this[property + 'Permohonan'] = this.permohonan.map(x => x['count_' + property]);
          this[property + 'verif'] = this.verif.map(x => x['count_' + property]);
          this[property + 'reject'] = this.reject.map(x => x['count_' + property]);
          this[property + 'penugasan'] = this.penugasan.map(x => x['count_' + property]);
          this[property + 'sedangDikerjakan'] = this.sedangDikerjakan.map(x => x['count_' + property]);
        });
        localStorage.setItem('active3',0)
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
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm")
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
    cursor:pointer;
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
.p-button.youtube {
    cursor:pointer;
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
.template .p-button.twitter {
    background: linear-gradient(to left, var(--blue-400) 50%, var(--blue-500) 50%);
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