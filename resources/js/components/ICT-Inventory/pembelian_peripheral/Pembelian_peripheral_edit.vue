<template>
    <div>
        <Toast />
        <div class="card">
        <Toolbar class="p-mb-4">
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
                  v-model="purch.suplier_code"
                  placeholder="Pilih Suplier"
                  :class="{ 'p-invalid': submitted && !purch.suplier_code }"
                />
                   <small class="p-error" v-if="submitted && !purch.suplier_code">
                     Suplier Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Tgl. Pembelian</label>
                 <div class="col-fixed w-11rem">
                      <DatePicker v-model="purch.purchase_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                          <div class="flex items-center">
                          <input
                            class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                            :value="inputValue"
                            @click="togglePopover"
                            readonly
                            placeholder="Pilih Tanggal Pembelian"
                          />
                      <Button icon="pi pi-calendar" v-if="!purch.purchase_date" @click="togglePopover"/>
                      <Button icon="pi pi-trash" class="p-button-danger" v-else @click="purch.purchase_date = null" />
                      </div>
                        </template>
                      </DatePicker>
                   <small class="p-error" v-if="submitted && !purch.purchase_date"
                      >Tgl. Pembelian Belum Diisi.
                   </small>
                </div>
              </div>
              
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Cara Pembayaran</label>
                 <div class="col-fixed w-9rem">
                <Dropdown
                  v-model="purch.purchase_pay_methode"
                  :options="methode_pay"
                  optionLabel="name"
                  optionValue="code"
                  :showClear="true"
                  :filter="true"
                  placeholder="Pilih Cara Pembayaran"
                  :class="{ 'p-invalid': submitted && !purch.purchase_pay_methode }"
                />
                   <small class="p-error" v-if="submitted && !purch.purchase_pay_methode"
                      >Cara Pembayaran Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Petugas</label>
                 <div class="col-fixed w-9rem">
                <InputText
                  type="text"
                  v-model="purch.purchase_petugas"
                  placeholder="Masukan Petugas"
                  :class="{ 'p-invalid': submitted && !purch.purchase_petugas }"
                />
                   <small class="p-error" v-if="submitted && !purch.purchase_petugas"
                      >Petugas Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Mata Uang</label>
                 <div class="col-fixed w-9rem">
               <Dropdown
                  v-model="purch.valuta_code"
                  :options="code_money"
                  :showClear="true"
                  optionLabel="name"
                  optionValue="code"
                  :filter="true"
                  placeholder="Pilih Mata Uang"
                  :class="{ 'p-invalid': submitted && !purch.valuta_code }"
                />
                   <small class="p-error" v-if="submitted && !purch.valuta_code"
                      >Mata Uang Belum Diisi.
                   </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Keterangan</label>
                 <div class="col-fixed w-9rem">
                   <Textarea
                        type="text"
                        v-model="purch.purchase_remark"
                        :autoResize="true"
                        rows="5" 
                        cols="20"
                        placeholder="Masukan Keterangan"
                        :class="{ 'p-invalid': submitted && !purch.purchase_remark }"
                    />
                   <small class="p-error" v-if="submitted && !purch.purchase_remark"
                      >Keterangan Belum Diisi.
                   </small>
                </div>
              </div>
              <!-- <div class="field grid">
                <div class="p-field p-grid">
                <label  style="width:150px">Status</label>
                 <div class="p-col-3">
               <Dropdown
                  v-model="purch.purchase_status"
                  :options="stat"
                  :showClear="true"
                  optionLabel="nama"
                  optionValue="code"
                  placeholder="Pilih Status"
                  :class="{ 'p-invalid': submitted && !purch.purchase_status }"
                />
                   <small class="p-error" v-if="submitted && !purch.purchase_status"
                      >Status Belum Diisi.
                   </small>
                  </div>
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
      purch:[],
      suplier:[],
      code_money:[],
      methode_pay:[],
      errors: [],
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
  created(){
    this.getDetailFormRequest();
  },
  methods: {
    getDetailFormRequest(){
      this.axios.get('/api/edit-pem/'+this.$route.params.code).then((response)=>{
        this.purch = response.data.data.pem;
        this.suplier = response.data.data.supp;
        this.methode_pay = response.data.data.metode;
        this.code_money = response.data.data.uang;
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
        this.purch.suplier_code != null &&
        this.purch.purchase_date != null &&
        this.purch.purchase_pay_methode != null &&
        this.purch.purchase_petugas != null &&
        this.purch.valuta_code != null &&
        // this.purch.purchase_status != null &&
        this.purch.purchase_remark != null
      ) {
        this.axios.put('/api/update-pem/'+this.$route.params.code, this.purch).then((response)=>{
          setTimeout( () => this.$router.push('/pembelian-peripheral'),1000);
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