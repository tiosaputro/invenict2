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
            <form @submit.prevent="UpdateDetail">
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Kode Peripheral</label>
                    <div class="col-fixed w-9rem">
                      <Dropdown 
                        v-model="detail.invent_code"
                        :options="kodeperi"
                        optionLabel="name"
                        optionValue="code"
                        disabled
                      />
                  </div>
                 </div>
                 <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Jumlah (Qty)</label>
                    <div class="col-fixed w-9rem">
                        <InputNumber
                         v-model="detail.dpurchase_qty"
                         placeholder="Masukan Jumlah (Qty)"
                         :change="getTotal()"
                         :class="{ 'p-invalid': submitted && !detail.dpurchase_qty }"
                         />
                      <small class="p-error" v-if="submitted && !detail.dpurchase_qty"
                        >Jumlah (Qty) Belum Diisi.
                      </small>
                  </div>
                </div>
             <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Satuan</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="detail.dpurchase_sat"
                        :options="sat"
                        :showClear="true"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Satuan"
                        :class="{ 'p-invalid': submitted && !detail.dpurchase_sat }"
                     />
                      <small class="p-error" v-if="submitted && !detail.dpurchase_sat"
                        >Satuan Belum Diisi.
                      </small>
                  </div>
              </div>
               <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Harga Satuan</label>
                    <div class="col-fixed w-9rem">
                    <InputNumber
                      mode="currency"
                      :locale="locale"
                      :currency="currency"
                      :change="getTotal()"
                      v-model="detail.dpurchase_prc_sat"
                      placeholder="Masukan Harga Satuan"
                      :class="{ 'p-invalid': submitted && !detail.dpurchase_prc_sat }"
                    />
                      <small class="p-error" v-if="submitted && !detail.dpurchase_prc_sat"
                        >Harga Satuan Belum Diisi.
                      </small>
                  </div>
              </div>
               <div class="field grid">
                  <label class="col-fixed w-9rem" style="width:145px">Total Harga</label>
                    <div class="col-fixed w-9rem">
                      <InputNumber
                          mode="currency"
                          :locale="locale"
                          :currency="currency"
                          v-model="detail.dpurchase_prc"
                          placeholder="Masukan Total Harga"
                          :class="{ 'p-invalid': submitted && !detail.dpurchase_prc }"
                          readonly
                        />
                      <small class="p-error" v-if="submitted && !detail.dpurchase_prc"
                        >Total Harga Belum Diisi.
                      </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:145px">Keterangan</label>
                 <div class="col-fixed w-9rem">
                  <Textarea
                    v-model="detail.dpurchase_remark"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Masukan Keterangan"
                    :class="{ 'p-invalid': submitted && !detail.dpurchase_remark }"
                  />
                      <small class="p-error" v-if="submitted && !detail.dpurchase_remark"
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
      sat:[],
      kodeperi:[],
      detail:[],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      divisi: [],
      id : localStorage.getItem('id'),
    };
  },
  created(){
    this.cekUser();
  },
  methods:{
    cekUser(){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
         if(this.checkname.includes("Pembelian Peripheral") || this.checkto.includes("/pembelian-peripheral")){
          this.getDetail();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
    getTotal(){
      this.detail.dpurchase_prc = this.detail.dpurchase_qty * this.detail.dpurchase_prc_sat
    },
    getDetail(){
        this.axios.get('/api/edit-detail-pem/'+this.$route.params.purchase,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.detail = response.data.dtl;
            this.valuta = response.data.valuta;
            this.sat = response.data.ref;
            this.kodeperi = response.data.mas;
            this.getValutaCode();
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
    getValutaCode(){
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
    },
    UpdateDetail() {
      this.submitted=true;
      if (
        this.detail.dpurchase_qty != null &&
        this.detail.dpurchase_sat != null &&
        this.detail.dpurchase_prc_sat != null &&
        this.detail.dpurchase_prc != null &&
        this.detail.dpurchase_remark != null &&
        this.detail.invent_code != null 
      ) {
        this.axios.put('/api/update-detail-pem/'+ this.$route.params.code + '/' +this.$route.params.purchase, this.detail,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          setTimeout( () => this.$router.push('/pembelian-peripheral-detail/'+ this.$route.params.code),1000);
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