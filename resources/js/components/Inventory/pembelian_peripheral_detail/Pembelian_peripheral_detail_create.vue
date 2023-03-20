<template>
  <div>
      <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
              <h4>Pembelian Peripheral(Detail)</h4>
            </template>
          </Toolbar>
          <div class="card-body">
            <form @submit.prevent="CreateDetail">
               <div class="field grid">
                  <label class="col-fixed w-9rem">Kode Peripheral</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown 
                        v-model="kode"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        :showClear="true"
                        :filter="true"
                        placeholder="Pilih Kode"
                        :class="{ 'p-invalid': submitted && !kode }"
                      />
                      <small class="p-error" v-if="submitted && !kode"
                        >Kode Peripheral Belum Diisi.
                      </small>
                      <small v-if="errors.invent_code" class="p-error">
                        {{ errors.invent_code[0] }}
                      </small>
                    </div>
                   </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem">Jumlah (Qty)</label>
                      <div class="col-fixed w-9rem">
                        <InputNumber
                         v-model="qty"
                         placeholder="Masukan Jumlah (Qty)"
                         :change="getTotal()"
                         :class="{ 'p-invalid': submitted && !qty }"
                         />
                      <small class="p-error" v-if="submitted && !qty"
                        >Jumlah (Qty) Belum Diisi.
                      </small>
                  </div>
                </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Satuan</label>
                    <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="satuan"
                        :options="sat"
                        :showClear="true"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Satuan"
                        :class="{ 'p-invalid': submitted && !satuan }"
                     />
                      <small class="p-error" v-if="submitted && !satuan"
                        >Satuan Belum Diisi.
                      </small>
                  </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Harga Satuan</label>
                    <div class="col-fixed w-9rem">
                    <InputNumber
                       mode="currency"
                      :change="getTotal()"
                      :locale="locale"
                      :currency="currency"
                      v-model="hrgsatuan"
                      placeholder="Masukan Harga Satuan"
                      :class="{ 'p-invalid': submitted && !hrgsatuan }"
                    />
                      <small class="p-error" v-if="submitted && !hrgsatuan"
                        >Harga Satuan Belum Diisi.
                      </small>
                  </div>
              </div>
                <div class="field grid">
                  <label class="col-fixed w-9rem">Total Harga</label>
                    <div class="col-fixed w-9rem">
                     <InputNumber
                         mode="currency"
                        :currency="currency"
                        :locale="locale"
                        v-model="pricetotal"
                        placeholder="Masukan Total Pembayaran"
                        :class="{ 'p-invalid': submitted && !pricetotal }"
                        readonly
                    />
                      <small class="p-error" v-if="submitted && !pricetotal"
                        >Total Harga Belum Diisi.
                      </small>
                </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem">Keterangan</label>
                 <div class="col-fixed w-9rem">
                  <Textarea
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
                  @click="$router.go(-1)"
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
      locale:'id-ID',
      currency:'IDR',
      submitted: false,
      errors: [],
      kode: null,
      satuan:null,
      ket:null,
      pricetotal:null,
      qty:null,
      hrgsatuan:null,
      sat:[],
      kodeperi:[],
      valuta:[],
    };
  },
  created(){
    this.getValutaCode();
  },
  methods: {
    getValutaCode(){
      this.axios.get('/api/getValuta/'+ this.$route.params.code).then((response)=>{
        this.valuta = response.data.data.dtl;
        this.sat = response.data.data.ref;
        this.kodeperi = response.data.data.mas;

        if(this.valuta.valuta_code == '$'){
          this.locale = 'en-US';
          this.currency = 'USD';
        }
        if(this.valuta.valuta_code == 'Rp'){
          this.locale = 'id-ID';
          this.currency = 'IDR';
        }
        if(this.valuta.valuta_code == '¥'){
          this.locale = 'zh-CN';
          this.currency = 'CNY';
        }
        }).catch(error=>{
          if (error.response.status == 401) {
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem('Expired','true')
            setTimeout( () => this.$router.push('/login'),2000);
           }
           if(error.response.status == 403){
             this.$router.push('/access');
           }
      });
    },
    getTotal(){
      if(this.qty && this.hrgsatuan){
      this.pricetotal = this.qty * this.hrgsatuan
      }
    },
    CreateDetail() {
      this.submitted=true;
      this.errors = [];
      if (
        this.kode != null &&
        this.satuan != null &&
        this.ket != null &&
        this.pricetotal != null &&
        this.hrgsatuan != null &&
        this.qty != null 
      ) {
        const data = new FormData();
        data.append("invent_code", this.kode);
        data.append("satuan", this.satuan);
        data.append("ket", this.ket);
        data.append("pricetotal", this.pricetotal);
        data.append("hrgsatuan", this.hrgsatuan);
        data.append("qty", this.qty);
        this.axios.post('/api/add-detail-pem/'+ this.$route.params.code, data).then((response)=>{
          setTimeout( () => this.$router.push('/pembelian-peripheral-detail/'+ this.$route.params.code),1000);
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
    }
  },
};
</script>