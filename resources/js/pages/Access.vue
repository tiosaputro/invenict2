<template>
    <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="grid justify-content-center p-2 lg:p-0" style="min-width:80%">
            <div class="col-12 xl:col-6" style="border-radius:56px; padding:0.3rem; background: linear-gradient(180deg, rgba(247, 149, 48, 0.4) 10%, rgba(247, 149, 48, 0) 30%);">
                <div class="h-full w-full m-0 py-7 px-4" style="border-radius:53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0));">
                    <div class="grid flex flex-column align-items-center">
                        <!-- <div class="flex justify-content-center align-items-center bg-orange-500 border-circle" style="width:3.2rem; height:3.2rem;"> -->
                            <v-icon name="gi-padlock" animation="ring" speed="slow" scale="3" fill="#f21326"/>
                            <!-- <i class="pi pi-fw pi-lock text-2xl text-50"></i> -->
                        <!-- </div> -->
                        <h1 class="text-900 font-bold text-4xl lg:text-5xl mb-2">Access Denied</h1>
                        <span class="text-900 text-center">You do not have the necesary permisions. Please contact admins.</span>
                        <img src="layout/images/asset-access.svg" alt="Access denied" class="mt-5" width="80%"/>
                        <div class="col-12 mt-5 text-center">
                            <Button icon="bi bi-arrow-left" class="p-button-raised p-button-text" label="Go to Dashboard" @click="this.$router.push('/dashboard')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>