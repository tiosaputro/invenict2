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
               <!-- <div class="field grid">
                  <label class="col-fixed w-9rem">Kode</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                        type="text"
                        disabled
                      />
                  </div>
                </div> -->
                <div class="field grid">
                  <label class="col-fixed w-9rem">Nama Peripheral</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown
                        v-model="nama"
                        :options="kategori"
                        optionLabel="name"
                        optionValue="name"
                        :showClear="true"
                        :filter="true"
                        placeholder="Pilih Peripheral"
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
                  <label class="col-fixed w-9rem">Merk</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown
                        v-model="merk"
                        :options="merks"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Pilih Merk"
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
                  <label class="col-fixed w-9rem">Tipe</label>
                    <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model= "type"
                        placeholder= "Masukan Tipe"
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
                  label="Simpan"
                  type="submit"
                />
                <Button
                  v-if="this.loading == false"
                  label="Cancel"
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
      aktif:true,
      displayImage:false,
      preview:'',
      errors: [],
      error : [],
      merks: [],
      kondi:[],
      bisnis:[],
      kategori:[],
      nama:'',
      tgl:'',
      sn:'',
      bu:'',
      merk:'',
      type:'',
      foto: null,
      prevuser:'',
      lastuser:'',
      prevloct:'',
      lastloct:'',
      kondisi:'',
      barcode:'',
      garansi:null,
      mask:{
        input: 'DD MMM YYYY'
      },
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
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMerk();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      getMerk(){
        this.axios.get('api/rsrcsupp',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.merks = response.data.merk;
            this.bisnis = response.data.bisnis;
            this.kondi = response.data.kondisi;
            this.kategori = response.data.nama;
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
          
        this.axios.post('api/add-mas',data,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
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