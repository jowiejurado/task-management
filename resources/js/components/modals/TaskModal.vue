<script setup>
import { defineEmits, computed } from "vue";

const props = defineProps({
	type: String,
	taskForm: Object,
	onClose: Function,
	onSave: Function,
	onDelete: Function,
});

const isDeleteModal = computed(() => props.type === "delete");
</script>

<template>
	<div
		class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
	>
		<div class="bg-white p-6 rounded-lg shadow-lg w-96">
			<h2 class="text-xl font-semibold mb-4">
				{{
					type === "add"
						? "Add New Task"
						: type === "edit"
						? "Edit Task"
						: "Delete Task"
				}}
			</h2>

			<form v-if="!isDeleteModal" @submit.prevent="onSave">
				<div class="mb-4">
					<label class="block text-sm font-medium text-gray-700">Title</label>
					<input
						v-model="taskForm.title"
						type="text"
						required
						class="w-full p-2 border rounded"
					/>
				</div>

				<div class="mb-4">
					<label class="block text-sm font-medium text-gray-700"
						>Description</label
					>
					<textarea
						v-model="taskForm.description"
						required
						class="w-full p-2 border rounded"
					></textarea>
				</div>

				<div class="mb-4">
					<label class="block text-sm font-medium text-gray-700"
						>Priority</label
					>
					<select
						v-model="taskForm.priority"
						required
						class="w-full p-2 border rounded bg-white"
					>
						<option value="low">Low</option>
						<option value="medium">Medium</option>
						<option value="high">High</option>
					</select>
				</div>

				<div class="mb-4">
					<label class="block text-sm font-medium text-gray-700">Status</label>
					<select
						v-model="taskForm.status"
						required
						class="w-full p-2 border rounded bg-white"
					>
						<option value="pending">Pending</option>
						<option value="in_progress">In Progress</option>
						<option value="completed">Completed</option>
					</select>
				</div>

				<div class="flex justify-end space-x-2">
					<button
						type="button"
						@click="onClose"
						class="px-4 py-2 bg-gray-500 text-white rounded-md"
					>
						Cancel
					</button>
					<button
						type="submit"
						class="px-4 py-2 bg-blue-500 text-white rounded-md"
					>
						{{ type === "add" ? "Save" : "Update" }}
					</button>
				</div>
			</form>

			<div v-else>
				<p class="mb-4">Are you sure you want to delete this task?</p>
				<div class="flex justify-end space-x-2">
					<button
						@click="onClose"
						class="px-4 py-2 bg-gray-500 text-white rounded-md"
					>
						Cancel
					</button>
					<button
						@click="onDelete"
						class="px-4 py-2 bg-red-500 text-white rounded-md"
					>
						Delete
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
