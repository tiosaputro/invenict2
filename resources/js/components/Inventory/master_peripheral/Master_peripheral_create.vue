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
             <form @submit.prevent="CreateMaster">
                <div class="field grid">
                  <label class="col-fixed w-9rem">Peripheral</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown
                        v-model="nama"
                        :options="kategori"
                        optionLabel="name"
                        optionValue="name"
                        :showClear="true"
                        :filter="true"
                        placeholder="Choose One"
                        autofocus
                        :class="{ 'p-invalid': errors.nama }"
                      />
                      <small v-if="errors.nama" class="p-error">
                        {{ errors.nama[0] }}
                      </small>
                      <small v-if="error.nama" class="p-error">
                        {{ error.nama }}
                      </small>
                    </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Brand</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown
                        v-model="merk"
                        :options="merks"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Choose One"
                        autofocus
                        :class="{ 'p-invalid': errors.merk }"
                      />
                      <small v-if="errors.merk" class="p-error">
                          {{ errors.merk[0] }}
                      </small>
                      <small v-if="error.merk" class="p-error">
                          {{ error.merk }}
                      </small>
                    </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Type</label>
                    <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model= "type"
                        placeholder= "Input Type"
                        :class="{ 'p-invalid': errors.type }"
                      />
                      <small v-if="errors.type" class="p-error">
                          {{ errors.type[0] }}
                      </small>
                      <small v-if="error.type" class="p-error">
                          {{ error.type }}
                      </small>
                    </div>
                </div>
              <div class="form-group">
                 <Button
                  v-if="this.loading == false"
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Save"
                  type="submit"
                />
                <Button
                  v-if="this.loading == false"
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/master-peripheral')"
                />
                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" v-else/>
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
      errors: [],
      error : [],
      merks: [],
      kategori:[],
      nama:'',
      merk:'',
      type:'',
    };
  },
  created(){
      this.getMerk();
  },
  methods: {
      getMerk(){
        this.axios.get('api/rsrcsupp').then((response)=>{
            this.merks = response.data.data.merk;
            this.kategori = response.data.data.nama;
        }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
          if (error.response.status == 403){
            this.$router.push('/access');
          }
        });
      },
    CreateMaster() {
      this.errors = [];
      this.error = [];
      if (
        this.merk != null &&
        this.nama != null
      ) {
          this.loading = true;
          const data = new FormData();
          data.append("nama", this.nama);
          data.append("merk", this.merk);
          data.append("type", this.type);
          
        this.axios.post('api/add-mas',data).then(()=>{
          setTimeout( () => this.$router.push('/master-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.loading = false;
            this.errors = error.response.data.errors;
        });
      }else{
        if(this.merk == null){
          this.error.merk = "Merk Belum Diisi"
        }
        if(this.nama == null){
          this.error.nama = "Nama Peripheral Belum Diisi"
        }
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