<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Cash Advance</h4>
            </template>
          </Toolbar>
            <form @submit.prevent="CreateCash" v-if="this.ca">
              <div class="card"> <!-- card-->
              <div class="p-fluid formgrid grid">
               <div class="field grid col">
                    <label class="col-fixed w-9rem" style="width:160px">No. Request</label>
                    <div class="col">
                      <Dropdown
                        v-model="noreq"
                        :options="req"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        @change="get(noreq)"
                        placeholder="Pilih No. Request"
                        :class="{ 'p-invalid': errors.noreq }"
                        autofocus
                      />
                      <small v-if="errors.noreq" class="p-error">
                          {{ errors.noreq[0] }}
                      </small>
                      <small v-if="error.noreq" class="p-error">
                          {{ error.noreq }}
                      </small> 
                    </div>
                  </div>
                   <div class="field grid col" v-if="noreq">
                    <label class="col-fixed w-9rem" style="width:160px">Requester</label>
                    <div class="col">
                        <InputText
                            type ="text"
                            v-model="ca.req"
                            disabled
                        />
                     </div>
                    </div>
                 </div>
                 <div class="p-fluid formgrid grid" v-if="noreq">
                  <div class="field grid col">
                   <label class="col-fixed w-9rem" style="width:160px">Tgl. Request</label>
                    <div class="col">
                       <InputText
                            type ="text"
                            v-model="ca.ireq_date"
                            disabled
                        />
                  </div>
                  </div>
                  <div class="field grid col" v-if="noreq">
                  <label class="col-fixed w-9rem" style="width:160px">Bisnis Unit</label>
                    <div class="col">
                        <InputText
                            type ="text"
                            v-model="ca.bu"
                            disabled
                        />
                      </div>
                    </div>
                 </div>
                </div><!-- card-->
                 <div class="card-body"><!-- card body -->
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:180px">Jumlah</label>
                      <div class="col">
                        <InputNumber
                            mode="currency" 
                            currency="IDR" 
                            locale="id"
                            v-model="jum"
                            placeholder="Masukan Jumlah"
                            :class="{ 'p-invalid': errors.jum }"
                        />
                        <small v-if="errors.jum" class="p-error">
                          {{ errors.jum[0] }}
                        </small> 
                      </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:180px">Tgl. Submit</label>
                      <div class="col-12 md:col-3">
                          <DatePicker v-model="tglsub" :masks="mask" >
                            <template v-slot="{ inputValue, togglePopover }">
                             <div class="flex items-center">
                              <input
                                class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                                :value="inputValue"
                                @click="togglePopover"
                                readonly
                                placeholder="Pilih Tgl. Submit"
                              />
                              <Button icon="pi pi-calendar" v-if="!tglsub" @click="togglePopover"/>
                              <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tglsub = ''" />
                              </div>
                              </template>
                          </DatePicker>
                          <small v-if="errors.tglsub" class="p-error">
                            {{ errors.tglsub[0] }}
                          </small> 
                      </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem" style="width:180px">Tgl. Terima Cash</label>
                      <div class="col-12 md:col-3">
                          <DatePicker v-model="tglrecvcash" :min-date="this.tglsub" :masks="mask" >
                            <template v-slot="{ inputValue, togglePopover }">
                             <div class="flex items-center">
                              <input
                                class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                                :value="inputValue"
                                @click="togglePopover"
                                readonly
                                placeholder="Pilih Tgl. Terima Cash"
                              />
                            <Button icon="pi pi-calendar" v-if="!tglrecvcash" @click="togglePopover"/>
                            <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tglrecvcash = ''" />
                            </div>
                            </template>
                          </DatePicker>
                          <small v-if="errors.tglrecvcash" class="p-error">
                              {{ errors.tglrecvcash[0] }}
                          </small>
                      </div>
                  </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:180px">Tgl. Pembelian</label>
                    <div class="col-12 md:col-3">
                        <DatePicker v-model="tglbuy" :min-date="this.tglrecvcash || this.tglsub" :masks="mask" >
                          <template v-slot="{ inputValue, togglePopover }">
                           <div class="flex items-center">
                            <input
                              class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                              :value="inputValue"
                              @click="togglePopover"
                              readonly
                              placeholder="Pilih Tgl. Pembelian"
                            />
                          <Button icon="pi pi-calendar" v-if="!tglbuy" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tglbuy = ''" />
                          </div>
                          </template>
                        </DatePicker>
                        <small v-if="errors.tglbuy" class="p-error">
                            {{ errors.tglbuy[0] }}
                        </small> 
                    </div>
                </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:180px">Tgl. Terima Barang</label>
                 <div class="col-12 md:col-3">
                      <DatePicker v-model="tglrecvunit" :min-date="this.tglbuy || this.tglrecvcash || this.tglsub" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl Terima Barang"
                          />
                        <Button icon="pi pi-calendar" v-if="!tglrecvunit" @click="togglePopover"/>
                        <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tglrecvunit = ''" />
                         </div>
                        </template>
                      </DatePicker>
                      <small v-if="errors.tglrecvunit" class="p-error">
                        {{ errors.tglrecvunit[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                 <label class="col-fixed w-9rem" style="width:180px">Tgl. Penyerahan Ke User</label>
                    <div class="col-12 md:col-3">
                        <DatePicker v-model="tgltouser" :min-date="this.tglrecvunit || this.tglbuy || this.tglrecvcash || this.tglsub" :masks="mask" >
                          <template v-slot="{ inputValue, togglePopover }">
                           <div class="flex items-center">
                            <input
                              class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                              :value="inputValue"
                              @click="togglePopover"
                              readonly
                              placeholder="Pilih Tgl Penyerahan"
                            />
                            <Button icon="pi pi-calendar" v-if="!tgltouser" @click="togglePopover"/>
                            <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tgltouser = ''" />
                            </div>
                          </template>
                        </DatePicker>
                        <small v-if="errors.tgltouser" class="p-error">
                          {{ errors.tgltouser[0] }}
                        </small>
                    </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:180px">Tgl. Closing</label>
                 <div class="col-12 md:col-3">
                      <DatePicker v-model="tglclosing" :min-date="this.tgltouser || this.tglrecvunit || this.tglbuy || this.tglrecvcash || this.tglsub" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl Terima Barang"
                          />
                          <Button icon="pi pi-calendar" v-if="!tglclosing" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="tglclosing = ''" />
                          </div>
                        </template>
                      </DatePicker>
                      <small v-if="errors.tglclosing" class="p-error">
                        {{ errors.tglclosing[0] }}
                      </small>
                  </div>
              </div> 
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-success mr-2"
                  icon="pi pi-check"
                  label="Simpan"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/cash-advance')"
                />
              </div>
              </div><!-- card body -->
            </form>
      </div><!-- card -->
    </div>
</template>
<script>
export default {
  data() {
    return {
      errors: [],
      error:[],
      req:[],
      ca:[],
      noreq:'',
      jum:null,
      tglsub: new Date(),
      tglclosing:'',
      tglrecvcash:'',
      tglbuy:'',
      tglrecvunit:'',
      tgltouser:'',
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      id : localStorage.getItem('id'),
      checkname : [],
      checkto : []
    };
  },
  watch:{
    tglsub: function() {
      this.tglrecvcash = '';
      this.tglbuy= '';
      this.tglrecvunit= '';
      this.tgltouser= '';
      this.tglclosing='';
    },
    tglrecvcash:function(){
      this.tglbuy= '';
      this.tglrecvunit= '';
      this.tgltouser= '';
      this.tglclosing='';
    },
    tglbuy:function(){
      this.tglrecvunit= '';
      this.tgltouser= '';
      this.tglclosing='';
    },
    tglrecvunit:function(){
      this.tgltouser= '';
      this.tglclosing='';
    },
    tgltouser:function(){
      this.tglclosing='';
    },
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
        if(this.checkname.includes("Cash Advance") || this.checkto.includes("/cash-advance")){
            this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    get(noreq){
      if(this.noreq){
      this.axios.get('api/getNameBu/'+noreq,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.ca = response.data;
      });
      if(this.errors.noreq || this.error.noreq){
        this.errors.noreq == '';
        this.error == [];
        console.log(this.errors)
      }
      }
    }, 
    getNoreq(){
      this.axios.get('api/getNoreq',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.req = response.data;
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
    CreateCash() {
      this.errors=[];
      this.error = [];
      if( this.noreq != null){
      
        const data = new FormData();
        data.append("noreq", this.noreq);
        data.append("jum", this.jum);
        data.append("tglrecvunit", this.tglrecvunit);
        data.append("tglbuy", this.tglbuy);
        data.append("tglrecvcash", this.tglrecvcash);
        data.append("tglsub", this.tglsub);
        data.append("tgltouser", this.tgltouser);
        data.append("tglclosing", this.tglclosing);

        this.axios.post('api/add-cash', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          setTimeout( () => this.$router.push('/cash-advance'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
      }else{
        this.error.noreq = "No Request Wajib Diisi"
      }
    }
  },
};
</script>
<style scoped lang="scss">
.pegawai-image {
  width: 100px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>