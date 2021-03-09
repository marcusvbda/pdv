<template>
    <el-dialog
        id="expenses"
        top="10px"
        :visible.sync="dialogVisible"
        width="80%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
        title="Lançamentos de Caixa"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <div class="row">
            <div class="col-12">
                <el-tabs type="border-card" v-model="tabs" v-if="dialogVisible">
                    <el-tab-pane label="Lançamentos deste Caixa" name="list">
                        <expenses-list v-if="tabs == 'list'" />
                    </el-tab-pane>
                    <el-tab-pane label="Novo Lançamento" name="crud">
                        <crud-expense @finish="tabs = 'list'" />
                    </el-tab-pane>
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
        'crud-expense': require('./-crud-expense.vue').default,
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