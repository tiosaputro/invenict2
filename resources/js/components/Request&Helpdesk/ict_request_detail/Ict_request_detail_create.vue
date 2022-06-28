<template>
  <div>
    <Toast />
      <div class="card">
      <Toolbar class="mb-4">
        <template v-slot:start>
				  <h4>ICT Request (Detail)</h4>
        </template>
      </Toolbar>
          <div class="card-body">
             <form @submit.prevent="CreateIctDetail">
               <div class="field grid">
                <label class="col-fixed w-9rem">No. Request</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="detail.noreq"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
               <label class="col-fixed w-9rem">Request Type</label>
                <div class="col-fixed w-9rem">
                  <Dropdown 
                    v-model="tipereq"
                    :options="type"
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Select One"
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
                <label class="col-fixed w-9rem"> Peripheral</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="kode"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One "
                        :showClear="true"
                        :filter="true"
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
              <!-- <div class="field grid" v-if="this.cekTipeReq =='P' || this.cekTipeReq =='S' ">
                <label class="col-fixed w-9rem">Description</label>
                 <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model="desk"
                        placeholder="Masukan Deskripsi(Optional)"
                     />
                </div>
              </div> -->
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed w-9rem">Qty</label>
                 <div class="col-fixed w-9rem">
                     <InputNumber
                        v-model="qty"
                        placeholder="Enter Qty"
                        :class="{ 'p-invalid': errors.qty }"
                     />
                     <small v-if="errors.qty" class="p-error">
                      {{ errors.qty[0] }}
                    </small>
                </div>
              </div>
              <div class="field grid"  v-if="this.cekTipeReq =='P' || this.cekTipeReq =='S'">
                <label class="col-fixed w-9rem">Remark</label>
                 <div class="col-fixed w-9rem">
                     <Textarea
                        :autoResize="true"
                        type="text"
                        v-model="ket"
                        rows="5" 
                        placeholder="Enter Remark"
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
                  v-tooltip.left="'Click to save detail'"
                  label="Save"
                  type="submit"
                />
                <Button
                  class="p-button-rounded p-button-success mr-2 mt-2"
                  icon="pi pi-plus"
                  v-tooltip.bottom="'Click to save & add new detail'"
                  label="Add Request"
                  @click="saveclick"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  v-tooltip.right="'Click to cancel create data'"
                  icon="pi pi-times"
                  @click="$router.push({
                    name: 'Ict Request Detail',
                    params: { code: this.$route.params.code }, })"
                />
              </div>
            </form>
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
          this.error.kode = "Peripheral not filled"
        }
        if(this.tipereq == null){
          this.error.tipereq = "Request Type not filled"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Request Type not filled"
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
          this.error.tipereq = "Request Type not filled"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Request Type not filled"
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
          this.error.kode = "Peripheral not filled"
        }
        if(this.tipereq == null){
          this.error.tipereq = "Request Type not filled"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Request Type not filled"
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
          this.error.tipereq = "Request Type not filled"
        }
        if(this.tipereq == 'null'){
          this.error.tipereq = "Request Type not filled"
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