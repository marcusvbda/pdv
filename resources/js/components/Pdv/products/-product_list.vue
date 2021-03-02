<template>
    <div class="col-md-4 col-sm-12" v-loading="loading_categories">
        <el-tabs type="border-card">
            <el-tab-pane v-for="(c, i) in categories" :key="i" :label="c.name">
                <cat-product :category="c" />
            </el-tab-pane>
        </el-tabs>
    </div>
</template>
<script>
export default {
    components: {
        'cat-product': require('./-cat-product.vue').default,
    },
    computed: {
        loading_categories() {
            return this.$store.state.categories_loading
        },
        categories() {
            return this.$store.state.categories
        },
    },
    created() {
        this.fetchCategories()
    },
    methods: {
        fetchCategories() {
            this.$store.dispatch('getCategories')
        },
    },
}
</script>