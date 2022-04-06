<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>ICT Request (Detail)</h4>
          </template>
        </Toolbar>
        <div class="row">
          <div class="col-sm-6">
             <form @submit.prevent="CreateIctDetail">
               <div class="field grid">
                <label class="col-fixed" style="width:120px">No. Request</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="detail.noreq"
                    disabled
                  />
                </div>
              </div>
            <div class="field grid">
              <label class="col-fixed" style="width:120px">Tipe Request</label>
                <div class="field col-10 md:col-5">
                  <Dropdown 
                    v-model="tipereq"
                    :options="type"
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Pilih Tipe Request"
                    @change="getIreq(tipereq)"
                    :showClear="true"
                    :class="{ 'p-invalid': error.tipereq }"
                  />
                     <small v-if="errors.tipereq" class="p-error">
                      {{ errors.tipereq[0] }}
                    </small>
                     <small v-if="error.tipereq" class="p-error">
                      {{ error.tipereq }}
                  </small>
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed" style="width:120px">Nama Peripheral</label>
                 <div class="field col-12 md:col-5">
                     <Dropdown 
                        v-model="kode"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Nama Peripheral "
                        @change="getImage()"
                        :showClear="true"
                        :class="{ 'p-invalid': error.kode }"
                     />
                     <small v-if="errors.invent_code" class="p-error">
                      {{ errors.invent_code[0] }}
                    </small>
                     <small v-if="error.kode" class="p-error">
                      {{ error.kode }}
                  </small>
                </div>
              </div>

              <div class="field grid">
                <label class="col-fixed" style="width:120px">Deskripsi</label>
                 <div class="col-fixed">
                     <InputText
                        type="text"
                        v-model="desk"
                        placeholder="Masukan Deskripsi(Optional)"
                     />
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed" style="width:120px">Qty</label>
                 <div class="col-6">
                     <InputNumber
                        v-model="qty"
                        placeholder="Masukan Qty"
                        :class="{ 'p-invalid': errors.qty }"
                     />
                     <small v-if="errors.qty" class="p-error">
                      {{ errors.qty[0] }}
                    </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed" style="width:120px">Keterangan</label>
                 <div class="col-6">
                     <Textarea
                        :autoResize="true"
                        type="text"
                        v-model="ket"
                        rows="5" 
                        placeholder="Masukan Keterangan"
                        :class="{ 'p-invalid': errors.ket }"
                        class="inputfield"
                     />
                     <small v-if="errors.ket" class="p-error">
                      {{ errors.ket[0] }}
                    </small>
                </div>
            </div>
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Simpan"
                  type="submit"
                />
                <Button
                  class="p-button-rounded p-button-success mr-2"
                  icon="pi pi-check"
                  label="Simpan & Add"
                  @click="saveclick"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push({
                    name: 'Ict Request Detail',
                    params: { code: this.$route.params.code }, })"
                />
              </div>
            </form>
          </div>
              <div class="col-6">
                    <img :src="'/master_peripheral/' + photo.photo" class="ict-image" v-if="this.kode" />
              </div>
          </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      errors: [],
      error:[],
      detail: [],
      photo:[],
      kode:'',
      desk:'',
      qty:null,
      ket:'',
      tipereq: '',
      kodeperi:[],
      type: [],
      bu: [],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
      cekTipeReq:'',
    };
  },
  mounted(){
      this.cekUser();
  },
  methods: {
    getIreq(tipereq){
      this.cekTipeReq = tipereq;
      if(this.cekTipeReq == 'S'){
        this.qty = null;
        this.kode = '';
      }
      this.errors = [];
      this.error = [];
    },
    saveclick(){
      this.errors = [];
      this.error = [];
      if(this.tipereq == 'P'){
       if ( this.kode != null && this.tipereq != null && this.tipereq != 'null' ) 
       {
        const data = new FormData();
        data.append("invent_code", this.kode);
        data.append("desk", this.desk);
        data.append("qty", this.qty);
        data.append("ket", this.ket);
        data.append("tipereq", this.tipereq);

        this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
          life: 500
        });
        setTimeout( () => this.kode = null,this.desk = '', this.qty = null, this.ket = '',1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.kode == null){
          this.error.kode = "Nama Peripheral Belum Diisi"
        }
        if(this.tipereq == null){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
      }
     }else{
       if (this.tipereq != null && this.tipereq != 'null' ) 
       {
        const data = new FormData();
        data.append("desk", this.desk);
        data.append("ket", this.ket);
        data.append("tipereq", this.tipereq);

        this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
          life: 500
        });
        setTimeout( () => this.desk = '', this.ket = '',1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.tipereq == null){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
      }
     }
    },
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
           this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getImage(){
      if(this.kode){
      this.axios.get('/api/getImage/'+this.kode, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.photo = response.data;
      });
      }
    },   
    getNoreq(){
      this.axios.get('/api/get-noreq/'+ this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
      this.detail = response.data;
      this.tipereq = this.detail.ireq_type
      this.cekTipeReq = this.detail.ireq_type
      this.getType();
      }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
      },
      getType(){
        this.axios.get('/api/getAddDetail', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.type = response.data.ref;
          this.kodeperi = response.data.kode;
          });
      },
    CreateIctDetail() {
      this.errors = [];
      this.error = [];
      if(this.tipereq=='P'){
       if ( this.kode != null && this.tipereq != null && this.tipereq != 'null' ) 
       {
        const data = new FormData();
        data.append("invent_code", this.kode);
        data.append("desk", this.desk);
        data.append("qty", this.qty);
        data.append("ket", this.ket);
        data.append("tipereq", this.tipereq);

        this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.kode == null){
          this.error.kode = "Nama Peripheral Belum Diisi"
        }
        if(this.tipereq == null){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
      }
      }else{
        if (this.tipereq != null && this.tipereq != 'null' ) 
       {
        const data = new FormData();
        data.append("desk", this.desk);
        data.append("ket", this.ket);
        data.append("tipereq", this.tipereq);

        this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.tipereq == null){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Tipe Request Belum Diisi"
        }
      }
      }
      },
  },
};
</script>
<style scoped lang="scss">
.ict-image {
  width: 450px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>