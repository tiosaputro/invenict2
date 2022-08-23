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
            <form @submit.prevent="CreateMutasi">
               <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:145px">Peripheral</label>
                    <div class="field col-12 md:col-6">
                      <Dropdown 
                        v-model="kode"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        @change="getSn()"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !kode }"
                      />
                      <small v-if="errors.kode" class="p-error">
                          {{ errors.kode[0] }}
                      </small>  
                      <small class="p-error" v-if="submitted && !kode"
                        > Peripheral not filled
                      </small>
                    </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:145px">S/N</label>
                    <div class="field col-12 md:col-6">
                      <Dropdown 
                        v-model="invent_sn"
                        :options="sn"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        @change="getImage()"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !invent_sn }"
                      />
                      <small v-if="errors.invent_sn" class="p-error">
                          {{ errors.invent_sn[0] }}
                      </small>  
                      <small class="p-error" v-if="submitted && !invent_sn"
                        > S/N not filled.
                      </small>
                    </div>
                  </div>
                  <div class="field grid ">
                   <label class="col-fixed w-9rem" style="width:145px">From Date</label>
                    <div class="col-12 md:col-6">
                      <DatePicker v-model="fromdate" :masks="mask" >
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
                      <DatePicker v-model="todate" :masks="mask" :min-date="fromdate" >
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
                      v-model="lokasi"
                      placeholder="Masukan Lokasi. . ."
                      :class="{ 'p-invalid': submitted && !lokasi }"
                    />
                      <small class="p-error" v-if="submitted && !lokasi"
                        >Lokasi Belum Diisi.
                      </small>
                  </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">User</label>
                    <div class="col-12 md:col-6">
                      <InputText
                          type="text"
                          v-model="user"
                          placeholder="Masukan Pengguna . . ."
                          :class="{ 'p-invalid': submitted && !user }"
                        />
                        
                      <small class="p-error" v-if="submitted && !user"
                        >Pengguna Belum Diisi.
                      </small>
                      <small v-if="errors.user" class="p-error">
                          {{ errors.user[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Division User</label>
                    <div class="col-12 md:col-6">
                      <Dropdown 
                        v-model="invent_divisi"
                        :options="divisi"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !invent_divisi }"
                      />
                        
                      <small class="p-error" v-if="submitted && !invent_divisi"
                        >Pengguna Belum Diisi.
                      </small>
                      <small v-if="errors.invent_divisi" class="p-error">
                          {{ errors.invent_divisi[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Business Unit</label>
                    <div class="col-12 md:col-6">
                      <Dropdown 
                        v-model="invent_bu"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Select"
                        :class="{ 'p-invalid': submitted && !invent_bu }"
                      />
                        
                      <small class="p-error" v-if="submitted && !invent_bu"
                        >Business Unit not filled.
                      </small>
                      <small v-if="errors.invent_bu" class="p-error">
                          {{ errors.invent_bu[0] }}
                      </small>
                </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Remark</label>
                 <div class="col-12 md:col-6">
                  <Textarea
                    v-model="ket"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Enter remark"
                    :class="{ 'p-invalid': submitted && !ket }"
                  />
                      <small class="p-error" v-if="submitted && !ket"
                        >Keterangan Belum Diisi.
                      </small>
                      <small v-if="errors.ket" class="p-error">
                        {{ errors.ket[0] }}
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
                  @click="$router.push('/mutasi-peripheral')"
                />
              </div>
            </form>
            </div>
          <div class="col-sm-6">
            <img :src="'/master_peripheral/' + detail.photo" class="mutasi-image" v-if="this.invent_sn" />
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
      this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Mutasi Peripheral") || this.checkto.includes("/mutasi-peripheral")){
          this.getKode();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getImage(){
      if(this.invent_sn){
      this.axios.get('api/getImage/'+this.invent_sn, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.detail = response.data;
      });
      }
    },
    getSn(){
      if(this.kode){
        this.axios.get('/api/get-sn-peripheral/'+this.kode, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.sn = response.data;
      });
      }
    },   
    getKode(){
      this.axios.get('api/get-kode-peripheral', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
    CreateMutasi() {
      this.$confirm.require({
        message: "Are you sure to save this data?",
        header: "Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
        this.submitted=true;
        if (
            this.kode != null &&
            this.invent_sn != null &&
            this.fromdate != null &&
            this.ket != null &&
            this.user != null &&
            this.invent_divisi != null &&
            this.invent_bu != null &&
            this.lokasi != null 
        ) {
        const data = new FormData();
        data.append("kode", this.kode);
        data.append("fromdate", this.fromdate);
        data.append("ket", this.ket);
        data.append("todate", this.todate);
        data.append("user", this.user);
        data.append("lokasi", this.lokasi);
        data.append("invent_bu", this.invent_bu);
        data.append("invent_divisi", this.invent_divisi);
        data.append("invent_sn", this.invent_sn);
        this.axios.post('api/add-mut', data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/mutasi-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
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