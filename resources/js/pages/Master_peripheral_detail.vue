<template>
 <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
   <div class="grid justify-content-center p-2 lg:p-0" style="min-width:150%">
     <div class="col-12 mt-5 xl:mt-0 text-center">
      <img src="/assets/layout/images/logo_emp_new.png" alt="logo" style="width:150px; height:100px;">
     </div>
     <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, rgb(180, 180, 180) 10%, rgba(247, 149, 48, 0) 30%);">
        <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
          <div class="grid flex flex-column align-items-center">
            <div class="row">
              <div class="col-sm-6">
                <div class="field grid">
                  <label style="width:155px">Kode</label>
                    <div class="col-4">
                      <InputText
                        type="text"
                        v-model="detail.invent_code"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="field grid">
                    <label style="width:155px">Merk</label>
                      <div class="col-4">
                        <InputText
                          v-model="detail.invent_brand"
                          disabled
                        />
                      </div>
                    </div>
                    <div class="field grid">
                      <label style="width:155px">Tipe</label>
                        <div class="col-4">
                          <InputText
                            disabled
                            v-model= "detail.invent_type"
                          />
                        </div>
                      </div>
                      <div class="field grid">
                        <label style="width:155px">S/N</label>
                          <div class="col-4">
                            <InputText
                              v-model="detail.invent_sn"
                              disabled
                            />
                        </div>
                      </div>
                      <div class="field grid">
                        <label style="width:155px">Tgl. Perolehan</label>
                          <div class="col-4">
                            <InputText
                              v-model="detail.invent_tgl_perolehan"
                              disabled
                            />
                          </div>
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
                        <label style="width:155px">Kondisi</label>
                          <div class="col-4">
                            <InputText
                              v-model="detail.invent_kondisi"
                              disabled
                            />
                          </div>
                      </div>
                      <div class="field grid">
                        <label for="notlp2" style="width:155px">Bisnis Unit</label>
                          <div class="col-4">
                            <InputText
                              v-model="detail.invent_bu"
                              disabled
                            />
                        </div>
                      </div>
                      <div class="field grid">
                        <label style="width:155px">Lokasi Terakhir</label>
                          <div class="col-6">
                            <InputText
                              type="text"
                              v-model="detail.invent_lokasi_update"
                              disabled
                            />
                          </div>
                      </div>
                      <div class="field grid">
                        <label style="width:155px">Pengguna Terakhir</label>
                          <div class="col-6">
                            <InputText
                              type="text"
                              v-model="detail.invent_pengguna_update"
                              disabled
                            />
                          </div>
                      </div> 
                    </div>
                      <div class="col-sm-6">
                        <div class="field grid">
                          <label style="width:155px">Nama</label>
                            <div class="col-4">
                              <InputText
                                v-model="detail.invent_desc"
                                disabled
                              />
                            </div>
                        </div> 
                        <div class="field grid">
                          <label style="width:155px"></label>
                            <div class="col-10 md-6">
                              <div class="card" style="height: 16 rem;">
                                <img :src="'/master_peripheral/' +detail.invent_photo" class="master-image" />
                              </div>
                            </div>
                        </div>
                        <div class="field grid">
                          <label style="width:155px">Lokasi Sebelumnya</label>
                            <div class="col-6">
                              <InputText
                                v-model="detail.invent_lokasi_previous"
                                disabled
                              />
                            </div>
                        </div>
                        <div class="field grid">
                          <label style="width:155px">Penguna Sebelumnya</label>
                            <div class="col-6">
                              <InputText
                                v-model="detail.invent_pengguna_previous"
                                disabled
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
</template>
<script>
import {FilterMatchMode} from 'primevue/api';
import Jspdf from 'jspdf';
export default {
  data() {
    return {
        detail:[],
        token: localStorage.getItem('token'),
    };
  },
  created() {
    this.getDetailKode();
  },
  methods: {
    getDetailKode(){
      this.axios.get('/api/detail-peripheral/' +this.$route.params.invent_code, {headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
        this.detail = response.data;
      });
    },
  },
};
</script>
<style scoped lang="scss">
.master-image {
  height:200pt;
  box-shadow: 0px 9px 46px 8px rgba(0, 0, 0, 0.12), 0px 24px 38px 3px rgba(0, 0, 0, 0.14), 0px 11px 15px rgba(0, 0, 0, 0.2);
}
</style>