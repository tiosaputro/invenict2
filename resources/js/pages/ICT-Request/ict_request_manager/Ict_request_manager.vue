<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toast />
                <ConfirmDialog> </ConfirmDialog>
                <Toolbar class="p-mb-4">
                    <template v-slot:start>
                        <h4>ICT Request - ICT Manager Approval</h4>
                    </template>
                </Toolbar>
                <TabView ref="tabview1" v-model:activeIndex="active2">
                    <TabPanel header="Waiting For Verification">
                        <DataTableRequest :value="blmdiverifikasi" :loading="loading" :showRemarkReviewer="showRemarkReviewerWaiting" :showRemarkManager="showRemarkManagerWaiting"
                            :showSpv="showSpvWaiting" printPdf="permohonan" :showPersonnel1="showPersonnelWaiting" @get-data="getIct" 
                            Active="0" @show-loading="showLoading" @hide-loading="hideLoading"
                        />
                    </TabPanel>
                    <TabPanel header="Approved">
                        <DataTableRequest :value="sdhdiverifikasi" :loading="loading" :showRemarkReviewer="showRemarkReviewerApproved" :showRemarkManager="showRemarkManagerApproved"
                            :showSpv="showSpvApproved" printPdf="verifikasi" :showPersonnel1="showPersonnelApproved" @get-data="getIct" 
                            Active="1" @show-loading="showLoading" @hide-loading="hideLoading"
                        />
                    </TabPanel>
                    <TabPanel header="Rejected">
                        <DataTableRequest :value="reject" :loading="loading" :showRemarkReviewer="showRemarkReviewerRejected" :showRemarkManager="showRemarkManagerRejected"
                            :showSpv="showSpvRejected" printPdf="reject" :showPersonnel1="showPersonnelRejected" @get-data="getIct" 
                            Active="2" @show-loading="showLoading" @hide-loading="hideLoading" showReason="1"
                        />
                    </TabPanel>
                    <TabPanel header="Request Assignment">
                        <DataTableRequest :value="penugasan" :loading="loading" :showRemarkReviewer="showRemarkReviewerPenugasan" :showRemarkManager="showRemarkManagerRejected"
                            :showSpv="showSpvPenugasan" printPdf="assignment_request" :showPersonnel1="showPersonnelRejected" @get-data="getIct" 
                            Active="3" @show-loading="showLoading" @hide-loading="hideLoading" showReason="1"
                        />
                    </TabPanel>
                    <TabPanel header="In Progress">
                        <DataTableRequest :value="sedangDikerjakan" :loading="loading" :showRemarkReviewer="showRemarkReviewerInProgress" :showRemarkManager="showRemarkManagerInProgress"
                            :showSpv="showSpvInProgress" printPdf="reject" :showPersonnel1="showPersonnelInProgress" @get-data="getIct" 
                            Active="4" @show-loading="showLoading" @hide-loading="hideLoading"
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

import DataTableRequest from "../../Components/IctManager/Request/DataTableRequestIctManager.vue";
import DataTableDetail from "../../Components/IctManager/Request/DataTableDetailIctManager.vue";

export default {
   components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
        showRemarkReviewerWaiting:[],
        showRemarkManagerWaiting:[],
        showSpvWaiting:[],
        showPersonnelWaiting:[],
        showRemarkReviewerApproved:[],
        showRemarkManagerApproved:[],
        showSpvApproved:[],
        showPersonnelApproved:[],
        showRemarkReviewerRejected:[],
        showRemarkManagerRejected:[],
        showSpvRejected:[],
        showPersonnelRejected:[],
        showRemarkReviewerPenugasan:[],
        showRemarkManagerPenugasan:[],
        showSpvPenugasan:[],
        showPersonnelPenugasan:[],
        showRemarkReviewerInProgress:[],
        showRemarkManagerInProgress:[],
        showSpvInProgress:[],
        showPersonnelInProgress:[],
        active2:JSON.parse(localStorage.getItem('active2')),
        loading: true,
        blmdiverifikasi: [],
        penugasan:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        sdhdiverifikasi: [],
        reject:[],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
        Type:{
          'report_type': ''
        },
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
    getIct(){
      this.axios.get('api/get-data-manager').then((response)=> {
        this.blmdiverifikasi = response.data.data.ict;
        this.sdhdiverifikasi = response.data.data.ict1;
        this.reject = response.data.data.ict2;
        this.penugasan = response.data.data.ict6;
        this.sedangDikerjakan = response.data.data.ict3;
        this.sudahDikerjakan = response.data.data.ict4;
        this.selesai = response.data.data.ict5;

        const properties = [
          { name: 'Waiting', data: this.blmdiverifikasi },
          { name: 'Approved', data: this.sdhdiverifikasi },
          { name: 'Rejected', data: this.reject },
          { name: 'Penugasan', data: this.penugasan },
          { name: 'InProgress', data: this.sedangDikerjakan }
        ];

        properties.forEach(({ name, data }) => {
          this['showRemarkReviewer' + name] = data.map(x => x.count_remark_reviewer);
          this['showRemarkManager' + name] = data.map(x => x.count_remark_manager);
          this['showSpv' + name] = data.map(x => x.count_spv);
          this['showPersonnel' + name] = data.map(x => x.count_personnel1);
        });

this.loading = false;
        localStorage.setItem('active2',0);
      }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
           }
           if (error.response.status == 403) {
            this.$router.push('/login');
           }
        });
    },
    CetakExcelBlmDiverifikasi(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-permohonan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSudahDiverifikasi(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-verifikasi',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelDireject(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-reject',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelAssignmentRequest(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-assignment-request',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSedangDikerjakan(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-sedang-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSudahDikerjakan(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-sudah-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelSelesai(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-manager-selesai',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
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
