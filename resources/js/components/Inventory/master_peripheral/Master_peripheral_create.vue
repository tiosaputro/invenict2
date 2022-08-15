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
                        disabled
                      />
                  </div>
                </div>
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
               <!-- <div class="field grid">
                  <label style="width:155px">S/N</label>
                    <div class="col-3 md-6">
                    <InputText
                      type ="text"
                      v-model="sn"
                      placeholder="Masukan S/N"
                      :class="{ 'p-invalid': errors.sn }"
                    />
                      <small v-if="errors.sn" class="p-error">
                          {{ errors.sn[0] }}
                      </small>
                 </div>
              </div>
              <div class="field grid">
                <label style="width:155px">Tgl. Perolehan</label>
                  <div class="col-12 md:col-6">
                    <DatePicker v-model="tgl" :masks="mask" >
                      <template v-slot="{ inputValue, togglePopover }">
                        <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Tanggal"
                            readonly
                          />
                          <Button icon="pi pi-calendar" v-if="!tgl" @click="togglePopover()"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-if="tgl" @click="tgl = null" />
                        </div>
                       </template>
                      </DatePicker>
                      <small v-if="errors.tgl" class="p-error">
                          {{ errors.tgl[0] }}
                      </small>
                  </div>
              </div>
               <div class="field grid">
                  <label style="width:155px">Lama Garansi</label>
                    <div class="col-12 md:col-6">
                      <div class="p-inputgroup">
                      <InputNumber
                          v-model="garansi"
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
                    v-model="kondisi"
                    :options="kondi"
                    :showClear="true"
                    :filter="true" 
                    optionLabel="name"
                    optionValue="code"
                    placeholder="Pilih Kondisi"
                    :class="{ 'p-invalid': errors.kondisi }"
                  />
                      <small v-if="errors.kondisi" class="p-error">
                          {{ errors.kondisi[0] }}
                      </small>
               </div>
              </div> -->
              <!-- <div class="field grid">
                <label style="width:155px">QR-Code</label>
                <div class="col-12 md:col-6">
                <div class="p-inputgroup">
                  <InputText v-model="barcode" readonly  v-if="barcode"/>
                  <img :src="'assets/loading2.gif'" height="50" v-if="!aktif && !barcode">
                  <Button icon="pi pi-trash" class="p-button-danger" v-if="barcode" @click="hapus()" @v-tooltip="'Click to delete'"/>
                  <Button icon="bi bi-qr-code-scan" v-if="aktif" class="p-button p-button-info" @click="Scan()" v-tooltip="'Click to scan'" />
                  </div>
                      <small v-if="errors.barcode" class="p-error">
                          {{ errors.barcode[0] }}
                      </small>
                </div>
              </div>  -->
              <!-- <div class="field grid">
                <label for="notlp2" style="width:155px">Bisnis Unit</label>
                  <div class="col-4">
                    <Dropdown 
                    v-model="bu"
                    :options="bisnis"
                    optionLabel="name"
                    optionValue="code"
                    :showClear="true"
                    :filter="true"
                    placeholder="Pilih Bisnis Unit"
                    :class="{ 'p-invalid': errors.bu }"
                  />
                      <small v-if="errors.bu" class="p-error">
                          {{ errors.bu[0] }}
                      </small>
                      <small v-if="error.bu" class="p-error">
                          {{ error.bu }}
                      </small>
                </div>
              </div>
                <div class="field grid">
                  <label style="width:155px">Lokasi Terakhir</label>
                    <div class="col-6">
                      <InputText
                        type="text"
                        v-model="lastloct"
                        :class="{ 'p-invalid': errors.lastloct }"
                        placeholder="Masukan Lokasi terakhir"
                        disabled
                      /> -->
                      <!-- <small v-if="errors.lastloct" class="p-error">
                          {{ errors.lastloct[0] }}
                      </small> -->
                    <!-- </div>
                  </div> -->
                  <!-- <div class="field grid">
                    <label style="width:155px">Pengguna Terakhir</label>
                      <div class="col-6">
                        <InputText
                          type="text"
                          v-model="lastuser"
                          :class="{ 'p-invalid': errors.lastuser }"
                          placeholder="Masukan Pengguna Terakhir"
                          disabled
                        /> -->
                        <!-- <small v-if="errors.lastuser" class="p-error">
                            {{ errors.lastuser[0] }}
                        </small> -->
                      <!-- </div>
                    </div>  -->
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
                  @click="$router.push('/master-peripheral')"
                />
              </div>
            </form>
           </div>
           <!-- <div class="col-sm-6">
            <div class="field grid">
              <label style="width:155px">Nama Peripheral</label>
                <div class="col-4">
                  <Dropdown
                    v-model="nama"
                    :options="kategori"
                    optionLabel="name"
                    optionValue="code"
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
            </div>  -->
            <!-- <div class="field grid">
              <label style="width:155px"></label>
                <div class="col-10 md-6">
                  <div class="card" style="height: 20 rem;">
                    <img :src="preview" class="master-image"/>
                  </div>
                </div>
            </div>
            <div class="field grid">
              <label style="width:155px"></label>
                <div class="col-10 md-6">
                  <input type="file" :class="{ 'p-invalid': error.foto }" name="foto" ref="fileInput" class="form-control" @change="fileImage" /> -->
                    <!-- <small class="p-error" v-if="error.foto">
                      {{ error.foto }}
                    </small> -->
                <!-- </div>
            </div> -->
                <!-- <div class="field grid">
                  <label style="width:155px">Lokasi Sebelumnya</label>
                    <div class="col-6">
                    <InputText
                        type="text"
                        v-model="prevloct"
                        :class="{ 'p-invalid': errors.prevloct }"
                        placeholder="Masukan Lokasi sebelumnya"
                        disabled
                    /> -->
                      <!-- <small class="p-error" v-if="errors.prevloct">
                        {{ errors.prevloct[0] }}
                      </small> -->
                    <!-- </div>
                 </div> -->
                 <!-- <div class="field grid">
                  <label style="width:155px">Penguna Sebelumnya</label>
                    <div class="col-6">
                       <InputText
                        type="text"
                        v-model="prevuser"
                        :class="{ 'p-invalid': errors.prevuser  }"
                        placeholder="Masukan Pengguna sebelumnya"
                        disabled
                    /> -->
                      <!-- <small class="p-error" v-if="errors.prevuser">
                        {{ errors.prevuser[0] }}
                      </small> -->
                    <!-- </div>
                 </div> -->
          <!-- </div> -->
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
      id : localStorage.getItem('id'),
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Master Peripheral") || this.checkto.includes("/master-peripheral")){
          this.getMerk();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
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
    // Scan(){
    //   this.aktif = false;
    //   let routeData = this.$router.resolve({name: 'Scan'});
    //   window.open(routeData.href, '_blank');
    //   setTimeout( () => this.getBarcode(),2000);
    // },
    // getBarcode(){
    //   this.barcode = localStorage.getItem('barcode');
    //   if(!this.barcode){
    //     setTimeout( () => this.getBarcode(),3000);
    //   }
    // },
    // hapus(){
    //   localStorage.removeItem('barcode');
    //   this.barcode = null;
    //   this.aktif = true;
    // },
    // fileImage(event) {
    //   this.foto = event.target.files[0];
    //   this.displayImage = true;
    //   this.preview = URL.createObjectURL(event.target.files[0]);
    //   this.createImage(this.foto);
    //   },
    // createImage(invent_photo) {
    //   var image = new Image();
    //   var reader = new FileReader();
    //   var vm = this;
    //   reader.onload = function (e) {
    //     vm.image = e.target.result;
    //   };
    //   reader.readAsDataURL(invent_photo);
    // },
    CreateMaster() {
      this.errors = [];
      this.error = [];
      if (
        // this.bu != null &&
        this.merk != null &&
        this.nama != null
        // this.foto != null
      ) {
        // if(this.image){
          // const data = new FormData();
          // data.append("nama", this.nama);
          // data.append("tgl", this.tgl);
          // data.append("sn", this.sn);
          // data.append("bu", this.bu);
          // data.append("merk", this.merk);
          // data.append("type", this.type);
          // data.append("lastuser", this.lastuser);
          // data.append("prevuser", this.prevuser);
          // data.append("prevloct", this.prevloct);
          // data.append("lastloct", this.lastloct);
          // data.append("foto", this.image);
          // data.append("kondisi", this.kondisi);
          // data.append("barcode", this.barcode);
          // data.append("garansi", this.garansi);
          
        // this.axios.post('api/add-mas',data,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        //   setTimeout( () => this.$router.push('/master-peripheral'),1000);
        //   this.$toast.add({
        //     severity: "success",
        //     summary: "Success Message",
        //     detail: "Success Create",
        //   });
        // }).catch(error=>{
        //     this.errors = error.response.data.errors;
        // });
        // }
        // else{
          const data = new FormData();
          data.append("nama", this.nama);
          // data.append("code", this.code);
          // data.append("tgl", this.tgl);
          // data.append("sn", this.sn);
          // data.append("bu", this.bu);
          data.append("merk", this.merk);
          data.append("type", this.type);
          // data.append("lastuser", this.lastuser);
          // data.append("prevuser", this.prevuser);
          // data.append("prevloct", this.prevloct);
          // data.append("lastloct", this.lastloct);
          // data.append("foto", this.image);
          // data.append("kondisi", this.kondisi);
          // data.append("barcode", this.barcode);
          // data.append("garansi", this.garansi);
          
        this.axios.post('api/add-mas',data,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/master-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
        // }
      }else{
        // if(this.bu == null){
        //   this.error.bu = "Bisnis Unit Belum Diisi"
        // }
        if(this.merk == null){
          this.error.merk = "Merk Belum Diisi"
        }
        if(this.nama == null){
          this.error.nama = "Nama Peripheral Belum Diisi"
        }
        // if(this.foto == null){
        //   this.error.foto = "Foto Belum Diisi"
        // }
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