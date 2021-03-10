// import Vue from 'vue'

export function setInitialStates(state, payload) {
	this.commit("setCashier", payload.cashier)
	state.permissions = payload.permissions
	this.dispatch("getPaymentMethods")
}

export function setCashier(state, payload) {
	state.cashier = payload
}

export function setPaymentMethods(state, payload) {
	state.payment_methods = payload
}
