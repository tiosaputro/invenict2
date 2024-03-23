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
                  <label class="col-fixed w-9rem">Merk</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                        type="text"
                        v-model="detail.tes"
                        disabled
                      />
                    </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Tipe</label>
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
                      placeholder="Masukan S/N"
                      :class="{ 'p-invalid': errors.invent_sn }"
                      autofocus
                    />
                      <small v-if="errors.invent_sn" class="p-error">
                        {{ errors.invent_sn[0] }}
                      </small>
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
                  @click="$router.push('/master-peripheral-detail/'+this.$route.params.code)"
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
                    <img :src="preview" class="master-image"/>
                  </div>
                </div>
            </div>
            <div class="field grid">
              <label class="col-fixed w-9rem"></label>
                <div class="col-10 md-6">
                  <input type="file" :class="{ 'p-invalid': error.foto }" accept="image/jpg,image/png,image/jpeg" name="foto" ref="fileInput" class="form-control" @change="fileImage" />
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
      detail:[]
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
          this.getDetail();
          this.getMerk();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getDetail(){
      this.axios.get('/api/add-master-detail/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
      this.foto = event.target.files[0];
      this.displayImage = true;
      this.preview = URL.createObjectURL(event.target.files[0]);
      this.createImage(this.foto);
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
    CreateMaster() {
      this.errors = [];
      this.error = [];
          
        this.axios.post('/api/save-master-detail',this.detail,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/master-peripheral-detail/'+this.$route.params.code),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
    }
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:188pt;
  object-fit:contain;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>