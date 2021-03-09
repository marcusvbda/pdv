<template>
    <div id="crud-expense">
        <div class="container">
            <div class="row mt-3 d-flex justify-content-center mb-4">
                <div class="col-6 d-flex justify-content-center flex-column">
                    <div class="row">
                        <div class="col-12">
                            <label>Tipo de Lançamento *</label>
                            <select class="form-control" v-model="form.type">
                                <option v-for="(op, i) in options" :key="i" :value="op.value">{{ op.label }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label>Descrição *</label>
                            <input class="form-control" v-model="form.data.description" />
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label>Valor *</label>
                            <currency-input class="form-control" currency="BRL" :auto-decimal-mode="true" v-model="form.data.value" :min="0" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary px-4" @click="submit">Lançar</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const getInitialForm = () => ({
    type: null,
    data: {
        description: null,
        value: 0,
    },
})
export default {
    data() {
        return {
            loading: false,
            options: [
                { label: 'Entrada', value: 'cash_in' },
                { label: 'Saida', value: 'cash_out' },
            ],
            form: getInitialForm(),
        }
    },
    created() {
        this.$nextTick(() => {
            this.form = getInitialForm()
        })
    },
    methods: {
        submit() {
            if (!this.form.data.description) return this.$message.error('Descrição do lançamento é obrigatória')
            if (!this.form.data.value) return this.$message.error('Valor do lançamento é obrigatória')
            if (!this.form.type) return this.$message.error('Tipo do lançamento é obrigatória')
            this.$confirm('Efetuar lançamento no caixa ?', 'Confirmação').then(() => {
                let loading = this.$loading({ text: 'Efetuando Lançamento ...' })
                this.$store.dispatch('storeExpense', {
                    form: this.form,
                    callback: (data) => {
                        this.form = getInitialForm()
                        this.$message.success('Lançamento efetuado com sucesso !!')
                        this.$emit('finish')
                        loading.close()
                    },
                })
            })
        },
    },
}
</script>