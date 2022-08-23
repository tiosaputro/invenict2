<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.tahunUser" @click="printPerDivisiUserTahun()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.tahunUser"/> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Divisi User Per Tahun</h5>
            <Dropdown @change="getPerDivisiUserTahun()" :showClear="true" v-model="tahunUser" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" />
            <Chart type="bar" :data="perDivisiUserTahun" v-if="this.tahunUser" id="perStatus" />
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
            perDivisiUserTahun:{},
            tahunUser:null,
            tahunn:[],
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getPerDivisiUserTahun();
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
            if(this.checkname.includes("Divisi User Per Tahun") || this.checkto.includes("/req-per-divisi-user-per-tahun")){
            this.getTahun();
            }
            else {
            this.$router.push('/access');
            }
        });
        },
         getTahun(){
            this.axios.get('api/get-tahun', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
        getPerDivisiUserTahun(){
            if(this.tahunUser != null){
                this.axios.get('api/count-per-divuser-tahun/'+this.tahunUser, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{ 
                    this.perDivisiUserTahun = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label:this.tahunUser ,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                        
                    }
                });
            }
        },
        printPerDivisiUserTahun(){
            let bar = document.getElementById("perDivisiUserTahun");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi User Tahun '+this.tahunUser);
            });
        },
    }
}

</script>