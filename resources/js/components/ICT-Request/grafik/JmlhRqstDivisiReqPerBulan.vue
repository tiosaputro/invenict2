<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger mr-2" v-if="this.tahunnRequestor && this.bulanRequestor" @click="printperDivisiRequestorBulan()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.tahunnRequestor && this.bulanRequestor" /> 
        </div>
        <div class="text-center">
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Divisi Requestor Per Bulan</h5>
            <Dropdown @change="getTahunRequestor()" :showClear="true" v-model="bulanRequestor" :options="bulan" optionValue="code" optionLabel="name" placeholder="Pilih Bulan" class="mr-2" />
            <Dropdown @change="getPerDivisiRequestorBulan()" :showClear="true" v-model="tahunnRequestor" :options="tahunnnn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" class="mr-2" v-if="this.bulanRequestor" />
            <Chart :options="options" type="bar" :data="perDivisiRequestorBulan" v-if="this.tahunnRequestor && this.bulanRequestor" id="chart" />
        </div>
    </div>
</template>
<script>
import Exporter from "vue-chartjs-exporter";
export default {
    data() {
        return {
            options : {
                title: {
                    position : top,
                    display : true,
                    text: "tes"
                }
            },
            color: '1976D2',
            token: localStorage.getItem('token'),
            perDivisiRequestorBulan:{},
            bulanRequestor:null,
            tahunnRequestor:null,
            tahun:null,
            nameBulanUser: null,
            tahunn:[],
            tahunnnn: [],
            bulan:[], 
            checkname : [],
            checkto : [],
        };
    },
    watch : {
        "color"(){
            this.getPerDivisiRequestorBulan();
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
            if(this.checkname.includes("Divisi Requestor Per Bulan") || this.checkto.includes("/req-per-divisi-req-per-bulan")){
                this.getBulan();
            }
            else {
            this.$router.push('/access');
            }
         });
        },
        getTahunRequestor(){
            this.tahunnRequestor = null;
            if(this.bulanRequestor != null){
                this.axios.get('api/get-tahun-requestor/'+this.bulanRequestor, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                    this.tahunnnn = response.data;
                });
            }
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
        getPerDivisiRequestorBulan(){
            if(this.tahunnRequestor != null &&
                this.bulanRequestor != null){
                this.axios.get('api/count-per-divreq-bulan/'+this.tahunnRequestor +'/'+this.bulanRequestor, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{ 
                    this.nameBulanRequestor = response.data[0].bulan + this.tahunnRequestor;
                    this.perDivisiRequestorBulan = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label: 'Jumlah Request Divisi Requestor Pada Bulan '+response.data[0].bulan+this.tahunnRequestor,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                    }
                }).catch(error => console.log(error))
            }
        },
        printperDivisiRequestorBulan(){
            let bar = document.getElementById("chart");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi Requestor Bulan '+this.nameBulanRequestor);
            });
        },
    }
}

</script>