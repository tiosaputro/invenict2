<template>  
  <div>  
    <Toast />  
    <div class="card">  
      <Toolbar class="mb-4">  
        <template v-slot:start>  
          <h4 class="text-center toolbar-heading">ICT Request - Add Request</h4>  
        </template>  
      </Toolbar>  
      <div class="row">  
        <div class="col-sm-5">  
          <form @submit.prevent="createIct">  
            <div class="flex flex-wrap mb-4">  
              <div class="w-9rem">Request For</div>  
              <div class="flex align-items-center col-fixed w-9rem">  
                <RadioButton v-model="requestFor" inputId="requestfor" name="1" value="1" />  
                <label for="requestfor" class="ml-2">Personal</label>  
              </div>  
              <div class="flex align-items-center">  
                <RadioButton v-model="requestFor" inputId="requestfor" name="2" value="2" />  
                <label for="requestfor" class="ml-2">Others</label>  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor">
              <label class="col-fixed w-9rem"> Priority Level </label>
                <div class="col-fixed w-10rem">
                  <Dropdown 
                    v-model="request.priolev"
                    :options="level"
                    optionLabel="name"
                    optionValue="code"
                    @change="changePrio"
                    placeholder="Select One"
                  />
                  <small v-if="error.priolev" class="p-error">
                      {{ error.priolev }}
                  </small>
                </div>
            </div> 
            <div class="field grid" v-if="prioUrgent">
              <label class="col-fixed w-9rem"> Reason </label>
                <div class="col-fixed w-10rem">
                  <Textarea  
                    :autoResize="true"  
                    type="text"  
                    v-model="request.ireq_prio_level_reason"  
                    placeholder="Enter Remark"  
                    :class="{ 'p-invalid': error.ireq_prio_level_reason }" 
                    :resize="true"
                  />  
                  <small v-if="error.ireq_prio_level_reason" class="p-error">
                      {{ error.ireq_prio_level_reason }}
                  </small>
                </div>
            </div> 
            <div class="field grid" v-if="requestFor">  
              <label class="col-fixed w-9rem">Request type</label>  
              <div class="col-fixed w-10rem">  
                <Dropdown   
                  v-model="request.request_type"  
                  :options="requestTypeOptions"  
                  optionLabel="name"  
                  @change="getIreq"  
                  optionValue="code"  
                  placeholder="Select One"  
                  @click="handleAddRow"  
                />  
                <small v-if="error.request_type" class="p-error">  
                  {{ error.request_type }}  
                </small>  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor">  
              <label class="col-fixed w-9rem">Requestor</label>                                                                                                                                                                                            
              <div :class="computedColClass('requestor_name')">  
                <InputText  
                  type="text"  
                  v-model="request.requestor_name"  
                  style="width: 100%;" 
                  disabled  
                />  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor">  
              <label class="col-fixed w-9rem">User</label>  
              <div class="col-fixed w-10rem">  
                <Dropdown   
                  :disabled="isDisabledUser"  
                  v-model="request.usr_domain"  
                  :options="userList"  
                  optionLabel="usr_fullname"  
                  optionValue="usr_domain"  
                  @change="getDataBuDivision"   
                  placeholder="Select One"  
                  :filter="true"  
                  :showClear="isShowClear"  
                />  
                <small v-if="error.usr_domain" class="p-error">  
                  {{ error.usr_domain }}  
                </small>  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor">  
              <label class="col-fixed w-9rem">User Business Unit</label>  
              <div class="col-fixed w-10rem">  
                <InputText  
                  type="text"  
                  v-model="request.usr_bu_id"  
                  disabled  
                />  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor">  
              <label class="col-fixed w-9rem">User Division</label>  
              <div class="col-fixed w-10rem">  
                <InputText  
                  type="text"  
                  v-model="request.usr_div_id"  
                  disabled  
                />  
              </div>  
            </div>  
            <div class="field grid" v-if="requestFor" style="display: flex; align-items: center;">    
              <label class="col-fixed w-9rem">User Department</label>    
              <div :class="computedColClass('usr_department')">    
                <InputText    
                  type="text"    
                  v-model="request.usr_department"    
                  disabled      
                  style="width: 100%;"    
                />    
              </div>    
            </div>  
            <div class="form-group">  
              <Button  
                v-if="!loading"  
                class="p-button-rounded p-button-primary mr-2"  
                icon="pi pi-check"  
                label="Submit Request"  
                type="submit"  
              />  
              <Button  
                v-if="!loading"  
                label="Cancel"  
                class="p-button-rounded p-button-secondary mt-2"  
                icon="pi pi-times"  
                @click="$router.push('/ict-request')"  
              />  
              <ProgressSpinner v-else style="width:50px;height:50px" strokeWidth="8" animationDuration=".5s" />  
            </div>  
          </form>  
        </div>  
        <div class="col-sm-7" v-if="request.request_type">  
          <DataTable   
            :value="detailRequest"   
            editMode="row"  
            responsiveLayout="scroll"  
          >  
            <template #header>  
              <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">  
                <span class="text-xl font-bold">Detail Request</span>  
                <span class="block mt-2 md:mt-0 p-input-icon-left">  
                  <Button  
                    label="Add Row"  
                    icon="pi pi-plus"  
                    class="mt-3 mr-2"  
                    @click="addRow"  
                  />  
                </span>  
              </div>  
            </template>  
            <Column field="catalog" header="Catalog">  
              <template #body="slotProps">  
                <TreeSelect  
                  v-model="slotProps.data.catalog"  
                  :options="catalogOptions"  
                  selectionMode="single"  
                  :filter="true"  
                  placeholder="Select Catalog"  
                />  
              </template>  
            </Column>  
            <Column field="qty" header="Qty" v-if="cekTipeReq === 'P'">  
              <template #body="slotProps">  
                <InputNumber  
                  v-model="slotProps.data.qty"  
                  :min="0"  
                  placeholder="Enter Quantity"  
                />  
              </template>  
            </Column>  
            <Column field="remark" header="Remark">  
              <template #body="slotProps">  
                <Textarea  
                  :autoResize="true"  
                  type="text"  
                  v-model="slotProps.data.remark"  
                  placeholder="Enter Remark"  
                  :class="{ 'p-invalid': errors.ket }"  
                  class="inputfield"  
                />  
              </template>  
            </Column>  
            <Column field="attachment" header="Attachment">  
              <template #body="slotProps">  
                <input   
                  type="file"  
                  ref="fileInput"   
                  class="form-control"  
                  accept="image/jpg,image/png,image/jpeg,application/pdf"  
                  @change="onFileChange($event, slotProps.data.id)"  
                />  
              </template>  
            </Column>  
            <Column header="">  
              <template #body="slotProps">  
                <Button   
                  icon="pi pi-trash"   
                  class="p-button-danger"   
                  @click="deleteRow(slotProps.rowIndex)"   
                />  
              </template>  
            </Column>  
          </DataTable>  
        </div>  
      </div>  
    </div>  
  </div>  
