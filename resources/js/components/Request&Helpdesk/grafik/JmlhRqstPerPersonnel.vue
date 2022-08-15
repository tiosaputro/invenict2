<template>
<Toast/>
    <div class="card">
        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
            <Button class="p-button-lg p-button-rounded p-button-danger" @click="printstatusPerIctPersonnel()" icon="pi pi-file-pdf" label="PDF"/>
            <ColorPicker v-model="color"/> 
        </div>
        <div class="text-center" >
            <h5 style="font-size:20pt; font-weight: bold;">Statistik Request Per ICT-Personnel</h5>
            <Chart type="bar" :data="statusPerIctPersonnel" id="statusPerIctPersonnel"/>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import Exporter from "vue-chartjs-exporter";
export default {
    data() {
        return {
            color: '1976D2',
            token: localStorage.getItem('token'),
            statusPerIctPersonnel:{},
            date: new Date(),
            id : localStorage.getItem('id'),
            checkname : [],
            checkto : []
        };
    },
    watch : {
        "color"(){
            this.getPersonnel();
        }
    },
    created(){ 
        this.cekUser(); 
    },
    methods: {
        cekUser(){
        if(this.id){
        this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.checkname = response.data.map((x)=> x.name)
            this.checkto = response.data.map((x)=> x.to)
            if(this.checkname.includes("Per Personnel") || this.checkto.includes("/req-per-personnel")){
            this.getPersonnel();
            }
            else {
            this.$router.push('/access');
            }
        });
      } else {
        this.$router.push('/login');
      }
        },
        getPersonnel(){
            this.axios.get('api/get-tahun', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.statusPerIctPersonnel = {
                        labels : response.data.personnel.map((x)=>x.ireq_assigned_to),
                        datasets : [
                            {
                                label:'ICT Personnel',
                                backgroundColor: '#'+this.color,
                                data: response.data.personnel.map((x)=>x.jumlah)
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
        printstatusPerIctPersonnel(){
            const formattedDate = moment(this.date).format('DD MMM YYYY')
            console.log(formattedDate)
            let bar = document.getElementById("statusPerIctPersonnel");
            const exp = new Exporter([bar]);
            exp.export_pdf().then((pdf) => {
                pdf.save('Statistik Request Per ICT-Personnel ' + formattedDate);
            });
        },
    }
}

</script>