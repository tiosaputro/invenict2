<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Mutasi Peripheral ICT</h4>
            </template>
          </Toolbar>
          <div class="row">
          <div class="col-sm-6">
            <form @submit.prevent="UpdateMutasi">
              <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:155px">Kode Peripheral</label>
                    <div class="col">
                      <InputText
                        type="text" 
                        v-model="mutasi.invent_code"
                        disabled
                      /> 
                  </div>
                 </div>
                 <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:155px">Dari Tgl</label>
                    <div class="col-12 md:col-6">
                      <DatePicker v-model="mutasi.imutasi_tgl_dari" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                          <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Tanggal"
                            readonly
                          />
                          <Button icon="pi pi-calendar" v-if="!mutasi.imutasi_tgl_dari" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="mutasi.imutasi_tgl_dari = ''" /> 
                         </div>
                        </template>
                      </DatePicker> 
                      <small class="p-error" v-if="submitted && !mutasi.imutasi_tgl_dari"
                        >Dari Tgl Belum Diisi.
                      </small>
                    </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:155px">SD Tgl</label>
                      <div class="col-12 md:col-6">
                        <DatePicker v-model="mutasi.imutasi_tgl_sd" :min-date="mutasi.imutasi_tgl_dari" :masks="mask" >
                          <template v-slot="{ inputValue, togglePopover }">
                            <div class="flex items-center">
                              <input
                                class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                                :value="inputValue"
                                @click="togglePopover"
                                placeholder="Pilih Tanggal"
                                readonly
                              />
                              <Button icon="pi pi-calendar" v-if="!mutasi.imutasi_tgl_sd" @click="togglePopover"/>
                              <Button icon="pi pi-trash" class="p-button-danger" v-else @click="mutasi.imutasi_tgl_sd = ''" />
                            </div>
                          </template>
                        </DatePicker>
                      </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:155px">Lokasi</label>
                      <div class="col">
                        <InputText
                          type ="text"
                          v-model="mutasi.imutasi_lokasi"
                          placeholder="Masukan Lokasi. . ."
                          :class="{ 'p-invalid': submitted && !mutasi.imutasi_lokasi }"
                        />
                        <small class="p-error" v-if="submitted && !mutasi.imutasi_lokasi"
                          >Lokasi Belum Diisi.
                        </small>
                      </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:155px">Pengguna</label>
                      <div class="col">
                        <InputText
                          type="text"
                          v-model="mutasi.imutasi_pengguna"
                          placeholder="Masukan Pengguna . . ."
                          :class="{ 'p-invalid': submitted && !mutasi.imutasi_pengguna }"
                        />
                      <small class="p-error" v-if="submitted && !mutasi.imutasi_pengguna"
                        >Pengguna Belum Diisi.
                      </small>
                    </div>
                  </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:155px">Keterangan</label>
                 <div class="col">
                  <Textarea
                    v-model="mutasi.imutasi_keterangan"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Masukan Keterangan . . ."
                    :class="{ 'p-invalid': submitted && !mutasi.imutasi_keterangan }"
                  />
                      <small class="p-error" v-if="submitted && !mutasi.imutasi_keterangan"
                        >Keterangan Belum Diisi.
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
                  @click="$router.push('/mutasi-peripheral')"
                />
              </div>
            </form>
          </div>
              <div class="col-sm-6">
                    <img :src="'/master_peripheral/' +mutasi.invent_photo" class="mutasi-image" />
              </div>
      </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      mutasi:[],
      submitted:false,
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
    };
  },
  created(){
    this.cekUser();
  },
  methods: {
  cekUser(){
    if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Mutasi Peripheral") || this.checkto.includes("/mutasi-peripheral")){
          this.getMutasi();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    
    getMutasi(){
      this.axios.get('/api/edit-mut/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.mutasi = response.data;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem('Expired','true')
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
    },
    UpdateMutasi() {
      this.submitted=true;
      this.errors = [];
      if (
        this.mutasi.imutasi_tgl_dari != '' &&
        this.mutasi.imutasi_lokasi != null &&
        this.mutasi.imutasi_pengguna != null &&
        this.mutasi.imutasi_keterangan != null 
      ) {
        this.axios.put('/api/update-mut/' + this.$route.params.code, this.mutasi, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/mutasi-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
            this.submitted = false;
        });
      }
    }
  },
};
</script>
<style scoped lang="scss">
.mutasi-image {
  width: 450px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>