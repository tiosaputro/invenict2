<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
            <h4>ICT Request - Reviewer</h4>
          </template>
        </Toolbar>
            <TabView v-model:activeIndex="active1">
              <TabPanel header="Request">
                <DataTableRequest :value="permohonan" :loading="loading" :showRemark="showRemarkPermohonan" :showSpv="showSpvPermohonan"
                     printPdf="permohonan" :showPersonnel1="showPersonelPermohonan" showTotalDetail ="1" @get-data="getIct" 
                    Active="0" @show-loading="showLoading" @hide-loading="hideLoading"
                />  
              </TabPanel>
              <TabPanel header="Higher Level">
                <DataTableRequest :value="atasandivisi" :loading="loading" :showRemark="showRemarkAtasanDivisi" :showSpv="showSpvAtasanDivisi"
                     printPdf="atasan" :showPersonnel1="showPersonelAtasanDivisi" @get-data="getIct" 
                    @show-loading="showLoading" @hide-loading="hideLoading" Active="1"
                />  
              </TabPanel>
              <TabPanel header="ICT Manager">
                <DataTableRequest :value="manager" :loading="loading" :showRemark="showRemarkManager" :showSpv="showSpvManager"
                     printPdf="manager" :showPersonnel1="showPersonelmanager" @get-data="getIct" 
                    @show-loading="showLoading" @hide-loading="hideLoading" Active="2"
                /> 
              </TabPanel>
              <TabPanel header="Rejected">
                <DataTableRequest :value="reject" :loading="loading" :showRemark="showRemarkManager" :showSpv="showSpvManager"
                  @show-loading="showLoading" @hide-loading="hideLoading"  printPdf="reject" @get-data="getIct" showReason="1" Active="3"
                />
              </TabPanel>
              <TabPanel header="Request Assignment">
                <DataTableRequest :value="penugasan" :loading="loading" :showRemark="showRemarkPenugasan" :showSpv="showSpvPenugasan"
                     printPdf="assignment_request" :showPersonnel1="showPersonelPenugasan" @get-data="getIct" 
                    @show-loading="showLoading" @hide-loading="hideLoading" :showReasonPersonnel="showReasonPersonnel" Active="4"
                />
              </TabPanel>
              <TabPanel header="In Progress">
                <DataTableRequest :value="sedangDikerjakan" :loading="loading" :showRemark="showRemarkSedangDikerjakan" :showSpv="showSpvSedangDikerjakan" @show-loading="showLoading" @hide-loading="hideLoading"  printPdf="sedang_dikerjakan" :showPersonnel1="showPersonelSedangDikerjakan" Active="5"/>
              </TabPanel>
              <TabPanel header="Done">
                <DataTableDetail :value="sudahDikerjakan" @get-data="getIct" :loading="loading" @show-loading="showLoading" @hide-loading="hideLoading" printPdf="sudah_dikerjakan"/>
              </TabPanel>
              <TabPanel header="Close">
                <DataTableDetail :value="selesai" :loading="loading" printPdf="selesai" @show-loading="showLoading" @hide-loading="hideLoading"/>
              </TabPanel>
            </TabView>
            
      </div>
    </div>
  </div>
</template>
<script>

import DataTableRequest from '../../Components/Reviewer/Request/DataTableRequestReviewer.vue';
import DataTableDetail from '../../Components/Reviewer/Request/DataTableDetailReviewer.vue';
export default {
  components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
        loading: true,
        permohonan: [],
        detail:[],
        ireq_no :'',
        active1:JSON.parse(localStorage.getItem('active1')),
        atasandivisi:[],
        manager:[],
        reject:[],
        penugasan:[],
        sedangDikerjakan:[],
        showRemarkSedangDikerjakan:[],
        showSpvSedangDikerjakan:[],
        showPersonelSedangDikerjakan:[],
        sudahDikerjakan:[],
        selesai:[],
        showPersonelPermohonan:[],
        showPersonelAtasanDivisi:[],
        showSpvAtasanDivisi:[],
        showPersonelmanager:[],
        showRemarkPermohonan:[],
        showSpvPermohonan:[],
        showRemarkAtasanDivisi:[],
        showRemarkManager:[],
        showSpvManager:[],
        showRemarkPenugasan:[],
        showSpvPenugasan:[],
        showPersonelPenugasan:[],
        showRemarkApprover2Manager:[],
        showReasonPersonnel:[],
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
      this.axios.get('api/get-data-reviewer').then((response)=> {
          this.permohonan = response.data.ict;
          this.showPersonelPermohonan = this.permohonan.map((x)=>x.ireq_count_status);
          this.showRemarkPermohonan = this.permohonan.map((x)=>x.count_remark);
          this.showSpvPermohonan = this.permohonan.map((x)=>x.ireq_count_spv);
          this.loading = false;
          this.atasandivisi = response.data.ict1;
          this.showPersonelAtasanDivisi = this.atasandivisi.map((x)=>x.ireq_count_status);
          this.showRemarkAtasanDivisi = this.atasandivisi.map((x)=>x.count_remark);
          this.showSpvAtasanDivisi = this.atasandivisi.map((x)=>x.ireq_count_spv);
          this.manager = response.data.ict2;
          this.showPersonelmanager = this.manager.map((x)=>x.ireq_count_status);
          this.showRemarkManager = this.manager.map((x)=>x.count_remark);
          this.showSpvManager = this.manager.map((x)=>x.ireq_count_spv);
          this.showRemarkApprover2Manager = this.manager.map((x)=>x.count_remark_approver2);
          this.reject = response.data.ict3;
          this.penugasan = response.data.ict7;
          this.showSpvPenugasan = this.penugasan.map((x)=>x.ireq_count_spv);
          this.showPersonelPenugasan = this.penugasan.map((x)=>x.ireq_count_status);
          this.showRemarkPenugasan = this.penugasan.map((x)=>x.count_remark);
          this.showReasonPersonnel = this.penugasan.map((x)=>x.countreason);
          this.sedangDikerjakan = response.data.ict4;
          this.showRemarkSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.count_remark);
          this.showSpvSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.ireq_count_spv);
          this.showPersonelSedangDikerjakan = this.sedangDikerjakan.map((x)=>x.ireq_count_status);
          this.sudahDikerjakan = response.data.ict5;
          this.selesai = response.data.ict6;
          localStorage.setItem('active1',0);
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
    CetakExcelPermohonan(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-permohonan',{headers: {'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelAtasanDivisi(){
      const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-atasan-divisi',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelIctManager(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-ict-manager',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'ICT REQUEST STATUS REPORT LIST ON '+today+'.xlsx');
          document.body.appendChild(link);
          link.click();
          this.loading = false;
       });
    },
    CetakExcelReject(){
       const date = new Date();
      const today = this.$moment(date).format("DD MMM YYYY")
      this.loading = true;
       this.axios.get('api/report-ict-excel-reviewer-reject',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-assignment-request',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-sedang-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-sudah-dikerjakan',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
       this.axios.get('api/report-ict-excel-reviewer-selesai',{headers: { 'Content-Type': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'},responseType: 'arraybuffer',}).then((response)=>{
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
