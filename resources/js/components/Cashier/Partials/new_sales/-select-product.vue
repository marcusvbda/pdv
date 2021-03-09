<template>
    <div id="product-select">
        <div class="h-100 p-0 d-flex flex-column">
            <template v-if="!selected_product">
                <div class="container-fluid my-3">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <el-input placeholder="Digite o nome do produto que deseja encontrar ..." prefix-icon="el-icon-search" v-model="filter" clearable />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="infinite-list-wrapper" v-if="products_list.length" :infinite-scroll-disabled="disabled">
                                <div class="list" v-infinite-scroll="remoteProductMethod">
                                    <table class="table table-sm table-dark table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="w-10 f-12">#</th>
                                                <th class="f-12">Categoria</th>
                                                <th class="f-12">Nome</th>
                                                <th class="f-12">Preço Unit.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(product, i) in products_list"
                                                :key="i"
                                                style="cursor: pointer"
                                                @click="selecting.product_id = product.id"
                                            >
                                                <td class="f-12">
                                                    {{ product.code }}
                                                </td>
                                                <td class="f-12">
                                                    {{ product.category.name }}
                                                </td>
                                                <td class="f-12">
                                                    <b>{{ product.name }}</b>
                                                </td>
                                                <td class="f-12">
                                                    {{ product.f_price }}
                                                </td>
                                            </tr>
                                            <tr v-if="loading">
                                                <td colspan="4">Carregando...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-else class="d-flex py-4 align-items-center justify-content-center">Nenhum Produto Encontrado ...</div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="container-fluid py-4">
                    <div class="row mt-3">
                        <div class="col-12 d-flex flex-row">
                            <div class="image-list-preview d-flex align-items-center justify-content-center">
                                <el-image class="image big" :src="image" :preview-src-list="imageList" />
                            </div>
                            <div class="d-flex flex-column justify-content-between h-100 ml-3">
                                <b class="text-muted mt-2">{{ selected_product.name }}</b>
                                <span class="original_price">{{ selecting_product_total.currency() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="mb-0">Preço Unitário</label>
                            <input class="form form-control form-control-sm" :value="selected_product.f_price" disabled />
                        </div>
                        <div class="col-md-6">
                            <label class="mb-0">Quantidade</label>
                            <input class="form form-control form-control-sm" v-model="selecting.qty" type="number" min="1" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="mb-0">Desconto ( - R$ )</label>
                            <currency-input
                                class="form-control form-control-lg"
                                currency="BRL"
                                :auto-decimal-mode="true"
                                v-model="selecting.disccount"
                                min="0"
                                :max="selected_product.price"
                            />
                        </div>
                        <div class="col-md-6">
                            <label class="mb-0">Acréscimo ( + R$ )</label>
                            <currency-input
                                class="form-control form-control-lg"
                                currency="BRL"
                                :auto-decimal-mode="true"
                                v-model="selecting.addition"
                                min="0"
                            />
                        </div>
                    </div>
                </div>
                <div class="row mt-auto row-btn">
                    <div class="col-12">
                        <div class="d-flex flex-row justify-content-end">
                            <el-button-group>
                                <el-button type="danger" @click="cancelSelection">
                                    <div class="d-flex flex-row align-items-center">
                                        <i class="el-icon-back mr-2" />
                                        Voltar a Listagem
                                    </div>
                                </el-button>
                                <el-button type="success" @click="addProduct">
                                    <div class="d-flex flex-row align-items-center">
                                        <i class="el-icon-circle-plus mr-2" />
                                        Adicionar Produto
                                    </div>
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
const initial_selection_product = () => ({
    product_id: null,
    qty: 1,
    disccount: 0,
    addition: 0,
})
const initial_product_current_page = () => ({
    current_page: 0,
    total: -1,
})
export default {
    props: ['sale'],
    data() {
        return {
            selecting: initial_selection_product(),
            loading: true,
            filter: '',
            product_current_page: initial_product_current_page(),
            products_list: [],
            timeout: null,
        }
    },
    watch: {
        filter(val) {
            clearTimeout(this.timeout)
            this.timeout = setTimeout(() => {
                this.product_current_page = initial_product_current_page()
                this.remoteProductMethod()
            }, 500)
        },
        'selecting.addition'(val) {
            val = Number(val)
            if (val < 0) this.selecting.addition = 0
        },
        'selecting.disccount'(val) {
            val = Number(val)
            if (this.selected_product) {
                if (val < 0) this.selecting.addition = 0
                let price = Number(this.selected_product.price)
                if (val > price) this.selecting.disccount = price
            }
        },
    },
    computed: {
        image() {
            let images = this.imageList
            return images.length ? images[0] : '/assets/placeholder.jpg'
        },
        imageList() {
            let images = this.selected_product.images ?? []
            return images.length >= 0 ? images : []
        },
        noMore() {
            return this.products_list.length == this.product_current_page.total
        },
        disabled() {
            return this.loading || this.noMore
        },
        models() {
            return this.$store.state.models
        },
        selected_product_raw_price() {
            return this.selected_product.price * this.selecting.qty
        },
        selecting_product_total() {
            let price = Number(this.selected_product_raw_price)
            let disccount = Number(this.selecting.disccount)
            let addition = Number(this.selecting.addition)
            return Number(price - disccount + addition)
        },
        selected_product() {
            return this.products_list.find((x) => x.id == this.selecting.product_id)
        },
    },
    created() {
        this.remoteProductMethod()
    },
    methods: {
        cancelSelection() {
            this.selecting = initial_selection_product()
        },
        addProduct() {
            let adding_product = Object.assign({}, this.selecting)
            adding_product.product = this.selected_product
            adding_product.subtotal = this.selecting_product_total
            this.sale.items.push(adding_product)
            this.cancelSelection()
        },
        remoteProductMethod() {
            if (this.noMore) return
            this.loading = true
            let page = ++this.product_current_page.current_page
            let query = this.filter
            let params = {
                model: this.models.product,
                includes: ['category'],
                per_page: 30,
                page: page,
                filters: {
                    order_by: ['name', 'desc'],
                    where: {
                        name: {
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
                this.product_current_page = data
                if (data.current_page == 1) {
                    this.products_list = data.data
                } else {
                    this.products_list = this.products_list.concat(data.data)
                }
                this.loading = false
            })
        },
    },
}
</script>
<style lang="scss">
#product-select {
    overflow: hidden;
    .infinite-list-wrapper {
        overflow-y: scroll;
        height: 480px;
    }
}
</style>