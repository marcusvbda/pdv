<template>
    <div class="col-md-6 col-sm-12">
        <el-tabs type="border-card">
            <el-tab-pane label="Produtos">
                <div class="row">
                    <div class="col-12">
                        <el-select
                            class="w-100"
                            v-model="selecting.product_id"
                            filterable
                            remote
                            clearable
                            reserve-keyword
                            placeholder="Encontro o produto ..."
                            :remote-method="remoteProductMethod"
                            :loading="loading_product"
                            suffix-icon="el-icon-search"
                        >
                            <el-option-group v-for="(group, g) in Object.keys(grouped_product)" :key="g" :label="group">
                                <el-option v-for="(product, p) in grouped_product[group]" :key="p" :label="product.name" :value="product.id">
                                    <div class="d-flex flex-row">
                                        <b>{{ product.name }}</b>
                                        <span class="ml-auto">{{ product.f_price }}</span>
                                    </div>
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </div>
                </div>
                <template v-if="selected_product">
                    <div class="row mt-3">
                        <div class="col-12 d-flex flex-row justify-content-between align-items-end">
                            <div class="image-list-preview d-flex align-items-center justify-content-center">
                                <el-image
                                    class="image big"
                                    :src="selected_product.images.length > 0 ? selected_product.images[0] : '/assets/placeholder.jpg'"
                                    :preview-src-list="selected_product.images.length > 0 ? selected_product.images : []"
                                />
                            </div>
                            <div class="d-flex flex-column">
                                <b class="text-muted">Subtotal</b>
                                <span class="original_price">{{ selecting_product_total }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="mb-0">Quantidade</label>
                            <input class="form form-control form-control-sm" v-model="selecting.qty" type="number" min="1" />
                        </div>
                        <div class="col-md-3">
                            <label class="mb-0">Preço Unitário</label>
                            <input class="form form-control form-control-sm" :value="selected_product.f_price" disabled />
                        </div>
                        <div class="col-md-3">
                            <label class="mb-0">Desconto ( % )</label>
                            <input class="form form-control form-control-sm" v-model="selecting.disccount" type="number" min="0" max="100" />
                        </div>
                        <div class="col-md-3">
                            <label class="mb-0">Subtotal</label>
                            <input class="form form-control form-control-sm" :value="selecting_product_total" disabled />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="mb-0">Observação</label>
                            <textarea class="form form-control form-control-sm" v-model="selecting.obs" rows="5" style="resize: none" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex flex-row justify-content-end">
                                <el-button type="success">
                                    <div class="d-flex flex-row align-items-center" @click="addProduct">
                                        <i class="el-icon-circle-plus mr-2" />
                                        Adicionar Produto
                                    </div>
                                </el-button>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="my-4 text-center">
                        <span class="text-muted">Nenhum Produto Selecionado ...</span>
                    </div>
                </template>
            </el-tab-pane>
            <el-tab-pane label="Clientes">
                <div class="row" v-if="!sale.customer.id">
                    <div class="col-12">
                        <el-select
                            class="w-100"
                            v-model="selecting.customer_id"
                            filterable
                            remote
                            clearable
                            reserve-keyword
                            placeholder="Encontro o cliente ..."
                            :remote-method="remoteCustomerMethod"
                            :loading="loading_customer"
                            suffix-icon="el-icon-search"
                        >
                            <el-option v-for="(customer, c) in customers" :key="c" :label="customer.name" :value="customer.id" />
                        </el-select>
                    </div>
                </div>
                <template v-if="selected_customer">
                    <div class="row mt-3">
                        <div class="col-12 d-flex flex-row justify-content-center align-items-center">
                            <div class="image-list-preview d-flex align-items-center justify-content-center">
                                <el-image
                                    class="image big"
                                    :src="selected_customer.images.length > 0 ? selected_customer.images[0] : '/assets/placeholder.jpg'"
                                    :preview-src-list="selected_customer.images.length > 0 ? selected_customer.images : []"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="mb-0">Nome</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.name" disabled />
                        </div>
                        <div class="col-md-4">
                            <label class="mb-0">Email</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.email" disabled />
                        </div>
                        <div class="col-md-4">
                            <label class="mb-0">CPF</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.doc" disabled />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="mb-0">Telefone</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.phone" disabled />
                        </div>
                        <div class="col-md-4">
                            <label class="mb-0">Celular</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.cellphone" disabled />
                        </div>
                        <div class="col-md-4">
                            <label class="mb-0">CPF</label>
                            <input class="form form-control form-control-sm" :value="selected_customer.doc" disabled />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex flex-row justify-content-end">
                                <el-button-group>
                                    <el-button type="danger" @click="cancelCustomer" v-if="sale.customer.id">
                                        <div class="d-flex flex-row align-items-center">
                                            <i class="el-icon-remove mr-2" />
                                            Desmarcar Cliente
                                        </div>
                                    </el-button>
                                    <el-button type="success" v-if="!sale.customer.id" @click="addCustomer">
                                        <div class="d-flex flex-row align-items-center">
                                            <i class="el-icon-circle-plus mr-2" />
                                            Selecionar Cliente
                                        </div>
                                    </el-button>
                                </el-button-group>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="my-4 text-center">
                        <span class="text-muted">Nenhum Cliente Selecionado ...</span>
                    </div>
                </template>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>
<script>
const initial_seletion_product = () => ({
    product_id: null,
    customer_id: null,
    qty: 1,
    disccount: 0,
})
export default {
    props: ['sale'],
    data() {
        return {
            selecting: initial_seletion_product(),
            loading_customer: false,
            loading_product: false,
            products: [],
            customers: [],
        }
    },
    computed: {
        models() {
            return this.$store.state.models
        },
        grouped_product() {
            return _.chain(this.products)
                .groupBy((x) => x.category.name)
                .value()
        },
        selected_product_raw_price() {
            return this.selected_product.price * this.selecting.qty
        },
        selecting_product_total() {
            let price = this.selected_product_raw_price
            let disccount = (price * this.selecting.disccount) / 100
            return (price - disccount).currency()
        },
        selected_product() {
            return this.products.find((x) => x.id == this.selecting.product_id)
        },
        selected_customer() {
            return this.customers.find((x) => x.id == this.selecting.customer_id)
        },
    },
    created() {
        this.products = []
        this.remoteProductMethod('')
        this.remoteCustomerMethod('')
    },
    methods: {
        cancelCustomer() {
            this.$confirm('Cancelar seleção deste cliente ?', 'Confirmação').then(() => {
                this.$set(this.sale, 'customer', {})
                this.selecting.customer_id = null
                this.remoteCustomerMethod('')
            })
        },
        addCustomer() {
            this.$confirm('Selecionar cliente ?', 'Confirmação').then(() => {
                this.$set(this.sale, 'customer', this.selected_customer)
            })
        },
        addProduct() {
            this.$confirm('Adicionar produto a esta compra ?', 'Confirmação').then(() => {
                alert('addeu')
            })
        },
        remoteCustomerMethod(query) {
            let params = {
                model: this.models.customer,
                filters: {
                    order_by: ['name', 'desc'],
                    where: {
                        name: {
                            like: `%${query}%`,
                        },
                    },
                    // or_where: {
                    //     id: {
                    //         like: `%${query}%`,
                    //     },
                    // },
                    // or_where: {
                    //     ean: {
                    //         like: `%${query}%`,
                    //     },
                    // },
                },
            }
            this.$http.post('/vstack/json-api', params, { retries: 3 }).then(({ data }) => {
                this.customers = data
            })
        },
        remoteProductMethod(query) {
            let params = {
                model: this.models.product,
                includes: ['category'],
                filters: {
                    order_by: ['name', 'desc'],
                    where: {
                        name: {
                            like: `%${query}%`,
                        },
                    },
                    or_where: {
                        id: {
                            like: `%${query}%`,
                        },
                    },
                    or_where: {
                        ean: {
                            like: `%${query}%`,
                        },
                    },
                },
            }
            this.$http.post('/vstack/json-api', params, { retries: 3 }).then(({ data }) => {
                this.products = data
            })
        },
    },
}
</script>