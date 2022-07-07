<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="p-mb-4">
          <template v-slot:start>
				        <h4>Location</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="UpdateLocation">
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Kode</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="loc.loc_code"
                  disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Deskripsi</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="loc.loc_desc"
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
                  v-model="loc.loc_email"
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
                  label="Update"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/referensi-location')"
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
      id : localStorage.getItem('id'),
      checkname : [],
      checkto : [],
      loc: [],
      token: localStorage.getItem('token'),
      name:''
    };
  },
  created(){
    this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Location") || this.checkto.includes("/referensi-location")){
          this.getLoc();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      getLoc(){
        this.axios.get('/api/edit-loc/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}} ).then((response)=> {
            this.loc = response.data;
        }).catch(error=>{
          if ((error.response.status == 401)){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
      },
    UpdateLocation(){
        this.errors = [];   
        this.axios.put('/api/update-loc/' + this.$route.params.code, this.loc, {headers: {'Authorization': 'Bearer '+this.token}} ).then((response) => {
           this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
         setTimeout( () => this.$router.push('/referensi-location'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },
  },
};
</script>