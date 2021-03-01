<template>
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-7">
                <div class="card" v-loading="loading">
                    <div class="card-header" v-if="!accepted">
                       atentamente o termo de garantia
                    </div>
                    <div class="card-body">
                        <v-runtime-template :template="`<span>${term}</span>`" />
                        <el-alert  v-if="accepted" class="mt-3"
						title="Obrigado" 
						type="success" description="O Termo de garantia foi aceito com sucesso !!" 
						:closable="false" 
						show-icon
                        />
                    </div>
                    <div class="card-footer" e v-if="!accepted">
                        <div class="d-flex flex-row justify-content-lg-between align-items-center">
                            <el-checkbox v-model="checked">Eu, {{ customer.name }}, declaro que li e aceito o termo de garantia</el-checkbox>
                            <button class="btn btn-sm btn-primary" :disabled="!checked" @click="confirm">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'

export default {
    props: ['customer', 'tenant'],
    components: {
        VRuntimeTemplate,
    },
    computed: {
        term() {
            return this.tenant.data.term
        },
        accepted() {
            return this.customer.accepted_terms
        },
    },
    data() {
        return {
            checked: false,
            loading: false,
        }
    },
    methods: {
        confirm() {
            this.$http.post(`/termo-de-garantia/${this.customer.code}/aceitar`).then(() => window.location.reload())
        },
    },
}
</script>