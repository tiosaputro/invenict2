<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>Laporan Request Divisi Requestor Per Status</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="req"
          :rows="25"
          :rowHover="true"
          responsiveLayout="scroll"
          :loading="loading"
          stripedRows
        >
         <template #loading>
          Loading data. Please wait.
         </template>
        <template #empty>
          Not Found
        </template>
         <template #header>
            <div class="table-header p-text-left">
                <Dropdown @change="getStatusDivisiRequestor()" :showClear="true" v-model="statusRequestor" :options="status" optionValue="code" optionLabel="name" placeholder="Pilih Status"/>
            </div>
          </template>
          <Column field="div_name" header="Divisi Requestor" style="min-width:10rem" v-if="statusRequestor"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:10rem" v-if="statusRequestor"/>
          <template #footer v-if="statusRequestor">
            <div class="p-grid p-dir-col">
			  <div class="p-col">
			    <div class="box">
                  <SplitButton 
                    v-if="this.req.length"
                    label="Print" 
                    :model="items"
                  />
                </div>
			  </div>
            </div>
           </template>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
         statusRequestor:null,
         status:[],
         loading: false,
         req: [],
         token: localStorage.getItem('token'),
         checkname : [],
         checkto : [],
            items: [
                {
                    label: 'Pdf',
                    icon: 'bi bi-file-earmark-pdf text-danger',
                    command: () => {
                        window.open('api/req-div-req-per-status-pdf/'+this.statusRequestor);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-div-req-per-status-excel/'+this.statusRequestor);
                    }
                }
            ],
    };
  },
  created() {
    this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('api/cek-user').then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Divisi Requestor Per Status") || this.checkto.includes("/report-div-req-per-status")){
          this.getStatus();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
        getStatus(){
            this.axios.get('api/get-tahun').then((response)=>{
                this.status = response.data.grafik1;
            }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
        },
        getStatusDivisiRequestor(){
            if(this.statusRequestor != null){
                this.loading = true;
                this.axios.get('api/count-per-divreq-status/'+this.statusRequestor).then((response)=>{
                    this.req = response.data;
                    this.loading = false;
                });
            }
        },
  },
};
</script>