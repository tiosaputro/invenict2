<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Management User</h4>
          </template>
        </Toolbar>
          <div class="row">
            <div class="col-sm-6">
             <form @submit.prevent="UpdateUser">
              <div class="card-body">
                <div class="field grid">
                <label style="width:120px">User ID</label>
                 <div class="col-3 md-6">
                    <InputText
                      v-model="user.usr_id"
                      disabled
                    />
                </div>
              </div>
               <div class="field grid">
                <label style="width:120px">Full Name</label>
                 <div class="col-6">
                    <InputText
                      type="text"
                      v-model="user.usr_fullname"
                      placeholder="Masukan Full Name"
                      style="text-transform:uppercase;"
                      :class="{ 'p-invalid': errors.usr_fullname }"
                      autofocus
                    />
                   <small v-if="errors.usr_fullname" class="p-error">
                      {{ errors.usr_fullname[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Username</label>
                 <div class="col-3 md-6">
                  <InputText
                    v-model="user.usr_name"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Email</label>
                 <div class="col-4">
                  <InputText
                    type="text"
                    v-model="user.usr_email"
                    placeholder="Masukan Email"
                    :class="{ 'p-invalid': errors.usr_email  }"
                  />
                    <small v-if="errors.usr_email" class="p-error">
                      {{ errors.usr_email[0] }}
                  </small>
                </div>
              </div>
               <div class="field grid">
                <label style="width:120px">Password</label>
                 <div class="col-3 md-6">
                   <Password
                    v-model="user.usr_password"
                    placeholder="Password Baru(Optional)"
                    toggleMask
                    :feedback="false"
                   />
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Alamat</label>
                 <div class="col-4">
                <Textarea
                  v-model="user.usr_alamat"
                    :autoResize="true" 
                    rows="5" 
                    cols="30"
                    placeholder="Masukan Keterangan . . ."
                    :class="{ 'p-invalid': errors.usr_alamat }"
                />
                   <small v-if="errors.usr_alamat" class="p-error">
                      {{ errors.usr_alamat[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Bisnis Unit</label>
                 <div class="col-4">
                  <Dropdown
                      v-model="user.usr_bu"
                      :options="bu"
                      :showClear="true"
                      :filter="true"
                      optionLabel="name"
                      optionValue="code"
                      placeholder="Select A Bisnis Unit"
                      :class="{ 'p-invalid': errors.div }"
                    />
                   <small v-if="errors.usr_bu" class="p-error">
                      {{ errors.usr_bu[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Divisi</label>
                 <div class="col-4">
               <Dropdown
                  v-model="user.div_id"
                  :options="divisi"
                  :showClear="true"
                  :filter="true"
                  optionLabel="name"
                  optionValue="code"
                  placeholder="Select A Divisi"
                  :class="{ 'p-invalid': errors.div_id }"
                />
                   <small v-if="errors.div_id" class="p-error">
                      {{ errors.div_id[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Status</label>
                 <div class="col-4">
                  <Dropdown
                      v-model="user.usr_stat"
                      :options="stat"
                      :showClear="true"
                      :filter="true"
                      optionLabel="nama"
                      optionValue="code"
                      placeholder="Select A Status"
                      :class="{ 'p-invalid': errors.usr_stat }"
                  />
                   <small v-if="errors.usr_stat" class="p-error">
                      {{ errors.usr_stat[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Lokasi</label>
                 <div class="col-4">
                  <Dropdown
                      v-model="user.usr_loc"
                      :options="loc"
                      :showClear="true"
                      :filter="true"
                      optionLabel="name"
                      optionValue="code"
                      placeholder="Select One"
                      :class="{ 'p-invalid': errors.usr_loc }"
                    />
                   <small v-if="errors.usr_loc" class="p-error">
                      {{ errors.usr_loc[0] }}
                  </small>
                </div>
              </div>
              </div>
              <div class="card" style="width: 33rem;">
              <div class="field grid">
                <label style="width:120px">Roles</label>
                 <div class="col-3">
                  <MultiSelect 
                    v-model="role.role" 
                    :options="roles" 
                    optionValue="code"
                    optionLabel="name" 
                    display="chip"
                    placeholder="Select Roles" 
                    :class="{ 'p-invalid': error.role }"
                  />
                   <small v-if="error.role" class="p-error">
                      {{ error.role }}
                  </small>
                  </div>
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
                  @click="$router.push('/mng-user')"
                />
              </div>
            </form>
          </div>
          
              <div class="col-sm-6">
                  <div class="field grid">
                      <label style="width:155px"></label>
                      <div class="col-10 md-6">
                        <div class="card" style="height: 23.5rem;">
                          <img :src="preview" class="user-image" v-if="preview"/>
                          <img :src="'/profile/' +user.usr_foto" class="user-image" v-else />
                        </div>
                  </div>
                 </div>
                 <div class="field grid">
                      <label style="width:155px"></label>
                    <div class="col-10 md-6">
                      <InputText type="file" name="foto" ref="fileInput" class="form-control" @change="fileImage" />
                    </div>
                 </div>
              </div>
      </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      error:[],
      errors: [],
      preview:'',
      user:[],
      role: {
        role : ''
      },
      foto:'',
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      roles: [],
      divisi:[],
      checkname : [],
      checkto : [],
      bu:[],
      loc:[]
    };
  },
  created(){
      this.detailRequest();
  },
  methods: {
    detailRequest(){
      this.axios.get('/api/detail-add-request-user').then((response)=>{
        this.bu = response.data.bisnis;
        this.divisi = response.data.divisi;
        this.roles= response.data.roles;
        this.loc = response.data.location;
        this.getUser();
      }).catch(error=>{
        if ((error.response.status == 401)){
            this.$toast.add({
              severity:'error', summary: 'Error', detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
          }
          if (error.response.status == 403){
            this.$router.push('/access');
          }
         });
    },
    getUser(){
      this.axios.get('/api/edit-user/' +this.$route.params.code).then((response)=>{
        this.user = response.data.user;
        this.role.role = response.data.role;
      })
    },
    fileImage(event) {
        this.foto = event.target.files[0];
        this.displayImage = true;
        this.preview = URL.createObjectURL(event.target.files[0]);
        this.createImage(this.foto);
    },
    createImage(foto) {
        var image = new Image();
        var reader = new FileReader();
        var vm = this.user;
        reader.onload = function (e) {
            vm.image = e.target.result;
        };
        reader.readAsDataURL(foto);
    },
    UpdateUser() {
        this.errors = [];
        this.error = [];
        if(this.role.role != ''){
        this.axios.put('/api/update-user/' +this.$route.params.code , this.user).then(()=>{
        this.axios.put('/api/update-usr-role/'+this.$route.params.code, this.role );
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
          setTimeout( () => this.$router.push('/mng-user'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
        }else{
          this.error.role = "Roles Belum Diisi"
        }
    },
  },
};
</script>
<style scoped lang="scss">
.user-image {
  object-fit:contain;
  height:227pt;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
@media screen and (max-width: 640px) {
    .p-multiselect {
        width: 100%;
    }
}
</style>