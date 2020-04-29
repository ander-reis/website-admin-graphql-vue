<template>
    <layout>
        <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-form>

            <v-select
                name="ds_categoria"
                label="Categoria"
                :items="items"
                item-text="ds_categoria"
                item-value="id"
                :error-messages="DsCategoriaErrors"
                :success="!$v.form.id_categoria.$invalid"
                v-model.trim="$v.form.id_categoria.$model"
            ></v-select>

            <v-text-field
                name="ds_resumo"
                label="Título"
                counter
                maxlength="80"
                :error-messages="DsResumoErrors"
                :success="!$v.form.ds_resumo.$invalid"
                v-model.trim="$v.form.ds_resumo.$model"
            ></v-text-field>

            <v-textarea
                autocomplete="email"
                label="Texto"
                :error-messages="DsTextoErrors"
                :success="!$v.form.ds_texto.$invalid"
                v-model.trim="$v.form.ds_texto.$model"
            ></v-textarea>

<!--            <v-text-field-->
<!--                name="ds_palavra_chave"-->
<!--                label="Palavra Chave"-->
<!--                counter-->
<!--                color="indigo"-->
<!--                maxlength="150"-->
<!--                v-model.trim="form.ds_palavra_chave"-->
<!--            ></v-text-field>-->

            <v-combobox multiple
                        v-model.trim="form.ds_palavra_chave"
                        label="Palavras Chave"
                        append-icon
                        chips
                        maxlength="150"
                        color="indigo"
                        deletable-chips
                        class="tag-input">
            </v-combobox>

            <v-row>
                <v-col cols="12" sm="12" md="6">
                    <v-menu
                        ref="menu"
                        v-model="menu"
                        :close-on-content-click="false"
                        :return-value.sync="form.date"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                label="Picker in menu"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                :value="formattedDate"
                            ></v-text-field>
                        </template>
                        <v-date-picker v-model="form.date" locale="pt-br" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.menu.save(form.date)">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-menu
                        ref="menu"
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="form.time"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="form.time"
                                label="Horário"
                                prepend-icon="access_time"
                                readonly
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-time-picker
                            v-if="menu2"
                            v-model="form.time"
                            full-width
                            @click:minute="$refs.menu.save(form.time)"
                        ></v-time-picker>
                    </v-menu>
                </v-col>
            </v-row>

            <v-label>Status da Noitícia</v-label>
            <v-switch v-model="form.fl_status" color="red" flat :label="formatStatus"></v-switch>

        </v-form>

        <v-spacer></v-spacer>

        <v-btn
            :disabled="$v.$invalid"
            color="success"
            class="mr-4"
            @click="submit"
        >{{ buttonText }}
        </v-btn>

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
    import moment from 'moment'
    import {required, minLength} from 'vuelidate/lib/validators'
    import SnackbarConfigMixin from '../../../mixins/snackbar-config'
    import NoticiasService from '../../../services/noticias-service'

    export default {
        name: "NoticiasCreateComponent",
        mixins: [
            SnackbarConfigMixin
        ],
        data: () => ({
            loading: true,
            buttonText: null,
            items: [],
            subscriptions: [],
            form: {
                id_categoria: null,
                ds_resumo: null,
                ds_texto: null,
                ds_palavra_chave: [],
                fl_status: true,
                date: new Date().toISOString().substr(0, 10),
                time: new Date().getHours() + ":" + new Date().getMinutes(),
            },
            menu: false,
            menu2: false,
            modal: false,

        }),
        created() {
            this.getDataCategorias
        },
        mounted() {
            const route = this.$route.params
            if (!route.id) {
                this.buttonText = 'Cadastrar'
                this.setTitle({title: 'Cadastrar Notícia'})
            } else {
                this.buttonText = 'Editar'
                this.setTitle({title: 'Editar Notícia'})
                this.getNoticia(route.id)
            }
        },
        destroyed () {
            // destroyed subscribe
            this.subscriptions.forEach(s => s.unsubscribe())
        },
        validations: {
            form: {
                id_categoria: {required},
                ds_resumo: {required, minLength: minLength(6)},
                ds_texto: {required},
            }
        },
        computed: {
            getDataCategorias() {
                this.subscriptions.push(
                    NoticiasService.noticiaCategoriaQuery().subscribe((data) => {
                        this.items = data;
                        this.loading = false;
                    }))
            },
            formattedDate() {
                return moment(this.form.date).format('DD/MM/YYYY')
            },
            formatStatus() {
                console.log(this.form.fl_status)
                return this.form.fl_status ? 'Ativo' : 'Desabilitado'
            },
            DsCategoriaErrors() {
                const errors = []
                const id_categoria = this.$v.form.id_categoria
                if (!id_categoria.$dirty) {
                    return errors
                }
                !id_categoria.required && errors.push('Categoria é obrigatória')
                return errors
            },
            DsResumoErrors() {
                const errors = []
                const item = this.$v.form.ds_resumo
                if (!item.$dirty) {
                    return errors
                }
                !item.required && errors.push('Título é obrigatório')
                !item.minLength && errors.push(`Insira pelo menos ${item.$params.minLength.min} caracteres!`)
                return errors
            },
            DsTextoErrors() {
                const errors = []
                const ds_texto = this.$v.form.ds_texto
                if (!ds_texto.$dirty) {
                    return errors
                }
                !ds_texto.required && errors.push('Texto é obrigatória')
                return errors
            },

        },
        methods: {
            ...mapActions(['setTitle']),
            getNoticia(id) {
                this.subscriptions.push(
                    NoticiasService.noticiasQuery({id: id}).subscribe((data) => {
                        console.log(data[0])
                        this.form = data[0];
                        this.loading = false
                    }))
            },
            async submit() {
                const id = this.$route.params.id
                if(!id) {
                    // cadastrar
                    try {

                        this.form.dt_noticia = this.form.date + ' ' + this.form.time;

                        NoticiasService.noticiaCreate(this.form)


                        this.snackbarConfig('Criado com Sucesso.', 'success')
                        this.$router.push('/noticias')
                    } catch (error) {
                        console.log(error)
                        //this.snackbarConfig(error.message, 'error')
                    } finally {

                    }
                } else {
                    console.log('editar')
                }

            }
        }
    }
</script>

<style scoped>

</style>