</template>  
  
<script>  
export default {  
  data() {  
    return {  
      requestFor: '',  
      isDisabledUser: false, 
      prioUrgent: false, 
      isShowClear: true,  
      loading: false,  
      errors: [],  
      error: [],  
      userList: [],  
      catalogOptions: [],  
      requestor: {  
        user_domain: null,  
        requestor_name: null,  
        user_nm_perush: null,  
        user_division: null,
        user_department: null  
      },  
      cekTipeReq: '',  
      requestTypeOptions: [  
        { name: "Emergency", code: "E" },  
        { name: "Service", code: "S" },  
        { name: "Peripheral", code: "P" },  
      ],  
      request: {  
        tgl: new Date(),  
        priolev: null,  
        usr_department: '',  
        usr_domain: '',  
        usr_bu_id: '',  
        usr_div_id: '',  
        request_remark: '',  
        request_qty: '',  
        request_catalog: '',  
        request_type: ''  
      },  
      detailRequest: [],  
      level: [],
      rowAdded: false,  
    };  
  },  
  computed: {  
    computedColClass() {  
      return (field) => {
        const textLength = this.request[field]?.length || 0;
        if (textLength < 10) return 'col-fixed w-10rem';  
        if (textLength < 20) return 'col-fixed w-13rem'; 
        if (textLength > 30) return 'col-fixed w-16rem';   
        return 'col-fixed w-18rem';
      };
    }  
  },
  watch: {
  requestFor(newVal) {
    if (newVal === '1') {
      if (this.requestor && this.requestor.user_domain) {
        this.setRequestorData(true);
      } else {
        // tunggu data requestor siap
        this.$nextTick(() => {
          this.setRequestorData(true);
        });
      }
    } else if (newVal === '2') {
      this.setRequestorData(false);
    }
  }
},
  mounted() {  
    this.getData();  
  },  
  methods: {  
    changePrio(){
      if(this.request.priolev === 'U'){
        this.prioUrgent = true;
      }else{
        this.prioUrgent = false;
      }
    },
    handleAddRow() {  
      if (!this.rowAdded) {  
        this.addRow();  
        this.rowAdded = true;  
      }  
    },  
    deleteRow(index) {  
      this.detailRequest.splice(index, 1);   
    },  
    onFileChange(event, id) {  
      const file = event.target.files[0];  
      const rowIndex = this.detailRequest.findIndex(row => row.id === id);  
  
      if (rowIndex !== -1 && file) {  
        this.detailRequest[rowIndex].attachment = file;  
      }  
    },  
    addRow() {  
      this.detailRequest.push({ id: this.detailRequest.length, catalog: null, qty: 0, remark: '', attachment: null });  
    },  
    getIreq() {  
      this.requestCatalog = '';  
      this.cekTipeReq = this.request.request_type;  
      if (this.cekTipeReq) {  
        this.axios.get('/api/get-catalog-request/' + this.cekTipeReq).then((res) => {  
          this.catalogOptions = res.data;  
        });  
      }  
      if (this.cekTipeReq === 'S') {  
        this.request.request_qty = null;  
        this.request.request_catalog = '';  
      }  
      this.errors = [];  
      this.error = [];  
    },  
    changeUser() {  
      if (this.requestFor === '1') {  
        this.setRequestorData(true);  
      } else if (this.requestFor === '2') {  
        this.setRequestorData(false);  
      }  
    },  
    setRequestorData(isPersonal) {  
      if (isPersonal) {  
        const requestorCopy = JSON.parse(JSON.stringify(this.requestor)); 
        console.log(requestorCopy);
        this.request.usr_domain = null;
        this.request.usr_domain = requestorCopy.user_domain;
        this.request.usr_department = requestorCopy.user_department;  
        this.request.usr_bu_id = requestorCopy.user_nm_perush;  
        this.request.usr_div_id = requestorCopy.user_division;  
        this.isDisabledUser = true;  
        this.isShowClear = false;  
      } else {  
        this.request.usr_domain = '';  
        this.request.usr_department = '';  
        this.request.usr_bu_id = '';  
        this.request.usr_div_id = '';  
        this.isDisabledUser = false;  
        this.isShowClear = true;  
      }  
    },  
    getDataBuDivision() {  
      const code = this.request.usr_domain;  
      if (code) {  
        this.request.usr_div_id = this.getDataById(code, 'usr_division');  
        this.request.usr_bu_id = this.getDataById(code, 'usr_bu');  
        this.request.usr_department = this.getDataById(code, 'usr_department');  
      }  
    },  
    getDataById(usr_domain, propertyToGet) {  
      const result = this.userList.find(item => item.usr_domain === usr_domain);  
      return result ? result[propertyToGet] : '';  
    },  
    getData() {  
      this.axios.get('api/add-request').then((response) => {  
        const { ref, requestor, priority, userList } = response.data;  
        this.type = ref;  
        this.request = requestor;  
        this.requestor.user_department = requestor.usr_department;  
        this.requestor.user_domain = requestor.usr_domain;
        this.requestor.user_nm_perush = requestor.usr_nm_perush;
        this.requestor.user_division = requestor.usr_division;
        this.request.requestor_name = requestor.usr_fullname;  
        this.level = priority;  
        this.userList = userList;  
      }).catch(error => {  
        if (error.response.status === 401) {  
          this.$router.push('/login');  
        }  
      });  
    },  
    createIct() {  
      this.errors = [];  
      this.error = [];  
      this.loading = true;  
      if (this.request.usr_domain) {  
        const formData = new FormData();  
        formData.append("request", JSON.stringify(this.request));  
        this.detailRequest.forEach((item, index) => {  
          formData.append(`detailRequest[${index}]`, JSON.stringify({  
            catalog: item.catalog,  
            qty: item.qty,  
            ket: item.ket,  
            remark: item.remark  
          }));  
          if (item.attachment) {  
            formData.append('detailRequest[${index}][attachment]', item.attachment);  
          }  
        });  
  
        this.axios.post('api/save-request', formData, { headers: { 'content-type': 'multipart/form-data' } }).then(() => {  
          this.$toast.add({  
            severity: "success",  
            summary: "Success Message",  
            detail: "Success Create",  
          });  
          setTimeout(() => this.$router.push('/ict-request'), 1000);  
        }).catch(error => {  
          this.loading = false;  
          this.errors = error.response.data.errors;  
        });  
      } else {  
        this.loading = false;  
        if (!this.request.usr_domain) {  
          this.error.usr_domain = "User not filled";  
        }  
      }  
    },  
  },  
};  
</script>  
