<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.statusRequestor" @click="printstatusPerDivisiRequestor()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.statusRequestor"/> 
        </div>
        <div class="text-center">
            <h5 style="font-size:20pt;">Statistik Request Divisi Requestor Per Status</h5>
            <Dropdown @change="getStatusDivisiRequestor()" :showClear="true" v-model="statusRequestor" :options="status" optionValue="code" optionLabel="name" placeholder="Pilih Status"/>
            <Chart type="bar" :data="statusPerDivisiRequestor" v-if="this.statusRequestor" id="statusPerDivisiRequestor" />
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
            statusPerDivisiRequestor:{},
            statusRequestor: null,
            status: [],
            nameStatusRequestor:null,
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getStatusDivisiRequestor();
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
            if(this.checkname.includes("Divisi Requestor Per Status") || this.checkto.includes("/req-per-divisi-req-per-status")){
            this.getStatus();
            }
            else {
            this.$router.push('/access');
            }
        });
        },
        getStatus(){
            this.axios.get('api/get-tahun').then((response)=>{
                this.status = response.data.grafik1;
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
        getStatusDivisiRequestor(){
            if(this.statusRequestor != null){
                this.axios.get('api/count-per-divreq-status/'+this.statusRequestor).then((response)=>{
                if(response.data.length){
                    this.nameStatusRequestor = response.data[0].name;
                    this.statusPerDivisiRequestor = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label: 'Jumlah Request Divisi Requestor Per Status'+response.data[0].name,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                    }
                } else{
                    this.statusPerDivisiRequestor={};
                }
                });
            }
        },
        
        printstatusPerDivisiRequestor(){
            let bar = document.getElementById("statusPerDivisiRequestor");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi Requestor Per Status ' +this.nameStatusRequestor);
            });
        },
    }
}

</script>