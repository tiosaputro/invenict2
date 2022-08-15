<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Management Role</h4>
          </template>
        </Toolbar>
          <div class="row">
            <div class="col-sm-6">
             <form @submit.prevent="CreateRole">
              <div class="card-body">
               <div class="field grid">
                <label style="width:120px">Role Name</label>
                 <div class="col-6">
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
              <div class="card" style="width: 33rem;">
                <div class="p-fluid">
              <div class="field grid">
                <label style="width:120px">Menu</label>
                 <div class="col-8">
                    <MultiSelect 
                        v-model="role.menu" 
                        :options="menus" 
                        optionValue="code"
                        optionLabel="name" 
                        :class="{ 'p-invalid': error.menu }"
                        placeholder="Select Menu" 
                        display="chip" 
                    />
                   <!-- <TreeSelect 
                      v-model="menu" 
                      :options="menus"
                      display="chip"
                      selectionMode="checkbox" 
                      :metaKeySelection="false"
                      selectable="key"
                      placeholder="Select Items"
                    />{{ menu }} -->
                    <small v-if="error.menu" class="p-error">
                      {{error.menu}}
                    </small>
                    </div>
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
    </div>
</template>
<script>
export default {
  data() {
    return {
      error:[],
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
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
    };
  },
  created(){
    this.cekUser();
  },
  methods: {
    cekUser(){
      if(this.id){
      this.axios.get('/api/cek-user/'+ this.id, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Role Menu") || this.checkto.includes("/mng-role")){
          this.getMenu();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
    getMenu(){
      this.axios.get('api/get-menu',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
      this.menus = response.data
    }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
        });
    },
    CreateRole() {
        this.errors = [];
        this.error=[];
        if(this.role.menu != '' && this.role.menu != null){
        this.axios.post('api/save-role',this.role,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
          this.axios.post('api/save-role-menu',this.role,{headers: {'Authorization': 'Bearer '+this.token}});
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push('/mng-role'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
        }else{
            this.error.menu = 'Menu Belum Diisi'
        }
      },
  },
};
</script>