<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Location</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="CreateLocation">
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Kode</label>
                 <div class="col">
                <InputText
                  type="text"
                  v-model="loc_code"
                  placeholder="Masukan Kode"
                  :class="{ 'p-invalid': errors.loc_code }"
                />
                   <small v-if="errors.loc_code" class="p-error">
                      {{ errors.loc_code[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Deskripsi</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="loc_desc"
                    placeholder="Masukan Deskripsi"
                    :class="{ 'p-invalid': errors.loc_desc }"
                  />
                    <small v-if="errors.loc_desc" class="p-error">
                      {{ errors.loc_desc[0] }}
                  </small>
                </div>
              </div>
              
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Email</label>
                 <div class="col">
                <InputText
                  type="email"
                  v-model="loc_email"
                  placeholder="Masukan Email"
                  :class="{ 'p-invalid': errors.loc_email }"
                />
                   <small v-if="errors.loc_email" class="p-error">
                      {{ errors.loc_email[0] }}
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
                  @click="$router.push('/referensi-lookups')"
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
      loc_code:'' ,
      loc_desc:'' ,
      loc_email: '',
      token: localStorage.getItem('token'),
      id : localStorage.getItem('id'),
      checkname : [],
      checkto : [],
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Location") || this.checkto.includes("/referensi-location")){
        }
        else {
          this.$router.push('/access');
        }
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
    CreateLocation() {
        this.errors = [];

        const data = new FormData();
        data.append("loc_code", this.loc_code);
        data.append("loc_desc", this.loc_desc);
        data.append("loc_email", this.loc_email);

        this.axios.post('api/add-loc', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((resoonse)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/referensi-location'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>