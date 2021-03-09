<template>
    <el-dialog
        id="expenses"
        top="10px"
        :visible.sync="dialogVisible"
        width="80%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
        title="Fechamento de Caixa"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <div class="row">
            <div class="col-12">
                <el-tabs type="border-card" v-model="tabs" v-if="dialogVisible">
                    <el-tab-pane label="Resumo" name="details">
                        <cashier-details v-if="tabs == 'details'" />
                    </el-tab-pane>
                    <el-tab-pane label="Vendas" name="sales">
                        <sale-list v-if="tabs == 'sales'" only_view />
                    </el-tab-pane>
                    <el-tab-pane label="Lançamentos" name="expenses">
                        <expenses-list v-if="tabs == 'expenses'" only_view />
                    </el-tab-pane>
                    <el-tab-pane label="Conferência" name="conference">
                        <table-conference v-if="tabs == 'conference'" :cashier_id="cashier.id" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-end" @click="finishCashier">
                <button class="btn btn-primary px-4" type="button">Encerrar Caixa</button>
            </div>
        </div>
    </el-dialog>
</template>
<script>
export default {
    data() {
        return {
            dialogVisible: false,
            tabs: 'details',
        }
    },
    components: {
        'cashier-details': require('../sales/view_sales/-cashier-details.vue').default,
        'sale-list': require('../sales/view_sales/-sale-list.vue').default,
        'expenses-list': require('../expenses/-expenses-list.vue').default,
    },
    computed: {
        cashier() {
            return this.$store.state.cashier
        },
    },
    methods: {
        open() {
            this.dialogVisible = true
            this.tabs = 'details'
        },
        finishCashier() {
            this.$confirm('Deseja mesmo encerrar este caixar ?', 'Confirmação').then(() => {
                this.$loading({ text: 'Encerrando este caixa ...' })
                this.$store.dispatch('finishCashier', {
                    callback: () => {
                        window.location.href = '/admin/caixas'
                    },
                })
            })
        },
    },
}
</script>
<style lang="scss">
#expenses {
    .el-dialog__body {
        padding: 25px;
    }
}
</style>