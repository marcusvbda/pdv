<template>
    <el-dialog
        id="new-sale"
        :visible.sync="dialogVisible"
        width="98%"
        heigth="650px"
        top="20"
        :modal-append-to-body="true"
        :append-to-body="true"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
        :before-close="beforeClose"
    >
        <a href="#" class="btn-close" @click.prevent="beforeClose">
            <span class="el-icon-error" />
        </a>
        <header class="opened">
            <a href="#" class="link-label" @click.prevent="tabs = 'customers'">
                {{ !sale.customer ? 'Nova Venda Sem Cliente' : 'Nova Venda para ' + sale.customer.name }}
                <i class="el-icon-edit ml-2" />
            </a>
        </header>
        <article class="row h-100">
            <div class="col-6">
                <select-product :sale="sale" v-if="dialogVisible" />
            </div>
            <div class="col-6 d-flex flex-column pl-0 pt-3 pb-4">
                <el-tabs v-model="tabs">
                    <el-tab-pane label="Cliente" name="customers">
                        <template slot="label">
                            <span class="d-flex align-items-center"> <span class="el-icon-warning mr-1 text-danger" v-if="!sale.customer" />Cliente </span>
                        </template>
                        <select-customer v-if="dialogVisible" :sale="sale" />
                    </el-tab-pane>
                    <el-tab-pane label="Items" name="items">
                        <template slot="label">
                            <span class="d-flex align-items-center">Items ({{ totalItems }})</span>
                        </template>
                        <product-list v-if="dialogVisible" :sale="sale" />
                    </el-tab-pane>
                </el-tabs>
                <div class="container-fluid mt-auto ligth">
                    <div class="row">
                        <div class="col-12 px-0 mb-2">
                            <div class="row">
                                <div class="col-4">
                                    <span class="d-flex align-items-center justify-content-start pl-4"> <b class="mr-1">Items : </b> {{ totalItems }} </span>
                                </div>
                                <div class="col-4">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <b class="mr-1">Acréscimos : </b> {{ totalAddition.currency() }}
                                    </span>
                                </div>
                                <div class="col-4">
                                    <span class="d-flex align-items-center justify-content-end pr-4">
                                        <b class="mr-1">Descontos : </b> {{ totalDisccounts.currency() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" v-hotkey="keymap" v-if="dialogVisible">
                            <finsish-sales ref="modal_payment" :sale="sale" @finished="close" />
                            <el-button type="success" class="w-100" @click="finish" :disabled="!canFinish">
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    Finalizar venda ( F4 )
                                    <span>{{ total.currency() }}</span>
                                </div>
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </el-dialog>
</template>

<script>
const initial_sale = () => {
    return {
        items: [],
        customer: undefined,
    }
}
import Vue from 'vue'
import VueHotkey from 'v-hotkey'
Vue.use(VueHotkey)
export default {
    data() {
        return {
            dialogVisible: false,
            sale: initial_sale(),
            tabs: 'items',
        }
    },
    components: {
        'finsish-sales': require('./-finish-sales.vue').default,
        'select-product': require('./-select-product.vue').default,
        'product-list': require('./-product-list.vue').default,
        'select-customer': require('./-select-customer.vue').default,
    },
    watch: {
        'selecting.product_id'(val) {
            if (!val) this.selecting = initial_seletion_product()
        },
        'selecting.qty'(val) {
            if (parseInt(val) <= 0) this.selecting.qty = 1
        },
        'selecting.disccount'(val) {
            if (parseInt(val) < 0) this.selecting.disccount = 0
            if (parseInt(val) > 100) this.selecting.disccount = 100
        },
    },
    computed: {
        models() {
            return this.$store.state.models
        },
        canFinish() {
            return this.sale.items.length > 0
        },
        keymap() {
            return {
                f4: this.finish,
            }
        },
        totalAddition() {
            return this.sale.items.map((x) => x.addition).reduce((a, b) => Number(a) + Number(b), 0)
        },
        totalDisccounts() {
            return this.sale.items.map((x) => x.disccount).reduce((a, b) => Number(a) + Number(b), 0)
        },
        totalItems() {
            return this.sale.items.map((x) => x.qty).reduce((a, b) => Number(a) + Number(b), 0)
        },
        total() {
            return this.sale.items.map((x) => x.subtotal).reduce((a, b) => Number(a) + Number(b), 0)
        },
        isOpened() {
            return this.dialogVisible
        },
        hasContent() {
            return this.sale.items.length > 0 || this.sale.customer
        },
    },
    methods: {
        finish() {
            if (!this.canFinish) return
            this.$refs.modal_payment.open()
        },
        open() {
            this.dialogVisible = true
            this.tabs = 'items'
            this.sale = initial_sale()
        },
        close() {
            this.dialogVisible = false
            this.$emit('on-close')
        },
        beforeClose() {
            if (this.hasContent) {
                this.$confirm('Deseja abandonar esta venda ?', 'Confirmação').then(() => {
                    return this.close()
                })
            } else this.close()
        },
    },
}
</script>
<style lang="scss">
#new-sale {
    .row-btn {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 40px;
        background-color: #01345f;
        &.ligth {
            background-color: white;
        }
    }
    .el-dialog__header {
        display: none;
    }
    .el-dialog__body {
        padding: 0;
        max-height: 650px;
        height: 650px;
        overflow: hidden;
    }

    .btn-close {
        color: white;
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 48px;
    }
    header {
        &.opened {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #01345f;
            color: #ffffff;
            font-weight: bold;
            font-size: 33px;
            padding: 10px;
        }
    }

    .original_price {
        font-size: 40px;
        font-weight: bold;
    }

    .link-label {
        color: white;
        &:hover {
            text-decoration: unset;
        }
    }
}
</style>