<template>
  <div>
      <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
              <h4>Master Peripheral</h4>
            </template>
          </Toolbar>
           <div class="row">
            <div class="col-sm-6">
            <form @submit.prevent="UpdateMaster">
              <div class="field grid">
                <label class="col-fixed w-9rem">Code</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type="text"
                      v-model="master.invent_code"
                      disabled
                    /> 
                  </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Peripheral</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type ="text"
                      v-model="master.invent_desc"
                      placeholder="Masukan Nama"
                      disabled
                    />
                  </div>
              </div> 
              <div class="field grid">
                <label class="col-fixed w-9rem">Merk</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type="text"
                      v-model="master.lookup_desc"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Type</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type="text"
                      v-model="master.invent_type"
                      placeholder= "Masukan Type"
                      :class="{ 'p-invalid': submitted && !master.invent_type }"
                    />
                    <small class="p-error" v-if="submitted && !master.invent_type"
                      >Type Belum Diisi.
                    </small>
                  </div>
              </div>
                <div class="p-p-0 p-p-sm-1 p-p-md-2 p-p-lg-3">
                  <Button
                    class="p-button-rounded p-button-primary mr-2"
                    v-if="this.loading == false"
                    icon="pi pi-check"
                    label="Save"
                    type="submit"
                  />
                  <Button
                    label="Cancel"
                    v-if="this.loading == false"
                    class="p-button-rounded p-button-secondary mr-2"
                    icon="pi pi-times"
                    @click="$router.push('/master-peripheral')"
                  />
                  <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" animationDuration=".5s" v-else/>
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
      loading:false,
      submitted: false,
      errors: [],
      master:[],
    };
  },
  created(){
      this.getMaster();
  },
  methods: {
      getMaster(){
          this.axios.get('/api/edit-mas/' + this.$route.params.code).then((response)=>{
              this.master = response.data.data;
          }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403) {
            this.$router.push('/access');
          }
        });
      },
    UpdateMaster() {
      this.loading = true;
      this.submitted=true;
      if (
        this.master.invent_type != null 
      ) {
        this.axios.put('/api/update-mas/' + this.$route.params.code ,this.master).then((response)=>{
          localStorage.removeItem("barcode");
          setTimeout( () => this.$router.push('/master-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
        }).catch(error=>{
          this.loading = false;
          if (error.response.status == 422) {
            this.submitted = false;
            this.errors = error.response.data.errors;
          }
        });
      }
    }
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:200pt;
  object-fit:contain;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>