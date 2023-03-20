<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Laporan Request Divisi User Per Tahun</h4>
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
               <Dropdown @change="getPerDivisiUserTahun()" :showClear="true" v-model="tahunUser" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" />
            </div>
          </template>
          <Column field="div_name" header="Divisi User" style="min-width:8rem" v-if="tahunUser"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:8rem" v-if="tahunUser"/>
          <template #footer v-if="tahunUser">
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
         tahunUser:null,
         tahunn:[],
         loading: false,
         req: [],
         checkname : [],
         checkto : [],
            items: [
                {
                    label: 'Pdf',
                    icon: 'bi bi-file-earmark-pdf text-danger',
                    command: () => {
                        window.open('api/req-div-user-per-tahun-pdf/'+this.tahunUser);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-div-user-per-tahun-excel/'+this.tahunUser);
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
        if(this.checkname.includes("Divisi User Per Tahun") || this.checkto.includes("/report-div-user-per-tahun")){
          this.getTahun();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
        getPerDivisiUserTahun(){
            if(this.tahunUser != null){
            this.loading = true;
                this.axios.get('api/count-per-divuser-tahun/'+this.tahunUser).then((response)=>{ 
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