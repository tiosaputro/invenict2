<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Management Module</h4>
          </template>
        </Toolbar>
          <div class="row">
            <div class="col-sm-6">
             <form @submit.prevent="UpdateModule">
                 <div class="card-body">
                  <div class="field grid">
                   <label style="width:120px">Module ID</label>
                    <div class="col-3 md-6">
                    <InputText
                      v-model="modul.mod_id"
                      disabled
                    />
                   <small v-if="errors.mod_name" class="p-error">
                      {{ errors.mod_name[0] }}
                  </small>
                </div>
              </div>
               <div class="field grid">
                <label style="width:120px">Module Name</label>
                 <div class="col-3 md-6">
                <InputText
                  v-model="modul.mod_name"
                  placeholder="Masukan Module Name"
                  :class="{ 'p-invalid': errors.mod_name }"
                  autofocus
                />
                   <small v-if="errors.mod_name" class="p-error">
                      {{ errors.mod_name[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Module Description</label>
                 <div class="col-3 md-6">
                  <InputText
                    v-model="modul.mod_desc"
                    placeholder="Masukan Module Description"
                    :class="{ 'p-invalid': errors.mod_desc  }"
                  />
                    <small v-if="errors.mod_desc" class="p-error">
                      {{ errors.mod_desc[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Module Status</label>
                 <div class="col-3 md-6">
               <Dropdown
                  v-model="modul.mod_stat"
                  :options="stat"
                  :showClear="true"
                  optionLabel="nama"
                  optionValue="code"
                  placeholder="Select A Status"
                  :class="{ 'p-invalid': errors.mod_stat }"
                />
                   <small v-if="errors.mod_stat" class="p-error">
                      {{ errors.mod_stat[0] }}
                  </small>
                </div>
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
                  @click="$router.push('/mng-module')"
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
      modul:[],
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
    };
  },
  created(){
      this.getModule();
  },
  methods: {
    getModule(){
        this.axios.get('/api/edit-module/' + this.$route.params.code).then((response)=>{
            this.modul = response.data;
        }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if(error.response.status == 403){
            this.$router.push('/access');
          }
        });
    },
    UpdateModule() {
        this.errors = [];

        this.axios.put('/api/update-module/'+ this.$route.params.code,this.modul).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        setTimeout( () => this.$router.push('/mng-module'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>