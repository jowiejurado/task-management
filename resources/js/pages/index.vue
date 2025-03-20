<script setup>
import { ref, computed, watch, onBeforeMount } from "vue";
import { useStore } from "vuex";
import plusIcon from "@/assets/icons/plus.svg";
import editIcon from "@/assets/icons/edit.svg";
import deleteIcon from "@/assets/icons/delete.svg";
import upIcon from "@/assets/icons/up.svg";
import downIcon from "@/assets/icons/down.svg";
import TaskModal from "@/components/modals/TaskModal.vue";

const store = useStore();
const searchQuery = ref(null);
const sortKey = ref("created_at");
const sortOrder = ref("desc");
const currentPage = ref(1);
const itemsPerPage = ref(10);
const isLoading = ref(false);

const showModal = ref(false);
const modalType = ref("add");
const selectedTask = ref(null);
const taskForm = ref({ title: "", description: "", priority: "", status: "" });

const getTasks = async () => {
	isLoading.value = true;
	try {
		await store.dispatch("tasks/paginate", {
			page: currentPage.value,
			search: searchQuery.value,
			orderBy: sortKey.value,
			orderDirection: sortOrder.value,
			itemsPerPage: itemsPerPage.value,
		});
	} catch (error) {
		console.error("Error fetching tasks:", error);
	} finally {
		isLoading.value = false;
	}
};

const tasks = computed(() => store.getters["tasks/list"]);
const meta = computed(() => store.getters["tasks/meta"]);

watch([searchQuery, currentPage, itemsPerPage, sortKey, sortOrder], getTasks);

const sortBy = (key) => {
	if (sortKey.value === key) {
		if (sortOrder.value === "asc") {
			sortOrder.value = "desc";
		} else {
			sortKey.value = null;
			sortOrder.value = null;
		}
	} else {
		sortKey.value = key;
		sortOrder.value = "asc";
	}
};

const changePage = (page) => {
	if (page >= 1 && page <= meta.value.last_page) {
		currentPage.value = page;
	}
};

const openModal = (type, task = null) => {
	modalType.value = type;
	showModal.value = true;
	if (task) {
		selectedTask.value = task;
		taskForm.value = { ...task };
	} else {
		taskForm.value = { title: "", description: "", priority: "", status: "" };
	}
};

const closeModal = () => {
	showModal.value = false;
	selectedTask.value = null;
};

const saveTask = async () => {
	if (modalType.value === "add") {
		await store.dispatch("tasks/save", taskForm.value);
	} else {
		await store.dispatch("tasks/update", taskForm.value);
	}
	closeModal();
	await getTasks();
};

const deleteTask = async () => {
	await store.dispatch("tasks/destroy", selectedTask.value.id);
	closeModal();
	await getTasks();
};

onBeforeMount(async () => {
	await getTasks();
});
</script>

<template>
	<div class="flex flex-col items-center justify-center bg-gray-100 w-full">
		<div class="flex flex-wrap justify-between w-full max-w-4xl mb-6">
			<button
				@click="openModal('add')"
				class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
			>
				<img :src="plusIcon" alt="plus icon" class="w-5 h-5 mr-2" />
				New Task
			</button>
			<input
				v-model="searchQuery"
				type="text"
				placeholder="Search tasks..."
				class="px-4 py-2 border rounded-md w-64"
			/>
		</div>

		<div class="w-full max-w-4xl bg-white shadow-md rounded-lg">
			<table class="w-full border-collapse border border-gray-300">
				<thead>
					<tr class="bg-blue-500 text-white">
						<th
							v-for="col in [
								'title',
								'description',
								'priority',
								'status',
								'action',
							]"
							:key="col"
							@click="sortBy(col)"
							:class="[
								col === 'action' ? 'text-center' : 'text-left',
								'px-4 py-3 cursor-pointer select-none',
							]"
						>
							<div class="flex items-center justify-between">
								<span>{{ col.charAt(0).toUpperCase() + col.slice(1) }}</span>
								<span v-if="sortKey === col">
									<img
										:src="sortOrder === 'asc' ? upIcon : downIcon"
										alt="Sort icon"
										class="w-4 h-4"
									/>
								</span>
							</div>
						</th>
					</tr>
				</thead>
				<tbody v-if="tasks.length > 0">
					<tr
						v-for="task in tasks"
						:key="task.id"
						class="border hover:bg-gray-100"
					>
						<td class="px-4 py-2">{{ task.title }}</td>
						<td class="px-4 py-2">{{ task.description }}</td>
						<td class="px-4 py-2">
							<span
								class="px-2 py-1 text-xs font-semibold text-white rounded-full capitalize"
								:class="{
									'bg-green-500': task.priority === 'low',
									'bg-yellow-500': task.priority === 'medium',
									'bg-red-500': task.priority === 'high',
								}"
							>
								{{ task.priority }}
							</span>
						</td>
						<td class="px-4 py-2">
							<span
								class="px-2 py-1 text-xs font-semibold text-white rounded-full capitalize"
								:class="{
									'bg-gray-500': task.status === 'pending',
									'bg-blue-500': task.status === 'in_progress',
									'bg-green-500': task.status === 'completed',
								}"
							>
								{{ task.status.replace("_", " ") }}
							</span>
						</td>
						<td class="px-4 py-2 text-center">
							<button @click="openModal('edit', task)" class="mr-4">
								<img :src="editIcon" class="w-5 h-5" />
							</button>
							<button @click="openModal('delete', task)">
								<img :src="deleteIcon" class="w-5 h-5" />
							</button>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr>
						<td
							colspan="5"
							class="text-center py-4 text-gray-500 font-semibold"
						>
							No records found
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div
			class="flex flex-wrap justify-between items-center w-full max-w-4xl mt-4"
		>
			<div class="flex items-center">
				<label class="mr-2 text-gray-700">Show:</label>
				<select
					v-model="itemsPerPage"
					class="px-2 bg-white py-2 border rounded-md"
				>
					<option :value="5">5</option>
					<option :value="10">10</option>
					<option :value="20">20</option>
					<option :value="50">50</option>
					<option :value="100">100</option>
				</select>
			</div>

			<div class="flex items-center space-x-2">
				<button
					@click="changePage(currentPage - 1)"
					:disabled="currentPage === 1"
					class="px-4 py-2 bg-blue-500 text-white rounded-md disabled:opacity-50 hover:bg-blue-600 transition"
				>
					Prev
				</button>
				<span class="text-gray-700">
					Page {{ currentPage }} of
					{{ meta !== undefined ? meta.last_page : 1 }}
				</span>
				<button
					@click="changePage(currentPage + 1)"
					:disabled="currentPage === meta.last_page"
					class="px-4 py-2 bg-blue-500 text-white rounded-md disabled:opacity-50 hover:bg-blue-600 transition"
				>
					Next
				</button>
			</div>
		</div>

		<TaskModal
			v-if="showModal"
			:type="modalType"
			:taskForm="taskForm"
			:onClose="closeModal"
			:onSave="saveTask"
			:onDelete="deleteTask"
		/>
	</div>
</template>
