<template>
  <div class="layout-footer">
	<footer class="text-center text-white" style="color: var(--newbodytext) !important;">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
      <!-- Section: CTA -->
      <section class="">
        <p class="d-flex justify-content-center align-items-center">
          <span class="me-3">Need Help? Click <a rel="noopener" :href="this.target" target="_blank">here</a></span>
        </p>
      </section>
      <!-- Section: CTA -->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
    <div class="text-center p-3">
	    <img :src="`${$baseUrl}/assets/layout/images/logo-emp.png`" alt="Emp" width="80"/> ICT HELPDESK SYSTEM
  	</div>
    <!-- Copyright -->
  </footer>
  <!-- Footer -->
  </div>
</template>

<script>
	export default {
    data() {
        return {
            target:null
        }
    },
    created(){
      this.cek();
    },
		name: "AppFooter",
		methods: {
      cek(){
        var rol = localStorage.getItem('usr_loc');
        if (rol == 'OB' || rol == 'FB'){
            this.target = 'mailto:icthelpdesk.admin-bentu@emp.id?subject=%20help%20for%20app%20usage&body=';
        } else if (rol =='OJ') {
          this.target = 'mailto:icthelpdesk.admin@emp.id?subject=%20help%20for%20app%20usage&body=';
          }
          else if (rol =='OK' || rol == 'FK'){
          this.target = 'mailto:icthelpdesk.admin-kurau@emp.id?subject=%20help%20for%20app%20usage&body=';
        }
      },
		},
		computed: {
			darkTheme() {
				return this.$appState.darkTheme;
			}
		}
	}
</script>