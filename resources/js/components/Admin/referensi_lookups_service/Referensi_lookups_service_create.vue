<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>Referensi - Service Catalog</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="CreateLookup">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Kode</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="lookup_code"
                    placeholder="Masukan Kode. . ."
                    :class="{ 'p-invalid': errors.lookup_code  }"
                  />
                    <small v-if="errors.lookup_code" class="p-error">
                      {{ errors.lookup_code[0] }}
                  </small>
                </div>
              </div>
              
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Deskripsi</label>
                 <div class="col-fixed w-9rem">
                <InputText
                  type="text"
                  v-model="lookup_desc"
                  placeholder="Masukan Deskripsi. . ."
                  :class="{ 'p-invalid': errors.lookup_desc }"
                />
                   <small v-if="errors.lookup_desc" class="p-error">
                      {{ errors.lookup_desc[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Status</label>
                 <div class="col-fixed w-9rem">
               <Dropdown
                  v-model="lookup_status"
                  :options="stat"
                  :showClear="true"
                  optionLabel="nama"
                  optionValue="code"
                  placeholder="Select A Status"
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
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/referensi-service')"
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
      lookup_type:'Kat_Service' ,
      lookup_code:'' ,
      lookup_desc: '',
      lookup_status: '',
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        if(this.checkto.includes("/referensi-service")){
        }
        else {
          this.$router.push('/access');
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
    CreateLookup() {
        this.errors = [];

        const data = new FormData();
        data.append("lookup_type", this.lookup_type);
        data.append("lookup_code", this.lookup_code);
        data.append("lookup_desc", this.lookup_desc);
        data.append("lookup_status", this.lookup_status);

        this.axios.post('api/add-ref', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((resoonse)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/referensi-service'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>