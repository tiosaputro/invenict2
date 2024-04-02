<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toast />
                <ConfirmDialog />
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <h4>User Domain List</h4>
                    </template>
                </Toolbar>
                <DataTable :value="user" :paginator="true" :rows="10" v-model:filters="filters" :loading="loading"
                    :rowHover="true"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5,10,25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Management User"
                    responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <Button label="Update" class="p-button-raised" icon="pi pi-spin pi-spinner"
                                @click="UpdateDataDirectory()"/>
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
                    <Column field="usr_fullname" header="User Fullname" :sortable="true" style="min-width:10rem" />
                    <Column field="usr_domain" header="User Domain" :sortable="true" style="min-width:8rem" />
                    <Column field="usr_fullname" header="User Fullname" :sortable="true" style="min-width:10rem" />
                    <Column field="created_at" header="Created Date" :sortable="true" style="min-width:10rem">
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.created_at) }}
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
                filters: {
                    'global': {
                        value: null,
                        matchMode: this.$FilterMatchMode.CONTAINS
                    }
                },
            };
        },
        created() {
            this.getUser();
        },
        methods: {
            formatDate(date) {
                return this.$moment(date).format("DD MMM YYYY HH:mm")
            },
            getUser() {
                this.axios.get('api/get-user-domain').then((response) => {
                    this.user = response.data.data;
                    this.loading = false;
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$toast.add({
                            severity: 'error',
                            summary: 'Error',
                            detail: 'Session login expired'
                        });
                        localStorage.clear();
                        localStorage.setItem("Expired", "true")
                        setTimeout(() => this.$router.push('/login'), 2000);
                    } else if (error.response.status == 403) {
                        this.$router.push('/access');
                    }
                });
            },
            UpdateDataDirectory() {
                this.loading = true;
                this.axios.get('api/update-user-domain').then((response) => {
                    this.getUser();
                    this.$toast.add({
                            severity: 'Success',
                            summary: 'Success',
                            detail: response.data.message
                    });
                });
            }
        },
    };

</script>