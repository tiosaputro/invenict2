<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>ICT Request</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="CreateIct">
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">No. Request</label>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Tgl. Request</label>
                 <div class="col-fixed w-11rem">
                      <DatePicker v-model="tgl" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            placeholder="Pilih Tgl. Request"
                            readonly
                          />
                          <Button icon="pi pi-calendar" v-if="!tgl" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tgl = '' " />
                         </div>
                        </template>
                      </DatePicker>
                      <small v-if="errors.tgl" class="p-error">
                        {{ errors.tgl[0] }}
                      </small>
                    </div>
                </div>
              
              <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tipe Request</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="tipereq"
                        :options="type"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Tipe Request"
                        :showClear="true"
                        :class="{ 'p-invalid': error.tipereq }"
                     />
                        <small v-if="error.tipereq" class="p-error">
                          {{error.tipereq}}
                        </small>
                </div>
              </div> -->
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Priority Level</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="priolev"
                        :options="level"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Priority Level"
                        :showClear="true"
                        :filter="true"
                        :class="{ 'p-invalid': error.priolev }"
                     />
                        <small v-if="error.priolev" class="p-error">
                          {{error.priolev}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Pengguna</label>
                 <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model="usr_name"
                        placeholder="Masukan Pengguna"
                        :class="{ 'p-invalid': error.usr_name }"
                     />
                        <small v-if="error.usr_name" class="p-error">
                          {{error.usr_name}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Divisi Pengguna</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="usr_divisi"
                        :options="divisi"
                        optionLabel="name"
                        optionValue="code"
                        :class="{ 'p-invalid': error.usr_divisi }"
                        placeholder="Pilih Divisi Pengguna"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.usr_divisi" class="p-error">
                          {{error.usr_divisi}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Bisnis Unit</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="bisnis"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        :class="{ 'p-invalid': error.bisnis }"
                        placeholder="Pilih Bisnis Unit"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.bisnis" class="p-error">
                          {{error.bisnis}}
                        </small>
                </div>
              </div>
              <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Keterangan</label>
                 <div class="field col-12 md:col-4">
                  <Textarea 
                    v-model="ket"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Masukan Keterangan . . ."
                    :class="{ 'p-invalid': error.ket }"
                  />
                      <small class="p-error" v-if="error.ket"
                        >{{error.ket}}
                      </small>
                </div>
              </div> -->
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Simpan"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/ict-request')"
                />
              </div>
            </form>
          </div>
      </div>
      </div>
</template>
<script>
export default {
  data() {
    return {
      errors: [],
      level:[],
      error:[],
      tgl: new Date(),
      tipereq: null,
      priolev:null,
      usr_name:null,
      usr_divisi : null,
      ket:null,
      divisi:[],
      bisnis: null,
      type: [],
      bu: [],
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
      code: null,
      // user:[],
    };
  },
  watch:{
    
  },
  mounted(){
      this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
          // this.getUser();
          this.getType();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    // getUser(){
    //   this.axios.get('api/user',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
    //     this.user = response.data;
    //     this.usr_name = this.user.usr_name;
      //   this.bisnis = this.user.usr_bu;
      //   this.usr_divisi = this.user.div_id
      // });
    // },
    getType(){
      this.axios.get('api/getAddReq', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.type = response.data.ref;
        this.bu = response.data.bisnis;
        this.divisi = response.data.divisi;
        this.level = response.data.prio;
      });
    },
    CreateIct() {
      this.errors = [];
      this.error = [];
      if (
        // this.tipereq != null &&
        this.priolev != null &&
        this.usr_name != null &&
        this.usr_divisi != null &&
        this.bisnis != null
      )
      {
        const data = new FormData();
        data.append("tgl", this.tgl);
        data.append("tipereq", this.tipereq);
        data.append("bisnis", this.bisnis);
        data.append("user_name", this.usr_name);
        data.append("user_divisi", this.usr_divisi);
        // data.append("ket", this.ket);
        data.append("priolev", this.priolev);

        this.axios.post('api/add-ict', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        this.code = response.data.ireq_id;
        setTimeout( () => this.$router.push({name: 'Add Ict Request Detail', params: { code: this.code }, }),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }else{
        if(this.priolev == null){
          this.error.priolev = "Priority Level Belum Diisi"
        }
        if(this.bisnis == null){
          this.error.bisnis = "Bisnis Unit Belum Diisi"
        }
        if(this.usr_name == null){
          this.error.usr_name = "Pengguna Belum Diisi"
        }
        if(this.usr_divisi == null){
          this.error.usr_divisi = "Divisi Pengguna Unit Belum Diisi"
        }
        
      }
    },
  },
};
</script>