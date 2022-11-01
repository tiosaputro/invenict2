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
                <label class="col-fixed w-9rem">Kode</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type="text"
                      v-model="master.invent_code"
                      disabled
                    /> 
                  </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Nama Peripheral</label>
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
                <label class="col-fixed w-9rem">Tipe</label>
                  <div class="col-fixed w-9rem">
                    <InputText
                      type="text"
                      v-model="master.invent_type"
                      placeholder= "Masukan Tipe"
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
                    label="Simpan"
                    type="submit"
                  />
                  <Button
                    label="Cancel"
                    v-if="this.loading == false"
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
      aktif: false,
      displayImage:false,
      submitted: false,
      errors: [],
      kondi:[],
      bisnis:[],
      master:[],
      preview:null,
      invent_photo:null,
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  created(){
      this.getMaster();
  },
  methods: {
    cekUser(){
      this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMaster();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      getMaster(){
          this.axios.get('/api/edit-mas/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.master = response.data.mas;
              this.bisnis = response.data.bisnis;
              this.kondi = response.data.kondisi;
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
        this.axios.put('/api/update-mas/' + this.$route.params.code ,this.master, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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