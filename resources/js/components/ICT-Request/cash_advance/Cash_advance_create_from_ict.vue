<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Cash Advance</h4>
            </template>
          </Toolbar>
            <form @submit.prevent="CreateCash">
              <div class="card"> <!-- card-->
              <div class="p-fluid formgrid grid">
               <div class="field grid col">
                    <label class="col-fixed w-9rem" style="width:160px">No. Request</label>
                    <div class="col-fixed">
                      <InputText
                        v-model="ca.ireq_no"
                        disabled
                      />
                    </div>
                    <small v-if="errors.ireq_id" class="p-error">
                      {{ errors.ireq_id[0] }}
                    </small>
                  </div>
                   <div class="field grid col" v-if="ca.ireqd_id">
                    <label class="col-fixed w-9rem" style="width:160px">No. Detail</label>
                    <div class="col-fixed">
                        <InputText
                            type ="text"
                            v-model="ca.ireqd_id"
                            disabled
                        />
                     </div>
                    </div>
                 </div>
                 <div class="p-fluid formgrid grid">
               <div class="field grid col" v-if="ca.req">
                    <label class="col-fixed w-9rem" style="width:160px">Requestor</label>
                    <div class="col-fixed">
                      <InputText
                        v-model="ca.req"
                        disabled
                      />
                    </div>
                  </div>
                   <div class="field grid col" v-if="ca.ireq_user">
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
                 <div class="fluid formgrid grid" v-if="ca.ireq_date">
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
                  <div class="field grid col" v-if="ca.bu">
                  <label for="file" class="col-fixed w-9rem" style="width:160px">Bisnis Unit</label>
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
                      <div class="col-fixed">
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
                      <DatePicker v-model="tglbuy" :min-date="this.tglrecvcash" :masks="mask" >
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
                      <DatePicker v-model="tglrecvunit" :min-date="this.tglbuy" :masks="mask" >
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
                      <DatePicker v-model="tgltouser" :min-date="this.tglrecvunit" :masks="mask" >
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
                  @click="$router.push('/ict-request-divisi3')"
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
      req:[],
      ca:[],
      jum:null,
      tglsub: new Date(),
      tglrecvcash:'',
      tglbuy:'',
      tglrecvunit:'',
      tgltouser:'',
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  created(){
      this.cekUser();
  },
  methods: {
    cekUser(){
        this.axios.get('/api/cek-user', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.checkto = response.data.map((x)=> x.to)
          this.checkname = response.data.map((x)=> x.name)
          if(this.checkname.includes("Status Change Request") || this.checkto.includes("/ict-request-divisi3")){ 
            this.get();
          }
          else {
            this.$router.push('/access');
          }
        });
    },
    get(){
      this.axios.get('/api/getNameBu/'+this.$route.params.code+'/'+this.$route.params.dtl,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
        this.ca = response.data;
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
    getNoreq(){
      this.axios.get('/api/getNoreq',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
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
    CreateCash() {
      this.errors=[];
      
        const data = new FormData();
        data.append("ireq_id", this.ca.ireq_id);
        data.append("ireqd_id",this.ca.ireqd_id)
        data.append("jum", this.jum);
        data.append("tglrecvunit", this.tglrecvunit);
        data.append("tglbuy", this.tglbuy);
        data.append("tglrecvcash", this.tglrecvcash);
        data.append("tglsub", this.tglsub);
        data.append("tgltouser", this.tgltouser);

        this.axios.post('/api/add-cash', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          setTimeout( () => this.$router.push('/cash-advance'),1000);
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