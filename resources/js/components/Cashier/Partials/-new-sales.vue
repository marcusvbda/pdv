<template>
    <el-dialog
        id="new-sale"
        :visible.sync="dialogVisible"
        fullscreen
        :modal-append-to-body="true"
        :append-to-body="true"
        :close-on-press-escape="false"
        :close-on-click-modal="false"
        :before-close="beforeClose"
    >
        <a href="#" class="btn-close" @click.prevent="beforeClose">
            <span class="el-icon-error" />
        </a>
        <header class="opened">Nova Venda</header>
        <div class="container-fluid py-3">
            <div class="row">
                <select-product-customer :sale="sale" v-if="dialogVisible" />
                <div class="col-md-6 col-sm-12">{{ sale }}</div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
const initial_sale = () => {
    return {
        items: [],
        customer: {},
    }
}
export default {
    data() {
        return {
            dialogVisible: false,
            sale: initial_sale(),
        }
    },
    components: {
        'select-product-customer': require('./-select-product-customer.vue').default,
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
        isOpened() {
            return this.dialogVisible
        },
        hasContent() {
            return this.sale.items.length > 0 || this.sale.customer.id
        },
    },
    methods: {
        open() {
            this.dialogVisible = true
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
    .el-dialog__header {
        display: none;
    }
    .el-dialog__body {
        padding: 0;
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
}
</style>