<template>
    <div>
      <Toast />
        <div class="card">
          <Toolbar class="mb-4">
            <template v-slot:start>
                <h4>Add Supervisor</h4>
            </template>
          </Toolbar>
            <div class="row">
              <div class="col-sm-6">
               <form @submit.prevent="CreateSpv">
                <div class="card-body">
                 <div class="field grid">
                  <label style="width:120px">Supervisor Job Title </label>
                   <div class="col-10">
                      <InputText
                        v-model="supervisor.spv_job_title"
                        placeholder="Input Job Title"
                        :class="{ 'p-invalid': errors.spv_job_title }"
                        required
                      />
                      <small v-if="errors.spv_job_title" class="p-error">
                          {{ errors.spv_job_title[0] }}
                      </small>
                  </div>
                </div>
                <div class="field grid">
                  <label style="width:120px">Supervisor Name</label>
                   <div class="col-6">
                      <Dropdown
                        v-model="supervisor.spv_name"
                        :options="listUser"
                        :showClear="true"
                        optionLabel="usr_fullname"
                        optionValue="usr_id"
                        placeholder="Choose One"
                        :class="{ 'p-invalid': errors.spv_name }"
                      />
                        <small v-if="errors.spv_name" class="p-error">
                          {{ errors.spv_name[0] }}
                      </small>
                    </div>
                   </div>
                </div>
                <div class="form-group">
                   <Button
                    class="p-button-rounded p-button-primary mr-2"
                    icon="pi pi-check"
                    label="Save"
                    type="submit"
                  />
                  <Button
                    label="Cancel"
                    class="p-button-rounded p-button-secondary mr-2"
                    icon="pi pi-times"
                    @click="$router.push('/supervisor-refs')"
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
        errors:[],
        listUser:[],
        supervisor : {
          spv_name:'',
          spv_job_title:'',
        },
      };
    },
    created(){
      this.getData();
    },
    methods: {
      getData(){
        this.axios.get('api/add-data-supervisor').then((response)=>{
          this.listUser = response.data.user
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
      CreateSpv() {
          this.errors = [];
          this.error=[];
          if(this.supervisor.spv_name != '' && this.supervisor.spv_name != null){
            this.axios.post('api/save-supervisor',this.supervisor).then(()=>{
              this.$toast.add({
                severity: "success",
                summary: "Success Message",
                detail: "Success Create",
              });
              setTimeout( () => this.$router.push('/supervisor-refs'),1000);
          }).catch(error=>{
            this.errors = error.response.data.errors;
           });
          }else{
              this.error.spv_name = 'Belum diisi'
          }
        },
    },
  };
  </script>