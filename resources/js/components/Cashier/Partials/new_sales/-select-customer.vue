<template>
    <div id="customer-select">
        <div class="h-100 p-0 d-flex flex-column">
            <template v-if="!selected_customer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <el-input placeholder="Digite o nome do cliente que deseja encontrar ..." prefix-icon="el-icon-search" v-model="filter" clearable />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="infinite-list-wrapper" v-if="customer_list.length" :infinite-scroll-disabled="disabled">
                                <div class="list" v-infinite-scroll="remoteCustomerMethod">
                                    <table class="table table-sm table-dark table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="w-10 f-12">#</th>
                                                <th class="f-12">Nome</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(customer, i) in customer_list"
                                                :key="i"
                                                style="cursor: pointer"
                                                @click="selecting.customer_id = customer.id"
                                            >
                                                <td class="f-12">
                                                    {{ customer.code }}
                                                </td>
                                                <td class="f-12">
                                                    {{ customer.name }}
                                                </td>
                                            </tr>
                                            <tr v-if="loading">
                                                <td colspan="2">Carregando...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-else class="d-flex py-4 align-items-center justify-content-center">Nenhum Cliente Encontrado ...</div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-row justify-content-end">
                                <el-button-group>
                                    <el-button type="danger" @click="cancelSelection">
                                        <div class="d-flex flex-row align-items-center">
                                            <i class="el-icon-back mr-2" />
                                            Selecionar Outro Cliente
                                        </div>
                                    </el-button>
                                </el-button-group>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 d-flex flex-row">
                            <div class="image-list-preview d-flex align-items-center justify-content-center">
                                <el-image class="image big" :src="image" :preview-src-list="imageList" />
                            </div>
                            <div class="d-flex flex-column justify-content-between h-100 ml-3">
                                <b class="text-muted mt-2">{{ selected_customer.name }}</b>
                                <span class="original_price">{{ selected_customer.doc }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6"><b class="mr-1">Nome :</b> {{ selected_customer.name }}</div>
                        <div class="col-6"><b class="mr-1">Email :</b> {{ selected_customer.email }}</div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6"><b class="mr-1">Telefone :</b> {{ selected_customer.phone }}</div>
                        <div class="col-6"><b class="mr-1">Celular :</b> {{ selected_customer.cellphone }}</div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <v-runtime-template :template="`<span>${selected_customer.f_accepted_terms}</span>`" />
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'

const initial_current_page = () => ({
    current_page: 0,
    total: -1,
})
export default {
    props: ['sale'],
    components: {
        VRuntimeTemplate,
    },
    data() {
        return {
            selecting: {
                customer_id: null,
            },
            loading: true,
            filter: '',
            customer_current_page: initial_current_page(),
            customer_list: [],
            timeout: null,
        }
    },
    watch: {
        selected_customer(val) {
            this.$set(this.sale, 'customer', val)
        },
        filter(val) {
            clearTimeout(this.timeout)
            this.timeout = setTimeout(() => {
                this.customer_current_page = initial_current_page()
                this.remoteCustomerMethod()
            }, 500)
        },
    },
    computed: {
        image() {
            let images = this.imageList
            return images.length ? images[0] : '/assets/placeholder.jpg'
        },
        imageList() {
            let images = this.selected_customer.images ?? []
            return images.length >= 0 ? images : []
        },
        noMore() {
            return this.customer_list.length == this.customer_current_page.total
        },
        disabled() {
            return this.loading || this.noMore
        },
        models() {
            return this.$store.state.models
        },
        selected_customer() {
            return this.customer_list.find((x) => x.id == this.selecting.customer_id)
        },
    },
    created() {
        this.remoteCustomerMethod()
    },
    methods: {
        cancelSelection() {
            this.selecting.customer_id = null
        },
        remoteCustomerMethod() {
            if (this.noMore) return
            this.loading = true
            let page = ++this.customer_current_page.current_page
            let query = this.filter
            let params = {
                model: this.models.customer,
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
                        doc: {
                            like: `%${query}%`,
                        },
                    },
                },
            }
            this.$http.post('/vstack/json-api', params, { retries: 3 }).then(({ data }) => {
                this.customer_current_page = data
                if (data.current_page == 1) {
                    this.customer_list = data.data
                } else {
                    this.customer_list = this.customer_list.concat(data.data)
                }
                this.loading = false
            })
        },
    },
}
</script>
<style lang="scss">
#customer-select {
    overflow: hidden;
    .infinite-list-wrapper {
        overflow-y: scroll;
        height: 350px;
    }
}
</style>