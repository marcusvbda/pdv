<template>
    <el-dialog
        id="sales-modal"
        top="10px"
        :visible.sync="dialogVisible"
        width="70%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
        title="Detalhes do Caixa"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <div class="row">
            <div class="col-12">
                <el-tabs type="border-card" v-model="tabs">
                    <el-tab-pane label="Informações" name="detail"> Detalhes </el-tab-pane>
                    <el-tab-pane label="Listagem de Vendas" name="salelist">
                        <sale-list />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </el-dialog>
</template>
<script>
export default {
    props: [],
    data() {
        return {
            dialogVisible: false,
            tabs: 'detail',
        }
    },
    components: {
        'sale-list': require('./-sale-list.vue').default,
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
            this.tabs = 'detail'
            this.loading = true
        },
    },
}
</script>
<style lang="scss">
#sales-modal {
    .el-dialog__body {
        padding: 25px;
    }
    .sale-row {
        &.paid {
            td {
                background-color: #f4fff4;
            }
        }
        &.canceled {
            td {
                background-color: #ffe7e7;
            }
        }
    }
}
</style>