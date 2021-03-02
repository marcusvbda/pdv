<template>
    <div v-loading="loading">produto {{ products }}</div>
</template>
<script>
export default {
    props: ['category'],
    created() {
        this.fetchProducts()
    },
    computed: {
        loading() {
            return this.$store.state.loading_products[this.category.id]
        },
        products() {
            return this.category.products
        },
    },
    methods: {
        fetchProducts() {
            this.$store.dispatch('getProducts', this.category.id)
        },
    },
}
</script>