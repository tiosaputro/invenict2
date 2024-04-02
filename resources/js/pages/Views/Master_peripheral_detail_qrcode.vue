<template>
  <ConfirmDialog> </ConfirmDialog>
  <Dialog></Dialog>
<Dialog
          v-model:visible="displayKode"
          :breakpoints="{'1000px': '95vw'}"
          :style="{ width: '600px' }"
          :header="this.header"
          :modal="true"
          class="fluid"
          :closable=false
        >
        <!-- <div class="hidden lg:inline-flex row"> -->
          <!-- <div class="col-sm-6"> -->
            <div class="field grid">
              <label class="col-fixed" style="width:100px">Kode</label>
                <!-- <div class="col-2">/ -->
                  <InputText
                    type="text"
                    v-model="detail.invent_code"
                    disabled
                  />
                <!-- </div> -->
              </div>
              <div class="field grid">
                      <label class="col-fixed" style="width:100px">Nama</label>
                        <!-- <div class="col-4"> -->
                          <InputText
                            v-model="detail.invent_desc"
                            disabled
                          />
                        <!-- </div> -->
                    </div> 
              <div class="field grid">
                <label class="col-fixed" style="width:100px">Merk</label>
                  <!-- <div class="col-4"> -->
                    <InputText
                      v-model="detail.invent_brand"
                      disabled
                    />
                  <!-- </div> -->
                </div>
                <div class="field grid">
                  <label class="col-fixed" style="width:100px">Tipe</label>
                    <!-- <div class="col-4"> -->
                      <InputText
                        v-model="detail.invent_type"
                        disabled
                      />
                    <!-- </div> -->
                  </div>
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">S/N</label>
                      <!-- <div class="col-4"> -->
                        <InputText
                          v-model="detail.invent_sn"
                          disabled
                        />
                    <!-- </div> -->
                  </div>
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Tgl. Perolehan</label>
                      <!-- <div class="col-4"> -->
                        <InputText
                          v-model="detail.invent_tgl_perolehan"
                          disabled
                        />
                      <!-- </div> -->
                  </div>
                  <!-- <div class="field grid">
                    <label style="width:155px">Lama Garansi</label>
                      <div class="col-3">
                        <div class="p-inputgroup">
                          <InputText
                            v-model="detail.invent_lama_garansi"
                            disabled
                          />
                            <span class="p-inputgroup-addon"> Tahun </span> 
                        </div>
                    </div>
                  </div> -->
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Kondisi</label>
                      <!-- <div class="col-4"> -->
                        <InputText
                          v-model="detail.invent_kondisi"
                          disabled
                        />
                      <!-- </div> -->
                  </div>
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Bisnis Unit</label>
                      <!-- <div class="col-4"> -->
                        <InputText
                          v-model="detail.invent_bu"
                          disabled
                        />
                    <!-- </div>/ -->
                  </div>
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Lokasi Terakhir</label>
                      <!-- <div class="col-6"> -->
                        <InputText
                          type="text"
                          v-model="detail.invent_lokasi_update"
                          disabled
                        />
                      <!-- </div> -->
                  </div>
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Pengguna Terakhir</label>
                      <!-- <div class="col-6"> -->
                        <InputText
                          type="text"
                          v-model="detail.invent_pengguna_update"
                          disabled
                        />
                      <!-- </div> -->
                  </div> 
                  <div class="field grid">
                    <label class="col-fixed" style="width:100px">Bisnis Unit Terakhir</label>
                      <!-- <div class="col-6"> -->
                        <InputText
                          type="text"
                          v-model="detail.invent_bu_update"
                          disabled
                        />
                      <!-- </div> -->
                  </div> 
                  <div class="field grid">
                      <label class="col-fixed" style="width:100px">Lokasi Sebelumnya</label>
                        <!-- <div class="col-6"> -->
                          <InputText
                            v-model="detail.invent_lokasi_previous"
                            disabled
                          />
                        <!-- </div> -->
                    </div>
                    <div class="field grid">
                      <label class="col-fixed" style="width:100px">Penguna Sebelumnya</label>
                        <!-- <div class="col-6"> -->
                          <InputText
                            v-model="detail.invent_pengguna_previous"
                            disabled
                          />
                        <!-- </div> -->
                    </div>
                    <div class="field grid">
                      <label class="col-fixed" style="width:100px">Bisnis Unit Sebelumnya</label>
                        <!-- <div class="col-6"> -->
                          <InputText
                            v-model="detail.invent_bu_previous"
                            disabled
                          />
                        <!-- </div> -->
                    </div>
                    
                    <div class="field grid">
                      <label class="col-fixed" style="width:100px"></label>
                        <!-- <div class="col-10 md-6"> -->
                          <div class="card" style="height: 16 rem;">
                            <img v-if="this.detail.invent_photo" :src="'/master_peripheral/' +detail.invent_photo" class="master-image" />
                          </div>
                        <!-- </div> -->
                    </div>
                   <template #footer>
                      <Button label="Close" class="p-button-raised p-button-danger mr-2" icon="pi pi-times" @click="this.$router.push('/dashboard')" autofocus/>
                    </template>
          </Dialog>  
</template>
<script>
export default {
  data() {
    return {
        displayKode: true,
        header:null,
        detail:[],
    };
  },
  created() {
    this.getDetailKode();
  },
  methods: {
    getDetailKode(){
      this.axios.get('/api/detail-peripherall/' +this.$route.params.invent_code_dtl).then((response)=>{
        this.detail = response.data;
        this.header = 'Detail Peripheral';
      });
    },
  },
};
</script>
<style scoped lang="scss">
.master-image {
  width: 100%;
  height: auto;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>