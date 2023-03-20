<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.statusUser" @click="printstatusPerDivisiUser()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.statusUser"/> 
        </div>
        <div class="text-center" >
        <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Divisi User Per Status</h5>
        <Dropdown @change="getStatusDivisiUser()" :showClear="true" v-model="statusUser" :options="status" optionValue="code" optionLabel="name" placeholder="Pilih Status"/>
        <Chart type="bar" :data="statusPerDivisiUser" v-if="this.statusUser"  id="statusPerDivisiUser"/>
    </div>
    </div>
</template>
<script>
import Exporter from "vue-chartjs-exporter";
export default {
    data() {
        return {
            statusUser:null,
            status: [],
            color: '1976D2',
            statusPerDivisiUser:{},
            nameStatusUser : null,
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getStatusDivisiUser();
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
            if(this.checkname.includes("Divisi User Per Status") || this.checkto.includes("/req-per-divisi-per-status")){
            this.getStatus();
            }
            else {
            this.$router.push('/access');
            }
        });
        },
        getStatusDivisiUser(){
            if(this.statusUser != null){
                this.axios.get('api/count-per-divuser-status/'+this.statusUser).then((response)=>{
                  if(response.data.length){
                    this.nameStatusUser = response.data[0].name;
                    this.statusPerDivisiUser = {
                        labels : response.data.map((x)=>x.div_name),
                        datasets : [
                            {
                                label: response.data[0].name,
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                    }
                  }else{
                      this.statusPerDivisiUser = {};
                  }
                });
            }
        },
        printstatusPerDivisiUser(){
            let bar = document.getElementById("statusPerDivisiUser");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Divisi User Per Status  '+this.nameStatusUser);
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
    }
}

</script>