<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.tahunnUser && this.bulanUser" @click="printperDivisiUserBulan()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.tahunnUser && this.bulanUser" /> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Divisi User Per Bulan</h5>
            <Dropdown @change="getTahunUser()" :showClear="true" v-model="bulanUser" :options="bulan" optionValue="code" optionLabel="name" placeholder="Pilih Bulan" class="mr-2" />
            <Dropdown @change="getPerDivisiUserBulan()" :showClear="true" v-model="tahunnUser" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" class="mr-2" v-if="this.bulanUser" />
            <Chart type="bar" :data="perDivisiUserBulan" v-if="this.tahunnUser && this.bulanUser" id="perStatus" />
        </div>
    </div>
</template>
<script>
import Exporter from "vue-chartjs-exporter";
export default {
    data() {
        return {
            color: '1976D2',
            token: localStorage.getItem('token'),
            perDivisiUserBulan:{},
            bulanUser:null,
            tahunnUser:null,
            tahun:null,
            nameBulanUser: null,
            tahunn:[],
            bulan:[],
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getPerDivisiUserBulan();
        }
    },
    created(){ 
        this.cekUser();  
    },
    methods: {
        cekUser(){
        this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.checkname = response.data.map((x)=> x.name)
            this.checkto = response.data.map((x)=> x.to)
            if(this.checkname.includes("Divisi User Per Bulan") || this.checkto.includes("/req-per-divisi-user-per-bulan")){
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
                this.axios.get('api/count-per-divuser-bulan/'+this.tahunnUser +'/'+this.bulanUser, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{ 
                    this.nameBulanUser = response.data[0].bulan+this.tahunnUser;
                    this.perDivisiUserBulan = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label: 'Jumlah Request Divisi User Pada Bulan '+this.nameBulanUser,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                        
                    }
                }).catch(error => console.log(error.response))
            }
        },
        printperDivisiUserBulan(){
            let bar = document.getElementById("perDivisiUserBulan");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi User Per Bulan '+this.tahunUser);
            });
        },
    }
}

</script>