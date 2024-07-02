<template>
  <div class="grid">
    <div class="col-12">
      <div class="card">
        <Toast />
        <ConfirmDialog/> 
        <Toolbar class="mb-4">
          <template v-slot:start>
				        <h4>Management User</h4>
          </template>
        </Toolbar>
        <DataTable
          :value="user"
          :paginator="true"
          :rows="10"
          v-model:filters="filters"
          :loading="loading"
          :rowHover="true"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          :rowsPerPageOptions="[5,10,25]"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Management User"
          responsiveLayout="scroll"
        >
        <template #header>
            <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
              <Button
              label="Add"
              class="p-button-raised"
              icon="bi bi-file-earmark-plus"
              @click="$router.push('/Add-mng-user')"
            />
              <span class="block mt-2 md:mt-0 p-input-icon-left">
                <i class="pi pi-search" />
                <InputText v-model="filters['global'].value" placeholder="Search. . ." />
              </span>
            </div>
          </template>
          <template #empty>
            Not Found
          </template>
          <template #loading>
            Loading data. Please wait.
          </template>
          <Column field="usr_fullname" header="User Fullname" :sortable="true" style="min-width:10rem"/>
          <Column field="usr_name" header="User Name" :sortable="true" style="min-width:8rem"/>
          <Column field="usr_domain" header="User Domain" :sortable="true" style="min-width:10rem"/>
          <Column field="usr_division" header="User Divisi" :sortable="true" style="min-width:10rem"/>
          <Column field="creation_date" header="Create Date" :sortable="true" style="min-width:10rem">
          <template #body="slotProps">
            {{ formatDate(slotProps.data.creation_date) }}
          </template>
          </Column>
          <Column header="User Photo" style="min-width:8rem">
            <template #body="slotProps">
                <img :src="'/profile/' +slotProps.data.usr_foto" class="profile-image" />
            </template>
          </Column>
          <Column field="usr_stat" header="User Status" :sortable="true" style="min-width:8rem">
            <template #body= "slotProps">
              <span :class="'user-status status-' + slotProps.data.status.toLowerCase()">{{slotProps.data.usr_stat}}</span>
            </template>
          </Column>
          <Column style="min-width:8rem">
            <template #body="slotProps">
              <Button
                class="p-button-rounded p-button-info mr-2"
                icon="pi pi-pencil"
                v-tooltip.left="'Edit'"
                @click="
                  $router.push({
                    name: 'Edit Management User',
                    params: { code: slotProps.data.usr_id},
                    })"/>
              <Button
                icon="pi pi-trash"
                v-tooltip.right="'Delete'"
                class="p-button-rounded p-button-danger mr-2"
                @click="DeleteUser(slotProps.data.usr_id)"/>
            </template>
          </Column>
        </DataTable>   
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        loading: true,
        user: [],
        filters: { 'global': {value: null, matchMode: this.$FilterMatchMode.CONTAINS} },
    };
  },
  created() {
    this.getUser();
  },
  methods: {
    formatDate(date) {
      return this.$moment(date).format("DD MMM YYYY HH:mm")
    },
    getUser(){
      this.axios.get('api/get-user').then((response)=> {
        this.user = response.data;
        this.loading = false;
      }).catch(error=>{
          if (error.response.status == 401){
            this.$toast.add({
            severity:'error', summary: 'Error', detail:'Session login expired'
          });
          localStorage.clear();
          localStorage.setItem("Expired","true")
          setTimeout( () => this.$router.push('/login'),2000);
           }
           else if (error.response.status == 403){
            this.$router.push('/access');
          }
        });
    },
    DeleteUser(usr_id){
       this.$confirm.require({
        message: "Are you sure to delete this record?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        acceptLabel: "Yes",
        rejectLabel: "No",
        accept: () => {
          this.$toast.add({
            severity: "info",
            summary: "Confirmed",
            detail: "Record deleted",
            life: 3000,
          });
          this.axios.delete('api/delete-user/' +usr_id ).then(()=>{
            this.loading = true;
            this.getUser();
          });
        },
        reject: () => {},
      });
    },
    CetakPdf(){
      window.open("api/report-lookups-pdf");
    },
    CetakExcel(){
      window.open('api/report-lookups-excel');
    },
  },
};
</script>
<style lang="scss" scoped>
.profile-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
</style>