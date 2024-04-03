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
                    :value="ict" :loading="loading" :showRemark="showRemarkPermohonan" :showSpv="showSpvPermohonan"
                    :filters="filters" printPdf="permohonan" @get-ict="getIct" @delete-data-ict="DeleteIct"
                  />                 
                </TabPanel>
                <TabPanel header="Reviewer">
                  <DataTableRequest 
                    :value="reviewer" :loading="loading" :showRemark="showRemarkReviewer" :showSpv="showSpvReviewer"
                    :filters="filters" printPdf="tab_reviewer" @get-ict="getIct" @delete-data-ict="DeleteIct"
                  />   
                </TabPanel>
                <TabPanel header="Verified">
                  <DataTableRequest 
                    :value="verif" :loading="loading" :showRemark="showRemarkVerified" :showSpv="showSpvVerified"
                    :filters="filters" printPdf="verifikasi" 
                  /> 
                </TabPanel>
                <TabPanel header="Rejected">
                  <DataTableRequest 
                    :value="reject" :loading="loading" :showRemark="showRemarkReject" :showSpv="showSpvReject"
                    :filters="filters" printPdf="reject" showReason="1" 
                  /> 
                </TabPanel>
                <TabPanel header="Request Assignment">
                  <DataTableRequest 
                    :value="penugasan" :loading="loading" :showRemark="showRemarkPenugasan" :showSpv="showSpvPenugasan"
                    :filters="filters" printPdf="assignment_request" showPersonnel1="1" 
                  /> 
                </TabPanel>
                <TabPanel header="In Progress">
                  <DataTableRequest 
                    :value="sedangDikerjakan" :loading="loading" :showRemark="showRemarksedangDikerjakan" :showSpv="showSpvsedangDikerjakan"
                    :filters="filters" printPdf="sedang_dikerjakan" showPersonnel1="1" 
                  /> 
                </TabPanel>
                <TabPanel header="Done">
                  <DataTableDetail :value="sudahDikerjakan" :loading="loading" :filters="filters" printPdf="sudah_dikerjakan"/> 
                </TabPanel>
                <TabPanel header="Close">
                  <DataTableDetail :value="selesai" :loading="loading" :filters="filters" printPdf="selesai" /> 
                </TabPanel>
            </TabView>
            <Dialog v-model:visible="dialogEdit"
              :style="{ width: '400px' }"
              header="Give Feedback"
              class="field grid"
            >
            <div class="fluid">
              <div class="field grid">
                <div class="col-fixed">
                  <star-rating v-bind:increment="1"
                    v-bind:max-rating="5"
                    v-bind:rating="rating"
                    v-bind:animate="true"
                    v-bind:show-rating="true"
                    v-bind:inline="true"
                    v-bind:star-size="40"
                    @hover:rating="check"
                    @update:rating ="setRating">
                  </star-rating> 
                  <Message severity="error" icon="bi bi-emoji-frown" :closable="false" v-if="sangat_kurang">Very Less</Message>
                  <Message severity="warn" icon="bi bi-emoji-frown" :closable="false" v-if="kurang"> Less</Message>
                  <Message severity="info" icon="bi bi-emoji-neutral" :closable="false" v-if="baik">Normal</Message>
                  <Message severity="info" icon="bi bi-emoji-laughing" :closable="false" v-if="bagus">Good</Message>
                  <Message severity="success" icon="bi bi-emoji-heart-eyes" :closable="false" v-if="sangat_bagus">Very Good</Message>
                  </div>
                </div>
            </div>
            <div class="field" v-if="must">
              <div class="field grid">
                <div class="col-5">
                  <Textarea
                    :autoResize="true"
                    v-if="must"
                    type="text"
                    rows="5"
                    v-model="reason.ket"
                    placeholder="Tell us about your experience and we will improve the quality of our service"
                    :class="{ 'p-invalid': submitted && !reason.ket }"
                  />
                  <small v-if="submitted && !reason.ket" class="p-error">
                    Ulasan Belum Diisi
                  </small>
                </div>
              </div>
            </div>
            <template #footer>
              <Button label="Yes" @click="Update()" class="p-button" autofocus />
              <Button label="No" @click="cancel()" class="p-button-text" />
            </template>
            </Dialog>  
      </div>
    </div>
  </div>
