<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.tahunRequestor" @click="printperDivisiRequestorTahun()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.tahunRequestor"/> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Divisi Requestor Per Tahun</h5>
            <Dropdown @change="getPerDivisiRequestorTahun()" :showClear="true" v-model="tahunRequestor" :options="tahunn" optionValue="tahun" optionLabel="tahun" placeholder="Pilih Tahun" />
            <Chart type="bar" :data="perDivisiRequestorTahun" v-if="this.tahunRequestor" id="perDivisiRequestorTahun" />
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
            perDivisiRequestorTahun:{},
            tahunRequestor:null,
            tahunn:[],
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getPerDivisiRequestorTahun();
        }
    },
    created(){ 
        this.cekUser();  
    },
    methods: {
        cekUser(){
        this.axios.get('api/cek-user').then((response)=>{
            this.checkname = response.data.map((x)=> x.name)
            this.checkto = response.data.map((x)=> x.to)
            if(this.checkname.includes("Divisi Requestor Per Tahun") || this.checkto.includes("/req-per-divisi-req-per-tahun")){
            this.getTahun();
            }
            else {
            this.$router.push('/access');
            }
        });
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
        getPerDivisiRequestorTahun(){
            if(this.tahunRequestor != null){
                this.axios.get('api/count-per-divreq-tahun/'+this.tahunRequestor).then((response)=>{ 
                    this.perDivisiRequestorTahun = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label:'Jumlah Request Divisi Requestor Pada Tahun '+this.tahunRequestor ,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                        
                    }
                });
            }
        },
        printperDivisiRequestorTahun(){
            let bar = document.getElementById("perDivisiRequestorTahun");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi Requestor Tahun ' +this.tahunRequestor);
            });
        },
    }
}

</script>