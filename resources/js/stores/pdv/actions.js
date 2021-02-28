import api from '~/config/libs/axios'

export function getProducts({ state, commit }) {
	commit("setLoadingProducts", true)
	let params = {
		per_page: 15,
		includes: ["tenant"],
		fields: ["*"],
		order_by: ["name", "desc"],
		filters: {
			where: {
				name: {
					like: "%bike%"
				}, price: {
					">=": 2100
				}
			},
			where_in: {
				id: [1]
			},
			where_not_in: {
				id: [3]
			}
		}
	}
	api.post("/admin/json-api/produtos", params, { retries: 3 }).then(({ data }) => {
		commit('setProducts', data)
		commit("setLoadingProducts", false)
	})
}