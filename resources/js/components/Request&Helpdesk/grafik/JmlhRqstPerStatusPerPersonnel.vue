<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" v-if="this.ictPersonnel" @click="printPerStatusIct()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color" v-if="this.ictPersonnel"/> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Per Status Request Per ICT-Personnel</h5>
            <Dropdown @change="getPerStatusIct()" :showClear="true" v-model="ictPersonnel" :options="personnel" optionValue="name" optionLabel="name" placeholder="Pilih Personnel"/>
            <Chart type="bar" :data="PerStatusIct" v-if="this.ictPersonnel" id="PerStatusIct" />
        </div>
    </div>
</template>
<script>
import Exporter from "vue-chartjs-exporter";
export default {
    data() {
        return {
            color: '1976D2',
            PerStatusIct:{},
            ictPersonnel: null,
            personnel:[],
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getPerStatusIct();
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
            if(this.checkname.includes("Per Status Per Personnel") || this.checkto.includes("/req-per-status-per-personnel")){
            this.getPersonnel();
            }
            else {
            this.$router.push('/access');
            }
        });
        },
        getPersonnel(){
            this.axios.get('api/get-tahun').then((response)=>{
                this.personnel = response.data.personnell;
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
        getPerStatusIct(){
            if(this.ictPersonnel !=null){
                this.axios.get('api/count-per-status-ict/'+ this.ictPersonnel).then((response)=>{
                    this.PerStatusIct = {
                        labels : response.data.map((x)=>x.status),
                        datasets : [
                            {
                                label: 'Statistik Status Request Per Personnel ('+this.ictPersonnel+')',
                                backgroundColor: '#'+this.color,
                                data: response.data.map((x)=>x.jumlah)
                            },
                        ]
                    }
                });
            }
        },
        printPerStatusIct(){
            let bar = document.getElementById("PerStatusIct");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Per Status Per ICT-Personnel '+this.ictPersonnel);
            });
        },
    }
}

</script>