</template>
<script>
import DataTableRequest from '../../Components/Requestor/DataTableRequestRequestor.vue';
import DataTableDetail from '../../Components/Requestor/DataTableDetailRequestor.vue';
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
      reason:{ id :null, ket:null,ireq_id:null},
      must:false,
      submitted:false,
      sangat_kurang:false,
      kurang:false,
      baik:false,
      bagus:false,
      sangat_bagus:false,
      dialogEdit:false,
      rating: 0,
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
      checkname : [],
      Type:{
        'report_type': ''
      },
    };
  },
  created() {
    this.getIct();
  },
  methods: {
    getDetail(ireq_attachment){
       var page = process.env.MIX_APP_URL+'/attachment_request/'+ireq_attachment;
         var myWindow = window.open(page, "_blank");
         myWindow.focus();
    },
    setRating(rating){
      if(rating <= 2){
        this.must = true;
      }
      else{
        this.submitted = false;
        this.must = false;
      }
      this.rating= rating;
    },
    Update(){
      if(this.rating <= '2'){
        this.submitted = true;
        if(this.reason.ket != null){
        const data = new FormData();
        data.append("rating", this.rating);
        data.append("ireq_id", this.reason.ireq_id);
        data.append("id", this.reason.id);
        data.append("ket", this.reason.ket);
        this.axios.post('/api/submit-rating',data).then(()=>{
          this.reason = {id:null, ket :null, ireq_id:null};
          this.dialogEdit = false;
          this.loading = true;
          this.sangat_bagus = false;
          this.bagus = false;
          this.baik = false;
          this.kurang = false;
          this.sangat_kurang = false;
          this.must = false;
          this.rating = 0;
          this.submitted = false;
          this.$toast.add({
            severity:'info', summary: 'Success Submit', detail:'Thanks for you feedback',life:2000
          });
          this.getIct();
        });
        }
      }
      else{
        this.dialogEdit = false;
        this.loading = true;
        const data = new FormData();
        data.append("rating", this.rating);
        data.append("id", this.reason.id);
        data.append("ireq_id", this.reason.ireq_id);
        this.axios.post('/api/submit-rating',data).then(()=>{
          this.rating = null;
          this.sangat_bagus = false;
          this.bagus=false;
          this.baik = false;
          this.kurang = false;
          this.sangat_kurang = false;
          this.must = false;
          this.$toast.add({
            severity:'info', summary: 'Success Submit', detail:'Thank for your feedback',life:2000
          });
          this.getIct();
        });
      }
    },
    cancel(){
      this.dialogEdit = false;
      this.reason = {id : null, ket : null};
    },
    tes(ireqd_id,ireq_id){
      this.reason.id = ireqd_id;
      this.reason.ireq_id = ireq_id;
      this.dialogEdit = true;
    },
    getIct(){
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
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm")
    },
    SubmitIct(ireq_id){
      this.$confirm.require({
        message: "Are you sure you want to submit this request?",
        header: "Confirmation Submit",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.loading = true;
          this.axios.get('api/updateStatusSubmit/' +ireq_id).then(()=>{
            this.getIct();
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Successfully Submit",
              life: 3000,
            });
          });
        },
        reject: () => {},
      })
    },
    DeleteIct(ireq_id){
       this.$confirm.require({
        message: "Are you sure you want to delete this record data?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.axios.delete('api/delete-ict/' +ireq_id).then(()=>{
            this.$toast.add({
              severity: "info",
              summary: "Confirmed",
              detail: "Record deleted",
              life: 3000,
            });
            this.loading = true;
            this.getIct();
          });
        },
        reject: () => {},
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