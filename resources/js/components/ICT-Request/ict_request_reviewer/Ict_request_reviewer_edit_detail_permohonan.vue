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
                  <InputText 
                      v-model="ict.request_type" 
                      disabled
                    />
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem"> Items </label>
                 <div class="col-4 md-4">
                    <InputText 
                      v-model="ict.name" 
                      disabled
                    />
                </div>
              </div>
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed w-9rem" style="width:120px">Qty</label>
                 <div class="col-fixed w-9rem">
                     <InputNumber
                        v-model="ict.ireq_qty"
                        placeholder="Enter Qty"
                        :class="{ 'p-invalid': errors.ireq_qty }"
                        disabled
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
                        cols="30" 
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
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Save"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.go(-1)"
                />
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
                        <Pdf :src="preview" :page="1" class="ict-pdf" v-if="this.preview" />
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
      errors: [],
      error:[],
      detail: [],
      preview:'',
      pdf:false,
      image:false,
      foto:'',
      ict:[],
      kodeperi:[],
      kode:'',
      type: [],
      bu: [],
      checkname : [],
      checkto : [],
      cekTipeReq:'',
    };
  },
  created(){
    this.getIct();
  },
  methods: {
    getAttach(event) {
      this.foto = event.target.files[0];
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
      this.cekTipeReq = this.ict.ireq_type;
       if(this.cekTipeReq == 'S'){
        this.ict.ireq_qty = null;
        this.ict.invent_code = '';
      }
    },
    getIct(){
        this.axios.get('/api/edit-ict-detail-reviewer/' +this.$route.params.ireq+'/'+this.$route.params.code).then((response)=>{
          this.ict = response.data.data.request;
          this.ict.ireq_qty = parseInt(this.ict.ireq_qty,10);
          if(this.ict.ireq_attachment){
            if(this.ict.ireq_attachment.split('.').pop()=='jpeg'||this.ict.ireq_attachment.split('.').pop()=='png'||this.ict.ireq_attachment.split('.').pop()=='jpg'){
              this.image = true;
            }
            else{
              this.pdf = true;
            }
          }
          this.cekTipeReq = this.ict.ireq_type;
        }).catch(error => {
          if(error.response.status == 403){
            this.$router.push('/access');
          }
        });
    },
    UpdateIctDetail() {
      this.errors = [];
      this.error = [];
      if(this.ict.ireq_type == 'P'){
       if ( this.ict.ireq_remark != null) 
       {
        this.axios.put('/api/update-ict-detail/'+ this.$route.params.ireq+'/'+this.$route.params.code, this.ict).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.go(-1),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.ict.ireq_remark == null){
          this.error.ireq_remark = "Remark not filled"
        }
      }
      }else{
        if ( this.ict.ireq_remark != null) 
       {
          this.axios.put('/api/update-ict-detail/' + this.$route.params.ireq +'/'+this.$route.params.code, this.ict).then(()=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
          setTimeout( () => this.$router.go(-1),1000);
          }).catch(error=>{
            this.errors = error.response.data.errors;
          });
      }else{
        if(this.ict.ireq_remark == null){
          this.error.ireq_remark = "Remark not filled"
        }
      }
      }
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