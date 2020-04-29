<template>
    <layout>
        <h1>Editar Noticias</h1>
    </layout>
</template>

<script>
    import {mapActions} from 'vuex'
    export default {
        name: "NoticiasEditarComponent",
        mounted () {
            this.setTitle({title: 'Editar Notícia'})
        },
        methods: {
            ...mapActions(['setTitle']),
        }
    }
</script>

<style scoped>

</style>
