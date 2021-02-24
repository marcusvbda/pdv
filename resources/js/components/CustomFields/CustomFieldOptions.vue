<template>
    <tr v-if="form.type == 'select'">
        <td class="w-25">
            <div class="d-flex flex-column">
                <b class="input-title" v-if="label" v-html="label" />
                <small class="text-muted mt-1" v-if="description" v-html="description" />
            </div>
        </td>
        <td>
            <el-select v-model="form.options" class="w-100" multiple filterable allow-create default-first-option placeholder="" />
        </td>
    </tr>
</template>
<script>
export default {
    props: ['form', 'data', 'errors'],
    computed: {
        label() {
            return this.field?.options?.label ?? false
        },
        description() {
            return this.field?.options?.description ?? false
        },
        field() {
            let card = this.data.fields.find((card) => card.label == 'Configurações')
            return card.inputs.find((x) => x.options.field == 'options')
        },
    },
    created() {
        this.initFields()
    },
    methods: {
        initFields() {
            this.$set(this.form, this.field.options.field, this.form?.options ? this.form?.options : [])
        },
    },
}
</script>