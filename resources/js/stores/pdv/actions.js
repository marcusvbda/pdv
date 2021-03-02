import api from '~/config/libs/axios'
const models = {
	product_group: '\\App\\Http\\Models\\ProductGroup',
	product: '\\App\\Http\\Models\\Product',
}

export function getCategories({ state, commit }) {
	commit("setLoadingCategories", true)
	let params = {
		model: models.product_group,
		per_page: 99999
	}
	api.post("/vstack/json-api", params, { retries: 3 }).then(({ data }) => {
		commit('setCategories', data.data)
		commit("setLoadingCategories", false)
	})
}

export function getProducts({ state, commit }, category_id) {
	commit("setLoadingProducts", { category_id, value: true })
	let params = {
		model: models.product,
		per_page: 15,
		filters: {
			order_by: ["name", "desc"],
			where: {
				product_group_id: {
					"=": category_id
				}
			}
		}
	}
	api.post("/vstack/json-api", params, { retries: 3 }).then(({ data }) => {
		commit('setCategoryProducts', { category_id, data })
		commit("setLoadingProducts", { category_id, value: false })
	})
}
