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

export function getPaymentMethods({ state, commit }) {
	api.post('/vstack/json-api', { model: state.models.payment_method }, { retries: 3 }).then(({ data }) => {
		commit("setPaymentMethods", data)
	})
}

export function storeSale({ state, commit }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/store`, { sale: payload.sale, payment: payload.payment }, { retries: 3 }).then(({ data }) => {
		payload.callback(data.sale)
	})
}