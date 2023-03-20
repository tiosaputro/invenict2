<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Divisi Refs</h4>
          </template>
        </Toolbar>
        <div class="row">
          <div class="col-sm-6">
            <form @submit.prevent="DivisiRefs">
              <div class="card-body">
               <div class="field grid">
                <label style="width:120px">Role Name</label>
                 <div class="col-3 md-6">
                <InputText
                  v-model="role.rol_name"
                  placeholder="Masukan Role Name"
                  :class="{ 'p-invalid': errors.rol_name }"
                />
                   <small v-if="errors.rol_name" class="p-error">
                      {{ errors.rol_name[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Role Deskripsi</label>
                 <div class="col-3 md-6">
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
                 <div class="col-3 md-6">
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
              <div class="card" style="width: 33rem;">
              <div class="field grid">
                <label style="width:120px">Menu</label>
                 <div class="col-3 md-6">
               <MultiSelect 
                v-model="role.menu" 
                :options="menus" 
                optionValue="code"
                optionLabel="name" 
                display="chip"
                placeholder="Select Menu" 
                :class="{ 'p-invalid': errors.menu }"
               />
                   <small v-if="errors.menu" class="p-error">
                      {{ errors.menu[0] }}
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
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/mng-role')"
                />
              </div>
            </form>
          </div>
      </div>
      </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      errors: [],
      role : {
        rol_name:'',
        rol_desc:'',
        rol_stat:'',
        menu:'',
      },
      menus:[],
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      checkname : [],
      checkto : [],
    };
  },
  created(){
    this.getMenu();
  },
  methods: {
    getMenu(){
    this.axios.get('api/get-menu').then((response)=>{
      this.menus = response.data
    }).catch(error=>{
      if(error.response.status == 403){
        this.$router.push('/access');
      }
      else if ( error.response.status == 401){
        this.$toast.add({
              severity:'error', 
              summary: 'Error', 
              detail:'Session login expired'
            });
            localStorage.clear();
            localStorage.setItem("Expired","true")
            setTimeout( () => this.$router.push('/login'),2000);
      }
    });
    },
    CreateRole() {
        this.errors = [];
        this.axios.post('api/save-role',this.role).then(()=>{
          this.axios.post('api/save-role-menu',this.role);
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/mng-role'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>