<template>
  <div>
        <Toast />
        <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>ICT Request</h4>
          </template>
        </Toolbar>
            <div class="card-body">
             <form @submit.prevent="UpdateIct">
               <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">No. Request</label>
                 <div class="col">
                  <InputText
                    type="text"
                    v-model="request.ireq_no"
                    disabled
                  />
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Priority Level</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="request.ireq_prio_level"
                        :options="level"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        :showClear="true"
                        :filter="true"
                        :class="{ 'p-invalid': error.ireq_prio_level }"
                        autofocus
                     />
                        <small v-if="error.ireq_prio_level" class="p-error">
                          {{error.ireq_prio_level}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Requestor</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="request.ireq_requestor"
                    disabled
                  />
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User</label>
                 <div class="col-fixed w-9rem">
                  <Dropdown 
                    v-model ="request.ireq_user"
                    :options="userList"
                    optionLabel="usr_fullname"
                    @change="getDataBuDvision(request.ireq_user)"
                    optionValue="usr_id"
                    :class="{ 'p-invalid': error.ireq_user }"
                    placeholder="Select One"
                    :filter="true"
                    :showClear="true"
                  />
                  <small v-if="error.ireq_user" class="p-error">
                    {{ error.ireq_user }}
                  </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User Business Unit</label>
                 <div class="col-fixed w-9rem">
                     <InputText v-model="request.company_user" disabled/>
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User Departement</label>
                 <div class="col-fixed">
                  <InputText v-model="request.department_user" disabled/>
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User Division</label>
                 <div class="col-fixed">
                  <InputText v-model="request.division_user" disabled/>
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Your Supervisor</label>
                  <div class="col-fixed w-9rem">
                      <Dropdown 
                        v-model ="request.ireq_spv"
                        :options="spvList"
                        optionLabel="spvnamejob"
                        optionValue="spv_id"
                        :class="{ 'p-invalid': error.ireq_spv }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.ireq_spv" class="p-error">
                          {{error.ireq_spv}}
                        </small>
                  </div>
              </div>
              <div class="form-group">
                 <Button
                  v-if="this.loading == false"
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Save"
                  type="submit"
                />
                <Button
                  v-if="this.loading == false"
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/ict-request')"
                />
                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" animationDuration=".5s" v-else/>
                
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
      loading:false,
      level:[],
      errors: [],
      error:[],
      request:[],
      type: [],
      divisi: [],
      userList:[],
      spvList:[],
      checkname : [],
      checkto : [],
      requestor:'',
    };
  },
  computed: {
    inputWidth() {
      // Adjust this multiplier according to your layout and styling preferences
      const multiplier = 8;
      // Calculate the width based on the length of department_user value
      const width = (this.request.department_user.length || 1) * multiplier;
      // Limit the maximum width to prevent excessively wide input boxes
      const maxWidth = 500; // Adjust the maximum width as needed
      console.log(`${Math.min(width, maxWidth)}px`);
      return `${Math.min(width, maxWidth)}px`;
      
    }
  },
  created(){
      this.cekUser();
  },
  methods: {
    getDataBuDvision(code){
        this.axios.get('/api/data-divbu/' + code).then((response)=>{
          this.request.division_user = response.data.division;
          this.request.bu_user = response.data.bu;
          this.request.department_user = response.data.department;
        });
    },
    cekUser(){
      this.axios.get('/api/cek-user').then((response)=>{
        this.checkto = response.data.map((x)=> x.to)
        this.checkname = response.data.map((x)=> x.name)
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
          this.getIct();
        }
        else {
          this.$router.push('/access');
        }
      });
    },
      getIct(){
          this.axios.get('/api/edit-ict/' + this.$route.params.code).then((response)=> {
            this.request = response.data.ict;
            this.divisi = response.data.divisi;
            this.type = response.data.ref;
            this.bu = response.data.bisnis;
            this.level = response.data.priority;
            this.userList = response.data.userlist;
            this.spvList = response.data.listSupervisor;
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
    UpdateIct() {
      this.loading = true;
      this.errors = [] ;
      this.error = [];
      if(
        this.request.ireq_prio_level != null &&
        this.request.ireq_user != null &&
        this.request.ireq_spv != null
      ){
        this.axios.put('/api/update-ict/'+ this.$route.params.code, this.request).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/ict-request'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
          this.loading = false;
         });
      }
      else{
        this.loading = false;
        if(this.request.ireq_prio_level == null){
          this.error.ireq_prio_level = "Priority level not filled"
      }
        if(this.request.ireq_user == null){
          this.error.ireq_divisi_user = "User not filled"
        }
        if(this.request.ireq_spv == null){
          this.error.ireq_bu = "Supervisor not filled"
        }
      }
      },
  },
};
</script>
<style scoped>
.col-fixed {
  width: auto;
}
</style>