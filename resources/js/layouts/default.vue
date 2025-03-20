<template>
	<div class="min-h-screen flex flex-col">
		<header class="bg-blue-600 text-white p-4 flex justify-between">
			<h1 class="text-lg">Task Management System</h1>
			<button @click="logout" class="text-white px-4 py-2 rounded">
				Log out
			</button>
		</header>
		<main class="flex-1 min-h-100 flex items-center justify-center bg-gray-100">
			<slot />
		</main>
	</div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const logout = async () => {
	const response = await axios.post("/api/logout");

	if (response.status === 204) {
		localStorage.removeItem("token");
		window.dispatchEvent(new Event("storage"));
		router.push({ name: "login" });
	}
};
</script>
