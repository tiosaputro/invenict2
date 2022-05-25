<template>
	<div class="layout-footer">
		<img alt="Logo" :src="footerImage()" height="30" class="mr-2" />
		<span class="font-medium ml-2">© 2022 ICT </span>
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