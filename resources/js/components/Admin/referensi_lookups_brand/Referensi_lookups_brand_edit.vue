<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="p-mb-4">
          <template v-slot:start>
			<h4>Referensi - Brand</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="UpdateLookup">
               <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tipe</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="ref.lookup_type"
                  disabled
                  />
                </div>
              </div> -->
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Kode</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="ref.lookup_code"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Deskripsi</label>
                 <div class="col">
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
                <label class="col-fixed w-9rem" style="width:120px">Status</label>
                 <div class="field col-12 md:col-4">
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
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/referensi-brand')"
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
      checkname : [],
      checkto : [],
      ref: [],
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      name:''
    };
  },
  created(){
    this.getRef();
  },
  methods: {
      getRef(){
        this.axios.get('/api/edit-brand/' + this.$route.params.code + '/' + this.$route.params.type ).then((response)=> {
            this.ref = response.data;
        }).catch(error=>{
          if ((error.response.status == 401)){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
          if(error.response.status == 403){
            this.router.push('/access');
          }
        });
      },
    UpdateLookup(){
        this.errors = [];   
        this.axios.put('/api/update-brand/' + this.$route.params.code + '/' + this.$route.params.type, this.ref ).then((response) => {
           this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
         setTimeout( () => this.$router.push('/referensi-brand'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },
  },
};
</script>