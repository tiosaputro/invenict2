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
                                <Dropdown v-model="mutasi.invent_code" :options="kodeperi" optionLabel="name"
                                    optionValue="code" :showClear="true" :filter="true" @change="getSn()"
                                    placeholder="Select" :class="{ 'p-invalid': submitted && !mutasi.invent_code }" />
                                <small v-if="errors.invent_code" class="p-error">
                                    {{ errors.invent_code[0] }}
                                </small>
                                <small class="p-error" v-if="submitted && !mutasi.invent_code"> Peripheral not filled
                                </small>
                            </div>
                        </div>
                        <div class="field grid">
                            <label class="col-fixed w-9rem" style="width:145px">S/N</label>
                            <div class="field col-12 md:col-6">
                                <Dropdown v-model="mutasi.invent_sn" :options="sn" optionLabel="name" optionValue="code"
                                    :showClear="true" :filter="true" @change="getImage()" placeholder="Select"
                                    :class="{ 'p-invalid': submitted && !mutasi.invent_sn }" />
                                <small v-if="errors.invent_sn" class="p-error">
                                    {{ errors.invent_sn[0] }}
                                </small>
                                <small class="p-error" v-if="submitted && !mutasi.invent_sn"> S/N not filled.
                                </small>
                            </div>
                        </div>
                        <div class="field grid ">
                            <label class="col-fixed w-9rem" style="width:145px">Out Date</label>
                            <div class="col-12 md:col-6">
                                <DatePicker v-model="mutasi.fromdate" :masks="mask">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div class="flex items-center">
                                            <input
                                                class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                                                :value="inputValue" @click="togglePopover"
                                                placeholder="Choose Out Date" readonly />
                                            <Button icon="pi pi-calendar" v-if="!mutasi.fromdate"
                                                @click="togglePopover" />
                                            <Button icon="pi pi-trash" class="p-button-danger" v-else
                                                @click="mutasi.fromdate = ''" />
                                        </div>
                                    </template>
                                </DatePicker>
                                <small class="p-error" v-if="submitted && !mutasi.fromdate"> Out Date Not Filled.
                                </small>
                            </div>
                        </div>
                        <div class="field grid">
                            <label class="col-fixed w-9rem" style="width:145px">In Date</label>
                            <div class="col-12 md:col-6">
                                <DatePicker v-model="mutasi.todate" :masks="mask" :min-date="mutasi.fromdate">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div class="flex items-center">
                                            <input
                                                class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                                                :value="inputValue" @click="togglePopover"
                                                placeholder="Choose In Date" readonly />
                                            <Button icon="pi pi-calendar" v-if="!mutasi.todate"
                                                @click="togglePopover" />
                                            <Button icon="pi pi-trash" class="p-button-danger" v-else
                                                @click="mutasi.todate = ''" />
                                        </div>
                                    </template>
                                </DatePicker>
                            </div>
                        </div>
                        <div class="field grid">
                            <label class="col-fixed w-9rem" style="width:145px">Location</label>
                            <div class="col-10 md:col-4">
                                <InputText type="text" v-model="mutasi.lokasi" placeholder="Masukan Lokasi. . ."
                                    :class="{ 'p-invalid': submitted && !mutasi.lokasi }" />
                                <small class="p-error" v-if="submitted && !mutasi.lokasi">Lokasi Belum Diisi.
                                </small>
                            </div>
                        </div>
                        <div class="field grid">
                            <label class="col-fixed w-9rem" style="width:145px">User</label>
                            <div class="col-12 md:col-6">
                                <Dropdown v-model="mutasi.user" :options="listUser" optionLabel="usr_fullname"
                                    optionValue="usr_id" :showClear="true" :filter="true" @change="getBuDiv()"
                                    placeholder="Select" :class="{ 'p-invalid': submitted && !mutasi.user }" />

                                <small class="p-error" v-if="submitted && !mutasi.user">Pengguna Belum Diisi.
                                </small>
                                <small v-if="errors.user" class="p-error">
                                    {{ errors.user[0] }}
                                </small>
                            </div>
                        </div>
                        <div class="field grid" v-if="this.mutasi.user">
                            <label class="col-fixed w-9rem" style="width:145px">User Division</label>
                            <div class="col-10 md:col-4">
                                <InputText type="text" v-model="mutasi.imutasi_divisi" disabled />
                            </div>
                        </div>
                        <div class="field grid" v-if="this.mutasi.user">
                            <label class="col-fixed w-9rem" style="width:145px">Business Unit</label>
                            <div class="col-10 md:col-4">
                                <InputText type="text" v-model="mutasi.imutasi_bu" disabled />
                            </div>
                        </div>
                        <div class="field grid">
                            <label class="col-fixed w-9rem" style="width:145px">Remark</label>
                            <div class="col-12 md:col-6">
                                <Textarea v-model="mutasi.ket" :autoResize="true" rows="5" cols="20"
                                    placeholder="Enter remark" :class="{ 'p-invalid': submitted && !mutasi.ket }" />
                                <small class="p-error" v-if="submitted && !mutasi.ket"
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
            <img :src="`${$baseUrl}/master_peripheral/` + detail.photo" class="mutasi-image" v-if="this.mutasi.invent_sn" />
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
      fromdate: new Date(),
      todate: '',
      detail:[],
      kodeperi:[],
      listUser:[],
      sn:[],
      mutasi:{
       invent_code : null,
       invent_sn : null,
       fromdate : null,
       ket : null,
       user : null,
       lokasi : null,
       imutasi_divisi : null,
       imutasi_bu :null
      },
      mask:{
        input: 'DD MMM YYYY'
      },
    };
  },
  mounted(){
    this.getKode();
  },
  methods: {
    getImage(){
      if(this.mutasi.invent_sn){
      this.axios.get('api/getImage/'+this.mutasi.invent_sn).then((response)=>{
        this.detail = response.data;
      });
      }
    },
    getSn(){
      if(this.mutasi.invent_code){
        this.axios.get('api/get-sn-peripheral/'+this.mutasi.invent_code).then((response)=>{
        this.sn = response.data;
      });
      }
    },  
    getBuDiv(){
      if(this.mutasi.user){
        this.axios.get('api/data-divbu/'+this.mutasi.user).then((response)=>{
        this.mutasi.imutasi_divisi = response.data.division;
        this.mutasi.imutasi_bu = response.data.bu;
      });
      }
    },    
    getKode(){
      this.axios.get('api/add-mut').then((response)=>{
        this.kodeperi = response.data.data.kode;
        this.listUser = response.data.data.listUser;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if(error.response.status == 403){
            this.$router.push('/access');
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
            this.mutasi.invent_code != null &&
            this.mutasi.invent_sn != null &&
            this.mutasi.fromdate != null &&
            this.mutasi.ket != null &&
            this.mutasi.user != null &&
            this.mutasi.lokasi != null 
        ) {
        this.axios.post('api/save-mut', this.mutasi).then(()=>{
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
