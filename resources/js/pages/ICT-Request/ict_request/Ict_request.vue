<template>
  <div class="grid crud-demo">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="mb-4">
              <template v-slot:start>
                <h4>ICT Request - Status</h4>
              </template>
            </Toolbar> 
            <TabView v-model:activeIndex="active">
                <TabPanel header="Request">
                  <DataTableRequest 
                    :value="ict" :loading="loading" :showRemark="showRemarkPermohonan" :showSpv="showSpvPermohonan" printPdf="permohonan" @get-ict="getIct" Active="0"
                  />                 
                </TabPanel>
                <TabPanel header="Reviewer">
                  <DataTableRequest 
                    :value="reviewer" :loading="loading" :showRemark="showRemarkReviewer" :showSpv="showSpvReviewer" printPdf="tab_reviewer" @get-ict="getIct" Active="1"
                  />   
                </TabPanel>
                <TabPanel header="Verified">
                  <DataTableRequest 
                    :value="verif" :loading="loading" :showRemark="showRemarkVerified" :showSpv="showSpvVerified" printPdf="verifikasi" Active="2"
                  /> 
                </TabPanel>
                <TabPanel header="Rejected">
                  <DataTableRequest 
                    :value="reject" :loading="loading" :showRemark="showRemarkReject" :showSpv="showSpvReject" printPdf="reject" showReason="1" Active="3"
                  /> 
                </TabPanel>
                <TabPanel header="Request Assignment">
                  <DataTableRequest 
                    :value="penugasan" :loading="loading" :showRemark="showRemarkPenugasan" :showSpv="showSpvPenugasan" printPdf="assignment_request" showPersonnel1="1" Active="4"
                  /> 
                </TabPanel>
                <TabPanel header="In Progress">
                  <DataTableRequest 
                    :value="sedangDikerjakan" :loading="loading" :showRemark="showRemarksedangDikerjakan" :showSpv="showSpvsedangDikerjakan" printPdf="sedang_dikerjakan" showPersonnel1="1" Active="5"
                  /> 
                </TabPanel>
                <TabPanel header="Done">
                  <DataTableDetail :value="sudahDikerjakan" @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading" printPdf="sudah_dikerjakan"/> 
                </TabPanel>
                <TabPanel header="Close">
                  <DataTableDetail :value="selesai" @show-loading="showLoading" @hide-loading="hideLoading" @get-data="getIct" :loading="loading" printPdf="selesai" /> 
                </TabPanel>
            </TabView>
      </div>
    </div>
  </div>
</template>
<script>
import DataTableRequest from '../../Components/Requestor/Request/DataTableRequestRequestor.vue';
import DataTableDetail from '../../Components/Requestor/Request/DataTableDetailRequestor.vue';
export default {
  components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
      showRemarkPermohonan:[],
      showSpvPermohonan:[],
      showRemarkReviewer:[],
      showSpvReviewer:[],
      showRemarkVerified:[],
      showSpvVerified:[],
      showRemarkReject:[],
      showSpvReject:[],
      showRemarkPenugasan:[],
      showSpvPenugasan:[],
      showRemarksedangDikerjakan:[],
      showSpvsedangDikerjakan:[],
      active:JSON.parse(localStorage.getItem('active')),
      loading: true,
      ict: [],
      verif:[],
      reject:[],
      penugasan:[],
      reviewer:[],
      sedangDikerjakan:[],
      sudahDikerjakan:[],
      selesai:[],
      filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
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
      this.loading = true;
      this.axios.get('api/get-ict').then((response)=> {
          this.ict = response.data.ict;
          this.showRemarkPermohonan = this.ict.map((x)=>x.countremark_reviewer);
          this.showSpvPermohonan = this.ict.map((x)=>x.countspv);
          this.loading = false;
          this.verif = response.data.ict1;
          this.showRemarkVerified = this.verif.map((x)=>x.countremark_reviewer);
          this.showSpvVerified = this.verif.map((x)=>x.countspv);
          this.reject = response.data.ict2;
          this.showRemarkReject = this.reject.map((x)=>x.countremark_reviewer);
          this.showSpvReject = this.reject.map((x)=>x.countspv);
          this.reviewer = response.data.ict6;
          this.showRemarkReviewer = this.reviewer.map((x)=>x.countremark_reviewer);
          this.showSpvReviewer = this.reviewer.map((x)=>x.countspv);
          this.penugasan = response.data.ict8;
          this.showRemarkPenugasan = this.penugasan.map((x)=>x.countremark_reviewer);
          this.showSpvPenugasan = this.penugasan.map((x)=>x.countspv);
          this.sedangDikerjakan = response.data.ict3;
          this.showRemarksedangDikerjakan = this.sedangDikerjakan.map((x)=>x.countremark_reviewer);
          this.showSpvsedangDikerjakan = this.sedangDikerjakan.map((x)=>x.countspv);
          this.sudahDikerjakan = response.data.ict4;
          this.selesai = response.data.ict5;
          localStorage.setItem('active',0);
        }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
          if(error.response.status == 403){
            this.$router.push('/access');
          }
        });
    }, 
    CetakExcelPermohonan(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-permohonan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelReviewer(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-tab-reviewer',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelVerifikasi(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-verifikasi',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelTabReject(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reject',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-assignment-request',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-sedang-dikerjakan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-tab-sudah-dikerjakan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-selesai',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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