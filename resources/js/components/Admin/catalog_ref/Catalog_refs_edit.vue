<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
			<h4>Catalog Refs</h4>
          </template>
        </Toolbar>
          <div class="card-body">
            <form @submit.prevent="UpdateCatalog">
               <div class="field grid">
                <label style="width:120px">Catalog Name</label>
                 <div class="col-3 md-6">
                  <InputText
                    v-model="catalog.catalog_name"
                    placeholder="Enter Catalog Name"
                    :class="{ 'p-invalid': errors.catalog_name }"
                    autofocus
                  />
                  <small v-if="errors.catalog_name" class="p-error">
                    {{ errors.catalog_name[0] }}
                  </small>
                </div>
               </div>
               <div class="field grid">
                <label style="width:120px">Catalog Description</label>
                 <div class="col-3 md-6">
                  <InputText
                    v-model="catalog.catalog_desc"
                    placeholder="Enter Catalog Description"
                    :class="{ 'p-invalid': errors.catalog_desc }"
                    autofocus
                  />
                  <small v-if="errors.catalog_desc" class="p-error">
                    {{ errors.catalog_desc[0] }}
                  </small>
                </div>
               </div>
              <div class="field grid">
                <label style="width:120px">Catalog Request Type</label>
                 <div class="col-3 md-6">
                    <Dropdown
                      v-model="catalog.catalog_request_type"
                      :options="requesttype"
                      :showClear="true"
                      optionLabel="nama"
                      optionValue="code"
                      placeholder="Select One"
                      :class="{ 'p-invalid': errors.catalog_request_type }"
                    />
                   <small v-if="errors.catalog_request_type" class="p-error">
                      {{ errors.catalog_request_type[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Catalog Type</label>
                 <div class="col-3 md-6">
                    <Dropdown
                      v-model="catalog.catalog_type"
                      :options="type"
                      :showClear="true"
                      optionLabel="nama"
                      optionValue="code"
                      placeholder="Select One"
                      :class="{ 'p-invalid': errors.catalog_type }"
                    />
                   <small v-if="errors.catalog_type" class="p-error">
                      {{ errors.catalog_type[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Catalog Status</label>
                 <div class="col-3 md-6">
                    <Dropdown
                      v-model="catalog.catalog_stat"
                      :options="stat"
                      :showClear="true"
                      optionLabel="nama"
                      optionValue="code"
                      placeholder="Select One"
                      :class="{ 'p-invalid': errors.catalog_stat }"
                    />
                   <small v-if="errors.catalog_stat" class="p-error">
                      {{ errors.catalog_stat[0] }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label style="width:120px">Parent ID</label>
                 <div class="col-3 md-6">
                  <Dropdown
                      v-model="catalog.parent_id"
                      :options="parent"
                      :showClear="true"
                      optionLabel="name"
                      optionValue="code"
                      placeholder="Select A Parent"
                      :class="{ 'p-invalid': errors.parent_id }"
                    />
                   <small v-if="errors.parent_id" class="p-error">
                      {{ errors.parent_id[0] }}
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
                  class="p-button-rounded p-button-secondary mr-2"
                  icon="pi pi-times"
                  @click="$router.push('/catalog-refs')"
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
      catalog:{
        catalog_name:'',
        catalog_desc:'',
        catalog_type: '',
        catalog_stat:'',
        catalog_request_type:'',
        parent_id:'',
      },
      requesttype:[
        { nama: "Peripheral", code: "P" },
        { nama: "Service", code: "S" },
      ],
      modul:[],
      parent:[],
      checkname : [],
      checkto : [],
      stat: [
        { nama: "Aktif", code: "T" },
        { nama: "Tidak Aktif", code: "F" },
      ],
      type: [
        { nama: "Node", code: "N" },
        { nama: "Leaf", code: "L"}
      ],
      token: localStorage.getItem('token')
    };
  },
  created(){
      this.getCatalog();
    },
  methods: {
    getCatalog(){
        this.axios.get('/api/edit-catalog/'+this.$route.params.code,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.catalog = response.data;
            this.getParent();
        }).catch(error=>{
            if(error.response.status == 401){
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
      getParent(){
          this.axios.get('/api/get-parent-catalog',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
              this.parent = response.data;
          });
      },
    UpdateCatalog() {
        this.errors = [];
        this.axios.put('/api/update-catalog/'+this.$route.params.code,this.catalog,{headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/catalog-refs'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      },
  },
};
</script>