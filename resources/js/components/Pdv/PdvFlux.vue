<template>
    <div id="pdv-flux">
        <div class="row">
            <div class="col-md-4 col-sm-12" v-loading="loading_products">{{ products.data }}</div>
        </div>
    </div>
</template>
<script>
import pdvStore from '~/stores/pdv'
export default {
    props: [],
    store: pdvStore,
    computed: {
        loading_products() {
            return this.$store.state.products_loading
        },
        products() {
            return this.$store.state.products
        },
    },
    created() {
        this.$nextTick(() => {
            this.fetchData()
        })
    },
    methods: {
        fetchData() {
            this.fetchProducts()
        },
        fetchProducts() {
            this.$store.dispatch('getProducts')
        },
    },
}
</script>
<style lang="scss" scoped>
#pdv-flux {
    /*  */
}
</style>