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
            <form @submit.prevent="CreateSupplier">
              <div class="formgroup-inline">
               <div class="field grid">
                  <label style="width:120px">Kode</label>
                    <div class="col-fixed">
                      <InputText
                        type="text"
                        v-model="code"
                        placeholder="Masukan Kode. . ."
                        :class="{ 'p-invalid': errors.code }"
                        class="inputfield"
                        autofocus
                      />
                      <small v-if="errors.code" class="p-error">
                          {{ errors.code[0] }}
                      </small>  
                      </div>
                  </div>
                   <div class="field grid">
                    <label class="col-fixed w-9rem">Nama</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                      type="text"
                      v-model="nama"
                      placeholder="Masukan Nama. . ."
                      :class="{ 'p-invalid': errors.nama }"
                      />
                      <small v-if="errors.nama" class="p-error">
                          {{ errors.nama[0] }}
                      </small>  
                      </div>
                   </div>
                  </div>
                  <div class="field grid">
                    <label class="col-fixed w-9rem">Contact Person</label>
                      <div class="col-fixed w-9rem">
                        <InputText
                        type="text"
                        v-model="contact"
                        placeholder="Masukan Contact Person. . ."
                        class="inputfield"
                        :class="{ 'p-invalid': errors.contact }"
                        />
                      <small v-if="errors.contact" class="p-error">
                          {{ errors.contact[0] }}
                      </small>
                  </div>
                  </div>
             <div class="field grid">
                <label class="col-fixed w-9rem">Alamat 1</label>
                 <div class="col-fixed w-9rem">
                <Textarea
                  type="text"
                  v-model="alamat1"
                  :autoResize="true"
                  placeholder="Masukan Alamat 1. . ."
                  :class="{ 'p-invalid': errors.alamat1 }"
                />
                 <small v-if="errors.alamat1" class="p-error">
                    {{ errors.alamat1[0] }}
                  </small>
                  </div>
              </div>
               <div class="field grid">
                <label class="col-fixed w-9rem">Alamat 2</label>
                <div class="col-fixed w-9rem">
               <Textarea
                  type="text"
                  v-model="alamat2"
                  :autoResize="true"
                  placeholder="(Optional)"
                />
                 </div>
              </div>
               <div class="formgroup-inline">
                <div class="field grid">
                  <label style="width:120px">Kota</label>
                    <div class="col-fixed">
                      <InputText
                      type="text"
                      v-model="kota"
                      placeholder="Masukan Kota. . ." 
                      :class="{ 'p-invalid': errors.kota }"
                      class="inputfield"
                      />
                      <small v-if="errors.kota" class="p-error">
                        {{ errors.kota[0] }}
                      </small>
                    </div>
                  </div>
                 <div class="field grid">
                  <label class="col-fixed w-9rem">Provinsi</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                      v-model="provinsi"
                      placeholder="Masukan Provinsi. . ."
                      type="text"
                      :class="{ 'p-invalid': errors.provinsi }" 
                      />
                      <small v-if="errors.provinsi" class="p-error">
                          {{ errors.provinsi[0] }}
                      </small>
                    </div>
                 </div>
                </div>
               <div class="field grid">
                  <label class="col-fixed w-9rem">Email</label>
                    <div class="col-fixed w-9rem">
                      <InputText
                          type="Email"
                          v-model="email"
                          placeholder="Masukan Email. . ."
                          :class="{ 'p-invalid': errors.email }"
                        />
                        <small v-if="errors.email" class="p-error">
                          {{ errors.email[0] }}
                      </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Fax</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                      type="text"
                      v-model="fax"
                      placeholder="Masukan Fax. . ."
                      :class="{ 'p-invalid': errors.fax }"
                    />
                      <small v-if="errors.fax" class="p-error">
                              {{ errors.fax[0] }}
                      </small>
               </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">No.Tlp-1</label>
                <div class="col-fixed w-9rem">
               <InputText
                  type="text"
                  v-model="notlp1"
                  placeholder="Masukan No.Tlp 1. . ."
                  :class="{ 'p-invalid': errors.notlp1 }"
                />
                      <small v-if="errors.notlp1" class="p-error">
                          {{ errors.notlp1[0] }}
                      </small>
                </div>
              </div> 
              <div class="field grid">
                <label class="col-fixed w-9rem">No.Tlp-2</label>
                <div class="col-fixed w-9rem">
               <InputText
                  type="text"
                  v-model="notlp2"
                  placeholder="(Optional)"
                  :class="{ 'p-invalid': errors.notlp2 }"
                />
                  <small v-if="errors.notlp2" class="p-error">
                    {{ errors.notlp2[0] }}
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
      code:'',
      nama:'',
      contact:'',
      alamat1:'',
      alamat2:'',
      kota:'',
      provinsi:'',
      email:'',
      fax:'',
      notlp1:'',
      notlp2:'',
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  methods: {
    CreateSupplier() {
      this.errors = [];
        const data = new FormData();
        data.append("nama", this.nama);
        data.append("code", this.code);
        data.append("contact", this.contact);
        data.append("alamat1", this.alamat1);
        data.append("alamat2", this.alamat2);
        data.append("kota", this.kota);
        data.append("provinsi", this.provinsi);
        data.append("email", this.email);
        data.append("fax", this.fax);
        data.append("kota", this.kota);
        data.append("notlp1", this.notlp1);
        data.append("notlp2", this.notlp2);

        this.axios.post('api/add-supp', data, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          setTimeout( () => this.$router.push('/referensi-supplier'),1000);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
        }).catch(error=>{
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          if(error.response.status == 403){
            this.router.push('/access');
          }
          if (error.response.status == 401){
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
          }
        });
    }
  },
};
</script>