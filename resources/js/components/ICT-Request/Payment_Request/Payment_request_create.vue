<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Payment Request</h4>
            </template>
          </Toolbar>
            <form @submit.prevent="CreatePayment">
              <div class="card"> <!-- card-->
              <div class="p-fluid formgrid grid">
               <div class="field grid col">
                    <label class="col-fixed w-9rem" style="width:160px">No. Request</label>
                    <div class="col-fixed">
                      <Dropdown
                        v-model="noreq"
                        :options="req"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        @change="getDetail(noreq)"
                        placeholder="Pilih No. Request"
                        :class="{ 'p-invalid': errors.noreq }"
                        autofocus
                      />
                      <small v-if="errors.ireq_id" class="p-error">
                          {{ errors.ireq_id[0] }}
                      </small>
                      <small v-if="error.noreq" class="p-error">
                          {{ error.noreq }}
                      </small> 
                    </div>
                  </div>
                   <div class="field grid col" v-if="noreq">
                    <label class="col-fixed w-9rem" style="width:160px">No Detail</label>
                    <div class="col-fixed">
                       <Dropdown
                        v-model="ireqd_id"
                        :options="detail"
                        optionLabel="code"
                        optionValue="code"
                        :showClear="true"
                        @change="get(noreq,ireqd_id)"
                        placeholder="Pilih No. Detail"
                        :class="{ 'p-invalid': errors.ireqd_id }"
                        autofocus
                      />
                      <small v-if="errors.ireqd_id" class="p-error">
                          {{ errors.ireqd_id[0] }}
                      </small>
                      <small v-if="error.ireqd_id" class="p-error">
                          {{ error.ireqd_id }}
                      </small> 
                     </div>
                    </div>
                 </div>
                 <div class="p-fluid formgrid grid" v-if="noreq && ireqd_id">
                  <div class="field grid col">
                   <label class="col-fixed w-9rem" style="width:160px">Requestor</label>
                    <div class="col-fixed">
                       <InputText
                            type ="text"
                            v-model="ca.req"
                            disabled
                        />
                  </div>
                  </div>
                  <div class="field grid col" v-if="noreq && ireqd_id">
                  <label class="col-fixed w-9rem" style="width:160px">Pengguna</label>
                    <div class="col-fixed">
                        <InputText
                            type ="text"
                            v-model="ca.ireq_user"
                            disabled
                        />
                      </div>
                    </div>
                 </div>
                 <div class="p-fluid formgrid grid" v-if="noreq && ireqd_id">
                  <div class="field grid col">
                   <label class="col-fixed w-9rem" style="width:160px">Tgl. Request</label>
                    <div class="col-fixed">
                       <InputText
                            type ="text"
                            v-model="ca.ireq_date"
                            disabled
                        />
                  </div>
                  </div>
                  <div class="field grid col" v-if="noreq && ireqd_id">
                  <label class="col-fixed w-9rem" style="width:160px">Bisnis Unit</label>
                    <div class="col-fixed">
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
                      <div class="col-fixed w-9rem">
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
                          <!-- <small v-if="errors.tglsub" class="p-error">
                            {{ errors.tglsub[0] }}
                          </small>  -->
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
                          <!-- <small v-if="errors.tglrecvcash" class="p-error">
                              {{ errors.tglrecvcash[0] }}
                          </small> -->
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
                        <!-- <small v-if="errors.tglbuy" class="p-error">
                            {{ errors.tglbuy[0] }}
                        </small>  -->
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
                      <!-- <small v-if="errors.tglrecvunit" class="p-error">
                        {{ errors.tglrecvunit[0] }}
                      </small> -->
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
                        <!-- <small v-if="errors.tgltouser" class="p-error">
                          {{ errors.tgltouser[0] }}
                        </small> -->
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
                      <!-- <small v-if="errors.tglclosing" class="p-error">
                        {{ errors.tglclosing[0] }}
                      </small> -->
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
                  @click="$router.push('/payment-request')"
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
      detail:[],
      noreq:'',
      ireqd_id:'',
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
    noreq:function(){
      this.error.noreq='';
      this.errors.ireq_id='';
    },
    ireqd_id:function(){
      this.error.ireqd_id='';
      this.errors.ireqd_id='';
    },
    jum:function(){
      this.error.jum='';
      this.errors.jum='';
    }
  },
  mounted(){
    this.cekUser();
  },
  methods: {
    cekUser(){
      this.axios.get('api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Payment Request") || this.checkto.includes("/payment-request")){
            this.getNoreq();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getDetail(noreq){
    if(this.noreq){
      this.axios.get('api/getDetail/'+noreq,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.detail = response.data;
        this.ireqd_id = '';
      });
      if(this.errors.noreq || this.error.noreq){
        this.errors.noreq == '';
        this.error == [];
      }
     }
    },
    get(noreq,ireqd_id){
      if(this.noreq &&this.ireqd_id){
      this.axios.get('api/getNameBu/'+noreq+'/'+ireqd_id,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.ca = response.data;
      });
      if(this.errors.noreq || this.error.noreq){
        this.errors.noreq == '';
        this.error == [];
      }
      }
    }, 
    getNoreq(){
      this.axios.get('api/getNoreq',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.req = response.data;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
          }
        });
    },   
    CreatePayment() {
      this.errors=[];
      this.error = [];
      if( this.noreq != null ||
      this.ireqd_id != null){
      
        const data = new FormData();
        data.append("ireq_id", this.noreq);
        data.append("ireqd_id",this.ireqd_id)
        data.append("jum", this.jum);
        data.append("tglrecvunit", this.tglrecvunit);
        data.append("tglbuy", this.tglbuy);
        data.append("tglrecvcash", this.tglrecvcash);
        data.append("tglsub", this.tglsub);
        data.append("tgltouser", this.tgltouser);

        this.axios.post('api/add-payment-request', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          setTimeout( () => this.$router.push('/payment-request'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
      }else{
        this.error.noreq = "No Request Belum Diisi"
        this.error.ireqd_id = "No Detail Belum Diisi"
      }
    }
  },
};
</script>