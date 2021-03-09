<template>
    <el-dialog
        :close-on-press-escape="false"
        :close-on-click-modal="false"
        id="payment-modal"
        :visible.sync="dialogVisible"
        width="40%"
        heigth="500px"
        :modal-append-to-body="true"
        :append-to-body="true"
    >
        <div v-if="selected_payment_method">
            <div class="row mb-2">
                <div class="col-12">
                    <label>À Receber</label>
                    <input class="form-control form-control-lg" :value="total.currency()" disabled />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label>Método de Pagamento</label>
                    <select class="form-control form-control-lg" v-model="form.method">
                        <option v-for="(m, i) in paymentMethods" :key="i" :value="m.id">{{ m.name }}</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label>Valor Recebido</label>
                    <currency-input class="form-control form-control-lg" currency="BRL" :auto-decimal-mode="true" v-model="form.paid" :disabled="!isMoney" />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label>Troco</label>
                    <input class="form-control form-control-lg" disabled :value="payback.currency()" />
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-primary btn-block btn-lg" @click="finish" :disabled="!canFinish">Efetuar Pagamento</button>
                </div>
            </div>
        </div>
    </el-dialog>
</template>
<script>
export default {
    props: ['sale'],
    data() {
        return {
            dialogVisible: false,
            form: {
                paid: this.total,
                method: null,
            },
        }
    },
    watch: {
        'form.method'(val) {
            if (this.isMoney != 'money') {
                this.form.paid = this.total
            }
        },
    },
    computed: {
        canFinish() {
            return this.payback >= 0 && this.form.paid >= this.total && this.form.method
        },
        isMoney() {
            return this.selected_payment_method.type == 'money'
        },
        selected_payment_method() {
            return this.paymentMethods.find((x) => x.id == this.form.method)
        },
        total() {
            return this.sale.items.map((x) => x.subtotal).reduce((a, b) => Number(a) + Number(b), 0)
        },
        payback() {
            return this.form.paid - this.total
        },
        paymentMethods() {
            return this.$store.state.payment_methods
        },
    },
    methods: {
        finish() {
            let loading = this.$loading({ text: 'Finalizando Venda ...' })
            this.$store.dispatch('storeSale', {
                sale: this.sale,
                payment: { ...this.form, payback: this.payback, payment_method: this.selected_payment_method, total: this.total },
                callback: (data) => {
                    loading.close()
                    this.openProof(data)
                    this.$emit('finished')
                },
            })
        },
        openProof(sale) {
            window.open(`/admin/vendas/${sale.code}/comprovante`, '_blank')
        },
        open() {
            this.dialogVisible = true
            this.form.method = this.paymentMethods[0]?.id ?? null
            this.form.paid = this.total
        },
    },
}
</script>
<style lang="scss">
#payment-modal {
    label {
        font-size: 20px;
    }
    .form-control-lg {
        height: 50px;
        font-size: 20px;
    }
    .el-dialog__body {
        padding: 25px;
    }
}
</style>