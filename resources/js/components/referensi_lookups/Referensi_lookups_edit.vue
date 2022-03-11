<template>
  <div class="container py-4">
    <div class="col-md-10">
        <Toast />
        <div class="card">
        <Toolbar class="p-mb-4">
          <template v-slot:start>
				        <h4>Referensi - Lookups</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="UpdateLookup">
               <div class="field grid">
                <label style="width:120px">Tipe</label>
                 <div class="col-3">
                  <InputText
                    type="text"
                    v-model="ref.lookup_type"
                  disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Kode</label>
                 <div class="col-3">
                  <InputText
                    type="text"
                    v-model="ref.lookup_code"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Deskripsi</label>
                 <div class="col-3">
                <InputText
                  type="text"
                  v-model="ref.lookup_desc"
                  :class="{ 'p-invalid': errors.lookup_desc }"
                />
                   <small v-if="errors.lookup_desc" class="p-error">
                      {{ errors.lookup_desc[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Status</label>
                 <div class="col-3">
               <Dropdown
                  v-model="ref.lookup_status"
                  :options="stat"
                  :showClear="true"
                  optionLabel="nama"
                  optionValue="code"
                  :class="{ 'p-invalid': errors.lookup_status }"
                />
                  <small v-if="errors.lookup_status" class="p-error">
                      {{ errors.lookup_status[0] }}
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
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/referensi-lookups')"
                />
              </div>
            </form>
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
      id : localStorage.getItem('id'),
      checkname : [],
      checkto : [],
      ref: [],
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
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
        if(this.checkname.includes("Lookups") || this.checkto.includes("/referensi-lookups")){
          this.getRef();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      getRef(){
        this.axios.get('/api/edit-ref/' + this.$route.params.code + '/' + this.$route.params.type, {headers: {'Authorization': 'Bearer '+this.token}} ).then((response)=> {
            this.ref = response.data;
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
    UpdateLookup(){
        this.errors = [];   
        this.axios.put('/api/update-ref/' + this.$route.params.code + '/' + this.$route.params.type, this.ref, {headers: {'Authorization': 'Bearer '+this.token}} ).then((response) => {
           this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
         setTimeout( () => this.$router.push('/referensi-lookups'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },
  },
};
</script>