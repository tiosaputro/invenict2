<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Referensi Brand</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="CreateLookup">
               <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tipe</label>
                 <div class="col">
                  <InputText
                   type="text"
                   v-model="lookup_type"
                   disabled
                  />
                </div>
              </div> -->
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
      lookup_type:'Merk' ,
      lookup_code:'' ,
      lookup_desc: '',
      lookup_status: '',
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      checkname : [],
      checkto : [],
    };
  },
  methods: {
    CreateLookup() {
        this.errors = [];

        const data = new FormData();
        data.append("lookup_type", this.lookup_type);
        data.append("lookup_code", this.lookup_code);
        data.append("lookup_desc", this.lookup_desc);
        data.append("lookup_status", this.lookup_status);

        this.axios.post('api/add-brand', data).then((resoonse)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/referensi-brand'),1000);
        }).catch(error => {
          this.errors = error.response.data.errors;
          if (error.response.status == 401){
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
  },
};
</script>