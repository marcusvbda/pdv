<template>
    <div class="container-fluid flex-grow-1" id="product-list">
        <div class="row">
            <div class="col-12 pl-0">
                <table class="table table-sm table-striped table-hover" v-if="sale.items.length">
                    <thead>
                        <tr>
                            <th class="w-10 f-12">#</th>
                            <th class="f-12">Categoria</th>
                            <th class="f-12">Nome</th>
                            <th class="f-12">Preço Unit.</th>
                            <th class="f-12">Qtde</th>
                            <th class="f-12">Acréscimo</th>
                            <th class="f-12">Desconto</th>
                            <th class="f-12">Subtotal</th>
                            <td />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in sale.items" :key="i">
                            <td class="f-12">{{ item.product.code }}</td>
                            <td class="f-12">{{ item.product.category.name }}</td>
                            <td class="f-12">
                                <b>{{ item.product.name }}</b>
                            </td>
                            <td class="f-12">
                                {{ item.product.price.currency() }}
                            </td>
                            <td class="f-12">
                                {{ item.qty }}
                            </td>
                            <td class="f-12">
                                {{ item.addition.currency() }}
                            </td>
                            <td class="f-12">
                                {{ item.disccount.currency() }}
                            </td>
                            <td class="f-12">
                                {{ item.subtotal.currency() }}
                            </td>
                            <td style="width: 1%">
                                <a href="#" class="text-danger destroy-link" @click="removeItem(i)">
                                    <span class="el-icon-error" />
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="d-flex py-4 align-items-center justify-content-center">Nenhum Item Adicionado ...</div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['sale'],
    methods: {
        removeItem(i) {
            this.$confirm('Deseja remover este item da compra ?', 'Confirmação').then(() => {
                this.sale.items = this.sale.items.filter((value, key) => key != i)
            })
        },
    },
}
</script>
<style scoped lang="scss">
#product-list {
    overflow-y: auto;
    height: 410px;

    .destroy-link {
        font-size: 20px;
        &:hover {
            text-decoration: unset;
        }
    }
}
</style>