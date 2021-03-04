<template>
    <div id="pos" ref="pos">
        <avaliable-pos v-if="is_avaliable" />
    </div>
</template>
<script>
import posStore from '~/stores/pos'
export default {
    props: ['_cashier', '_permissions'],
    store: posStore,
    data() {
        return {
            is_avaliable: true,
        }
    },
    components: {
        'avaliable-pos': require('./Partials/-avaliable-pos.vue').default,
    },
    created() {
        this.$nextTick(() => {
            this.setInitialStates()
        })
    },
    methods: {
        setInitialStates() {
            this.$store.commit('setInitialStates', {
                permissions: this._permissions,
                cashier: this._cashier,
            })
        },
    },
}
</script>
<style lang="scss">
#pos {
    //
}
</style>