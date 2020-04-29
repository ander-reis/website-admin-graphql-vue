<template>
    <layout>
        <v-btn color="primary" dark :to="{ name: 'noticias.create'}">Cadastrar Notícia</v-btn>
        <v-btn color="light-blue" dark :to="{ name: 'noticias.categorias.index'}">Notícias Categoria</v-btn>

        <v-data-table
            :headers="headers"
            :items="desserts"
            :items-per-page="15"
            class="elevation-1"
            item-key="loading"
            :loading="loading" loading-text="Carregando... Por favor espere"
        >
            <template v-slot:item.fl_status="{ item }">
                <v-icon class="text-center" :color="getColor(item.fl_status)">error</v-icon>
            </template>
            <template v-slot:item.dt_cadastro="{ item }">
                <v-chip class="text-center">
                    {{ getDataFormatted(item.dt_cadastro) }}
                </v-chip>
            </template>
            <template v-slot:item.dt_noticia="{ item }">
                <v-chip class="text-center">
                    {{ getDataFormatted(item.dt_noticia) }}
                </v-chip>
            </template>
            <template v-slot:item.edit="{ item }">
                <v-btn icon :to="(item.fl_status !== 0) ? {name: 'noticias.update', params: {id: item.id}} : ''" :color="getColor(item.fl_status)" :disabled="item.fl_status === 0 ? true : false">
                    <v-icon>{{ getIcon(item.fl_status, 'create', 'lock') }}</v-icon>
                </v-btn>
            </template>
            <template v-slot:item.show="{ item }">
                <v-btn icon @click="showItem(item)" :color="getColor(item.fl_status)" :disabled="item.fl_status === 0 ? true : false">
                    <v-icon>{{ getIcon(item.fl_status, 'visibility', 'visibility_off') }}</v-icon>
                </v-btn>
            </template>
        </v-data-table>

        <!--dialog-->
        <v-dialog v-model="dialog" max-width="800px">
            <v-card color="#385F73" dark>
                <v-card-title>
                    <span class="headline">{{ noticiaItem.ds_resumo }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row dense>
                            <v-col>
                                <v-card-subtitle>{{ noticiaItem.ds_texto }}</v-card-subtitle>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-card-subtitle>{{ noticiaItem.dt_noticia }}</v-card-subtitle>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-card-subtitle>{{ noticiaItem.dt_cadastro }}</v-card-subtitle>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-icon>{{ getIcon(noticiaItem.fl_status, 'visibility', 'visibility_off') }}</v-icon>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" dark text @click="dialog = false">Fechar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </layout>
</template>

<script>
    import {mapActions} from 'vuex'
    import NoticiasService from './../../../services/noticias-service'
    export default {
        name: "Noticias",
        data() {
            return {
                dialog: false,
                headers: [
                    {text: 'Id', align: 'start', sortable: true, value: 'id',},
                    {text: 'Título', value: 'ds_resumo'},
                    {text: 'Data Cadastro', align: 'center', value: 'dt_cadastro'},
                    {text: 'Status da Notícia', align: 'center', value: 'fl_status'},
                    {text: 'Data Notícia', align: 'center', value: 'dt_noticia'},
                    {text: 'Editar', align: 'center', sortable: false, value: 'edit'},
                    {text: 'Ver', align: 'center', sortable: false, value: 'show'},
                ],
                desserts: [],
                loading: true,
                noticiaItem: {
                    ds_resumo: '',
                    ds_texto: '',
                    dt_cadastro: '',
                    dt_noticia: '',
                    fl_status: '',
                },
                subscriptions: []
            }
        },
        created() {
            this.setData;
        },
        mounted() {
            this.setTitle({title: 'Notícias'})
        },
        destroyed () {
            // destroyed subscribe
            this.subscriptions.forEach(s => s.unsubscribe())
        },
        computed: {
            setData() {
                try {
                    this.subscriptions.push(
                        NoticiasService.noticiasQuery().subscribe((data) => {
                            console.log(data)
                            this.desserts = data;
                            this.loading = false;
                        }))
                } catch (error) {
                    console.log(error)
                } finally {
                    this.loading = false;
                }

            },
            formTitle() {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            },
        },
        watch: {
            // dialog (val) {
            //     val || this.close()
            // },
        },
        methods: {
            ...mapActions(['setTitle']),
            getColor(fl_status) {
                if (fl_status === 1) return 'green'
                else if (fl_status === 0) return 'error'
            },
            getIcon(fl_status, iconTrue, iconFalse) {
                if (fl_status === 1) return iconTrue
                else if (fl_status === 0) return iconFalse
            },
            getDataFormatted(data) {
                return data.slice(0, 11)
            },
            save(item) {
                console.log(item)
            },
            editItem(item) {
                return this.$router.push(`/noticias/${item.id}/editar`)
            },
            showItem(item) {
                this.noticiaItem = item;
                this.dialog = true
            },
            close() {
                this.dialog = false
                // setTimeout(() => {
                //     this.editedItem = Object.assign({}, this.defaultItem)
                //     this.editedIndex = -1
                // }, 300)
            },
        }
    }
</script>

<style scoped>

</style>
