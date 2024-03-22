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
             <form @submit.prevent="UpdateIctDetail">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">No. Request</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="ict.ireq_no"
                    disabled
                  />
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Request Type</label>
                 <div class="col-fixed w-9rem">
                       <Dropdown
                        :options="type"
                        type="text"
                        v-model="ict.ireq_type"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        @change="getIreq(ireq_type)"
                        :showClear="true"
                        :class="{ 'p-invalid': error.ireq_type }"
                  />
                     <small v-if="errors.ireq_type" class="p-error">
                      {{ errors.ireq_type[0] }}
                    </small>
                     <small v-if="error.ireq_type" class="p-error">
                      {{ error.ireq_type }}
                  </small>
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq">
                <label class="col-fixed w-9rem" style="width:120px">Catalog</label>
                 <div class="col-fixed w-9rem">
                     <TreeSelect 
                      v-model="kode" 
                      :options="catalog"
                      placeholder="Select Catalog"
                      display="chip"
                      :class="{ 'p-invalid': errors.invent_code }"
                      @change="change(kode)"
                    />
                     <small v-if="errors.invent_code" class="p-error">
                      {{ errors.invent_code[0] }}
                    </small>
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed w-9rem" style="width:120px">Qty</label>
                 <div class="col-fixed w-9rem">
                     <InputNumber
                        v-model="ict.ireq_qty"
                        placeholder="Enter Qty"
                        :class="{ 'p-invalid': errors.ireq_qty }"
                     />
                     <small v-if="errors.ireq_qty" class="p-error">
                      {{ errors.ireq_qty[0] }}
                    </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Remark</label>
                 <div class="col-fixed w-9rem">
                     <Textarea
                        :autoResize="true"
                        rows="5" 
                        type="text"
                        v-model="ict.ireq_remark"
                        placeholder="Enter Remark"
                        :class="{ 'p-invalid': errors.ireq_remark }"
                     />
                     <small v-if="errors.ireq_remark" class="p-error">
                      {{ errors.ireq_remark[0] }}
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
                  label="Save"
                  type="submit"
                />
                <Button
                  v-if="this.loading == false"
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.go(-1)"
                />
                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" animationDuration=".5s" v-else/>
              </div>
            </form>
          </div>
         <div class="col-sm-6" >
            <div class="field grid" v-if="this.image">
               <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <div class="card" style="height: 20 rem;">
                    <img :src="preview" class="ict-image" v-if="this.preview"/>
                    <img :src="'/attachment_request/'+this.ict.ireq_attachment" class="ict-image" v-else-if="!this.preview"/>
                  </div>
                </div>
              </div>
              <div class="field grid" v-else-if="this.pdf">
               <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <div class="card">
                    <Pdf :src="preview" class="ict-pdf" :page="1" v-if="this.preview" />
                    <Pdf :src="'/attachment_request/'+this.ict.ireq_attachment" :page="1" class="ict-pdf" v-else-if="!this.preview" />
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
      loading: false, 
      errors: [],
      error:[],
      detail: [],
      catalog:[],
      preview:'',
      pdf:false,
      image:false,
      foto:'',
      ict:[],
      kodeperi:[],
      kode:'',
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
    change(kode){
      var a =Object.keys(kode);
      this.ict.invent_code = a.toString();
    },
    getAttach(event) {
      this.foto = event.target.files[0];
      this.error.foto='';
        if(this.foto.size > 1024 * 1024){
          this.error.foto = "File too big (> 1MB)";
        }
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
      this.createFile(this.foto);
    },
    createFile(foto) {
        var reader = new FileReader();
        var vm = this.ict;
        reader.onload = function (e) {
            vm.image = e.target.result;
        };
        reader.readAsDataURL(foto);
      },
    getIreq(){
      this.kode  = '';
       if(this.ict.ireq_type){
        this.axios.get('/api/get-catalog-request/'+this.ict.ireq_type, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
          this.catalog = res.data;
        });
      }
      this.cekTipeReq = this.ict.ireq_type;
       if(this.cekTipeReq == 'S'){
        this.ict.ireq_qty = null;
        this.ict.invent_code = '';
      }
    },
    cekUser(){
      this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Status") || this.checkto.includes("/ict-request")){ 
        this.getIct();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getKode(){
        this.axios.get('/api/getAddDetail',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.type = response.data.ref;
        this.kodeperi = response.data.kode;   
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
    getCatalog(){
      this.axios.get('/api/get-catalog-request/'+this.ict.ireq_type, {headers: {'Authorization': 'Bearer '+this.token}}).then((res)=>{
          this.catalog = res.data;
        });
    },
    getidCatalog(){
      this.kode ="{"+'"'+this.ict.invent_code +'"'+':'+true+"}";
      this.kode = JSON.parse(this.kode);
      this.getKode();
      this.getCatalog();
    },
    getIct(){
        this.axios.get('/api/edit-ict-detail/' +this.$route.params.ireq+'/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.ict = response.data;
          if(this.ict.ireq_attachment){
            if(this.ict.ireq_attachment.split('.').pop()=='jpeg'||this.ict.ireq_attachment.split('.').pop()=='png'||this.ict.ireq_attachment.split('.').pop()=='jpg'){
              this.image = true;
            }
            else{
              this.pdf = true;
            }
          }
          this.cekTipeReq = this.ict.ireq_type;
          this.getidCatalog();
        });
      },
    UpdateIctDetail() {
      this.loading = true;
      if(!this.error.foto){
      this.errors = [];
      this.error = [];
      if(this.ict.ireq_type == 'P'){
       if ( this.ict.ireq_type != null && this.ict.invent_code != null) 
       {

        this.axios.put('/api/update-ict-detail/'+ this.$route.params.ireq+'/'+this.$route.params.code, this.ict,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
        }).catch(error=>{
          this.loading = false;
          this.errors = error.response.data.errors;
         });
      }else{
        this.loading = false;
        if(this.ict.ireq_type == null){
          this.error.ireq_type = "Request Type not filled"
        }
        if(this.ict.invent_code == null){
          this.error.invent_code = "Peripheral not filled"
        }
        if(this.ict.ireq_remark == null){
          this.error.ireq_remark = "Remark not filled"
        }
      }
      }else{
        if ( this.ict.ireq_type != null) 
       {

          this.axios.put('/api/update-ict-detail/' + this.$route.params.ireq +'/'+this.$route.params.code, this.ict,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
          setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
          }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
          });
      }else{
        this.loading = false;
        if(this.ict.ireq_type == null){
          this.error.ireq_type = "Request Type not filled"
        }
        if(this.ict.ireq_remark == null){
          this.error.ireq_remark = "Remark not filled"
        }
      }
      }
      } //size foto
      },
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
</style>