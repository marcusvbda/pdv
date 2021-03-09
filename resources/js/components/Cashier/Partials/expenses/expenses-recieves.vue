<template>
    <el-dialog
        id="expenses"
        top="10px"
        :visible.sync="dialogVisible"
        width="50%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
        title="Despesas e Recebimentos no Caixa"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <div class="row">
            <div class="col-12">
                <el-tabs type="border-card" v-model="tabs">
                    <el-tab-pane label="Lançamentos deste Caixa" name="list">
                        <expenses-list />
                    </el-tab-pane>
                    <el-tab-pane label="Novo Lançamento" name="crud">Novo Lançamento</el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </el-dialog>
</template>
<script>
export default {
    props: [],
    components: {
        'expenses-list': require('./-expenses-list.vue').default,
    },
    data() {
        return {
            dialogVisible: false,
            tabs: 'list',
        }
    },
    computed: {
        cashier() {
            return this.$store.state.cashier
        },
        permissions() {
            return this.$store.state.permissions
        },
    },
    methods: {
        open() {
            this.dialogVisible = true
            this.loading = true
            this.tabs = 'list'
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