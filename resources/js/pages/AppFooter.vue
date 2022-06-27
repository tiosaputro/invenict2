<template>
  <div class="layout-footer">
	<footer class="text-center text-white">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
      <!-- Section: CTA -->
      <section class="">
        <p class="d-flex justify-content-center align-items-center">
          <span class="me-3">Need Help? Click <a rel="noopener" href="mailto:icthelpdesk.admin@emp.id?subject=Need%20help%20for%20approval&body=" target="_blank">here</a></span>
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
		name: "AppFooter",
		methods: {
			footerImage() {
				return this.$appState.darkTheme ? '/assets/layout/images/logo_emp_new.png' : '/assets/layout/images/logo_emp_new.png';
			}
		},
		computed: {
			darkTheme() {
				return this.$appState.darkTheme;
			}
		}
	}
</script>