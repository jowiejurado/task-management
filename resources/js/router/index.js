import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "@/layouts/default.vue";
import BlankLayout from "@/layouts/blank.vue";

const isAuthenticated = () => !!localStorage.getItem("token");

const routes = [
	{
		path: "/login",
		name: "login",
		component: () => import("@/pages/login.vue"),
		meta: { requiresGuest: true, layout: BlankLayout },
	},
	{
		path: "/register",
		name: "register",
		component: () => import("@/pages/register.vue"),
		meta: { requiresGuest: true, layout: BlankLayout },
	},
	{
		path: "/",
		name: "home",
		component: () => import("@/pages/index.vue"),
		meta: { requiresAuth: true, layout: DefaultLayout },
	},
];

const router = createRouter({
	history: createWebHistory(),
	routes,
});

router.beforeEach((to, from, next) => {
	if (to.meta.requiresAuth && !isAuthenticated()) {
		next({ name: "login" });
	} else if (to.meta.requiresGuest && isAuthenticated()) {
		next({ name: "home" });
	} else {
		next();
	}
});

export default router;
