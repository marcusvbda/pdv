<template>
    <div>
        <div class="d-flex flex-row"><b class="mr-1"> Código do Caixa :</b> {{ cashier.code }}</div>
        <div class="d-flex flex-row"><b class="mr-1"> Responsável :</b> {{ cashier.user.name }}</div>
        <div class="d-flex flex-row"><b class="mr-1"> Abertura :</b> {{ cashier.f_created_at }}</div>
        <div class="d-flex flex-row"><b class="mr-1" v-if="is_closed"> Encerramento :</b> {{ cashier.f_closed_at }}</div>
        <div class="d-flex flex-row"><b class="mr-1"> Tempo Aberto :</b> {{ life_time }}</div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            life_time: 'Calculando ...',
        }
    },
    computed: {
        cashier() {
            return this.$store.state.cashier
        },
        is_closed() {
            return this.cashier.closed_at ? true : false
        },
    },
    created() {
        this.$nextTick(() => {
            this.init()
        })
    },
    methods: {
        init() {
            if (this.is_closed) return this.calculateLifeTimeFromTime(this.cashier.closed_at)
            this.initLifeTimeTimer()
        },
        calculateLifeTimeFromTime(_date) {
            let created = this.$moment(this.cashier.created_at)
            let now = this.$moment(_date)
            let diff = now.diff(created)
            let days = now.diff(created, 'days')
            days = days > 0 ? days : 0
            let hrs = (parseInt(this.$moment.utc(diff).format('HH')) + days * 24).pad(2)
            let min = this.$moment.utc(diff).format('mm')
            let sec = this.$moment.utc(diff).format('ss')
            this.life_time = [hrs, min, sec].join(':')
        },
        initLifeTimeTimer() {
            setInterval(() => {
                this.calculateLifeTimeFromTime(new Date())
            }, 1000)
        },
    },
}
</script>