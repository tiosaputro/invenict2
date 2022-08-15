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
                    v-model="mutasi.ireq_no"
                    disabled
                  />
                </div>
              </div>
              <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tgl. Request</label>
                 <div class="col-fixed w-11rem">
                      <DatePicker v-model="mutasi.ireq_date" :masks="mask" >
                        <template v-slot="{ inputValue, togglePopover }">
                          <div class="flex items-center">
                            <input
                              class="bg-white text-gray-900 w-full py-2 px-3 appearance-none border rounded-l focus:outline-none"
                              :value="inputValue"
                              @click="togglePopover"
                              placeholder="Pilih Tgl. Request"
                              readonly
                            />
                          <Button icon="pi pi-calendar" v-if="!mutasi.ireq_date" @click="togglePopover"/>
                          <Button icon="pi pi-trash" class="p-button-danger" v-else @click="mutasi.ireq_date = ''" />
                        </div>
                        </template>
                      </DatePicker>
                      <small v-if="error.ireq_date" class="p-error">
                        {{ error.ireq_date }}
                      </small>
                </div>
              </div>    -->
              <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Tipe Request</label>
                 <div class="field col-12 md:col-4">
                     <Dropdown 
                        v-model ="mutasi.ireq_type"
                        :options="type"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Pilih Tipe Request"
                        :showClear="true"
                     />
                </div>
              </div> -->
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Priority Level</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model="mutasi.ireq_prio_level"
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
                    v-model="requestor"
                    disabled
                  />
                 </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User</label>
                 <div class="col-fixed w-9rem">
                     <InputText
                        type="text"
                        v-model="mutasi.ireq_user"
                        placeholder="Enter User"
                        :class="{ 'p-invalid': errors.ireq_user }"
                     />
                        <small v-if="error.ireq_user" class="p-error">
                          {{ error.ireq_user }}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">User Division</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="mutasi.ireq_divisi_user"
                        :options="divisi"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        :filter="true"
                        :showClear="true"
                        :class="{ 'p-invalid': errors.ireq_divisi_user }"
                     />
                        <small v-if="error.ireq_divisi_user" class="p-error">
                          {{error.ireq_divisi_user}}
                        </small>
                </div>
              </div>
              <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Business Unit</label>
                 <div class="col-fixed w-9rem">
                     <Dropdown 
                        v-model ="mutasi.ireq_bu"
                        :options="bu"
                        optionLabel="name"
                        optionValue="code"
                        placeholder="Select One"
                        :showClear="true"
                        :filter="true"
                        :class="{ 'p-invalid': errors.ireq_bu }"
                     />
                        <small v-if="errors.ireq_bu" class="p-error">
                          {{ errors.ireq_bu[0] }}
                        </small>
                        <small v-if="error.ireq_bu" class="p-error">
                          {{ error.ireq_bu }}
                        </small>
                </div>
              </div>
              <!-- <div class="field grid">
                <label class="col-fixed w-9rem" style="width:120px">Keterangan</label>
                 <div class="field col-12 md:col-4">
                  <Textarea 
                    v-model="mutasi.ireq_remark"
                    :autoResize="true" 
                    rows="5" 
                    cols="20"
                    placeholder="Masukan Keterangan . . ."
                    :class="{ 'p-invalid': error.ireq_remark }"
                  />
                      <small class="p-error" v-if="error.ireq_remark"
                        >{{error.ireq_remark}}
                      </small>
                </div>
              </div> -->
              <div class="form-group">
                 <Button
                  class="p-button-rounded p-button-primary mr-2"
                  icon="pi pi-check"
                  label="Save"
                  type="submit"
                />
                <Button
                  label="Cancel"
                  class="p-button-rounded p-button-secondary mt-2"
                  icon="pi pi-times"
                  @click="$router.push('/ict-request')"
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
      level:[],
      errors: [],
      error:[],
      mutasi:[],
      type: [],
      divisi: [],
      bu: [],
      mask:{
        input: 'DD MMM YYYY'
      },
      token: localStorage.getItem('token'),
      checkname : [],
      checkto : [],
      id : localStorage.getItem('id'),
      requestor:localStorage.getItem('usr_name'),
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
        if(this.checkname.includes("Request") || this.checkto.includes("/ict-request")){ 
          this.getIct();
        }
        else {
          this.$router.push('/access');
        }
      });
      } else {
        this.$router.push('/login');
      }
    },
      getIct(){
          this.axios.get('/api/edit-ict/' + this.$route.params.code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=> {
            this.mutasi = response.data.ict;
            this.divisi = response.data.divisi;
            this.type = response.data.ref;
            this.bu = response.data.bisnis;
            this.level = response.data.prio;
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
      this.errors = [] ;
      this.error = [];
      if(
        this.mutasi.ireq_date != '' &&
        this.mutasi.ireq_prio_level != null &&
        // this.mutasi.ireq_remark != null  && 
        this.mutasi.ireq_divisi_user !=null &&
        this.mutasi.ireq_bu != null &&
        this.mutasi.ireq_user != null
      ){
        this.axios.put('/api/update-ict/'+ this.$route.params.code, this.mutasi, {headers: {'Authorization': 'Bearer '+this.token}}).then(()=>{
        this.$toast.add({
          severity: "success",
          summary: "Success Message",
          detail: "Success Update",
        });
        setTimeout( () => this.$router.push('/ict-request'),1000);
        }).catch(error=>{
          this.errors = error.response.data.errors;
         });
      }
      else{
        if(this.mutasi.ireq_prio_level == null){
          this.error.ireq_prio_level = "Priority level not filled"
      }
        if(this.mutasi.ireq_divisi_user == null){
          this.error.ireq_divisi_user = "User division not filled"
        }
        if(this.mutasi.ireq_bu == null){
          this.error.ireq_bu = "Business unit not filled"
        }
        if(this.mutasi.ireq_user == null){
          this.error.ireq_user = "User not filled"
        }
      }
      },
  },
};
</script>