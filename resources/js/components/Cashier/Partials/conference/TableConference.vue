<template>
    <div id="table-conference">
        <h1>Aqui a conferencia do caixa id : {{ cashier_id }}</h1>
    </div>
</template>
<script>
export default {
    props: ['cashier_id'],
}
</script>
<style lang="scss">
#table-conference {
    //
}
</style>