<template>
    <div v-loading="loading">
        <div class="row d-flex align-items-center">
            <div class="col-md-6">
                <h2>
                    <b>Saldo : {{ detail.balance.currency() }} </b>
                </h2>
                <cashier-infos />
            </div>
            <div class="col-md-6">
                <pie-chart :data="detail.graph_data" prefix="R$ " />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: true,
            detail: {
                balance: 0,
                graph_data: {},
            },
        }
    },
    components: {
        'cashier-infos': require('../../extra/-cashier-infos.vue').default,
    },
    computed: {
        cashier() {
            return this.$store.state.cashier
        },
        permissions() {
            return this.$store.state.permissions
        },
    },
    created() {
        this.getDetails()
    },
    methods: {
        getDetails() {
            this.loading = true
            this.$store.dispatch('getCashierDetails', {
                callback: (data) => {
                    this.detail = data
                    this.loading = false
                },
            })
        },
    },
}
</script>