<template>
    <div id="expense-list">
        <div class="d-flex flex-row mb-3">
            <div class="d-flex justify-content-start align-items-center">
                <span class="d-flex flex-row align-items-center f-12"> <span v-html="$getEnabledIcons(true)" class="mr-1" />Entrada </span>
                <span class="d-flex flex-row align-items-center f-12 ml-3"> <span v-html="$getEnabledIcons(false)" class="mr-1" />Saida </span>
            </div>
            <el-pagination
                class="ml-auto"
                background
                layout="prev, pager, next"
                :pager-count="5"
                :page-size="1"
                :total="list.last_page"
                @current-change="getExpenses"
            />
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-sm" v-if="list.data.length">
                    <thead>
                        <tr>
                            <th class="f-12 w-10">#</th>
                            <th class="f-12">Tipo</th>
                            <th class="f-12 w-10" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, i) in list.data" :key="i" :class="`expense-row ${row.type}`">
                            <td class="f-12">{{ row.code }}</td>
                            <td class="f-12">{{ row.f_type }}</td>
                            <td class="f-12">
                                <el-button-group size="mini">
                                    <el-button type="primary" size="mini" icon="el-icon-search" @click="see(row)" />
                                </el-button-group>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-center p-4" v-else>Nenhuma Despesa Registrada ...</p>
            </div>
        </div>
    </div>
</template>
<script>
const getInitialList = () => ({
    current_page: 0,
    data: [],
    last_page: 0,
})
export default {
    data() {
        return {
            list: getInitialList(),
            loading: true,
        }
    },
    created() {
        this.list = getInitialList()
        this.getExpenses(++this.list.current_page)
    },
    methods: {
        getExpenses(page) {
            this.$store.dispatch('getExpenses', {
                page,
                callback: (data) => {
                    this.list = data
                    this.loading = false
                },
            })
        },
        see(row) {
            console.log(row)
        },
    },
}
</script>
<style lang="scss">
#expense-list {
    .expense-row {
        &.cash_in {
            td {
                background-color: #f4fff4;
            }
        }
        &.cash_out {
            td {
                background-color: #ffe7e7;
            }
        }
    }
}
</style>