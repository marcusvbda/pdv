<template>
    <tr>
        <td class="w-25">
            <div class="d-flex flex-column">
                <b class="input-title" v-if="label" v-html="label" />
                <small class="text-muted mt-1" v-if="description" v-html="description" />
            </div>
        </td>
        <td>
            <div class="row mb-3">
                <div class="col-md-4 col-sm-12">
                    <b class="input-title">Cep</b>
                    <input class="form-control my-1" v-model="form.address.zipcode" @blur="getAddressByZipCode" />
                </div>
                <div class="col-md-4 col-sm-12">
                    <b class="input-title">Estado</b>
                    <el-select class="w-100 my-1" v-model="form.address.state" placeholder="" filterable @change="form.address.city = null">
                        <el-option v-for="(s, i) in _.orderBy(states, ['nome'], ['asc'])" :key="i" :label="s.nome" :value="s.sigla" />
                    </el-select>
                </div>
                <div class="col-md-4 col-sm-12" v-if="form.address.state">
                    <b class="input-title">Cidade</b>
                    <el-select
                        class="w-100 my-1"
                        v-model="form.address.city"
                        placeholder=""
                        filterable
                        remote
                        reserve-keyword
                        :remote-method="getCities"
                        :loading="loadingCities"
                    >
                        <el-option v-for="item in cities" :key="item.value" :label="item.nome" :value="item.nome" />
                    </el-select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 col-sm-12">
                    <b class="input-title">Bairro</b>
                    <input class="form-control my-1" v-model="form.address.district" />
                </div>
                <div class="col-md-8 col-sm-12">
                    <b class="input-title">Rua e Número</b>
                    <input class="form-control my-1" v-model="form.address.address" />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <b class="input-title">Complemento</b>
                    <input class="form-control my-1" v-model="form.address.complement" />
                </div>
            </div>
        </td>
    </tr>
</template>
<script>
export default {
    props: ['form', 'data', 'errors'],
    data() {
        return {
            fields: ['zipcode', 'city', 'state', 'district', 'address', 'complement'],
            states: [],
            cities: [],
            loadingCities: false,
        }
    },
    computed: {
        label() {
            return this.field?.options?.label ?? false
        },
        description() {
            return this.field?.options?.description ?? false
        },
        field() {
            let card = this.data.fields.find((card) => card.label == 'Localização')
            return card.inputs.find((x) => x.options.field == 'address')
        },
    },
    created() {
        this.init()
    },
    methods: {
        normalizeStr(s) {
            let r = s.toLowerCase()
            r = r.replace(new RegExp(/\s/g), '')
            r = r.replace(new RegExp(/[àáâãäå]/g), 'a')
            r = r.replace(new RegExp(/æ/g), 'ae')
            r = r.replace(new RegExp(/ç/g), 'c')
            r = r.replace(new RegExp(/[èéêë]/g), 'e')
            r = r.replace(new RegExp(/[ìíîï]/g), 'i')
            r = r.replace(new RegExp(/ñ/g), 'n')
            r = r.replace(new RegExp(/[òóôõö]/g), 'o')
            r = r.replace(new RegExp(/œ/g), 'oe')
            r = r.replace(new RegExp(/[ùúûü]/g), 'u')
            r = r.replace(new RegExp(/[ýÿ]/g), 'y')
            r = r.replace(new RegExp(/\W/g), '')
            return r
        },
        getCities(query) {
            this.loadingCities = true
            this.$http
                .get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${this.form.address.state}/distritos`, {}, { tries: 3 })
                .then(({ data }) => {
                    let filtered = data.filter((x) => this.normalizeStr(x.nome).includes(this.normalizeStr(query)))
                    this.cities = _.orderBy(filtered, ['nome'], ['asc'])
                    this.loadingCities = false
                })
        },
        getAddressByZipCode() {
            this.$http.get(`https://ws.apicep.com/cep/${this.form.address.zipcode}.json`, {}, { tries: 3 }).then(({ data }) => {
                this.fields.forEach((field) => {
                    console.log(field, data)
                    this.form.address[field] = data[field]
                })
            })
        },
        init() {
            this.initFields()
            this.$http.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados`, {}, { tries: 3 }).then(({ data }) => {
                this.states = data
            })
        },
        initFields() {
            let address = {}
            this.fields.forEach((field) => (address[field] = this.form?.address ? (this.form.address[field] ? this.form.address[field] : null) : null))
            this.$set(this.form, this.field.options.field, address)
        },
    },
}
</script>