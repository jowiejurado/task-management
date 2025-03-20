import axios from "axios";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Accept"] = "application/json";

const setAuthHeader = () => {
	const token = localStorage.getItem("token");
	if (token) {
		window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
	} else {
		delete window.axios.defaults.headers.common["Authorization"];
	}
};

setAuthHeader();

window.addEventListener("storage", setAuthHeader);

window.axios.interceptors.response.use(
	(response) => response,
	(error) => {
		if (error.response?.status === 401) {
			localStorage.removeItem("token");
		}
		return Promise.reject(error);
	}
);
