<template>
  <div>
    <ConfirmDialog> </ConfirmDialog>
    <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
              <h4>Mutasi Peripheral</h4>
            </template>
          </Toolbar>
        <div class="row">
          <div class="col-sm-6">
            <form @submit.prevent="UpdateMutasi">
               <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:145px">Peripheral</label>
                    <div class="field col-12 md:col-6">
                      <InputText
                      v-model="mut.invent_code"
                      disabled
                      />
                    </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:145px">S/N</label>
                    <div class="field col-12 md:col-6">
                      <InputText
                       v-model="mut.invent_sn"
                       disabled
                      />
                    </div>
                  </div>
                  <div class="field grid ">
                   <label class="col-fixed w-9rem" style="width:145px">From Date</label>
                    <div class="col-12 md:col-6">
                      <DatePicker v-model="mut.imutasi_tgl_dari" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Dari Tanggal"
                            readonly
                          />
                        <Button icon="pi pi-calendar" v-if="!fromdate" @click="togglePopover"/>
                        <Button icon="pi pi-trash" class="p-button-danger" v-else @click="fromdate = ''" />
                       </div>
                      </template>
                      </DatePicker>
                      <small class="p-error" v-if="submitted && !fromdate"
                        > Dari Tgl Belum Diisi.
                      </small>
                  </div>
                </div>
                <div class="field grid">
                 <label class="col-fixed w-9rem" style="width:145px">To Date</label>
                  <div class="col-12 md:col-6">
                      <DatePicker v-model="mut.imutasi_tgl_sd" :masks="mask" :min-date="fromdate" >
                        <template v-slot="{ inputValue, togglePopover }">
                          <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih SD Tanggal"
                            readonly
                          />
                          <Button icon="pi pi-calendar" v-if="!todate" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="todate = ''" />
                         </div>
                        </template>
                      </DatePicker>
                  </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Location</label>
                    <div class="col-10 md:col-4">
                    <InputText
                      type ="text"
                      v-model="mut.imutasi_lokasi"
                      placeholder="Masukan Lokasi. . ."
                      :class="{ 'p-invalid': submitted && !mut.imutasi_lokasi }"
                    />
                      <small class="p-error" v-if="submitted && !mut.imutasi_lokasi"
                        >Lokasi Belum Diisi.
                      </small>
                  </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">User</label>
                    <div class="col-12 md:col-6">
                      <InputText
                          type="text"
                          v-model="mut.imutasi_pengguna"
                          placeholder="Masukan Pengguna . . ."
                          :class="{ 'p-invalid': submitted && !mut.imutasi_pengguna }"
                        />
                        
                      <small class="p-error" v-if="submitted && !mut.imutasi_pengguna"
                        >Pengguna Belum Diisi.
                      </small>
                      <small v-if="errors.user" class="p-error">
                          {{ errors.user[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">User Division</label>
                    <div class="col-12 md:col-6">
                      <Dropdown 
                        v-model="mut.imutasi_divisi"
                        :options="divisi"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !mut.imutasi_divisi }"
                      />
                      <small class="p-error" v-if="submitted && !mut.imutasi_divisi"
                        >User Division not filled.
                      </small>
                      <small v-if="errors.imutasi_divisi" class="p-error">
                          {{ errors.imutasi_divisi[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Business Unit</label>
                    <div class="col-12 md:col-6">
                      <Dropdown 
                        v-model="mut.imutasi_bu"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !mut.imutasi_bu }"
                      />
                      <small class="p-error" v-if="submitted && !mut.imutasi_bu"
                        >Business Unit not filled.
                      </small>
                      <small v-if="errors.imutasi_bu" class="p-error">
                          {{ errors.imutasi_bu[0] }}
                      </small>
                </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Remark</label>
                 <div class="col-12 md:col-6">
                  <Textarea
                    v-model="mut.imutasi_keterangan"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Enter remark"
                    :class="{ 'p-invalid': submitted && !mut.imutasi_keterangan }"
                  />
                      <small class="p-error" v-if="submitted && !mut.imutasi_keterangan"
                        >Keterangan Belum Diisi.
                      </small>
                      <small v-if="errors.imutasi_keterangan" class="p-error">
                        {{ errors.imutasi_keterangan[0] }}
                      </small>
               </div>
              </div>
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Update"
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
            <img :src="'/master_peripheral/' + mut.invent_photo" class="mutasi-image" />
          </div>
       </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      submitted: false,
      errors: [],
      kode: null,
      fromdate: new Date(),
      todate: '',
      ket:null,
      user:null,
      lokasi:null,
      detail:[],
      kodeperi:[],
      divisi:[],
      invent_bu:null,
      invent_sn:null,
      invent_divisi:null,
      sn:[],
      bu:[],
      mutasi:[],
      mut:[],
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  mounted(){
    this.cekUser();
  },
  methods: {
  cekUser(){
      this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Mutasi Peripheral") || this.checkto.includes("/mutasi-peripheral")){
          this.getMutasi();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getMutasi(){
      this.axios.get('/api/edit-mut/'+this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.mut = response.data;
        this.getKode();
      });
    }, 
    getKode(){
      this.axios.get('/api/get-kode-peripheral', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.kodeperi = response.data.kode;
        this.divisi = response.data.divisi;
        this.bu = response.data.bu;
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
    UpdateMutasi() {
      this.$confirm.require({
        message: "Are you sure to update this data?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
        this.submitted=true;
        if (
            this.mut.imutasi_tgl_dari != null &&
            this.mut.imutasi_keterangan != null &&
            this.mut.imutasi_pengguna != null &&
            this.mut.imutasi_divisi != null &&
            this.mut.imutasi_bu != null &&
            this.mut.imutasi_lokasi != null 
        ) {

        this.axios.put('/api/update-mut/'+this.$route.params.code, this.mut, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
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
        },
        reject: () => {},
      });
    }
  },
};
</script>
<style scoped lang="scss">
.mutasi-image {
  width: 450px;
  object-fit:contain;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>