<template>
    <div id="expense-list">
        <template v-if="list.data.length || cashier.initial_balance">
            <div class="d-flex flex-row mb-3">
                <div class="d-flex justify-content-start align-items-center">
                    <span class="d-flex flex-row align-items-center f-12"> <span v-html="$getEnabledIcons(true)" class="mr-1" />Entrada </span>
                    <span class="d-flex flex-row align-items-center f-12 ml-3"> <span v-html="$getEnabledIcons(false)" class="mr-1" />Saida </span>
                </div>
                <div class="d-flex align-items-center ml-auto">
                    <span class="mr-2">Total : {{ list.total }}</span>
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        :pager-count="5"
                        :page-size="1"
                        :total="list.last_page"
                        @current-change="getExpenses"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="f-12 w-10">#</th>
                                <th class="f-12">Descrição</th>
                                <th class="f-12">Valor</th>
                                <th class="f-12">Tipo</th>
                                <th class="f-12 w-10" v-if="!isOnlyView" />
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="cashier.initial_balance">
                                <tr class="expense-row cash_in">
                                    <td class="f-12">-</td>
                                    <td class="f-12">Saldo Inicial de Caixa</td>
                                    <td class="f-12">{{ cashier.initial_balance.currency() }}</td>
                                    <td class="f-12">Entrada</td>
                                    <td class="f-12" v-if="!isOnlyView" />
                                </tr>
                            </template>
                            <tr v-for="(row, i) in list.data" :key="i" :class="`expense-row ${row.type}`">
                                <td class="f-12">{{ row.code }}</td>
                                <td class="f-12">{{ row.data.description }}</td>
                                <td class="f-12">{{ row.data.value.currency() }}</td>
                                <td class="f-12">{{ row.f_type }}</td>
                                <td class="f-12" v-if="!isOnlyView">
                                    <el-button-group size="mini">
                                        <el-button type="danger" size="mini" icon="el-icon-error" @click="destroy(row)" />
                                    </el-button-group>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
        <p class="text-center p-4" v-else>Nenhuma Despesa Registrada ...</p>
    </div>
</template>
<script>
const getInitialList = () => ({
    current_page: 0,
    data: [],
    last_page: 0,
})
export default {
    props: ['only_view'],
    data() {
        return {
            list: getInitialList(),
            loading: true,
        }
    },
    computed: {
        cashier() {
            return this.$store.state.cashier
        },
        isOnlyView() {
            return this.only_view != undefined
        },
    },
    created() {
        this.list = getInitialList()
        this.getExpenses(++this.list.current_page)
    },
    methods: {
        destroy(row) {
            this.$confirm('Excluir este lançamento  ?', 'Confirmação').then(() => {
                let loading = this.$loading({ text: 'Excluindo Lançamento ...' })
                this.$store.dispatch('destroyExpense', {
                    expense_code: row.code,
                    callback: () => {
                        loading.close()
                        this.getExpenses(1)
                        this.$message.success('Lançamento excluido com sucesso !!!')
                    },
                })
            })
        },
        getExpenses(page) {
            this.$store.dispatch('getExpenses', {
                page,
                callback: (data) => {
                    this.list = data
                    this.loading = false
                },
            })
        },
    },
}
</script>
<style lang="scss">
#expense-list {
    .expense-row {
        &.cash_in {
            td {
                background-color: #f4fff4;
            }
        }
        &.cash_out {
            td {
                background-color: #ffe7e7;
            }
        }
    }
}
</style>