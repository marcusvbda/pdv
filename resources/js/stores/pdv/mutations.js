import Vue from 'vue'

export function setLoadingCategories(state, payload) {
	state.categories_loading = payload
}

export function setCategories(state, payload) {
	state.categories = payload
}

export function setLoadingProducts(state, payload) {
	Vue.set(state.loading_products, payload.category_id, payload.value)
}

export function setCategoryProducts(state, payload) {
	let cat = state.categories.find(x => x.id == payload.category_id)
	if (cat) {
		Vue.set(cat, 'products', payload.data)
	}
}
