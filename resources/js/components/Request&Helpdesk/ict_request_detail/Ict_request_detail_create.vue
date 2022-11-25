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
              <div class="field grid" v-if="this.cekTipeReq">
                <label class="col-fixed w-9rem"> Catalog </label>
                 <div class="col-4 md-4">
                    <TreeSelect 
                      v-model="requestcatalog" 
                      :options="catalog"
                      selectionMode="single"
                      :filter="true"
                      placeholder="Select Catalog"
                      display="chip"
                      :class="{ 'p-invalid': errors.catalog }"
                      @change="change(requestcatalog)"
                    /> 
                     <small v-if="errors.catalog" class="p-error">
                      {{ errors.catalog[0] }}
                    </small>
                     <small v-if="error.requestcatalog" class="p-error">
                      {{ error.requestcatalog }}
                  </small>
                </div>
              </div>
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
              <div class="field grid" v-if="this.cekTipeReq =='P' || this.cekTipeReq =='S'">
                <label class="col-fixed w-9rem">Remark</label>
                 <div class="col-fixed w-9rem">
                     <Textarea
                        :autoResize="true"
                        type="text"
                        v-model="ket"
                        rows="8"
                        cols="30"
                        placeholder="Enter Remark"
                        :class="{ 'p-invalid': errors.ket }"
                        class="inputfield"
                     />
                     <small v-if="errors.ket" class="p-error">
                      {{ errors.ket[0] }}
                    </small>
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq =='P' || this.cekTipeReq =='S'">
                <label class="col-10 md-4">Attachment (JPEG,PNG,JPG,PDF) MAX SIZE 1MB</label>
                  <input type="file" name="foto" accept="image/jpg,image/png,image/jpeg,application/pdf" ref="fileInput" class="form-control" @change="getAttach" />
                    <small v-if="error.foto" class="p-error">
                      {{ error.foto }}
                    </small>
              </div>
              <div class="form-group">
                 <Button
                  v-if="this.loading == false"
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  v-tooltip.bottom="'Click to save detail'"
                  label="Save"
                  type="submit"
                />
                <Button
                  v-if="this.loading == false"
                  class="p-button-rounded p-button-success mr-2 mt-2"
                  icon="pi pi-plus"
                  v-tooltip.bottom="'Click to save & add new detail'"
                  label="Add Request"
                  @click="saveclick"
                />
                <Button
                  v-if="this.loading == false"
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  v-tooltip.bottom="'Click to cancel create detail'"
                  icon="pi pi-times"
                  @click="$router.push({
                    name: 'Ict Request Detail',
                    params: { code: this.$route.params.code }, })"
                />
                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" v-else/>
              </div>
             </form>
           </div>
           <div class="col-sm-6" >
            <div class="field grid" v-if="this.image">
               <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <div class="card" style="height: 20 rem;">
                    <img :src="preview" class="ict-image" />
                  </div>
                </div>
              </div>
              <div class="field grid" v-else-if="this.pdf">
               <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <div class="card">
                    <Pdf :src="preview" class="ict-pdf" />
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      loading:false,
      errors: [],
      error:[],
      detail: [],
      file:false,
      pdf:false,
      foto:'',
      preview:'',
      requestcatalog:[],
      kode:'',
      catalog:[],
      image:'',
      desk:'',
      qty:null,
      ket:'',
      tipereq: '',
      type: [],
      bu: [],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      cekTipeReq:'',
    };
  },
  mounted(){
      this.cekUser();
  },
  methods: {
    change(){
      this.kode = Object.keys(this.requestcatalog);
    },
    getAttach(event) {
      this.foto = event.target.files[0];
      this.error.foto = '';
        this.error.foto = '';
        if(this.foto['type']==='image/jpeg'||this.foto['type']==='image/jpg'||this.foto['type']==='image/png'){
          this.pdf = false;
          this.image = true;
          this.preview = URL.createObjectURL(this.foto);
        }
        if(this.foto['type']==='application/pdf'){
          this.image = false;
          this.pdf = true;
          this.preview = URL.createObjectURL(this.foto);
        }
    },
    getIreq(tipereq){
      this.requestcatalog = '';
      this.cekTipeReq = tipereq;
      if(tipereq != null){
        this.axios.get('/api/get-catalog-request/'+tipereq, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
          this.catalog = res.data;
        });
      }
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
      if(this.foto){
        if(this.foto.size > 1024 * 1024){
        this.error.foto = "File too big (> 1MB)"
      }
      else{
       if(this.tipereq == 'P'){
        if ( this.kode != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("file", this.foto);
          data.append("catalog", this.kode);
          data.append("qty", this.qty);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
            life: 500
          });
          setTimeout( () => this.kode = null,this.requestcatalog = null, this.desk = '', this.qty = null, this.ket = '', this.preview = '',this.$refs.fileInput.value = '', this.pdf=false,this.image = false,1000);
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
      }else{
        if (this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("file", this.foto);
          data.append("catalog", this.kode);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token, 'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
            life: 500
          });
          setTimeout( () => this.kode = null, this.requestcatalog = null, this.desk = '', this.ket = '',this.preview = '', this.pdf=false, this.image = false, this.$refs.fileInput.value = null,1000);
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
      }
     }else{ //if not attachment
      if(this.tipereq == 'P'){
        if ( this.kode != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("catalog", this.kode);
          data.append("qty", this.qty);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
            life: 500
          });
          setTimeout( () => this.kode = null,this.requestcatalog = null,this.desk = '', this.qty = null, this.ket = '', this.preview = '',this.$refs.fileInput.value = '', this.pdf=false,this.image = false,1000);
          }).catch(error=>{
            this.errors = error.response.data.errors;
          });
        }else{
          if(this.kode == null){
            this.error.kode = "Catalog not filled"
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
          data.append("ket", this.ket);
          data.append("catalog", this.kode);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token, 'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
            life: 500
          });
          setTimeout( () => this.kode = null,this.requestcatalog = null, this.desk = '', this.ket = '',this.preview = '', this.pdf=false, this.image = false, this.$refs.fileInput.value = null,1000);
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
     }
    },
    cekUser(){
      this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Status") || this.checkto.includes("/ict-request")){ 
           this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
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
            severity:'error', summary: 'Error', detail:'Session login expired'
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
          });
    },
    CreateIctDetail() {
      this.errors = [];
      this.error = [];
      if(this.foto){
        if(this.foto.size > 1024 * 1024){
        this.error.foto = "File too big (> 1MB)"
      }
      else{
        if(this.tipereq=='P'){
        this.loading = true;
        if ( this.requestcatalog != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("catalog", this.kode);
          data.append("file", this.foto);
          data.append("qty", this.qty);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
          }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
          });
        }else{
          this.loading = false;
          if(this.tipereq == null){
            this.error.tipereq = "Request Type not filled"
          }
          if(this.tipereq == 'null'){
            this.error.tipereq = "Request Type not filled"
          }
        }
        }else{
          this.loading = true;
          if ( this.requestcatalog != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("catalog", this.kode);
          data.append("file", this.foto);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
          }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
          });
        }else{
            this.loading = false;
          if(this.tipereq == null){
            this.error.tipereq = "Request Type not filled"
          }
          if(this.tipereq == 'null'){
            this.error.tipereq = "Request Type not filled"
          }
        }
       }
      }
     }else{
      this.loading = true;
      if(this.tipereq=='P'){
        if ( this.requestcatalog != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("catalog", this.kode);
          data.append("qty", this.qty);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
          }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
          });
        }else{
          this.loading = false;
          if(this.tipereq == null){
            this.error.tipereq = "Request Type not filled"
          }
          if(this.tipereq == 'null'){
            this.error.tipereq = "Request Type not filled"
          }
        }
        }else{
            this.loading = true;
          if (this.requestcatalog != null && this.tipereq != null && this.tipereq != 'null' ) 
        {
          const data = new FormData();
          data.append("catalog", this.kode);
          data.append("ket", this.ket);
          data.append("tipereq", this.tipereq);

          this.axios.post('/api/add-ict-detail/' + this.$route.params.code, data, {headers: {'Authorization': 'Bearer '+this.token,'content-type': 'multipart/form-data'}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
          }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
          });
        }else{
          this.loading = false;
          if(this.tipereq == null){
            this.error.tipereq = "Request Type not filled"
          }
          if(this.tipereq == 'null'){
            this.error.tipereq = "Request Type not filled"
          }
        }
       }
     }
    }
  },
};
</script>
<style scoped lang="scss">
.ict-image {
  height:330pt;
  object-fit:contain;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
.ict-pdf {
    height:100%;
    width:100%;
    object-fit:contain;
}
.p-treeselect {
    width:15rem;
}

@media screen and (max-width: 640px) {
    .p-treeselect {
        width: 100%;
    }
}
</style>