import api from '~/config/libs/axios'

export function getProducts({ state, commit }) {
	commit("setLoadingProducts", true)
	let params = {
		per_page: 15
		// includes: ["tenant"]
		// name: {
		// 	"like": "%bike%",
		// },
		// price: {
		// 	">=": 2100
		// }
	}
	api.post("/admin/produtos/json", params, { retries: 3 }).then(({ data }) => {
		commit('setProducts', data)
		commit("setLoadingProducts", false)
	})
}