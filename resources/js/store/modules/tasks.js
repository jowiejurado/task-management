import { useToast } from "vue-toastification";

const toast = useToast();
const apiUrl = "api/tasks";

const state = {
	meta: {},
	list: [],
};

const getters = {
	list: (state) => state.list,
	meta: (state) => state.meta,
};

const mutations = {
	SET_LIST(state, data) {
		state.list = data;
	},
	SET_META(state, meta) {
		state.meta = meta;
	},
	SET_DATA(state, data) {
		state.model = data;
	},
	INSERT(state, data) {
		state.list.push(data);
	},
	UPDATE(state, data) {
		const listIndex = state.list.map((l) => l.id).indexOf(data.id);

		state.list.splice(listIndex, 1, data);
	},
	DELETE(state, data) {
		const listIndex = state.list.map((l) => l.id).indexOf(data.id);

		state.list.splice(listIndex, 1);
	},
};

const actions = {
	/**
	 * Fetch a paginated collection of Task models from the database
	 */
	async paginate(
		{ commit },
		{
			page = 1,
			search = null,
			itemsPerPage = 10,
			orderBy = "created_at",
			orderDirection = "desc",
		} = {}
	) {
		const response = await axios.get(apiUrl, {
			params: {
				page,
				search,
				orderBy,
				orderDirection,
				itemsPerPage,
			},
		});

		commit("SET_LIST", response.data.data);
		commit("SET_META", response.data.meta);

		return response.data.data;
	},

	/**
	 * Updates an instance of Task instance
	 */
	async update({ commit }, params) {
		const response = await axios.put(`${apiUrl}/${params.id}`, params);

		commit("UPDATE", response.data.data);

		toast.success("Task has been successfully updated!");

		return response.data.data;
	},

	/**
	 * Fetch a Task model using an id
	 */
	async show({ commit }, id) {
		const response = await axios.get(`${apiUrl}/${id}`);

		commit("SET_DATA", response.data.data);

		return response.data.data;
	},

	/**
	 * Creates a new instance of a Task Model
	 */
	async save({ commit }, params) {
		const response = await axios.post(apiUrl, params);

		commit("INSERT", response.data.data);

		toast.success("New task has been successfully created!");

		return response.data.data;
	},

	/**
	 * Delete a single Task
	 */
	async destroy({ commit }, id) {
		const response = await axios.delete(`${apiUrl}/${id}`);

		commit("DELETE", id);

		toast.success("Task has been successfully deleted!");

		return response.data.data;
	},
};

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations,
};
