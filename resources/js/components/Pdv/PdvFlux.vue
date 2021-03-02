<template>
    <div id="pdv-flux">
        <div class="row">
            <product-list />
        </div>
    </div>
</template>
<script>
import pdvStore from '~/stores/pdv'
export default {
    props: [],
    store: pdvStore,
    components: {
        'product-list': require('./products/-product_list.vue').default,
    },
}
</script>
<style lang="scss" scoped>
#pdv-flux {
    /*  */
}
</style>