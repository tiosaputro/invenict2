<template>
<Toast />
<div class="grid">
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.belumdiverifikasiadmin >= 0">
			<div @click="blmDiverifikasiAdmin()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count.belumdiverifikasiadmin}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdiverifikasiadmin >= 0">
			<div @click="sdhDiverifikasiAdmin()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdiverifikasiadmin}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.direjectadmin >= 0">
			<div @click="diRejectAdmin()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.direjectadmin}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdikerjakanadmin >= 0">
			<div @click="sdgDikerjakanAdmin()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sedangdikerjakanadmin}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="font-size: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdikerjakanadmin >= 0">
				<div @click="sdhDikerjakanAdmin()" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Done</span>
							<div class="text-900 font-medium text-xl">{{count.sudahdikerjakanadmin}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color: red;"></i>
						</div>
					</div>
				</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahselesaiadmin >= 0">
				<div @click="sdhSelesaiAdmin()" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Close</span>
							<div class="text-900 font-medium text-xl">{{count.sudahselesaiadmin}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color : green;"></i>
						</div>
					</div>
				</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.countrequestadmin >= 0">
				<div @click="totalKeseluruhanAdmin()" class="card mb-0">
					<div class="flex justify-content-between mb-3">
						<div>
							<span class="block text-500 font-medium mb-3">Overall Request</span>
							<div class="text-900 font-medium text-xl">{{count.countrequestadmin}}</div>
						</div>
						<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
							<i class="pi pi-book text-xl" style="font-size: 4rem; color : green;"></i>
						</div>
					</div>
				</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdireviewmanager >= 0">
			<div @click="sdgDireview4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under Review </span>
						<div class="text-900 font-medium text-xl">{{count.sedangdireviewmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.blmdiverifikasimanager >= 0">
			<div @click="blmDiverifikasi4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{count.blmdiverifikasimanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdiverifikasimanager >= 0">
			<div @click="sdhDiverifikasi4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdiverifikasimanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.direjectmanager >= 0">
			<div @click="direject4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.direjectmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanrequestmanager >= 0">
			<div @click="penugasanRequest4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanrequestmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdikerjakanmanager >= 0">
			<div @click="sdgdikerjakan4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sedangdikerjakanmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="font-size: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdikerjakanmanager >= 0">
			<div @click="sdHDikerjakan4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdikerjakanmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahselesaimanager >= 0">
			<div @click="sdhSelesai4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sudahselesaimanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.totalrequestmanager >= 0">
			<div @click="totalRequest4()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.totalrequestmanager}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl" style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.blmDiverifikasibentu >= 0">
			<div @click="blmDiassign2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{count.blmDiverifikasibentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.atasandivisibentu >= 0">
			<div @click="atasanDivisi2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{count.atasandivisibentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.managerbentu >= 0">
			<div @click="IctManager2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{count.managerbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.rejectbentu >= 0">
			<div @click="direject2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.rejectbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanRequestbentu >= 0">
			<div @click="penugasanRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanRequestbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdgdikerjakanbentu >= 0">
			<div @click="sdgDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sdgdikerjakanbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhdikerjakanbentu >= 0">
			<div @click="sdhDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sdhdikerjakanbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhselesaibentu >= 0">
			<div @click="sdhSelesai2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sdhselesaibentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.totalRequestbentu >= 0">
			<div @click="totalRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.totalRequestbentu}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.blmDiverifikasikurau >= 0">
			<div @click="blmDiassign2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{count.blmDiverifikasikurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.atasandivisikurau >= 0">
			<div @click="atasanDivisi2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{count.atasandivisikurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.managerkurau >= 0">
			<div @click="IctManager2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{count.managerkurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.rejectkurau >= 0">
			<div @click="direject2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.rejectkurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanRequestkurau >= 0">
			<div @click="penugasanRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanRequestkurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdgdikerjakankurau >= 0">
			<div @click="sdgDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sdgdikerjakankurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhdikerjakankurau >= 0">
			<div @click="sdhDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sdhdikerjakankurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhselesaikurau >= 0">
			<div @click="sdhSelesai2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sdhselesaikurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.totalRequestkurau >= 0">
			<div @click="totalRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.totalRequestkurau}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.blmDiverifikasijakarta >= 0">
			<div @click="blmDiassign2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification</span>
						<div class="text-900 font-medium text-xl">{{count.blmDiverifikasijakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.atasandivisijakarta >= 0">
			<div @click="atasanDivisi2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Higher Level</span>
						<div class="text-900 font-medium text-xl">{{count.atasandivisijakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-clipboard-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.managerjakarta >= 0">
			<div @click="IctManager2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">ICT Manager</span>
						<div class="text-900 font-medium text-xl">{{count.managerjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.rejectjakarta >= 0">
			<div @click="direject2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.rejectjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanRequestjakarta >= 0">
			<div @click="penugasanRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanRequestjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdgdikerjakanjakarta >= 0">
			<div @click="sdgDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sdgdikerjakanjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhdikerjakanjakarta >= 0">
			<div @click="sdhDikerjakan2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sdhdikerjakanjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 3.5rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sdhselesaijakarta >= 0">
			<div @click="sdhSelesai2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sdhselesaijakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.totalRequestjakarta >= 0">
			<div @click="totalRequest2()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.totalRequestjakarta}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl"  style="font-size: 3.5rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanrequestpersonnel >= 0">
			<div @click="penugasanRequest3()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanrequestpersonnel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.rejectedpersonnel >= 0">
			<div @click="diReject3()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.rejectedpersonnel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.belumselesaipersonnel >= 0">
			<div @click="blmSelesai3()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.belumselesaipersonnel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-split text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdikerjakanpersonnel >= 0">
			<div @click="sdHDikerjakan3()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdikerjakanpersonnel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahselesaipersonnel >= 0">
			<div @click="sdhSelesai3()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sudahselesaipersonnel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdireviewhigherlevel >= 0">
			<div @click="sdgDireview1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under Review </span>
						<div class="text-900 font-medium text-xl">{{count.sedangdireviewhigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.belumdiverifikasihigherlevel >= 0">
			<div @click="blmDiverifikasi1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count.belumdiverifikasihigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdiverifikasihigherlevel >= 0">
			<div @click="sdhDiverifikasi1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already Verified</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdiverifikasihigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.direjecthigherlevel >= 0">
			<div @click="diReject1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.direjecthigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="font-size: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.penugasanrequesthigherlevel >= 0">
			<div @click="penugasanRequest1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Request Assignment</span>
						<div class="text-900 font-medium text-xl">{{count.penugasanrequesthigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdikerjakanhigherlevel >= 0">
			<div @click="sdgDikerjakan1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sedangdikerjakanhigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="font-size: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
    	<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdikerjakanhigherlevel >= 0">
			<div @click="sdhDikerjakan1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdikerjakanhigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>	
    	<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahselesaihigherlevel >= 0">
			<div @click="sdhSelesai1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{ count.sudahselesaihigherlevel }}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    	</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.totalhigherlevel >= 0">
			<div @click="totalRequest1()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.totalhigherlevel}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-book text-xl" style="font-size: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    	</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdireviewrequestor >= 0">
			<div @click="sdgDireview()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Under review </span>
						<div class="text-900 font-medium text-xl">{{count.sedangdireviewrequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.belumdiverifikasirequestor >= 0">
			<div @click="blmDiverifikasi()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Waiting for verification </span>
						<div class="text-900 font-medium text-xl">{{count.belumdiverifikasirequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-hourglass-bottom text-xl" style="color: gray;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdiverifikasirequestor >= 0">
			<div @click="sdhDiverifikasi()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Already verified</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdiverifikasirequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-check text-xl" style="color: green;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.direjectrequestor >= 0">
			<div @click="diReject()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Rejected</span>
						<div class="text-900 font-medium text-xl">{{count.direjectrequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-times text-xl" style="font-size: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sedangdikerjakanrequestor >= 0">
			<div @click="sdgDikerjakan()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">In Progress</span>
						<div class="text-900 font-medium text-xl">{{count.sedangdikerjakanrequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="pi pi-spin pi-spinner" style="font-size: 2rem; color: green;"></i>
					</div>
				</div>
			</div>
		</div>	
    	<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahdikerjakanrequestor >= 0">
			<div @click="sdhDikerjakan()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Done</span>
						<div class="text-900 font-medium text-xl">{{count.sudahdikerjakanrequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color: red;"></i>
					</div>
				</div>
			</div>
		</div>	
    	<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.sudahselesairequestor >= 0">
			<div @click="sdhSelesai()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Close</span>
						<div class="text-900 font-medium text-xl">{{count.sudahselesairequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-check2-all text-xl" style="font-size: 4rem; color : green;"></i>
					</div>
				</div>
			</div>
    	</div>	
    	<div class="col-12 lg:col-6 xl:col-3" v-if="this.count.countrequestrequestor >= 0">
			<div @click="totalKeseluruhan()" class="card mb-0">
				<div class="flex justify-content-between mb-3">
					<div>
						<span class="block text-500 font-medium mb-3">Overall Request</span>
						<div class="text-900 font-medium text-xl">{{count.countrequestrequestor}}</div>
					</div>
					<div class="flex align-items-center justify-content-center bg-gray-100 border-round" style="width:2.5rem;height:2.5rem">
						<i class="bi bi-journal-bookmark-fill text-xl" style="font-size: 4rem; color : green;"></i>
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
            count:[],
        }
    },
    mounted(){
        this.getData();
    },
    methods:{
		getData(){
			this.axios.get('api/data-dashboard').then((res)=>{
				this.count = res.data.data;
			}).catch(error=>{
			 if (error.response.status == 401) {
			 	localStorage.clear();
			 	localStorage.setItem('Expired','true')
			 	this.$router.push('/login');
			   }
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
<style scoped lang="scss">
.card{
cursor:pointer;
}
</style>