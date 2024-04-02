<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
			      <h4>Laporan Request Per Status Per ICT-Personnel</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="req"
          :rows="25"
          :loading="loading"
          :rowHover="true"
          responsiveLayout="scroll"
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
               <Dropdown @change="getPerStatusIct()" :showClear="true" v-model="ictPersonnel" :options="personnel" optionValue="name" optionLabel="name" placeholder="Pilih Personnel"/>
            </div>
          </template>
          <Column field="status" header="Status Request" style="min-width:8rem" v-if="ictPersonnel"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:8rem" v-if="ictPersonnel"/>
          <template #footer v-if="ictPersonnel">
            <div class="p-grid p-dir-col">
			  <div class="p-col">
			    <div class="box">
                  <SplitButton 
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
         ictPersonnel: null,
         personnel:[],
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
                        window.open('api/req-per-status-per-personnel-pdf/'+this.ictPersonnel);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-per-status-per-personnel-excel/'+this.ictPersonnel);
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
        if(this.checkname.includes("Per Status Per Pesonnel") || this.checkto.includes("/report-per-status-per-personnel")){
          this.getPersonnel();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
        getPerStatusIct(){
            if(this.ictPersonnel !=null){
                this.loading = true;
                this.axios.get('api/count-per-status-ict/'+ this.ictPersonnel).then((response)=>{
                    this.req= response.data;
                    this.loading = false;
                });
            }
        },
        getPersonnel(){
            this.axios.get('api/get-tahun').then((response)=>{
                this.personnel = response.data.personnell;
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
  },
};
</script>