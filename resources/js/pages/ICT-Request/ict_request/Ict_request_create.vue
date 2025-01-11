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
                  <label class="col-fixed w-9rem">Request type</label>
                  <div class="col-fixed w-10rem">
                      <Dropdown 
                          v-model="request.request_type"
                          :options="request_type"
                          optionLabel="name"
                          @change="getIreq(request.request_type)"
                          optionValue="code"
                          placeholder="Select One"
                          :class="{ 'p-invalid': error.request_type }"
                          @click="handleAddRow"
                      />
                          <small v-if="error.request_type" class="p-error">
                            {{error.request_type}}
                          </small>
                  </div>
                </div>
    
                <!-- <div class="field grid" v-if="this.requestfor">
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
                </div> -->
                <div class="field grid" v-if="this.requestfor">
                  <label class="col-fixed w-9rem">Requestor</label>
                  <div class="col-fixed w-10rem">
                    <InputText
                      type="text"
                      v-model="request.requestor_name"
                      disabled
                    />
                  </div>
                </div>
                <div class="field grid" v-if="this.requestfor" >
                  <label class="col-fixed w-9rem">User</label>
                  <div class="col-fixed w-10rem">
                    <Dropdown 
                          :disabled="isDisabledUser"
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
                        disabled
                      />
                    </div>
                </div>
                <div class="field grid" v-if="this.requestfor">
                  <label class="col-fixed w-9rem">User Division</label>
                  <div class="col-fixed w-10rem">
                      <InputText
                        type="text"
                        v-model="request.usr_div_id"
                        disabled
                      />
                  </div>
                </div>
                <div class="field grid" v-if="this.requestfor">
                  <label class="col-fixed w-9rem">User Department</label>
                  <div class="col-fixed w-12rem">
                      <InputText
                        type="text"
                        v-model="request.usr_department"
                        disabled
                        class="auto-resize-input"
                      />
                  </div>
                </div>
                <div class="form-group">
                  <Button
                    v-if="this.loading == false"
                    class="p-button-rounded p-button-primary mr-2"
                    icon="pi pi-check"
                    label="Submit Request"
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
                
                <!-- Catalog Column (TreeSelect) -->
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

                <!-- Qty Column (Number Input) -->
                <Column field="qty" header="Qty" v-if="this.cekTipeReq == 'P'">
                  <template #body="slotProps">
                    <InputNumber
                      v-model="slotProps.data.qty"
                      :min="0"
                      placeholder="Enter Quantity"
                    />
                  </template>
                </Column>

                <!-- Remark Column (Text Input) -->
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

                <!-- Attachment Column (File Input) -->
                <Column field="attachment" header="Attachment">
                  <template #body="slotProps">
                    <input 
                        type="file"
                        accept="image/jpg,image/png,image/jpeg,application/pdf"
                        @change="onFileChange($event, slotProps.data.id)"
                      />
                  </template>
                </Column>

                <!-- Delete Row Column -->
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
      catalogOptions:[],
      requestor:{
        'usr_domain' : null,
        'requestor_name' : null,
        "usr_nm_perush" : null,
        "usr_division" : null
      },
      cekTipeReq:'',
      request_type:[
        { name: "Emergency", code: "E" },
        { name: "Service", code: "S" },
        { name: "Peripheral", code: "P" },
      ],
      request:{
        tgl: new Date(),
        priolev:null,
        usr_department:'',
        usr_domain:'',
        usr_bu_id:'',
        usr_div_id:'',
        request_remark:'',
        request_qty:'',
        request_catalog:'',
        request_type:''
      },
      detailRequest:[],
      type: [],
      rowAdded: false,
    };
  },
  mounted(){
      this.getData();
  },
  methods: {
    handleAddRow() {
      if (!this.rowAdded) {
        this.addRow();
        this.rowAdded = true; // Prevent further additions
      }
    },
    deleteRow(index) {
      this.detailRequest.splice(index, 1); // Remove the row at the specified index
    },
    onFileChange(event, id) {
      const file = event.target.files[0];
      const rowIndex = this.detailRequest.findIndex(row => row.id === id); // Find row by ID

      if (rowIndex !== -1) {
        if (file) {
          this.detailRequest[rowIndex].attachment = file; // Assign the file
          console.log(`File assigned to row ${rowIndex}:`, file);
        }
      } else {
        console.error("Row with ID not found:", id);
      }
      console.log(this.detailRequest)
    },
    addRow() {
      this.detailRequest.push({ id: this.detailRequest.length, catalog : null, qty : 0, remark : '', attachment : null});
    },
    getIreq(tipereq){
      this.requestcatalog = '';
      this.cekTipeReq = tipereq;
      if(tipereq != null){
        this.axios.get('/api/get-catalog-request/'+tipereq).then((res)=>{
          this.catalogOptions = res.data;
        });
      }
      if(this.cekTipeReq == 'S'){
        this.qty = null;
        this.kode = '';
      }
      this.errors = [];
      this.error = [];
    },
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
        //this.spvList =  response.data.listSupervisor;
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
      if ( this.request.usr_domain != null )
      {
        const formData = new FormData();

        // Append the main request data
        formData.append("request", JSON.stringify(this.request));

        // Loop through detailRequest and append each item
        this.detailRequest.forEach((item, index) => {
          // Append detailRequest item data as JSON
          formData.append(`detailRequest[${index}]`, JSON.stringify({
            catalog: item.catalog,
            qty: item.qty,
            ket: item.ket,
            remark: item.remark
          }));

          // Append file if present
          if (item.attachment) {
            formData.append(`detailRequest[${index}][attachment]`, item.attachment);
          }
        });

        this.axios.post('api/save-request', formData, {headers: {'content-type': 'multipart/form-data'}}).then((response)=>{
          this.$toast.add({
            severity: "success",
            summary: "Success Message",
            detail: "Success Create",
          });
          setTimeout( () => this.$router.push({name: 'Add Ict Request Detail', params: { code: response.data.data.ireq_id }, }),1000);
        }).catch(error=>{
          this.loading = false;
          this.errors = error.response.data.errors;
         });
      }else{
        this.loading = false;
        if(this.request.usr_domain == null){
          this.error.usr_domain = "User not filled"
        }
      }
    },
  },
};
</script>