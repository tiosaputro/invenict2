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
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="ict.ireq_no"
                    disabled
                  />
                </div>
              </div>

            <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tipe Request</label>
                 <div class="field col-12 md:col-4">
                       <Dropdown
                       :options="type"
                        type="text"
                        v-model="ict.ireq_type"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Tipe Request"
                        @change="getIreq()"
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
              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed w-9rem" style="width:120px">Nama Peripheral</label>
                 <div class="field col-12 md:col-4">
                     <Dropdown 
                        v-model="ict.invent_code"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Nama Peripheral "
                        :showClear="true"
                        @change="getImage()"
                        :class="{ 'p-invalid': errors.invent_code }"
                     />
                     <small v-if="errors.invent_code" class="p-error">
                      {{ errors.invent_code[0] }}
                    </small>
                     <small v-if="error.invent_code" class="p-error">
                      {{ error.invent_code }}
                    </small>
              </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Deskripsi</label>
                 <div class="col">
                     <InputText
                        type="text"
                        v-model="ict.ireq_desc"
                        placeholder="Masukan Deskripsi(Optional)"
                     />
                </div>
              </div>

              <div class="field grid" v-if="this.cekTipeReq =='P'">
                <label class="col-fixed w-9rem" style="width:120px">Qty</label>
                 <div class="col">
                     <InputNumber
                        v-model="ict.ireq_qty"
                        placeholder="Masukan Qty"
                        :class="{ 'p-invalid': errors.ireq_qty }"
                     />
                     <small v-if="errors.ireq_qty" class="p-error">
                      {{ errors.ireq_qty[0] }}
                    </small>
                </div>
              </div>

              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Keterangan</label>
                 <div class="col">
                     <Textarea
                        :autoResize="true"
                        rows="5" 
                        type="text"
                        v-model="ict.ireq_remark"
                        placeholder="Masukan ket"
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
                  label="Simpan"
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
          <div class="col-6" v-if="this.cekTipeReq =='P'">
                    <img :src="'/master_peripheral/' + ict.photo" class="ict-image" v-if="this.ict.photo && !this.kode" />
                    <img :src="'/master_peripheral/' + ict.photo.photo" class="ict-image" v-if="this.kode" />
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
      ict:[],
      kodeperi:[],
      kode:'',
      type: [],
      bu: [],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
      cekTipeReq:'',
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    getIreq(){
      this.cekTipeReq = this.ict.ireq_type
    },
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
           this.getKode();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getKode(){
          this.axios.get('/api/get-kode',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.kodeperi = response.data;         
              this.getReq();
              this.getIct();
          }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
      },
      getIct(){
          this.axios.get('/api/edit-ict-detail/'+this.$route.params.code + '/' +this.$route.params.ireq,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.ict = response.data;
              this.cekTipeReq = this.ict.ireq_type
          });
      },
      getReq(){
          this.axios.get('/api/getType',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.type = response.data;
          });
      },
      getImage(){
        this.kode = this.ict.invent_code;
        if(this.kode){
          this.axios.get('/api/getImage/'+this.kode, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.ict.photo = response.data;
          });
        }
      },
    UpdateIctDetail() {
      this.errors = [];
      this.error = [];
      if(this.ict.ireq_type == 'P'){
       if ( this.ict.ireq_type != null && this.ict.invent_code != null) 
       {
        this.axios.put('/api/update-ict-detail/' + this.$route.params.code + '/' + this.$route.params.ireq, this.ict,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.ict.ireq_type == null){
          this.error.ireq_type = "Tipe Request Belum Diisi"
        }
        if(this.ict.invent_code == null){
          this.error.invent_code = "Nama Peripheral Belum Diisi"
        }
      }
      }else{
        if ( this.ict.ireq_type != null) 
       {
        this.axios.put('/api/update-ict-detail/' + this.$route.params.code + '/' + this.$route.params.ireq, this.ict,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/ict-request-detail/' +this.$route.params.code),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.ict.ireq_type == null){
          this.error.ireq_type = "Tipe Request Belum Diisi"
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