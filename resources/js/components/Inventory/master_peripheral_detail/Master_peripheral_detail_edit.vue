<template>
  <div>
    <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
              <h4>Master Peripheral(Detail)</h4>
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
                        v-model="detail.invent_code"
                        disabled
                      />
                  </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Brand</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                        type="text"
                        v-model="detail.invent_brand"
                        disabled
                      />
                    </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Type</label>
                    <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model="detail.invent_type"
                        disabled
                      />
                    </div>
                </div>
               <div class="field grid">
                  <label class="col-fixed w-9rem">S/N</label>
                    <div class="col-fixed w-9rem">
                    <InputText
                      type ="text"
                      v-model="detail.invent_sn"
                      disabled
                    />
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Tgl. Perolehan</label>
                  <div class="col-fixed w-11rem">
                    <DatePicker v-model="detail.invent_tgl_perolehan" :masks="mask" >
                      <template v-slot="{ inputValue, togglePopover }">
                        <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Tanggal"
                            readonly
                          />
                          <Button icon="pi pi-calendar" v-if="!detail.invent_tgl_perolehan" @click="togglePopover()"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-if="detail.invent_tgl_perolehan" @click="detail.invent_tgl_perolehan = null" />
                        </div>
                       </template>
                      </DatePicker>
                      <small v-if="errors.invent_tgl_perolehan" class="p-error">
                          {{ errors.invent_tgl_perolehan[0] }}
                      </small>
                  </div>
              </div>
               <div class="field grid">
                  <label class="col-fixed w-9rem">Lama Garansi</label>
                    <div class="col-fixed w-11rem">
                      <div class="p-inputgroup">
                        <InputNumber
                          v-model="detail.invent_lama_garansi"
                          placeholder="Masukan Garansi"
                        />
                        <span class="p-inputgroup-addon"> Tahun </span> 
                    </div>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Kondisi</label>
                 <div class="col-fixed w-9rem">
                  <Dropdown 
                    v-model="detail.invent_kondisi"
                    :options="kondi"
                    :showClear="true"
                    :filter="true" 
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Pilih Kondisi"
                    :class="{ 'p-invalid': errors.invent_kondisi }"
                  />
                      <small v-if="errors.invent_kondisi" class="p-error">
                          {{ errors.invent_kondisi[0] }}
                      </small>
               </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Bisnis Unit</label>
                  <div class="col-fixed w-9rem">
                    <Dropdown 
                    v-model="detail.invent_bu"
                    :options="bisnis"
                    optionLabel="name"
                    optionValue="code"
                    :showClear="true"
                    :filter="true"
                    placeholder="Pilih Bisnis Unit"
                    :class="{ 'p-invalid': errors.invent_bu }"
                  />
                      <small v-if="errors.invent_bu" class="p-error">
                          {{ errors.invent_bu[0] }}
                      </small>
                </div>
              </div>
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Save"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/master-peripheral-detail/'+this.$route.params.kode)"
                />
              </div>
            </form>
           </div>
           <div class="col-sm-6">
           <div class="field grid">
              <label class="col-fixed w-9rem">Peripheral</label>
                <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="detail.invent_desc"
                    disabled
                  />
                </div>
            </div> 
            <div class="field grid">
              <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <div class="card" style="height: 20 rem;">
                     <img :src="preview" class="master-image" v-if="preview"/>
                    <img :src="'/master_peripheral/' +detail.invent_photo" class="master-image" v-else />
                  </div>
                </div>
            </div>
            <div class="field grid">
              <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <input type="file" name="foto" ref="fileInput" class="form-control" accept="image/jpg,image/png,image/jpeg" @change="fileImage" />
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      aktif:true,
      displayImage:false,
      preview:'',
      errors: [],
      error : [],
      kondi:[],
      bisnis:[],
      kategori:[],
      foto: null,
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      detail:[],
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('/api/cek-user').then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMerk();
          this.getDetail();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getDetail(){
      this.axios.get('/api/edit-master-detail/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.detail = response.data;
      });
    },
      getMerk(){
        this.axios.get('/api/rsrcsupp',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            // this.merks = response.data.merk;
            this.bisnis = response.data.bisnis;
            this.kondi = response.data.kondisi;
            // this.kategori = response.data.nama;
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
      fileImage(event) {
      this.invent_photo = event.target.files[0];
      this.displayImage = true;
      this.preview = URL.createObjectURL(event.target.files[0]);
      this.createImage(this.invent_photo);
      },
      createImage(invent_photo) {
      var image = new Image();
      var reader = new FileReader();
      var vm = this.detail;
      reader.onload = function (e) {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(invent_photo);
    },
    UpdateMaster() {
      this.errors = [];
      this.error = [];
      if (
        // this.detail.invent_desc != null &&
        // this.detail.invent_brand != null &&
        // this.detail.invent_type != null 
        this.detail.invent_sn != null 
        // this.detail.invent_tgl_perolehan != null &&
        // this.detail.invent_lama_garansi != null &&
        // this.detail.invent_kondisi != null &&
        // this.detail.invent_barcode != null &&
        // this.detail.invent_bu != null 
        // this.detail.invent_lokasi_update != null &&
        // this.detail.invent_pengguna_update != null &&
        // this.detail.invent_lokasi_previous != null &&
        // this.detail.invent_pengguna_previous != null  
      ) {
        this.axios.put('/api/update-master-detail/'+this.$route.params.code,this.detail,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/master-peripheral-detail/'+this.$route.params.kode),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
        }
    }
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:155pt;
  object-fit:contain;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>