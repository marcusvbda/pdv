<template>
    <div id="proof">
        <div class="container">
            <div class="card-proof d-flex flex-column" id="section-to-print">
                <header>
                    <div class="container-fluid">
                        <p class="mb-1 text-center">
                            <b>{{ sale.polo.name }}</b>
                        </p>
                        <p class="mb-1 text-center">{{ sale.polo.complete_address }}</p>
                        <p class="mb-1 text-center">
                            CNPJ : {{ sale.polo.data.doc }} | Email : {{ sale.polo.data.email }} | Telefone : {{ sale.polo.data.phone }}
                        </p>
                    </div>
                </header>
                <el-divider class="my-2" />
                <header>
                    <div class="container-fluid">
                        <p class="mb-0 text-center">
                            <b>Extrato de Venda N° #{{ sale.code }}</b>
                        </p>
                        <p class="mb-0 text-center">
                            <b>Cupom Fiscal Eletrônico</b>
                        </p>
                    </div>
                </header>
                <el-divider class="my-2" />
                <header>
                    <div class="container-fluid">
                        <p class="mb-0"><b>CPF/CNPJ do Consumidor : </b> {{ doc_customer }}</p>
                        <p class="mb-0"><b>Nome/Razão do Consumidor : </b>{{ name_customer }}</p>
                    </div>
                </header>
                <el-divider class="my-2" />
                <article class="flex-grow-1">
                    <div class="container-fluid">
                        <table class="w-100">
                            <tr>
                                <th class="f-12">#</th>
                                <th class="f-12">NOME</th>
                                <th class="f-12">UNIT</th>
                                <th class="f-12">QTDE</th>
                                <th class="f-12">DESC</th>
                                <th class="f-12">ACR</th>
                                <th class="f-12">STOTAL</th>
                            </tr>
                            <tr v-for="(it, i) in items" :key="i">
                                <td class="f-12">{{ it.product.code }}</td>
                                <td class="f-12">{{ it.product.name }}</td>
                                <td class="f-12">{{ it.product.price.currency() }}</td>
                                <td class="f-12">{{ it.qty }}</td>
                                <td class="f-12">{{ it.disccount.currency() }}</td>
                                <td class="f-12">{{ it.addition.currency() }}</td>
                                <td class="f-12">{{ it.subtotal.currency() }}</td>
                            </tr>
                        </table>
                    </div>
                </article>
                <el-divider class="my-2" />
                <footer>
                    <div class="container-fluid">
                        <p class="mb-0"><b>Método de Pagamento : </b> {{ payment.payment_method.name }}</p>
                        <p class="mb-0"><b>Total da Compra : </b>{{ payment.total.currency() }}</p>
                        <p class="mb-0"><b>Total Pago : </b>{{ payment.paid.currency() }}</p>
                        <p class="mb-0"><b>Troco : </b>{{ payment.payback.currency() }}</p>
                        <div class="row mt-2" v-if="payment.comment">
                            <div class="col-12">
                                <p class="mb-0 text-center"><b>Comentário : </b> {{ payment.comment }}</p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['sale'],
    computed: {
        customer() {
            return this.sale.data.sale.customer ?? {}
        },
        payment() {
            return this.sale.data.payment ?? {}
        },
        items() {
            return this.sale.data.sale.items ?? []
        },
        doc_customer() {
            return this.customer.doc ?? 'Sem CPF/CNPJ'
        },
        name_customer() {
            return this.customer.name ?? 'Consumidor Padrão'
        },
    },
}
</script>
<style lang="scss">
#proof {
    font-size: 12px;
    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        .card-proof {
            height: 100%;
            width: 600px;
            background-color: #fffcef;
            border: 1px solid #e1e1e1;
            padding: 30px 0;
        }
    }
}
@media print {
    body * {
        visibility: hidden;
    }
    #section-to-print,
    #section-to-print * {
        visibility: visible;
    }
    #section-to-print {
        width: 100% !important;
        background-color: white;
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>