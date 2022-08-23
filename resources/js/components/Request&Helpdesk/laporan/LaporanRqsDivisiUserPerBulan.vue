<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog> </ConfirmDialog>
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Laporan Request Divisi User Per Bulan</h4>
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
                <Dropdown @change="getTahunUser()" :showClear="true" v-model="bulanUser" :options="bulan" optionValue="code" optionLabel="name" placeholder="Pilih Bulan" class="mr-2" />
                <Dropdown @change="getPerDivisiUserBulan()" :showClear="true" v-model="tahunnUser" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" class="mr-2" v-if="this.bulanUser" />
            </div>
          </template>
          <Column field="div_name" header="Divisi User" style="min-width:10rem" v-if="tahunnUser"/>
          <Column field="jumlah" header="Jumlah Request" style="min-width:10rem" v-if="tahunnUser"/>
          <template #footer v-if="tahunnUser">
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
         bulanUser:null,
         tahunnUser:null,
         bulan:[],
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
                        window.open('api/req-div-user-per-bulan-pdf/'+this.tahunnUser +'/'+this.bulanUser);
                    }
                },
                {
                    label: 'Excel',
                    icon: 'bi bi-file-earmark-excel text-success',
                    command: () => {
                        window.open('api/req-div-user-per-bulan-excel/'+this.tahunnUser +'/'+this.bulanUser);
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
      this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Divisi User Per Bulan") || this.checkto.includes("/report-div-user-per-bulan")){
          this.getBulan();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
        getBulan(){
            this.axios.get('api/get-tahun', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.bulan = response.data.grafik2;
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
        getTahunUser(){
            this.tahunnUser = null;
            if(this.bulanUser != null){
                this.axios.get('api/get-tahun-user/'+this.bulanUser, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                    this.tahunn = response.data;
                });
            }
        },
        getPerDivisiUserBulan(){
            if(this.tahunnUser != null &&
                this.bulanUser != null){
                this.loading = true;
                this.axios.get('api/count-per-divuser-bulan/'+this.tahunnUser +'/'+this.bulanUser, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{ 
                    this.req = response.data;
                    this.loading = false;
                }).catch(error => console.log(error.response))
            }
        },
  },
};
</script>