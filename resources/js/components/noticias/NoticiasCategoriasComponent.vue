<template>
    <layout>
        <v-btn color="primary" dark class="mb-2" @click.prevent="createItem">Cadastrar Categoria</v-btn>

        <v-spacer></v-spacer>

        <v-data-table
            :headers="headers"
            :items="desserts"
            :items-per-page="15"
            class="elevation-1"
            item-key="loading"
            :loading="loading"
            loading-text="Carregando..."
        >
            <template v-slot:item.edit="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click.prevent="modal('edit', item)"
                >edit
                </v-icon>
            </template>
            <template v-slot:item.delete="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click.prevent="modal('delete', item)"
                >delete
                </v-icon>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogEdit" max-width="800px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field
                                    label="Categoria"
                                    hide-details="auto"
                                    counter
                                    maxlength="20"
                                    :error-messages="inputErrors"
                                    :success="!$v.dataItem.ds_categoria.$invalid"
                                    v-model.trim="$v.dataItem.ds_categoria.$model"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeEdit">Cancelar</v-btn>
                    <v-btn color="blue darken-1" :disabled="$v.dataItem.$invalid" text @click="save">Salvar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDelete" max-width="800px">
            <v-card>
                <v-card-title>
                    <span class="headline">Deletar</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                       Deletar Categoria?
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItem">Excluir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="showSnackbar" top :color="snackbarColor">
            {{ message }}
            <v-btn color="white" icon @click="showSnackbar = false">
                <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>

    </layout>
</template>

<script>
    import {mapActions} from 'vuex'
    import {required, minLength} from 'vuelidate/lib/validators'
    import NoticiasService from "../../../services/noticias-service";
    import SnackbarConfigMixin from '../../../mixins/snackbar-config'

    export default {
        name: "NoticiasCategoriasComponent",
        props: ['item'],
        mixins: [
            SnackbarConfigMixin
        ],
        data() {
            return {
                dialogEdit: false,
                dialogDelete: false,
                headers: [
                    {text: 'Id', align: 'start', sortable: true, value: 'id',},
                    {text: 'Título', value: 'ds_categoria'},
                    {text: 'Editar', value: 'edit', sortable: false},
                    {text: 'Excluir', value: 'delete', sortable: false},
                ],
                desserts: [],
                loading: true,
                editedIndex: -1,
                title: '',
                dataItem: {
                    ds_categoria: undefined
                },
                defaultItem: {
                    ds_categoria: undefined
                },
                subscriptions: [],
            }
        },
        validations: {
            dataItem: {
                ds_categoria: {
                    required,
                    minLength: minLength(4)
                }
            },
        },
        created () {
            // cria data
            this.setData;
        },
        mounted() {
            // cria title component
            this.setTitle({title: 'Notícias Categorias'})
        },
        destroyed () {
            // destroyed subscribe
            this.subscriptions.forEach(s => s.unsubscribe())
        },
        computed: {
            setData() {
                // configura unsubscribe e recebe noticia_categoria data
                this.subscriptions.push(
                NoticiasService.noticiaCategoriaQuery().subscribe((data) => {
                    this.desserts = data;
                    this.loading = false;
                }))
            },
            // configura title dialog
            formTitle() {
                return this.editedIndex === -1 ? 'Cadastrar Categoria' : 'Editar Categoria'
            },
            // configura erros de validacao
            inputErrors() {
                const errors = []
                const item = this.$v.dataItem.ds_categoria

                if (!item.$dirty) {
                    return errors
                }

                !item.required && errors.push('Categoria é obrigatória')
                !item.minLength && errors.push(`Insira pelo menos ${item.$params.minLength.min} caracteres!`)
                return errors
            },
            snackColor (value) {
                return value;
            }
        },
        // monitora dialog
        watch: {
            dialogEdit(val) {
                val || this.closeEdit()
            },
            dialogDelete(val) {
                val || this.closeDelete()
            },
        },
        methods: {
            // action vuex setTitle
            ...mapActions(['setTitle']),
            // abre dialog ao editar
            createItem() {
                this.dialogEdit = true
            },
            // configura edit data
            modal(event, item) {

                this.editedIndex = this.desserts.indexOf(item)
                this.dataItem = Object.assign({}, item)

                switch (event) {
                    case 'edit':
                        this.dialogEdit = true
                        break
                    case 'delete':
                        this.dialogDelete = true
                        break
                }
            },
            // fecha dialog e limpa input
            closeEdit() {
                this.dialogEdit = false
                setTimeout(() => {
                    this.dataItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            closeDelete() {
                this.dialogDelete = false
                setTimeout(() => {
                    this.dataItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            // save, criar categoria e editar categoria
            save() {
                const id = this.dataItem.id;
                if (!id) {
                    try {
                        NoticiasService.noticiaCategoriaCreate({
                            ds_categoria: this.dataItem.ds_categoria
                        })
                        this.dialogEdit = false;

                        this.snackbarConfig('Criado com Sucesso.', 'success')
                    } catch (error) {
                        this.snackbarConfig(error.message, 'error')
                        console.log(error)
                    } finally {
                        this.dialogEdit = false;
                    }

                } else {
                    try {
                        NoticiasService.noticiasCategoriasUpdate({
                            id: id,
                            ds_categoria: this.dataItem.ds_categoria
                        })
                        this.dialogEdit = false;

                        this.snackbarConfig('Alterador com Sucesso.', 'success')

                    } catch (error) {
                        this.snackbarConfig(error.message, 'error')
                    } finally {
                        this.dialogEdit = false;
                    }
                }
            },
            async deleteItem() {
                const id = this.dataItem.id;
                try {
                    await NoticiasService.noticiasCategoriasDelete({ id: id })
                    this.dialogDelete = false;
                    this.snackbarConfig('Excluído com Sucesso.', 'success')
                } catch (error) {
                    this.snackbarConfig(error.message, 'error')
                } finally {
                    this.dialogDelete = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>
