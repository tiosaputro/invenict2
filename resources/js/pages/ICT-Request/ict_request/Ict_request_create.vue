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
              <div class="flex flex-wrap" style="margin-bottom:1em">
                <div class="w-9rem">Request For</div>
                <div class="flex align-items-center col-fixed w-9rem">
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
                 <div class="col-fixed w-10rem">
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
                 <div class="col-fixed w-10rem">
                  <InputText
                    type="text"
                    v-model="request.requestor_name"
                    readonly
                  />
                 </div>
              </div>
              <div class="field grid" v-if="this.requestfor" >
                <label class="col-fixed w-9rem">User</label>
                 <div class="col-fixed w-10rem">
                  <Dropdown 
                        :readonly="isDisabledUser"
                        v-model ="request.usr_domain"
                        :options="userList"
                        optionLabel="usr_fullname"
                        optionValue="usr_domain"
                        @change="getDataBuDvision(request.usr_domain)"
                        :class="{ 'p-invalid': error.usr_domain }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="isShowClear"
                     />
                        <small v-if="error.usr_domain" class="p-error">
                          {{error.usr_domain}}
                        </small>
                        <small v-if="error.usr_domain" class="p-error">
                          {{error.usr_domain}}
                        </small>
                 </div>
              </div>
              <div class="field grid" v-if="this.requestfor">
                <label class="col-fixed w-9rem">User Business Unit</label>
                 <div class="col-fixed w-10rem">
                        <InputText
                          type="text"
                          v-model="request.usr_bu_id"
                          readonly
                        />
                      </div>
              </div>
              <div class="field grid" v-if="this.requestfor">
                <label class="col-fixed w-9rem">User Division</label>
                 <div class="col-fixed w-10rem">
                    <InputText
                      type="text"
                      v-model="request.usr_div_id"
                      readonly
                    />
                </div>
              </div>
              <div class="field grid" v-if="this.requestfor">
                <label class="col-fixed w-9rem">User Department</label>
                 <div class="col-fixed w-10rem">
                    <InputText
                      type="text"
                      v-model="request.usr_department"
                      readonly
                    />
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
      requestorDomain:'',
      requestorDepartment:'',
      requestfor:'',
      isDisabledUser:false,
      isShowClear:true,
      loading:false,
      errors: [],
      level:[],
      error:[],
      userList:[],
      requestor:{
        'usr_domain' : null,
        'requestor_name' : null,
        "usr_nm_perush" : null,
        "usr_division" : null
      },
      request:{
        tgl: new Date(),
        priolev:null,
        usr_department:'',
        usr_domain:'',
        usr_bu_id:'',
        usr_div_id:'',
      },
      type: [],
      code: null,
    };
  },
  mounted(){
      this.getData();
  },
  methods: {
    changeUser(){
          if(this.requestfor == 1){
              this.request.usr_domain = this.requestorDomain;
              this.request.usr_department = this.requestorDepartment;
              this.request.usr_bu_id = this.requestor.usr_nm_perush;
              this.request.usr_div_id = this.requestor.usr_division;
              this.isDisabledUser = true;
              this.isShowClear = false;
            } else if(this.requestfor == 2) {
              this.request.usr_domain = '';
              this.request.usr_department = '';
              this.request.usr_bu_id = '';
              this.request.usr_div_id = '';
              this.isDisabledUser = false;
              this.isShowClear = true;
          }
    },
    getDataBuDvision(code){
      if(code){
          this.request.usr_div_id = this.getDataById(code, 'usr_division');
          this.request.usr_bu_id = this.getDataById(code, 'usr_bu');
          this.request.usr_department = this.getDataById(code, 'usr_department');
      }
    },
    getDataById(usr_domain, propertyToGet) {
        const result = this.userList.filter(item => item.usr_domain === usr_domain);
        return result.map(item => item[propertyToGet]).join(', ');
    },
    getData(){
      this.axios.get('api/add-request').then((response)=>{
        this.type = response.data.ref;
        this.request = response.data.requestor;
        this.requestor = response.data.requestor;
        this.request.requestor_name = this.requestor.usr_fullname;
        this.requestorDomain = this.requestor.usr_domain;
        this.requestorDepartment = this.requestor.usr_department;
        this.level = response.data.priority;
        this.userList = response.data.userList;
        // this.spvList =  response.data.listSupervisor;
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
        this.request.usr_domain != null 
      )
      {

        this.axios.post('api/save-request', this.request).then((response)=>{
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
        if(this.request.usr_domain == null){
          this.error.usr_domain = "User not filled"
        }
      }
    },
  },
};
</script>