<template>
  <div>
        <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                  <h4>Suplier</h4>
            </template>
          </Toolbar>
          <div class="card-body">
            <form @submit.prevent="UpdateSupplier">
              <div class="formgroup-inline">
              <div class="field grid">
                 <label style="width:120px">Kode</label>
                    <InputText 
                      type="text"
                      v-model="supp.suplier_code"
                      disabled
                    />
                </div>
                <div class="field grid">
                  <label style="width:120px">Nama</label>
                  <div class="col-5">
                      <InputText
                        type="text"
                        v-model="supp.suplier_name"
                        autofocus
                        :class="{ 'p-invalid': errors.suplier_name }"
                      />
                      <small class="p-error" v-if="errors.suplier_name"
                        >{{ errors.suplier_name[0] }}
                      </small>
                      </div>
               </div>
              </div>
                <div class="field grid">
                    <label style="width:120px">Contact Person</label>
                      <div class="col-3">
                        <InputText
                          type="text"
                          v-model="supp.suplier_contact"
                          :class="{ 'p-invalid': errors.suplier_contact }"
                        />
                        <small class="p-error" v-if="errors.suplier_contact"
                          >{{ errors.suplier_contact[0] }}
                        </small>
                    </div>
                  </div>
                <div class="field grid">
                    <label style="width:120px">Alamat 1</label>
                      <div class="col-3">
                        <Textarea
                          type="text"
                          v-model="supp.suplier_address1"
                          :autoResize="true"
                          :class="{ 'p-invalid': errors.suplier_address1 }"
                        />
                        <small class="p-error" v-if="errors.suplier_address1"
                          >{{ errors.suplier_address1[0] }}
                        </small>
                  </div>
                </div>
                <div class="field grid">
                    <label style="width:120px">Alamat 2</label>
                      <div class="col-4">
                        <Textarea
                          type="text"
                          v-model="supp.suplier_address2"
                          :autoResize="true"
                          placeholder="(Optional)"
                        />
                  </div>
                </div>
                <div class="formgroup-inline">
                  <div class="field grid">
                    <label style="width:115px">Kota</label>
                    <div class="col-3">
                        <InputText
                          type="text"
                          v-model="supp.suplier_city"
                          :class="{ 'p-invalid': errors.suplier_city}"
                        />
                        <small class="p-error" v-if="errors.suplier_city"
                          >{{ errors.suplier_city[0] }}
                        </small>
                        </div>
                    </div>
                  <div class="field grid">
                    <label style="width:120px">Provinsi</label>
                    <div class="col-4">
                        <InputText 
                          v-model="supp.suplier_prov" 
                          type="text"
                          :class="{ 'p-invalid': errors.suplier_prov }" 
                        />
                        <small class="p-error" v-if="errors.suplier_prov"
                          >{{ errors.suplier_prov[0] }}
                        </small>
                        </div>
                  </div>
                </div>
                <div class="field grid">
                    <label style="width:120px">Email</label>
                      <div class="col-3">
                        <InputText
                          type="Email"
                          v-model="supp.suplier_email"
                          :class="{ 'p-invalid': errors.suplier_email }" 
                        />
                        <small class="p-error" v-if="errors.suplier_email"
                          >{{ errors.suplier_email[0] }}
                        </small>
                  </div>
                </div>
                <div class="field grid">
                    <label style="width:120px">Fax</label>
                      <div class="col-3">
                        <InputText
                          type="text"
                          v-model="supp.suplier_fax"
                          :class="{ 'p-invalid': errors.suplier_fax }" 
                        />
                        <small class="p-error" v-if="errors.suplier_fax"
                          >{{ errors.suplier_fax[0] }}
                        </small>
                  </div>
                </div>
                <div class="field grid">
                    <label style="width:120px">No.Tlp-1</label>
                      <div class="col-3">
                        <InputText
                          type="text"
                          v-model="supp.suplier_tlp1"
                          placeholder="Masukan No.Tlp 1. . ."
                          :class="{ 'p-invalid': errors.suplier_tlp1 }" 
                        />
                        <small class="p-error" v-if="errors.suplier_tlp1"
                          >{{ errors.suplier_tlp1[0] }}
                        </small>
                  </div>
                </div> 
                <div class="field grid">
                    <label style="width:120px">No.Tlp-2</label>
                      <div class="col-3">
                        <InputText
                          type="text"
                          v-model="supp.suplier_tlp2"
                          placeholder="(Optional)"
                          :class="{ 'p-invalid': errors.suplier_tlp2 }" 
                        />
                        <small class="p-error" v-if="errors.suplier_tlp2"
                          >{{ errors.suplier_tlp2[0] }}
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
                  @click="$router.push('/referensi-supplier')"
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
      supp: [],
    };
  },
  created() {
    this.getSupp();
  },
  methods: { 
    getSupp() {
      this.axios.get('/api/edit-supp/' +this.$route.params.code).then((response)=>{
        this.supp = response.data;
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
            this.router.push('/access');
          }
        });
    },
    UpdateSupplier(){
      this.errors= [];
      this.axios.put('/api/update-supp/' +this.$route.params.code, this.supp).then(()=>{
        this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
        setTimeout( () => this.$router.push('/referensi-supplier'),1000);
      }).catch(error =>{
            this.errors = error.response.data.errors;
      });
  },
 },
};
</script>