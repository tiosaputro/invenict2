<template>
  <div>
    <Toast />
      <div class="card">
        <Toolbar class="mb-4">
          <template v-slot:start>
				    <h4>ICT Request - Add Request</h4>
          </template>
        </Toolbar>
          <div class="card-body">
            <form @submit.prevent="CreateIct">
              <div class="flex flex-wrap gap-3" style="margin-bottom:1em">
                <div class="w-9rem">Request For</div>
                <div class="flex align-items-center">
                    <RadioButton v-model="requestfor" @change="changeUser()" inputId="requestfor" name="1" value="1" />
                    <label for="requestfor" class="ml-2">Personnal</label>
                </div>
                <div class="flex align-items-center">
                    <RadioButton v-model="requestfor" @change="changeUser()" inputId="requestfor" name="2" value="2" />
                    <label for="requestfor" class="ml-2">Others</label>
                </div>
              </div>
              <div class="field grid" v-if="this.requestfor">
                <label class="col-fixed w-9rem">Priority Level</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="request.priolev"
                        :options="level"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        :class="{ 'p-invalid': error.priolev }"
                     />
                        <small v-if="error.priolev" class="p-error">
                          {{error.priolev}}
                        </small>
                </div>
              </div>
              <div class="field grid" v-if="this.requestfor">
                <label class="col-fixed w-9rem">Requestor</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="request.requestor_name"
                    disabled
                  />
                 </div>
              </div>
              <div class="field grid" v-if="this.requestfor" >
                <label class="col-fixed w-9rem">User</label>
                 <div class="col-fixed w-9rem">
                  <Dropdown 
                        :disabled="isDisabledUser"
                        v-model ="request.usr_name"
                        :options="userList"
                        optionLabel="usr_fullname"
                        optionValue="usr_id"
                        @change="getDataBuDvision(request.usr_name)"
                        :class="{ 'p-invalid': error.usr_name }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.usr_name" class="p-error">
                          {{error.usr_name}}
                        </small>
                  <small v-if="error.usr_name" class="p-error">
                    {{error.usr_name}}
                  </small>
                 </div>
              </div>
              <div class="field grid" v-if="this.request.usr_name">
                <label class="col-fixed w-9rem">User Business Unit</label>
                 <div class="col-fixed w-10rem">
                     <!-- <Dropdown 
                        v-model ="request.usr_requestor_bu_id"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        :class="{ 'p-invalid': error.usr_requestor_bu_id }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.usr_requestor_bu_id" class="p-error">
                          {{error.usr_requestor_bu_id}}
                        </small> -->
                        <InputText
                          type="text"
                          v-model="request.usr_bu_id"
                          disabled
                        />
                      </div>
              </div>
              <div class="field grid" v-if="this.request.usr_name">
                <label class="col-fixed w-9rem">User Division</label>
                 <div class="col-fixed w-10rem">
                    <InputText
                      type="text"
                      v-model="request.usr_div_id"
                      disabled
                    />
                </div>
              </div>
              <div class="field grid" v-if="this.request.usr_name">
                <label class="col-fixed w-9rem">User Department</label>
                 <div class="col-fixed w-10rem">
                    <InputText
                      type="text"
                      v-model="request.usr_department"
                      disabled
                    />
                </div>
              </div>
              <div class="field grid" v-if="this.request.usr_name">
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
                <ProgressSpinner style="width:50px;height:50px" strokeWidth="8" fill="var(--surface-ground)" animationDuration=".5s" v-else/>
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
      requestfor:'',
      isDisabledUser:false,
      loading:false,
      errors: [],
      level:[],
      error:[],
      userList:[],
      spvList:[],
      request:{
        tgl: new Date(),
        priolev:null,
        usr_name:null,
        ireq_spv:'',
        usr_department:'',
      },
      type: [],
      mask:{
        input: 'DD MMM YYYY'
      },
      code: null,
    };
  },
  mounted(){
      this.getType();
  },
  methods: {
    changeUser(){
        this.axios.get('api/user-list').then((response)=>{
          if(this.requestfor == 1){
              this.request.requestor_name = response.data.requestor.usr_fullname; 
              this.request.usr_name = response.data.requestor.usr_id;
              this.userList = response.data.userlist;
              this.isDisabledUser = true;
              this.getDataBuDvision(response.data.requestor.usr_id);
            }else{
              this.isDisabledUser = false;
              this.userList = response.data.userlist;
              this.request.usr_name = null;
              this.request.requestor_name = response.data.requestor.usr_fullname; 
          }
        });
    },
    getDataBuDvision(code){
      if(code){
        this.axios.get('api/data-divbu/' + code).then((response)=>{
          this.request.usr_div_id = response.data.division;
          this.request.usr_bu_id = response.data.bu;
          this.request.usr_department = response.data.department;
        });
      }
    },
    getType(){
      this.axios.get('api/getAddReq').then((response)=>{
        this.type = response.data.ref;
        this.bu = response.data.bisnis;
        this.level = response.data.priority;
        this.userList = response.data.userlist;
        this.spvList =  response.data.listSupervisor;
      }).catch(error=>{
        if(error.response.status == '401'){
          return this.$router.push('/login')
        }
      });
    },
    CreateIct() {
      this.errors = [];
      this.error = [];
      this.loading = true;
      if (
        this.request.priolev != null &&
        this.request.usr_name != null 
      )
      {

        this.axios.post('api/add-ict', this.request).then((response)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        this.code = response.data.data.ireq_id;
        setTimeout( () => this.$router.push({name: 'Add Ict Request Detail', params: { code: this.code }, }),1000);
        }).catch(error=>{
          this.loading = false;
          this.errors = error.response.data.errors;
         });
      }else{
        this.loading = false;
        if(this.request.priolev == null){
          this.error.priolev = "Priority level not filled"
        }
        if(this.request.usr_name == null){
          this.error.usr_name = "User not filled"
        }
      }
    },
  },
};
</script>