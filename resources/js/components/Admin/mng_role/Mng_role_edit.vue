<template>
  <div>
    <div class="col-16">
     <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>Role Menu</h4>
          </template>
        </Toolbar>
             <form @submit.prevent="UpdateRole">
          <div class="row">
            <div class="col-sm-6">
              <div class="card-body">
               <div class="field grid">
                <label style="width:120px">Role ID</label>
                 <div class="col-3 md-6">
                  <InputText
                    v-model="role.rol_id"
                    disabled
                  />
                </div>
              </div>
               <div class="field grid">
                <label style="width:120px">Role Name</label>
                 <div class="col-6">
                  <InputText
                    v-model="role.rol_name"
                    placeholder="Masukan Role Name"
                    :class="{ 'p-invalid': errors.rol_name }"
                    autofocus
                  />
                   <small v-if="errors.rol_name" class="p-error">
                      {{ errors.rol_name[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Role Deskripsi</label>
                 <div class="col-6">
                  <InputText
                    v-model="role.rol_desc"
                    placeholder="Masukan Role Deskripsi"
                    :class="{ 'p-invalid': errors.rol_desc  }"
                  />
                    <small v-if="errors.rol_desc" class="p-error">
                      {{ errors.rol_desc[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Role Status</label>
                 <div class="col-6">
               <Dropdown
                  v-model="role.rol_stat"
                  :options="stat"
                  :showClear="true"
                  optionLabel="nama"
                  optionValue="code"
                  placeholder="Select A Status"
                  :class="{ 'p-invalid': errors.rol_stat }"
                />
                   <small v-if="errors.rol_stat" class="p-error">
                      {{ errors.rol_stat[0] }}
                  </small>
                </div>
              </div>
              </div>
             </div>
            </div>
              <div class="card" style="width: 33rem;">
               <div class="p-fluid">
                <div class="field grid">
                 <label style="width:120px">Menu</label>
                  <div class="col-8">
                    <MultiSelect 
                      v-model="menuss.menu" 
                      :options="menus" 
                      optionValue="code"
                      optionLabel="name" 
                      display="chip"
                      :filter="true"
                      placeholder="Select Menu" 
                      :class="{ 'p-invalid': errors.menu }"
                    >
                    </MultiSelect>
                   <small v-if="errors.menu" class="p-error">
                      {{ errors.menu[0] }}
                  </small>
                  </div>
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
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/mng-role')"
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
      role:[],
      menus:[],
      menuss: {
        menu:null,
      },
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
    };
  },
  created(){
      this.getRole();
  },
  methods: {
    getMenus(){
        this.axios.get('/api/get-menu',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.menus = response.data
      });
    },
    getRole(){
        this.axios.get('/api/edit-role/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.role = response.data;
          this.getMenus();
          this.getMenu();
      }).catch(error=>{
          if (error.response.status == 401){
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
    getMenu(){
        this.axios.get('/api/edit-role-menu/' +this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
          this.menuss.menu = response.data;
      });
    },
    UpdateRole() {
        this.errors = [];
        this.axios.put('/api/update-role/'+this.$route.params.code, this.role, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.axios.put('/api/update-role-menu/'+this.$route.params.code, this.menuss, {headers: {'Authorization': 'Bearer '+this.token}});
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Update",
          });
        setTimeout( () => this.$router.push('/mng-role'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>