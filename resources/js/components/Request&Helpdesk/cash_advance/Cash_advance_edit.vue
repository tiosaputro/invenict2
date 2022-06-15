<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Cash Advance</h4>
            </template>
          </Toolbar>
            <form @submit.prevent="UpdateCash" v-if="this.ca">
           <div class="card">
              <div class="p-fluid formgrid grid">
               <div class="field grid col">
                <label class="col-fixed w-9rem" style="width:160px">No. Request</label>
                  <div class="col">
                    <InputText
                      type="text"
                      v-model="ca.ca_idd"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid col">
                <label class="col-fixed w-9rem" style="width:160px">No. Detail</label>
                  <div class="col">
                    <InputText
                      type ="text"
                      v-model="ca.ireqd_id"
                      disabled
                    />
                  </div>
              </div>
              </div>
              <div class="p-fluid formgrid grid">
               <div class="field grid col">
                <label class="col-fixed w-9rem" style="width:160px">Requestor</label>
                  <div class="col">
                    <InputText
                      type="text"
                      v-model="ca.req"
                      disabled
                    />
                  </div>
              </div>
              <div class="field grid col">
                <label class="col-fixed w-9rem" style="width:160px">Pengguna</label>
                  <div class="col">
                    <InputText
                      type ="text"
                      v-model="ca.ireq_user"
                      disabled
                    />
                  </div>
              </div>
              </div>
              <div class="p-fluid formgrid grid">
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
                  <div class="field grid col">
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
              </div>
              <div class="card-body">
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:180px">Jumlah</label>
                    <div class="col">
                      <InputNumber
                        mode="currency" 
                        currency="IDR" 
                        locale="id"
                        v-model="ca.ca_pic_name"
                        placeholder="Masukan Jumlah"
                        :class="{ 'p-invalid': errors.ca_pic_name }"
                      />
                      <small v-if="errors.ca_pic_name" class="p-error">
                        {{ errors.ca_pic_name[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:180px">Tgl. Submit</label>
                    <div class="col-12 md:col-3">
                        <DatePicker v-model="ca.ca_submit_date" :masks="mask" >
                          <template v-slot="{ inputValue, togglePopover }">
                           <div class="flex items-center">
                            <input
                              class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                              :value="inputValue"
                              @click="togglePopover"
                              readonly
                              placeholder="Pilih Tgl. Submit"
                            />
                            <Button icon="pi pi-calendar" v-if="!ca.ca_submit_date" @click="togglePopover"/>
                            <Button icon="pi pi-trash" class="p-button-danger" v-else @click="ca.ca_submit_date = ''" />
                            </div>
                          </template>
                        </DatePicker>
                       <small v-if="errors.ca_submit_date" class="p-error">
                         {{ errors.ca_submit_date[0] }}
                       </small>
                </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:180px">Tgl. Terima Cash</label>
                  <div class="col-12 md:col-3">
                      <DatePicker v-model="ca.ca_recv_cash_date" :min-date="this.ca.ca_submit_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl. Terima Cash"
                          />
                          <Button icon="pi pi-calendar" v-if="!ca.ca_recv_cash_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="ca.ca_recv_cash_date = ''" />
                          </div>
                        </template>
                      </DatePicker>
                    <small v-if="errors.ca_recv_cash_date" class="p-error">
                      {{ errors.ca_recv_cash_date[0] }}
                    </small>
               </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:180px">Tgl. Pembelian</label>
                    <div class="col-12 md:col-3">
                      <DatePicker v-model="ca.ca_purchase_date" :min-date="this.ca.ca_recv_cash_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl. Pembelian"
                          />
                          <Button icon="pi pi-calendar" v-if="!ca.ca_purchase_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="ca.ca_purchase_date = ''" />
                          </div>
                        </template>
                      </DatePicker>
                       <small v-if="errors.ca_purchase_date" class="p-error">
                          {{ errors.ca_purchase_date[0] }}
                        </small>
                </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:180px">Tgl. Terima Barang</label>
                 <div class="col-12 md:col-3">
                      <DatePicker v-model="ca.ca_recv_item_date" :min-date="this.ca.ca_purchase_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl Terima Barang"
                          />
                          <Button icon="pi pi-calendar" v-if="!ca.ca_recv_item_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="ca.ca_recv_item_date = ''" />
                          </div>
                        </template>
                      </DatePicker>
                       <small v-if="errors.ca_recv_item_date" class="p-error">
                          {{ errors.ca_recv_item_date[0] }}
                        </small>
                 </div>
               </div>
                <div class="field grid">
                 <label class="col-fixed w-9rem" style="width:180px">Tgl. Penyerahan Ke User</label>
                    <div class="col-12 md:col-3">
                      <DatePicker v-model="ca.ca_hand_over_date" :min-date="this.ca.ca_recv_item_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl Penyerahan"
                          />
                          <Button icon="pi pi-calendar" v-if="!ca.ca_hand_over_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="this.ca.ca_hand_over_date = ''" />
                          </div>
                        </template>
                      </DatePicker>
                       <small v-if="errors.ca_hand_over_date" class="p-error">
                          {{ errors.ca_hand_over_date[0] }}
                        </small>
                      </div>
                  </div>
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:180px">Tgl. Closing</label>
                 <div class="col-12 md:col-3">
                      <DatePicker v-model="ca.ca_settlement_date" :min-date="this.ca.ca_recv_item_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                         <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tgl Terima Barang"
                          />
                          <Button icon="pi pi-calendar" v-if="!ca.ca_settlement_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="ca.ca_settlement_date = ''" />
                          </div>
                        </template>
                      </DatePicker>
                       <small v-if="errors.ca_settlement_date" class="p-error">
                          {{ errors.ca_settlement_date[0] }}
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
                  @click="$router.push('/cash-advance')"
                />
              </div>
              </div>
            </form>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      errors: [],
      ca:[],
      mask:{
        input: 'DD MMM YYYY'
      }, 
      token: localStorage.getItem('token'),
      id : localStorage.getItem('id'),
      checkname : [],
      checkto : [],
    };
  },
  mounted(){
    this.getCash();
  },
  methods: {
    cekUser(){
    if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Cash Advance") || this.checkto.includes("/cash-advance")){
            this.getCash();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getCash(){
      this.axios.get('/api/edit-cash/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.ca = response.data;
      }).catch(error=>{
          if ((error.response.status == 401)){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Sesi Login Expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
    },   
    UpdateCash() {
      this.errors = [];
  
        this.axios.put('/api/update-cash/'+this.$route.params.code, this.ca, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          setTimeout( () => this.$router.push('/cash-advance'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
        }).catch(error=>{
            this.errors = error.response.data.errors;
        });
    }
  },
};
</script>