import api from '~/config/libs/axios'
const models = {
	products: '\\App\\Http\\Models\\Product'
}
export function getProducts({ state, commit }) {
	commit("setLoadingProducts", true)
	let params = {
		model: models.products,
		per_page: 15
	}
	api.post("/vstack/json-api", params, { retries: 3 }).then(({ data }) => {
		commit('setProducts', data)
		commit("setLoadingProducts", false)
	})
}

// let filter_example = {
// 	model: "\\App\\Http\\Models\\Product",
// 	per_page: 15,
// 	includes: ["tenant"],
// 	fields: ["*"],
// 	order_by: ["name", "desc"],
// 	filters: {
// 		where: {
// 			name: {
// 				like: "%bike%"
// 			}, price: {
// 				">=": 2100
// 			}
// 		},
// 		where_in: {
// 			id: [1]
// 		},
// 		where_not_in: {
// 			id: [3]
// 		}
// 	}
// }