<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Laporan Request Divisi Requestor Per Tahun</h4>
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
               <Dropdown @change="getPerDivisiRequestorTahun()" :showClear="true" v-model="tahunRequestor" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" />
            </div>
          </template>
          <Column field="div_name" header="Divisi Requestor" style="min-width:10rem" v-if="tahunRequestor"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:10rem" v-if="tahunRequestor"/>
          <template #footer v-if="tahunRequestor">
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
         bulanRequestor:null,
         tahunRequestor:null,
         tahunn:[],
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
                        window.open('api/req-div-req-per-tahun-pdf/'+this.tahunRequestor);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-div-req-per-tahun-excel/'+this.tahunRequestor);
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
        if(this.checkname.includes("Divisi Requestor Per Tahun") || this.checkto.includes("/report-div-req-per-tahun")){
          this.getTahun();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
        getPerDivisiRequestorTahun(){
            if(this.tahunRequestor != null){
              this.loading = true;
                this.axios.get('api/count-per-divreq-tahun/'+this.tahunRequestor).then((response)=>{ 
                    this.req = response.data;
                    this.loading = false;
                });
            }
        },
        getTahun(){
            this.axios.get('api/get-tahun').then((response)=>{
                this.tahunn = response.data.grafik;
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