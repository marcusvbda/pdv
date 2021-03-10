<template>
    <div id="table-conference" v-if="cashier">
        <h3 v-if="is_complete_view">Conferência de Caixa : #{{ cashier.code }}</h3>
        <div class="table-responsive-sm">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th class="w-25">Identificação</th>
                        <th class="w-25">Valor Registrado</th>
                        <th class="w-25">Valor em Caixa</th>
                        <th class="w-25">Diferença</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(method, i) in Object.keys(cashier_conference)" :key="i">
                        <td>{{ method }}</td>
                        <td>{{ cashier_conference[method].currency() }}</td>
                        <td>
                            <currency-input v-if="!is_confered" class="form-control" currency="BRL" :auto-decimal-mode="true" v-model="current_conference[method]" />
							<template v-else>{{current_conference[method].currency()}}</template>
                        </td>
                        <td>
                            <div class="d-flex flex-row align-items-center">
                                <span v-html="getDifferenceBadgePercentage(method)" />
                            </div>
                        </td>
                    </tr>
					<tr>
						<td>Saldo Inicial de Caixa</td>
						<td>{{ cashier.initial_balance.currency() }}</td>
						<td> - </td>
						<td> - </td>
					</tr>
					<tr>
						<td>Lançamento Tipo Entrada</td>
						<td>{{ total_cash_in.currency() }}</td>
						<td> - </td>
						<td> - </td>
					</tr>
					<tr>
						<td>Lançamento Tipo Saída</td>
						<td>{{ total_cash_out.currency() }}</td>
						<td> - </td>
						<td> - </td>
					</tr>
                    <tr>
                        <td colspan="4">
                            <div class="d-flex flex-row justify-content-end align-items-center">
								 <div class="col-6 py-3">
                                   <div class="card">
									   <div class="card-body">
										    <pie-chart  :donut="true" dounut ref="chart" :data="current_conference" prefix="R$ " />
									   </div>
								   </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0"><h4>Registrado : {{ total_registred.currency() }}</h4></p>
                                    <p class="mb-0"><h4>Informado : {{ total_informed.currency() }}</h4></p>
                                    <p class="mb-0"><h4 :class="total_class_alert">Diferença : {{ total_difference.currency() }}</h4></p>
                                </div>
                            </div>
							<div class="row mt-3"  v-if="!is_confered" >
								<div class="col-12 text-right">
									<button class="btn btn-success px-4" type="button" @click="finisih">
										<span class="el-icon-success mr-2" />
										Finalizar Conferência de Caixa
									</button>
								</div>
							</div>
                        </td>
                    </tr>
					<tr  v-if="is_confered">
                        <td colspan="4">
                            <div class="d-flex flex-row justify-content-end align-items-center" >
								 <div class="col-6 py-3">
                                   <div class="card">
									   <div class="card-body text-dark">
											<h4>
												<b class="d-flex align-items-center text-success">
													<span class="el-icon-circle-check text-success x mr-2" />Conferência de Caixa Realizada
												</b>
											</h4>
											<p class="mb-0">
												<b class="mr-1">Responsável : </b>{{complete_current_conference.user.name}}
											</p>
											<p class="mb-0">
												<b class="mr-1">Email :</b> {{complete_current_conference.user.email}}
											</p>
											<p class="mb-0">
												<b class="mr-1">Data :</b> {{complete_current_conference.f_created_at}}
											</p>
									   </div>
								   </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <template v-if="is_complete_view">
			<div class="row mt-3">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<sale-list only_view title="Vendas" />
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<expenses-list only_view  title="Lançamentos" />
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <cashier-details />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
import cashierStore from '~/stores/cashier'
export default {
    props: ['cashier_id', 'complete'],
    data() {
        return {
            complete_current_conference: {},
            cashier_conference: {},
            total_cash_in: 0,
            total_cash_out: 0,
        }
    },
    store: cashierStore,
    components: {
        'cashier-details': require('../sales/view_sales/-cashier-details.vue').default,
        'sale-list': require('../sales/view_sales/-sale-list.vue').default,
        'expenses-list': require('../expenses/-expenses-list.vue').default,
    },
    created() {
        this.$nextTick(() => {
            this.init()
        })
    },
    watch: {
        current_conference: {
            deep: true,
            handler() {
                this.$refs.chart.updateChart()
            },
        },
    },
    computed: {
        is_confered() {
            return this.complete_current_conference?.user?.id
        },
        current_conference() {
            return this.complete_current_conference.data ?? {}
        },
        total_class_alert() {
            let diff = this.total_difference
            return this.getAlertClass(diff < 0 ? diff * -1 : diff)
        },
        total_registred() {
            return this.withBalanceAndExpenses(
                Object.keys(this.cashier_conference)
                    .map((key) => this.cashier_conference[key])
                    .reduce((a, b) => a + b, 0)
            )
        },
        total_informed() {
            return this.withBalanceAndExpenses(
                Object.keys(this.current_conference)
                    .map((key) => this.current_conference[key])
                    .reduce((a, b) => a + b, 0)
            )
        },
        total_difference() {
            let informed = this.total_informed
            let registred = this.total_registred
            return informed - registred
        },
        is_complete_view() {
            return this.complete != undefined
        },
        cashier() {
            return this.$store.state.cashier
        },
    },
    methods: {
        withBalanceAndExpenses(value) {
            value += this.cashier.initial_balance
            value += this.total_cash_in
            value -= this.total_cash_out
            return value
        },
        getDifference(method) {
            return this.cashier_conference[method] - this.current_conference[method]
        },
        getPositiveDifference(method) {
            let diff = this.getDifference(method)
            return diff < 0 ? diff * -1 : diff
        },
        getDifferencePercentage(method) {
            let diff = this.getPositiveDifference(method)
            let real = this.cashier_conference[method]
            return +((diff * 100) / real).toFixed(2)
        },
        getAlertClass(diff) {
            let _class = ''
            if (diff == 0) _class = 'text-success'
            if (diff > 0 && diff <= 20) _class = 'text-warning'
            if (diff > 20) _class = 'text-danger'
            return _class
        },
        getDifferenceBadgePercentage(method) {
            let positive_diff = this.getPositiveDifference(method)
            let percentage = this.getDifferencePercentage(method)
            let _class = this.getAlertClass(percentage)
            return `
				<span class='${_class}'>${positive_diff.currency()}</span>
				<small class='ml-2 ${_class}'>${percentage} %</small>
			`
        },
        init() {
            this.$store.dispatch('getPaymentMethods')
            this.$store.dispatch('getCashier', {
                cashier_id: this.cashier_id,
                callback: () => {
                    this.$store.dispatch('getCashierConference', {
                        callback: (data) => {
                            this.complete_current_conference = data.current_conference
                            this.cashier_conference = data.conference_data
                            this.total_cash_in = data.total_cash_in
                            this.total_cash_out = data.total_cash_out
                        },
                    })
                },
            })
        },
        finisih() {
            this.$loading({ text: 'Finalizando Conferência de Caixa' })
            this.$store.dispatch('storeCashierConference', {
                current_conference: this.current_conference,
                callback: () => (window.location.href = '/admin/caixas'),
            })
        },
    },
}
</script>
<style lang="scss">
#table-conference {
    .table-responsive-sm {
        background-color: white;
    }
}
</style>