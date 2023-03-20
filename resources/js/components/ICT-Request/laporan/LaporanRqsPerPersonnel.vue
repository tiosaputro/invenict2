<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Laporan Request Per ICT-Personnel</h4>
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
           <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading. Please wait.
          </template>
          <Column field="ireq_assigned_to" header="Personnel ICT" style="min-width:10rem"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:10rem"/>
          <template #footer>
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
         loading: true,
         req: [],
         checkname : [],
         checkto : [],
            items: [
                {
                    label: 'Pdf',
                    icon: 'bi bi-file-earmark-pdf text-danger',
                    command: () => {
                        window.open('api/req-per-personnel-pdf');
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-per-personnel-excel');
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
        if(this.checkname.includes("Per ICT-Personnel") || this.checkto.includes("/report-div-req-per-status")){
          this.getPersonnel();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getPersonnel(){
        this.axios.get('api/get-tahun').then((response)=>{
            this.req = response.data.personnel;
            this.loading = false;
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