<template>
<Toast />
<div class="grid" v-if="this.role_name.includes('Admin')">
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiverifikasiAdmin()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count5.belumdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDiverifikasiAdmin()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count5.sudahdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="diRejectAdmin()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count5.direject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakanAdmin()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count5.sedangdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="fontSize: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3">
				<div @click="sdhDikerjakanAdmin()" style="cursor:pointer;" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Done</span>
							<div class="text-900 font-medium text-xl">{{count5.sudahdikerjakan}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color: red;"></i>
						</div>
					</div>
				</div>
			</div>	
		<div class="col-12 lg:col-6 xl:col-3">
				<div @click="sdhSelesaiAdmin()" style="cursor:pointer;" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Close</span>
							<div class="text-900 font-medium text-xl">{{count5.sudahselesai}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color : green;"></i>
						</div>
					</div>
				</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
				<div @click="totalKeseluruhanAdmin()" style="cursor:pointer;" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Overall Request</span>
							<div class="text-900 font-medium text-xl">{{count5.countrequest}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="pi pi-book text-xl" style="fontSize: 4rem; color : green;"></i>
						</div>
					</div>
				</div>
		</div>
</div>
<div class="grid" v-else-if="this.role_name.includes('Manager')">
	<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdgDireview4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under Review </span>
						<div class="text-900 font-medium text-xl">{{count4.sedangdireview}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
	<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiverifikasi4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{count4.blmdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdhDiverifikasi4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count4.sudahdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="direject4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count4.direject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count4.penugasanrequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdgdikerjakan4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count4.sedangdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="fontSize: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdHDikerjakan4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count4.sudahdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count4.sudahselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalRequest4()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count4.totalrequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl" style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="grid" v-else-if="this.role_name.includes('Reviewer Bentu')">
	<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiassign2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{countBentu.blmDiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="atasanDivisi2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{countBentu.atasandivisi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="IctManager2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{countBentu.manager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="direject2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{countBentu.reject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{countBentu.penugasanRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{countBentu.sdgdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{countBentu.sdhdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{countBentu.sdhselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{countBentu.totalRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
</div>
<div class="grid" v-else-if="this.role_name.includes('Reviewer Kurau')">
	<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiassign2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{countKurau.blmDiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="atasanDivisi2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{countKurau.atasandivisi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="IctManager2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{countKurau.manager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="direject2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{countKurau.reject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{countKurau.penugasanRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{countKurau.sdgdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{countKurau.sdhdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{countKurau.sdhselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{countKurau.totalRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
</div>
<div class="grid" v-else-if="this.role_name.includes('Reviewer Jakarta')">
	<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiassign2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.blmDiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="atasanDivisi2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.atasandivisi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="IctManager2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.manager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="direject2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.reject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.penugasanRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.sdgdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDikerjakan2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.sdhdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.sdhselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalRequest2()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{countJakarta.totalRequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="fontSize: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
</div>
<div class="grid" v-else-if="this.role_name.includes('Personel ICT')">
	<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest3()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count3.penugasanrequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="diReject3()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count3.rejected}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmSelesai3()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count3.belumselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-split text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdHDikerjakan3()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count3.sudahdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai3()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count3.sudahselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
</div>
<div class="grid" v-else-if="this.role_name.includes('Atasan Requestor Divisi')">
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdgDireview1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under Review </span>
						<div class="text-900 font-medium text-xl">{{count1.sedangdireview}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiverifikasi1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count1.belumdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDiverifikasi1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count1.sudahdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="diReject1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count1.direject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="fontSize: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="penugasanRequest1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count1.penugasanrequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakan1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count1.sedangdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="fontSize: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
    <div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDikerjakan1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count1.sudahdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>	
    <div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{ count1.sudahselesai }}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    </div>
	<div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalRequest1()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count1.total}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl" style="fontSize: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    </div>
</div>
<div class="grid" v-else-if="this.role_name.includes('Requestor Divisi') || this.role_name.includes('Default Role')">
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="sdgDireview()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under review </span>
						<div class="text-900 font-medium text-xl">{{count.sedangdireview}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" >
			<div @click="blmDiverifikasi()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count.belumdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDiverifikasi()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already verified</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdiverifikasi}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="diReject()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.direject}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="fontSize: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdgDikerjakan()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sedangdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="fontSize: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
    <div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhDikerjakan()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdikerjakan}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>	
    <div class="col-12 lg:col-6 xl:col-3">
			<div @click="sdhSelesai()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sudahselesai}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="fontSize: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    </div>	
    <div class="col-12 lg:col-6 xl:col-3">
			<div @click="totalKeseluruhan()" style="cursor:pointer;" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.countrequest}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="fontSize: 4rem; color : green;"></i>
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
			fullPage: false,
			loader:'',
            user:[],
			role_name :[],
            count:[],
            count1:[],
            countBentu:[],
            countKurau:[],
            countJakarta:[],
            count3:[],
            count4:[],
            count5:[],
            token: localStorage.getItem('token'),
        }
    },
    mounted(){
        this.CekUser();
    },
    methods:{
		CekUser(){
			this.loader = this.$loading.show
			({
				container: this.$refs.loadingContainer,
				color: '#2772d9',
				loader: 'spinner',
				width: 64,
				height: 64,
				backgroundColor: '#1f2d40',
				opacity: 0.5,
				zIndex: 999,
			}, {});
			this.axios.get('/api/cek-role',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
				this.role_name = response.data.map((x)=>x.rol_name);
				if(this.role_name.includes('Manager')){
					this.getData4();
				}	
				else if(this.role_name.includes('Reviewer Bentu')){
					this.getDataBentu();
				}
				else if(this.role_name.includes('Reviewer Kurau')){
					this.getDataKurau();
				}
				else if(this.role_name.includes('Reviewer Jakarta')){
					this.getDataJakarta();
				}
				else if(this.role_name.includes('Personel ICT')){
					this.getData3();
				}
				else if(this.role_name.includes('Atasan Requestor Divisi')){
					this.getData1();
				}
				else if(this.role_name.includes('Requestor Divisi') || this.role_name.includes('Default Role')){
					this.getData();
				}
				else if(this.role_name.includes('Admin')){
					this.getData5();
				}
			}).catch(error=>{
			if (error.response.status == 401) {
				localStorage.clear();
				localStorage.setItem('Expired','true')
				this.$router.push('/login');
				this.loader.hide();
			}
			});
		},
      	getData(){
            this.axios.get('/api/getCountUser',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.count = response.data.data;
				this.loader.hide();
            });
        },
        //dashboard approval user
        getData1(){
            this.axios.get('api/getCountDivisi1',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.count1 = response.data.data;
				this.loader.hide();
            });
        },
        getDataBentu(){
            this.axios.get('api/getCountReviewerBentu',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.countBentu = response.data;
				this.loader.hide();
            });
        },
		getDataKurau(){
            this.axios.get('api/getCountReviewerKurau',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.countKurau = response.data;
				this.loader.hide();
            });
        },
		getDataJakarta(){
            this.axios.get('api/getCountReviewerJakarta',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.countJakarta = response.data;
				this.loader.hide();
            });
        },
        getData3(){
            this.axios.get('api/getCountDivisi3',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.count3 = response.data.data;
				this.loader.hide();
            });
        },
        getData4(){
            this.axios.get('api/getCountDivisi4',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
                this.count4 = response.data.data;
				this.loader.hide();
            });
        },
        getData5(){
          this.axios.get('api/getCountAdmin',{headers: {'Authorization': 'Bearer '+this.token}}).then((response)=>{
            this.count5 = response.data.data;
				this.loader.hide();
          });
        },
        blmDiverifikasiAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',23);
        },
        sdhDiverifikasiAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',24);
        },
        diRejectAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',25);
        },
        sdgDikerjakanAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',26);
        },
        sdhDikerjakanAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',27);
        },
        sdhSelesaiAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',28);
        },
        totalKeseluruhanAdmin(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',29);
        },
		sdgDireview(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',40);
		},
        blmDiverifikasi(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',1);
        },
        sdhDiverifikasi(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',2);
        },
        diReject(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',3);
        },
        sdgDikerjakan(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',4);
        },
        sdhDikerjakan(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',5);
        },
        sdhSelesai(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',6);
        },
        totalKeseluruhan(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',22);
        },
		sdgDireview1(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',41);
		},
        blmDiverifikasi1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',7);
        },
        sdhDiverifikasi1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',8);
        },
        diReject1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',9);
        },
		penugasanRequest1(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',45);
		},
        sdgDikerjakan1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',10);
        },
        sdhDikerjakan1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',11);
        },
        sdhSelesai1(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',12);
        },
		totalRequest1(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',38);
		},
		atasanDivisi2(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',30)
		},
		IctManager2(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',31)
		},
		direject2(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',32)
		},
        blmDiassign2(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',13)
        },
		penugasanRequest2(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',43)
		},
        sdgDikerjakan2(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',14)
        },
        sdhDikerjakan2(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',15)
        },
        sdhSelesai2(){
          this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',16)
        },
		totalRequest2(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',37)
		},
        penugasanRequest3(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',46)
        },
        diReject3(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',47)
        },
        blmSelesai3(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',17)
        },
        sdHDikerjakan3(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',18)
        },
        sdhSelesai3(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',19)
        },
		sdgDireview4(){
		  this.$router.push('/ict-request-desc')
          localStorage.setItem('desc',42);
		},
		blmDiverifikasi4(){
		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',33)
		},
		sdhDiverifikasi4(){
		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',34)
		},
		direject4(){
		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',35);
		},
		penugasanRequest4(){
		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',44)
		},
		sdgdikerjakan4(){
 		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',36)
		},
        sdHDikerjakan4(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',20)
        },
        sdhSelesai4(){
          this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',21)
        },
		totalRequest4(){
		  this.$router.push('/ict-request-desc');
          localStorage.setItem('desc',39)
		},
    }
}
</script>