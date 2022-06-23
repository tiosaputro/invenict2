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
               <!-- <div class="field grid">
                  <label style="width:155px">S/N</label>
                    <div class="col-3 md-6">
                    <InputText
                      type ="text"
                      v-model="master.invent_sn"
                      placeholder="Masukan S/N"
                      :class="{ 'p-invalid': submitted && !master.invent_sn }"
                    />
                    <small class="p-error" v-if="submitted && !master.invent_sn"
                        >S/N Belum Diisi.
                      </small>
                  </div>
              </div>
              <div class="field grid">
                    <label style="width:155px">Tgl. Perolehan</label>
                    <div class="col-12 md:col-6">
                      <DatePicker v-model="master.invent_tgl_perolehan" :masks="mask">
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center"> 
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Tanggal"
                            readonly
                          />
                      <Button icon="pi pi-calendar" v-if="!master.invent_tgl_perolehan" @click="togglePopover"/>
                      <Button icon="pi pi-trash" v-tooltip="'Click to delete'" class="p-button-danger" v-else @click="master.invent_tgl_perolehan = null" />
                        </div>
                        </template>
                      </DatePicker>
                      <small class="p-error" v-if="submitted && !master.invent_tgl_perolehan"
                        > Belum Diisi.
                      </small>
                  </div>
                </div>
               <div class="field grid">
                  <label style="width:155px">Lama Garansi</label>
                    <div class="col-12 md:col-6">
                      <div class="p-inputgroup">
                      <InputNumber
                          v-model="master.invent_lama_garansi"
                          placeholder="Masukan Garansi"
                        />
                        <span class="p-inputgroup-addon"> Tahun </span>
                    </div>
                </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Kondisi</label>
                 <div class="col-4">
                  <Dropdown 
                    v-model="master.invent_kondisi"
                    :options="kondi"
                    :showClear="true"
                    :filter="true"
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Pilih Kondisi"
                    :class="{ 'p-invalid': submitted && !master.invent_kondisi }"
                  />                 
                    <small class="p-error" v-if="submitted && !master.invent_kondisi"
                      >Bisnis Unit Belum Diisi.
                    </small>
               </div>
              </div> -->
              <!-- <div class="field grid">
                <label style="width:155px">QR-Code</label>
                 <div class="col-12 md:col-6">
                <div class="p-inputgroup">
                  <InputText v-model="master.invent_barcode" readonly v-if="master.invent_barcode"/>
                  <img :src="'/assets/loading2.gif'" height="50" class="mb-3" v-if="!aktif && !master.invent_barcode" >
                 <Button icon="pi pi-trash" class="p-button-danger" v-if="master.invent_barcode" @click="hapus()" v-tooltip="'Click to delete'"/>
                  <Button icon="bi bi-qr-code-scan" v-if="aktif" class="p-button p-button-info" @click="Scan()" v-tooltip="'Click to scan'" />
                </div>
                      <small v-if="submitted && !master.invent_barcode" class="p-error">
                          QR-Code Belum Diisi.
                      </small>
                </div>
              </div>  -->
              <!-- <div class="field grid">
                <label style="width:155px">Bisnis Unit</label>
                  <div class="col-4">
                    <Dropdown 
                    v-model="master.invent_bu"
                    :options="bisnis"
                    optionLabel="name"
                    :showClear="true"
                    :filter="true"
                    optionValue="code"
                    placeholder="Pilih Bisnis Unit"
                    :class="{ 'p-invalid': submitted && !master.invent_bu }"
                    />
                    <small class="p-error" v-if="submitted && !master.invent_bu"
                        >Bisnis Unit Belum Diisi.
                      </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Lokasi Terakhir</label>
                    <div class="col-3 md-6">
                      <InputText
                        type="text"
                        v-model="master.invent_lokasi_update"
                        placeholder="Masukan Lokasi terakhir"
                        disabled
                      />
                  </div>
                </div>
                <div class="field grid">
                  <label style="width:155px">Pengguna Terakhir</label>
                    <div class="col-3 md-6">
                      <InputText
                        type="text"
                        v-model="master.invent_pengguna_update"
                        placeholder="Masukan Pengguna Terakhir"
                        disabled
                      />
                    </div>
                </div> -->
                <div class="p-p-0 p-p-sm-1 p-p-md-2 p-p-lg-3">
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
                    @click="$router.push('/master-peripheral')"
                  />
                </div>
            </form>
           </div>
          <!-- <div class="col-sm-6">
            <div class="field grid">
              <label style="width:155px">Nama</label>
                <div class="col-12 md:col-4">
                  <InputText
                    type ="text"
                    v-model="master.invent_desc"
                    placeholder="Masukan Nama"
                    disabled
                  />
                </div>
                </div> 
                <div class="field grid">
                  <label style="width:155px"></label>
                    <div class="col-10 md-6">
                      <div class="card" style="height: 19.5rem;">
                        <img :src="preview" class="master-image" v-if="preview"/>
                        <img :src="'/master_peripheral/' +master.invent_photo" class="master-image" v-else />
                      </div>
                    </div>
                </div>
                <div class="field grid">
                  <label style="width:155px"></label>
                    <div class="p-col-10 p-md-6">
                      <input type="file" name="foto" ref="fileInput" class="form-control" @change="fileImage" />
                    </div>
                </div>
                <div class="field grid">
                  <label style="width:155px">Lokasi Sebelumnya</label>
                    <div class="col-12 md:col-4">
                      <InputText
                          type="text"
                          v-model="master.invent_lokasi_previous"
                          placeholder="Masukan Lokasi sebelumnya"
                          disabled
                      />
                    </div>
                </div>
                <div class="field grid">
                  <label style="width:155px">Penguna Sebelumnya</label>
                    <div class="col-12 md:col-4">
                       <InputText
                        type="text"
                        v-model="master.invent_pengguna_previous"
                        placeholder="Masukan Pengguna sebelumnya"
                        disabled
                    />
                    </div>
                </div>
            </div> -->
          </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
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
      id : localStorage.getItem('id'),
    };
  },
  created(){
      this.getMaster();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMaster();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    // Scan(){
    //   this.aktif = false;
    //   let routeData = this.$router.resolve({name: 'Scan'});
    //   window.open(routeData.href, '_blank');
    //   setTimeout( () => this.getBarcode(),2000);
    // },
    // hapus(){
    //   this.master.invent_barcode = null;
    //   this.aktif = true;
    // },
    // getBarcode(){
    //   this.master.invent_barcode = localStorage.getItem("barcode");
    //   if(!this.master.invent_barcode){
    //     setTimeout( () => this.getBarcode(),3000);
    //   }
    // }, 
    //   getBisnis(){
    //     this.axios.get('/api/get-bisnis', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
    //       this.bisnis = response.data;
    //     });
    //   },
      // getMerk(){
      //   this.axios.get('/api/getMerk', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
      //       this.merks = response.data;
      //   });
      // },
      // getKondisi(){
      //   this.axios.get('/api/getKondisi', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
      //       this.kondi = response.data;
      //   });
      // },
      getMaster(){
          this.axios.get('/api/edit-mas/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.master = response.data.mas;
              this.bisnis = response.data.bisnis;
              this.kondi = response.data.kondisi;
          }).catch(error=>{
         if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
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
    //   fileImage(event) {
    //   this.invent_photo = event.target.files[0];
    //   this.displayImage = true;
    //   this.preview = URL.createObjectURL(event.target.files[0]);
    //   this.createImage(this.invent_photo);
    //   },
    //   createImage(invent_photo) {
    //   var image = new Image();
    //   var reader = new FileReader();
    //   var vm = this.master;
    //   reader.onload = function (e) {
    //     vm.image = e.target.result;
    //   };
    //   reader.readAsDataURL(invent_photo);
    // },
    UpdateMaster() {
      this.submitted=true;
      if (
        // this.master.invent_desc != null &&
        // this.master.invent_brand != null &&
        this.master.invent_type != null 
        // this.master.invent_sn != null &&
        // this.master.invent_tgl_perolehan != null &&
        // this.master.invent_lama_garansi != null &&
        // this.master.invent_kondisi != null &&
        // this.master.invent_barcode != null &&
        // this.master.invent_bu != null 
        // this.master.invent_lokasi_update != null &&
        // this.master.invent_pengguna_update != null &&
        // this.master.invent_lokasi_previous != null &&
        // this.master.invent_pengguna_previous != null  
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