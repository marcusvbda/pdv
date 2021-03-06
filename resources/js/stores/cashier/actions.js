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

export function storeSale({ state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/store-sale`, { sale: payload.sale, payment: payload.payment }, { retries: 3 }).then(({ data }) => {
		payload.callback(data.sale)
	})
}

export function getSales({ state, commit }, payload) {
	let params = {
		model: state.models.sale,
		page: payload.page,
		per_page: 10,
		order_by: ['id', 'desc'],
		filters: {
			where: [
				["cashier_id", "=", state.cashier.id]
			]
		}

	}
	api.post('/vstack/json-api', params).then(({ data }) => {
		payload.callback(data)
	})
}

export function cancelStore({ state }, payload) {
	api.delete(`/admin/caixas/${state.cashier.code}/vendas/${payload.sale.code}/cancel`).then(({ data }) => {
		payload.callback(data)
	})
}

export function viewSaleProof({ state }, payload) {
	window.open(`/admin/caixas/${state.cashier.code}/vendas/${payload.code}/comprovante`, '_blank')
}

export function getExpenses({ state, commit }, payload) {
	let params = {
		model: state.models.cashier_expense,
		page: payload.page,
		per_page: 10,
		order_by: ['id', 'desc'],
		filters: {
			where: [
				["cashier_id", "=", state.cashier.id]
			]
		}

	}
	api.post('/vstack/json-api', params).then(({ data }) => {
		payload.callback(data)
	})
}

export function getCashierDetails({ state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/details`).then(({ data }) => {
		payload.callback(data)
	})
}

export function storeExpense({ state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/store-expense`, payload.form).then(({ data }) => {
		payload.callback(data)
	})
}

export function destroyExpense({ state }, payload) {
	api.delete(`/admin/caixas/${state.cashier.code}/expenses/${payload.expense_code}/destroy`).then(({ data }) => {
		payload.callback(data)
	})
}

export function finishCashier({ state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/finish`).then(({ data }) => {
		payload.callback(data)
	})
}

export function getCashier({ state, commit }, payload) {
	let params = {
		model: state.models.cashier,
		includes: ["user"],
		filters: {
			where: [
				["id", "=", payload.cashier_id]
			]
		}

	}
	api.post('/vstack/json-api', params).then(({ data }) => {
		commit("setCashier", data[0])
		payload.callback(data)
	})
}

export function getCashierConference({ commit, state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/get-conference`).then(({ data }) => {
		payload.callback(data)
	})
}


export function storeCashierConference({ commit, state }, payload) {
	api.post(`/admin/caixas/${state.cashier.code}/store-conference`, payload.current_conference).then(({ data }) => {
		payload.callback(data)
	})
}
