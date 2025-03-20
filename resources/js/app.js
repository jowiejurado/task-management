import "./bootstrap";
import { createApp } from "vue";
import router from "./router";
import store from "./store";
import app from "./app.vue";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const toastOptions = {};

createApp(app).use(Toast, toastOptions).use(router).use(store).mount("#app");
