<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" @click="printperStatus()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color"/> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request</h5>
            <Chart type="bar" :data="perStatus"  id="perStatus"/>
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
            perStatus:{},
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getTahun();
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
                    if(this.checkname.includes("Per Status") || this.checkto.includes("/req-per-divisi-req-per-status")){
                    this.getTahun();
                    }
                    else {
                    this.$router.push('/access');
                    }
             });
          },
        getTahun(){
            this.axios.get('api/get-tahun', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                    this.perStatus = {
                        labels : response.data.grafik3.map((x)=>x.ireq_status),
                        datasets : [
                            {
                                label: 'Jumlah Request Per Status',
                                backgroundColor: '#'+this.color,
                                data: response.data.grafik3.map((x)=>x.jumlah)
                            },
                        ]
                    }
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
        printperStatus(){
                let bar = document.getElementById("perStatus");
                const exp = new Exporter([bar]);
                exp.export_pdf().then((pdf) => {
                    pdf.save("Statistik Request User.pdf");
                });
            },
    }
}

</script>