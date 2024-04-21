<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
          <ConfirmDialog> </ConfirmDialog>
            <Toolbar class="mb-4">
              <template v-slot:start>
                <h4>ICT Request - Status Change Request</h4>
              </template>
            </Toolbar>
            <TabView ref="tabview1">
              <TabPanel header="Assignment Request">
                <DataTableRequest :value="penugasan" :showRemarkReviewer="countRemarkReviewerPenugasan" :loading="loading" @get-data="getData"
                  @show-loading="showLoading" @hide-loading="hideLoading" printRequestListByStatus="assignment_request"
                />
              </TabPanel>
              <TabPanel header="Rejected">
                <DataTableDetail :value="reject" :showReason="countReasonRejected" :showRemarkReviewer="countRemarkReviewerRejected" 
                  :showRemarkAssigned="countRemarkAssignedRejected" printRequestListByStatus="reject" :showRemarkRequestor="true" :loading="loading" @get-data="getData" @show-loading="showLoading" @hide-loading="hideLoading"/>
              </TabPanel>
              <TabPanel header="In Progress">
                <DataTableDetail :value="sedangDikerjakan" :showRemarkReviewer="countRemarkReviewerInProgress" 
                  :showRemarkAssigned="countRemarkAssignedInProgress" printRequestListByStatus="sedang_dikerjakan" :showRemarkRequestor="true" :showNoteAssigned="countNoteAssignedInProgress" :loading="loading" @get-data="getData" @show-loading="showLoading" 
                  @hide-loading="hideLoading"
                />
              </TabPanel>
              <TabPanel header="Done">
                <DataTableDetail :value="sudahDikerjakan" :showRemarkReviewer="countRemarkReviewerDone" 
                  :showRemarkAssigned="countRemarkAssignedDone" :showRemarkRequestor="true" :showNoteAssigned="countNoteAssignedDone" :loading="loading" @get-data="getData" @show-loading="showLoading" 
                  @hide-loading="hideLoading" printRequestListByStatus="sudah_dikerjakan"
                /> 
              </TabPanel>
              <TabPanel header="Close">
                <DataTableDetail :value="selesai" :showRemarkReviewer="countRemarkReviewerClose" 
                  :showRemarkAssigned="countRemarkAssignedClose" :showRemarkRequestor="true" :showNoteAssigned="countNoteAssignedClose" :loading="loading" @get-data="getData" @show-loading="showLoading" 
                  @hide-loading="hideLoading" printRequestListByStatus="selesai"
                /> 
              </TabPanel>
            </TabView>
      </div>
    </div>
  </div>
</template>
<script>
import DataTableRequest from '../../Components/Change_request/DataTableRequestPersonnel.vue';
import DataTableDetail from '../../Components/Change_request/DataTableDetailPersonnel.vue';
export default {
  components:{
    DataTableRequest,
    DataTableDetail
  },
  data() {
    return {
        countRemarkReviewerRejected:[],
        countRemarkAssignedRejected:[],
        countReasonRejected:[],
        countRemarkReviewerInProgress:[],
        countRemarkAssignedInProgress:[],
        countNoteAssignedInProgress:[],
        countRemarkReviewerPenugasan:[],
        countRemarkReviewerDone:[],
        countRemarkAssignedDone:[],
        countNoteAssignedDone:[],
        countRemarkReviewerClose:[],
        countRemarkAssignedClose:[],
        countNoteAssignedClose:[],
        loading: true,
        selesai: [],
        penugasan:[],
        reject:[],
        sedangDikerjakan:[],
        sudahDikerjakan:[],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    showLoading(){
      this.loading = true;
    },
    hideLoading(){
      this.loading = false;
    },
    getData(){
      this.axios.get('api/get-sedang-dikerjakan').then((response)=> {
        this.penugasan = response.data.data.ict3;
        this.countRemarkReviewerPenugasan = this.penugasan.map((x)=>x.countremarkreviewer);
        this.reject = response.data.data.ict4;
        this.countRemarkReviewerRejected = this.reject.map((x)=>x.countremarkreviewer);
        this.countRemarkAssignedRejected = this.reject.map((x)=>x.countremarkassigned);
        this.countReasonRejected = this.reject.map((x)=>x.countreason);
        this.sedangDikerjakan = response.data.data.ict;
        this.countRemarkReviewerInProgress = this.sedangDikerjakan.map((x)=>x.countremarkreviewer);
        this.countRemarkAssignedInProgress = this.sedangDikerjakan.map((x)=>x.countremarkassigned);
        this.countNoteAssignedInProgress = this.sedangDikerjakan.map((x)=>x.countnoteassigned);
        this.sudahDikerjakan = response.data.data.ict1;
        this.countRemarkReviewerDone = this.sudahDikerjakan.map((x)=>x.countremarkreviewer);
        this.countRemarkAssignedDone = this.sudahDikerjakan.map((x)=>x.countremarkassigned);
        this.countNoteAssignedDone = this.sudahDikerjakan.map((x)=>x.countnoteassigned);
        this.selesai = response.data.data.ict2;
        this.countRemarkReviewerClose = this.selesai.map((x)=>x.countremarkreviewer);
        this.countRemarkAssignedClose = this.selesai.map((x)=>x.countremarkassigned);
        this.countNoteAssignedClose = this.selesai.map((x)=>x.countnoteassigned);
        this.loading = false;
      }).catch(error=>{
          if(error.response.status == 403){
             this.$router.push('/access');
          }
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
</style>