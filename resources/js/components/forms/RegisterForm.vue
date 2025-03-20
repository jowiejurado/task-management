<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import showIcon from "@/assets/icons/show-password.svg";
import hideIcon from "@/assets/icons/hide-password.svg";

const router = useRouter();

const model = ref({
	first_name: "",
	last_name: "",
	email: "",
	password: "",
	confirm_password: "",
});

const isLoading = ref(false);
const errorMessage = ref("");
const errors = ref({});
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const onRegister = async () => {
	errorMessage.value = "";
	errors.value = {};
	isLoading.value = true;

	if (model.value.password !== model.value.confirm_password) {
		errors.value.confirm_password = ["Passwords do not match."];
		isLoading.value = false;
		return;
	}

	try {
		await axios.post("/api/register", model.value);
		router.push({ name: "login" });
	} catch (error) {
		if (error.response?.status === 422) {
			errors.value = error.response.data.errors || {};
		} else {
			errorMessage.value =
				error.response?.data?.message || "Registration failed.";
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

const toggleConfirmPassword = () => {
	showConfirmPassword.value = !showConfirmPassword.value;
};

const goToLogin = () => {
	router.push({ name: "login" });
};
</script>

<template>
	<div class="min-h-screen flex items-center justify-center bg-gray-100">
		<div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
			<h2 class="text-2xl font-bold text-center mb-4">Register</h2>

			<form @submit.prevent="onRegister" autocomplete="off">
				<div class="mb-4">
					<label class="block text-gray-700">First Name</label>
					<input
						v-model="model.first_name"
						@input="clearError('first_name')"
						type="text"
						placeholder="Enter your first name"
						required
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2"
						:class="
							errors.first_name
								? 'border-red-500 focus:ring-red-400'
								: 'focus:ring-blue-400'
						"
					/>
					<p v-if="errors.first_name" class="text-red-500 text-sm mt-1">
						{{ errors.first_name[0] }}
					</p>
				</div>

				<div class="mb-4">
					<label class="block text-gray-700">Last Name</label>
					<input
						v-model="model.last_name"
						@input="clearError('last_name')"
						type="text"
						placeholder="Enter your last name"
						required
						class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2"
						:class="
							errors.last_name
								? 'border-red-500 focus:ring-red-400'
								: 'focus:ring-blue-400'
						"
					/>
					<p v-if="errors.last_name" class="text-red-500 text-sm mt-1">
						{{ errors.last_name[0] }}
					</p>
				</div>

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

				<div class="mb-4 relative">
					<label class="block text-gray-700">Confirm Password</label>
					<div class="relative">
						<input
							v-model="model.confirm_password"
							@input="clearError('confirm_password')"
							:type="showConfirmPassword ? 'text' : 'password'"
							placeholder="Confirm your password"
							required
							class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 pr-10"
							:class="
								errors.confirm_password
									? 'border-red-500 focus:ring-red-400'
									: 'focus:ring-blue-400'
							"
						/>
						<button
							type="button"
							@click="toggleConfirmPassword"
							class="absolute inset-y-0 right-2 flex items-center px-2"
						>
							<img
								:src="showConfirmPassword ? hideIcon : showIcon"
								alt="Toggle Password"
								class="w-5 h-5"
							/>
						</button>
					</div>
					<p v-if="errors.confirm_password" class="text-red-500 text-sm mt-1">
						{{ errors.confirm_password[0] }}
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
					{{ isLoading ? "Registering..." : "Register" }}
				</button>

				<p class="text-center text-sm text-gray-600 mt-4">
					Already have an account?
					<button
						type="button"
						@click="goToLogin"
						class="text-blue-500 hover:underline"
					>
						Login here
					</button>
				</p>
			</form>
		</div>
	</div>
</template>
