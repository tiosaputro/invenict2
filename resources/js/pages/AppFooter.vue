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
	  <img alt="Logo" :src="footerImage()" height="30" class="mr-2" >  © 2022 Copyright ICT
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
			footerImage() {
				return this.$appState.darkTheme ? '/assets/layout/images/logo-emp.png' : '/assets/layout/images/logo-emp.png';
			}
		},
		computed: {
			darkTheme() {
				return this.$appState.darkTheme;
			}
		}
	}
</script>