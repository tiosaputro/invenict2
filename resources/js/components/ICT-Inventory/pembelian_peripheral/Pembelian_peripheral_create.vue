<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Pembelian Peripheral</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="CreatePurch">
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Suplier</label>
                 <div class="col-fixed w-9rem">
                  <Dropdown
                    :options="suplier"
                    optionLabel="name"
                    optionValue="code"
                    :showClear="true"
                    :filter="true"
                    v-model="supp"
                    placeholder="Pilih Suplier"
                    :class="{ 'p-invalid': submitted && !supp }"
                  />
                   <small class="p-error" v-if="submitted && !supp"
                      >Suplier Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Tgl. Pembelian</label>
                 <div class="col-fixed w-11rem">
                      <DatePicker v-model="purch_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                          <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tanggal Pembelian"
                          />
                          <Button icon="pi pi-calendar" v-if="!purch_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="purch_date = null" />
                          </div>
                        </template>
                      </DatePicker>
                      <small class="p-error" v-if="submitted && !purch_date"
                          >Tgl. Pembelian Belum Diisi.
                      </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Cara Pembayaran</label>
                 <div class="col-fixed w-9rem">
                <Dropdown
                  v-model="pay"
                  :options="methode_pay"
                  optionLabel="name"
                  optionValue="code"
                  :showClear="true"
                  :filter="true"
                  placeholder="Pilih Cara Pembayaran"
                  :class="{ 'p-invalid': submitted && !pay }"
                />
                   <small class="p-error" v-if="submitted && !pay"
                      >Cara Pembayaran Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Petugas</label>
                 <div class="col-fixed w-9rem">
                <InputText
                  type="text"
                  v-model="petugas"
                  placeholder="Masukan Petugas"
                  :class="{ 'p-invalid': submitted && !petugas }"
                />
                   <small class="p-error" v-if="submitted && !petugas"
                      >Petugas Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Mata Uang</label>
                 <div class="col-fixed w-9rem">
               <Dropdown
                  v-model="money"
                  :options="code_money"
                  :showClear="true"
                  optionLabel="name"
                  optionValue="code"
                  :filter="true"
                  placeholder="Pilih Mata Uang"
                  :class="{ 'p-invalid': submitted && !money }"
                />
                   <small class="p-error" v-if="submitted && !money"
                      >Mata Uang Belum Diisi.
                   </small>
                </div>
              </div>
              <!-- <div class="p-fluid">
                <div class="p-field p-grid">
                <label class="p-col-12 p-mb-2 p-md-2 p-mb-md-0" style="width:150px">Total Pembayaran</label>
                 <div class="p-col-3">
                   <div class="p-inputgroup">
                    <span class="p-inputgroup-addon" v-if="money">{{money}}</span>
                     <InputNumber
                        v-model="purchase_total"
                        placeholder="Masukan Total Pembayaran"
                    />
                   </div>
                  </div>
                </div>
              </div> -->
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Keterangan</label>
                 <div class="col-fixed w-9rem">
                   <Textarea
                        type="text"
                        v-model="ket"
                        :autoResize="true"
                        rows="5" 
                        cols="20"
                        placeholder="Masukan Keterangan"
                        :class="{ 'p-invalid': submitted && !ket }"
                    />
                   <small class="p-error" v-if="submitted && !ket"
                      >Keterangan Belum Diisi.
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
                  @click="$router.push('/pembelian-peripheral')"
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
      checkname : [],
      checkto : [],
      errors: [],
      suplier:[],
      code_money:[],
      methode_pay:[],
      purch_date:null,
      status:null,
      money:null,
      petugas:null,
      purchase_total:null,
      pay:null,
      supp:null,
      ket:null,
      submitted: false,
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      mask:{
        input: 'DD MMM YYYY'
      },
    };
  },
  mounted(){
      this.getDetailFormRequest();
  },
  methods: {
    getDetailFormRequest(){
      this.axios.get('api/rsrcsuppo').then((response)=> {
        this.suplier = response.data.data.supp;
        this.methode_pay = response.data.data.metode;
        this.code_money = response.data.data.uang;
        this.petugas = response.data.data.user;
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
    CreatePurch() {
      this.submitted=true;
      if (
        this.supp != null &&
        this.purch_date != null &&
        this.pay != null &&
        this.petugas != null &&
        this.money != null &&
        this.ket != null 
      ) {
        const data = new FormData();
        data.append("supp", this.supp);
        data.append("purch_date", this.purch_date);
        data.append("pay", this.pay);
        data.append("ket", this.ket);
        data.append("petugas", this.petugas);
        data.append("money", this.money);
        data.append("purchase_total", this.purchase_total);
        data.append("status", this.status);
        this.axios.post('api/add-pem', data).then((response)=>{
          setTimeout( () => this.$router.push('/pembelian-peripheral'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
            this.submitted = false;
            this.errors = error.response.data.errors;
        });
      }
    }
  },
};
</script>