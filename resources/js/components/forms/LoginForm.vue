<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import showIcon from "@/assets/icons/show-password.svg";
import hideIcon from "@/assets/icons/hide-password.svg";

const router = useRouter();

const model = ref({
	email: "",
	password: "",
});

const isLoading = ref(false);
const errorMessage = ref("");
const errors = ref({});
const showPassword = ref(false);

const onLogin = async () => {
	errorMessage.value = "";
	errors.value = {};
	isLoading.value = true;

	try {
		const response = await axios.post("/api/login", model.value);
		localStorage.setItem("token", response.data.token);
		window.dispatchEvent(new Event("storage"));
		router.push({ name: "home" });
	} catch (error) {
		if (error.response?.status === 422) {
			errors.value = error.response.data.errors || {};
		} else {
			errorMessage.value = error.response?.data?.message || "Login failed.";
		}
	} finally {
		isLoading.value = false;
	}
};

const clearError = (field) => {
	if (errors.value[field]) {
		errors.value[field] = null;
	}
};

const togglePassword = () => {
	showPassword.value = !showPassword.value;
};

const goToRegister = () => {
	router.push({ name: "register" });
};
</script>

<template>
	<div class="min-h-screen flex items-center justify-center bg-gray-100">
		<div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-2xl font-bold text-center mb-4">Login</h2>

			<form @submit.prevent="onLogin">
				<div class="mb-4">
					<label class="block text-gray-700">Email</label>
					<input
						v-model="model.email"
						@input="clearError('email')"
						type="email"
						placeholder="Enter your email address"
						required
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2"
						:class="
							errors.email
								? 'border-red-500 focus:ring-red-400'
								: 'focus:ring-blue-400'
						"
					/>
					<p v-if="errors.email" class="text-red-500 text-sm mt-1">
						{{ errors.email[0] }}
					</p>
				</div>

				<div class="mb-4 relative">
					<label class="block text-gray-700">Password</label>
					<div class="relative">
						<input
							v-model="model.password"
							@input="clearError('password')"
							:type="showPassword ? 'text' : 'password'"
							placeholder="Enter your password"
							required
							class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 pr-10"
							:class="
								errors.password
									? 'border-red-500 focus:ring-red-400'
									: 'focus:ring-blue-400'
							"
						/>

						<button
							type="button"
							@click="togglePassword"
							class="absolute inset-y-0 right-2 flex items-center px-2"
						>
							<img
								:src="showPassword ? hideIcon : showIcon"
								alt="Toggle Password"
								class="w-5 h-5"
							/>
						</button>
					</div>
					<p v-if="errors.password" class="text-red-500 text-sm mt-1">
						{{ errors.password[0] }}
					</p>
				</div>

				<p v-if="errorMessage" class="text-red-500 text-sm mb-3">
					{{ errorMessage }}
				</p>

				<button
					type="submit"
					:disabled="isLoading"
					class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition disabled:opacity-50"
				>
					{{ isLoading ? "Logging in..." : "Login" }}
				</button>

				<p class="text-center text-sm text-gray-600 mt-4">
					Don't have an account?
					<button
						type="button"
						@click="goToRegister()"
						class="text-blue-500 hover:underline"
					>
						Create an account
					</button>
				</p>
			</form>
		</div>
	</div>
</template>
