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
               <!-- <div class="field grid">
                <label class="col-fixed w-9rem">No. Request</label>
               </div> -->
              <div class="field grid">
                <label class="col-fixed w-9rem">Priority Level</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="priolev"
                        :options="level"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        :showClear="true"
                        :filter="true"
                        :class="{ 'p-invalid': error.priolev }"
                     />
                        <small v-if="error.priolev" class="p-error">
                          {{error.priolev}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">Requestor</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="requestor"
                    disabled
                  />
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">User</label>
                 <div class="col-fixed w-9rem">
                  <InputText
                    type="text"
                    v-model="usr_name"
                    placeholder="Enter User"
                    :class="{ 'p-invalid': error.usr_name }"
                  />
                  <small v-if="error.usr_name" class="p-error">
                    {{error.usr_name}}
                  </small>
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">User Business Unit</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="bisnis"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        :class="{ 'p-invalid': error.bisnis }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.bisnis" class="p-error">
                          {{error.bisnis}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem">User Division</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="usr_divisi"
                        :options="divisi"
                        optionLabel="name"
                        optionValue="code"
                        :class="{ 'p-invalid': error.usr_divisi }"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                     />
                        <small v-if="error.usr_divisi" class="p-error">
                          {{error.usr_divisi}}
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
      loading:false,
      errors: [],
      level:[],
      error:[],
      tgl: new Date(),
      tipereq: null,
      priolev:null,
      usr_name:null,
      usr_divisi : null,
      requestor:'',
      ket:null,
      divisi:[],
      bisnis: null,
      type: [],
      bu: [],
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      code: null,
      // user:[],
    };
  },
  watch:{
    
  },
  mounted(){
      this.getType();
  },
  methods: {
    getDivision(){
      this.axios.get('api/get-division-user/'+this.bisnis, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.divisi = response.data;
      });
    },
    getType(){
      this.axios.get('api/getAddReq', {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.type = response.data.ref;
        this.bu = response.data.bisnis;
        this.divisi = response.data.divisi;
        this.level = response.data.prio;
        this.requestor = response.data.username; 
      }).catch(error=>{
        if(response.status == '401'){
          return this.$router.push('/login')
        }
      });
    },
    CreateIct() {
      this.errors = [];
      this.error = [];
      this.loading = true;
      if (
        this.priolev != null &&
        this.usr_name != null &&
        this.usr_divisi != null &&
        this.bisnis != null
      )
      {
        const data = new FormData();
        data.append("tgl", this.tgl);
        data.append("bisnis", this.bisnis);
        data.append("user_name", this.usr_name);
        data.append("user_divisi", this.usr_divisi);
        data.append("priolev", this.priolev);

        this.axios.post('api/add-ict', data,{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Create",
        });
        this.code = response.data.ireq_id;
        setTimeout( () => this.$router.push({name: 'Add Ict Request Detail', params: { code: this.code }, }),1000);
        }).catch(error=>{
          this.loading = false;
          this.errors = error.response.data.errors;
         });
      }else{
        this.loading = false;
        if(this.priolev == null){
          this.error.priolev = "Priority level not filled"
        }
        if(this.bisnis == null){
          this.error.bisnis = "Business unit not filled"
        }
        if(this.usr_name == null){
          this.error.usr_name = "User not filled"
        }
        if(this.usr_divisi == null){
          this.error.usr_divisi = "User division not filled"
        }
        
      }
    },
  },
};
</script>