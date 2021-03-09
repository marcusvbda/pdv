<template>
    <div id="pos" ref="pos">
        <div id="avaliable-view" v-hotkey="keymap" v-if="cashier">
            <header class="available">{{ cashier_status }}</header>
            <section class="icon">
                <i class="el-icon-sell" />
            </section>
            <footer class="available">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="d-flex flex-column justify-content-between w-100">
                                <cashier-infos />
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 d-flex align-items-center justify-content-between">
                            <el-button-group class="w-100 d-flex">
                                <el-button class="btn-action flex-grow-1" type="info" @click="newSale">
                                    <div class="d-flex flex-column">
                                        <new-sales ref="new_sales" />
                                        <div>Nova Venda</div>
                                        <div class="mt-2">( F1 )</div>
                                    </div>
                                </el-button>
                                <el-button class="btn-action flex-grow-1" type="info" @click="saleList">
                                    <div class="d-flex flex-column">
                                        <view-sales ref="view_sales" />
                                        <div>Detalhes do Caixa</div>
                                        <div class="mt-2">( F2 )</div>
                                    </div>
                                </el-button>
                                <el-button class="btn-action flex-grow-1" type="info" @click="recieves">
                                    <div class="d-flex flex-column">
                                        <expenses-recieves ref="expenses_recieves" />
                                        <div>Despesas e Recebimentos</div>
                                        <div class="mt-2">( F3 )</div>
                                    </div>
                                </el-button>
                                <el-button class="btn-action flex-grow-1" type="info" @click="back">
                                    <div class="d-flex flex-column">
                                        <div>Sair do Frente de Caixa</div>
                                        <div class="mt-2">( F12 )</div>
                                    </div>
                                </el-button>
                                <el-button class="btn-action flex-grow-1" type="info" @click="close">
                                    <div class="d-flex flex-column">
                                        <div>Finalizar de Caixa</div>
                                        <div class="mt-2">( F13 )</div>
                                    </div>
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
<script>
import Vue from 'vue'
import VueHotkey from 'v-hotkey'
Vue.use(VueHotkey)
import cashierStore from '~/stores/cashier'
export default {
    props: ['_cashier', '_permissions'],
    store: cashierStore,
    data() {
        return {
            is_avaliable: true,
        }
    },
    components: {
        'new-sales': require('./Partials/sales/new_sales/-new-sales.vue').default,
        'view-sales': require('./Partials/sales/view_sales/-view-sales.vue').default,
        'expenses-recieves': require('./Partials/expenses/expenses-recieves.vue').default,
        'cashier-infos': require('./Partials/extra/-cashier-infos.vue').default,
    },
    computed: {
        cashier_status() {
            return this.is_avaliable ? 'Caixa Disponível' : 'Caixa Ocupado'
        },
        keymap() {
            return {
                f1: this.newSale,
                f2: this.saleList,
                f3: this.recieves,
                f12: this.back,
                f13: this.close,
            }
        },
        cashier() {
            return this.$store.state.cashier
        },
        permissions() {
            return this.$store.state.permissions
        },
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
        newSale() {
            this.$refs.new_sales.open()
        },
        saleList() {
            this.$refs.view_sales.open()
        },
        recieves() {
            this.$refs.expenses_recieves.open()
        },
        back() {
            this.$confirm('Este caixa continuará aberto, sair da página de vendas ?', 'Confirmação').then(() => {
                window.location = this.permissions.viewlist_cashiers ? '/admin/caixas' : '/admin/admin'
            })
        },
        close() {
            this.$confirm('Encerrar vendas deste caixa ?', 'Confirmação').then(() => {
                // window.location = this.permissions.viewlist_cashiers ? '/admin/caixas' : '/admin/admin'
                alert('Inicia encerramento de caixa')
            })
        },
    },
}
</script>
<style lang="scss">
#pos {
    #avaliable-view {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        header {
            &.available {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #01345f;
                color: #ffffff;
                font-weight: bold;
                font-size: 50px;
                padding: 10px 0 20px;
            }
        }
        section {
            text-align: center;
            &.icon {
                font-size: 300px;
                color: #d9d9d9;
            }
        }
        footer {
            &.available {
                font-size: 12px;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #01345f;
                color: #ffffff;
                padding: 10px 0;
                .btn-action {
                    padding: 20px;
                }
            }
        }
    }
}
</style>