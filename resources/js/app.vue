<template>
	<component :is="layout">
		<router-view />
	</component>
</template>

<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import DefaultLayout from "@/layouts/default.vue";

const route = useRoute();

const layout = computed(() => route.meta.layout || DefaultLayout);
</script>
