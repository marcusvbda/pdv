<template>
    <el-dialog
        id="sales-modal"
        top="10px"
        :visible.sync="dialogVisible"
        width="70%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
        title="Vendas deste Caixa"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
    >
        <div class="d-flex flex-row mb-3">
            <div class="d-flex justify-content-start align-items-center">
                <span class="d-flex flex-row align-items-center f-12"> <span v-html="$getEnabledIcons(true)" class="mr-1" />Paga </span>
                <span class="d-flex flex-row align-items-center f-12 ml-3"> <span v-html="$getEnabledIcons(false)" class="mr-1" />Cancelada </span>
            </div>
            <el-pagination
                class="ml-auto"
                background
                layout="prev, pager, next"
                :pager-count="5"
                :page-size="1"
                :total="list.last_page"
                @current-change="getSales"
            />
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="f-12 w-10">#</th>
                            <th class="f-12">Cliente</th>
                            <th class="f-12">Total</th>
                            <th class="f-12">Forma de Pagto</th>
                            <th class="f-12">Data/Hora</th>
                            <th class="f-12">Status</th>
                            <th class="f-12" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sale, i) in list.data" :key="i" :class="`sale-row ${sale.status}`">
                            <td class="f-12">{{ sale.code }}</td>
                            <td class="f-12">{{ sale.data.sale.customer ? sale.data.sale.customer.name : 'Consumidor Padrão' }}</td>
                            <td class="f-12">{{ sale.data.payment.total.currency() }}</td>
                            <td class="f-12">{{ sale.data.payment.payment_method.name }}</td>
                            <td class="f-12">{{ sale.f_created_at }}</td>
                            <td class="f-12">{{ sale.f_status }}</td>
                            <td class="f-12 w-10">
                                <el-button-group size="mini">
                                    <el-button type="primary" size="mini" icon="el-icon-search" @click="seeProof(sale)" />
                                    <el-button type="danger" size="mini" icon="el-icon-error" @click="cancelSale(sale)" v-if="sale.status != 'canceled'" />
                                </el-button-group>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </el-dialog>
</template>
<script>
const getInitialList = () => ({
    current_page: 0,
    data: [],
    last_page: 0,
})
export default {
    props: [],
    data() {
        return {
            dialogVisible: false,
            list: getInitialList(),
            loading: true,
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
        seeProof(sale) {
            this.$confirm('Ver detalhadamente esta venda ?', 'Confirmação').then(() => {
                window.open(`/admin/vendas/${sale.code}/comprovante`, '_blank')
            })
        },
        cancelSale(sale) {
            this.$confirm('Cancelar esta venda ?', 'Confirmação').then(() => {
                let loading = this.$loading({ text: 'Cancelando Venda ...' })
                this.$store.dispatch('cancelStore', {
                    sale: sale,
                    callback: () => {
                        loading.close()
                        this.getSales(this.list.current_page)
                        this.$message.success('Venda cancelada com sucesso !!!')
                    },
                })
            })
        },
        open() {
            this.dialogVisible = true
            this.list = getInitialList()
            this.loading = true
            this.getSales(++this.list.current_page)
        },
        getSales(page) {
            this.$store.dispatch('getSales', {
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