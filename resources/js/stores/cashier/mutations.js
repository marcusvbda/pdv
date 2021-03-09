// import Vue from 'vue'

export function setInitialStates(state, payload) {
	state.cashier = payload.cashier
	state.permissions = payload.permissions
	this.dispatch("getPaymentMethods")
}

export function setPaymentMethods(state, payload) {
	state.payment_methods = payload
}