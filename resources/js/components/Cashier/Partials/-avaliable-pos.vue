<template>
    <div id="avaliable-view" v-hotkey="keymap" v-if="cashier">
        <header class="available">Caixa Disponível</header>
        <article class="icon">
            <i class="el-icon-sell" />
        </article>
        <footer class="available">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="d-flex flex-column justify-content-between w-100">
                            <div class="d-flex flex-row"><b class="mr-1"> Código do Caixa :</b> {{ cashier.code }}</div>
                            <div class="d-flex flex-row"><b class="mr-1"> Responsável :</b> {{ cashier.user.name }}</div>
                            <div class="d-flex flex-row"><b class="mr-1"> Abertura :</b> {{ cashier.f_created_at }}</div>
                            <div class="d-flex flex-row"><b class="mr-1"> Tempo Aberto :</b> {{ life_time }}</div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8 d-flex align-items-center justify-content-between">
                        <el-button-group class="w-100 d-flex">
                            <el-button class="btn-action flex-grow-1" type="info" @click="newSale">
                                <div class="d-flex flex-column">
                                    <div>Nova Venda</div>
                                    <div class="mt-2">( F1 )</div>
                                </div>
                            </el-button>
                            <el-button class="btn-action flex-grow-1" type="info" @click="saleList">
                                <div class="d-flex flex-column">
                                    <div>Visualizar Vendas</div>
                                    <div class="mt-2">( F2 )</div>
                                </div>
                            </el-button>
                            <el-button class="btn-action flex-grow-1" type="info" @click="recieves">
                                <div class="d-flex flex-column">
                                    <div>Despesas e Recebimentos</div>
                                    <div class="mt-2">( F3 )</div>
                                </div>
                            </el-button>
                            <el-button class="btn-action flex-grow-1" type="info" @click="back">
                                <div class="d-flex flex-column">
                                    <div>Sair do Frente de Caixa</div>
                                    <div class="mt-2">( F14 )</div>
                                </div>
                            </el-button>
                            <el-button class="btn-action flex-grow-1" type="info" @click="close">
                                <div class="d-flex flex-column">
                                    <div>Finalizar de Caixa</div>
                                    <div class="mt-2">( F12 )</div>
                                </div>
                            </el-button>
                        </el-button-group>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
<script>
import Vue from 'vue'
import VueHotkey from 'v-hotkey'
Vue.use(VueHotkey)
export default {
    data() {
        return {
            life_time: 'Calculando ...',
        }
    },
    computed: {
        keymap() {
            return {
                f1: this.newSale,
                f2: this.saleList,
                f3: this.recieves,
                f4: this.back,
                f12: this.close,
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
        this.initLifeTimeTimer()
    },
    methods: {
        newSale() {
            alert('Nova Venda')
        },
        saleList() {
            alert('Ver Vendas')
        },
        recieves() {
            alert('Ver Despesas e recebimentos')
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
        initLifeTimeTimer() {
            setInterval(() => {
                let created = this.$moment(this.cashier.created_at)
                let now = this.$moment(new Date())
                let diff = now.diff(created)
                let hrs = this.$moment.utc(diff).format('HH')
                let min = this.$moment.utc(diff).format('mm')
                let sec = this.$moment.utc(diff).format('ss')
                this.life_time = [hrs, min, sec].join(':')
            }, 1000)
        },
    },
}
</script>
<style lang="scss">
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
    article {
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
</style>