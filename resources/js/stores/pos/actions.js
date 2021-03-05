import api from '~/config/libs/axios'

export function getCategories({ state, commit }) {
	commit("setLoadingCategories", true)
	let params = {
		model: state.models.product_group
	}
	api.post("/vstack/json-api", params, { retries: 3 }).then(({ data }) => {
		commit('setCategories', data)
		commit("setLoadingCategories", false)
	})
}