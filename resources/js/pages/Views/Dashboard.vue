<template>
	<Toast />
	<div class="grid">
		<div v-for="card in visibleCards" :key="card.key" class="col-12 lg:col-6 xl:col-3">
			<div @click="card.action" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">{{ card.label }}</span>
						<div class="text-900 font-medium text-xl">
							{{ countData[card.key] ?? 0 }}
						</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round"
						style="width:2.5rem;height:2.5rem">
						<i :class="card.icon" :style="card.iconStyle"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			countData: {}
		}
	},
	mounted() {
		this.getData();
	},
	methods: {
		getData() {
			this.axios.get('api/data-dashboard').then((res) => {
				this.countData = res.data.data;
				console.log(this.countData);
			}).catch(error => {
				if (error.response.status == 401) {
					localStorage.clear();
					localStorage.setItem('Expired', 'true')
					this.$router.push('/login');
				}
			});
		},
		goToDesc(descId) {
			this.$router.push('/ict-request-desc');
			localStorage.setItem('desc', descId);
		}
	},
	computed: {
		cards() {
			return [
				{
					key: 'belumdiverifikasirequestor',
					label: 'Waiting for verification',
					action: () => this.goToDesc(1),
					icon: 'bi bi-hourglass-bottom',
					iconStyle: 'color: gray;'
				},
				{
					key: 'sudahdiverifikasirequestor',
					label: 'Already verified',
					action: () => this.goToDesc(2),
					icon: 'pi pi-check',
					iconStyle: 'color: green;'
				},
				{
					key: 'direjectrequestor',
					label: 'Rejected',
					action: () => this.goToDesc(3),
					icon: 'pi pi-times',
					iconStyle: 'font-size: 4rem; color: red;'
				},
				{
					key: 'sedangdikerjakanrequestor',
					label: 'In Progress',
					action: () => this.goToDesc(4),
					icon: 'pi pi-spin pi-spinner',
					iconStyle: 'font-size: 2rem; color: green;'
				},
				{
					key: 'sudahdikerjakanrequestor',
					label: 'Done',
					action: () => this.goToDesc(5),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size: 4rem; color: red;'
				},
				{
					key: 'sudahselesairequestor',
					label: 'Close',
					action: () => this.goToDesc(6),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size: 4rem; color: green;'
				},
				{
					key: 'countrequestrequestor',
					label: 'Overall Request',
					action: () => this.goToDesc(22),
					icon: 'bi bi-journal-bookmark-fill',
					iconStyle: 'font-size: 4rem; color: green;'
				},
				// === Admin ===
				{
					key: 'belumdiverifikasiadmin',
					label: 'Waiting for verification',
					action: () => this.goToDesc(23),
					icon: 'pi pi-check text-xl',
					iconStyle: 'color:red;'
				},
				{
					key: 'sudahdiverifikasiadmin',
					label: 'Already Verified',
					action: () => this.goToDesc(24),
					icon: 'pi pi-check text-xl',
					iconStyle: 'color:green;'
				},
				{
					key: 'direjectadmin',
					label: 'Rejected',
					action: () => this.goToDesc(25),
					icon: 'pi pi-times text-xl',
					iconStyle: 'color:red;'
				},
				{
					key: 'sedangdikerjakanadmin',
					label: 'In Progress',
					action: () => this.goToDesc(26),
					icon: 'pi pi-spin pi-spinner',
					iconStyle: 'font-size:2rem;color:green;'
				},
				{
					key: 'sudahdikerjakanadmin',
					label: 'Done',
					action: () => this.goToDesc(27),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size:4rem;color:red;'
				},
				{
					key: 'sudahselesaiadmin',
					label: 'Close',
					action: () => this.goToDesc(28),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size:4rem;color:green;'
				},
				{
					key: 'countrequestadmin',
					label: 'Overall Request',
					action: () => this.goToDesc(29),
					icon: 'pi pi-book',
					iconStyle: 'font-size:4rem;color:green;'
				},

				// === Manager ===
				{
					key: 'sedangdireviewmanager',
					label: 'Under Review',
					action: () => this.goToDesc(42),
					icon: 'bi bi-journal-check',
					iconStyle: 'color:green;'
				},
				{
					key: 'blmdiverifikasimanager',
					label: 'Waiting for verification',
					action: () => this.goToDesc(33),
					icon: 'pi pi-check',
					iconStyle: 'color:red;'
				},
				{
					key: 'sudahdiverifikasimanager',
					label: 'Already Verified',
					action: () => this.goToDesc(34),
					icon: 'pi pi-check',
					iconStyle: 'color:green;'
				},
				{
					key: 'direjectmanager',
					label: 'Rejected',
					action: () => this.goToDesc(35),
					icon: 'pi pi-times',
					iconStyle: 'color:red;'
				},
				{
					key: 'penugasanrequestmanager',
					label: 'Request Assignment',
					action: () => this.goToDesc(44),
					icon: 'bi bi-hourglass-bottom',
					iconStyle: 'color:gray;'
				},
				{
					key: 'sedangdikerjakanmanager',
					label: 'In Progress',
					action: () => this.goToDesc(36),
					icon: 'pi pi-spin pi-spinner',
					iconStyle: 'font-size:2rem;color:green;'
				},
				{
					key: 'sudahdikerjakanmanager',
					label: 'Done',
					action: () => this.goToDesc(20),
					icon: 'bi bi-check2-all',
					iconStyle: 'color:red;'
				},
				{
					key: 'sudahselesaimanager',
					label: 'Close',
					action: () => this.goToDesc(21),
					icon: 'bi bi-check2-all',
					iconStyle: 'color:green;'
				},
				{
					key: 'totalrequestmanager',
					label: 'Overall Request',
					action: () => this.goToDesc(39),
					icon: 'pi pi-book',
					iconStyle: 'font-size:3.5rem;color:green;'
				},

				// === Reviewer ===
				{
					key: 'blmDiverifikasi',
					label: 'Waiting for verification',
					action: () => this.goToDesc(13),
					icon: 'pi pi-check',
					iconStyle: 'color:red;',
				},
				{
					key: 'atasandivisi',
					label: 'Higher Level',
					action: () => this.goToDesc(30),
					icon: 'bi bi-clipboard-check',
					iconStyle: 'color:green;',
				},
				{
					key: 'manager',
					label: 'ICT Manager',
					action: () => this.goToDesc(31),
					icon: 'bi bi-journal-bookmark-fill',
					iconStyle: 'color:green;',
				},
				{
					key: 'reject',
					label: 'Rejected',
					action: () => this.goToDesc(32),
					icon: 'pi pi-times',
					iconStyle: 'color:red;',
				},
				{
					key: 'penugasanRequest',
					label: 'Request Assignment',
					action: () => this.goToDesc(43),
					icon: 'bi bi-hourglass-bottom',
					iconStyle: 'color:gray;',
				},
				{
					key: 'sdgdikerjakan',
					label: 'In Progress',
					action: () => this.goToDesc(14),
					icon: 'pi pi-check',
					iconStyle: 'color:green;',
				},
				{
					key: 'sdhdikerjakan',
					label: 'Done',
					action: () => this.goToDesc(15),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size:3.5rem;color:red;',
				},
				{
					key: 'sdhselesai',
					label: 'Close',
					action: () => this.goToDesc(16),
					icon: 'bi bi-check2-all',
					iconStyle: 'font-size:3.5rem;color:green;',
				},
				{
					key: 'totalRequest',
					label: 'Overall Request',
					action: () => this.goToDesc(37),
					icon: 'pi pi-book',
					iconStyle: 'font-size:3.5rem;color:green;',
				},

				// === Personnel ===
				{
					key: 'penugasanrequestPersonnel',
					label: 'Request Assignment',
					action: () => this.goToDesc(46),
					icon: 'bi bi-hourglass-bottom',
					iconStyle: 'color: gray;'
				},
				{
					key: 'rejectedPersonnel',
					label: 'Rejected',
					action: () => this.goToDesc(47),
					icon: 'pi pi-times',
					iconStyle: 'color: red;'
				},
				{
					key: 'belumselesaiPersonnel',
					label: 'In Progress',
					action: () => this.goToDesc(17),
					icon: 'bi bi-hourglass-split text-xl',
					iconStyle: 'color: red;'
				},
				{
					key: 'sudahdikerjakanPersonnel',
					label: 'Done',
					action: () => this.goToDesc(18),
					icon: 'pi pi-check',
					iconStyle: 'color: green;'
				},
				{
					key: 'sudahselesaiPersonnel',
					label: 'Close',
					action: () => this.goToDesc(19),
					icon: 'bi bi-check2-all',
					iconStyle: 'color: green;'
				},

			]
		},
		visibleCards() {
			return this.cards.filter((card) => card.key in this.countData)
		}
	}
}
</script>
<style scoped lang="scss">
.card {
	cursor: pointer;
}
</style>