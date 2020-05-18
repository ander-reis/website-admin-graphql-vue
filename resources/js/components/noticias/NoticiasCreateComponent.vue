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
                :success="!$v.form.categoria.id.$invalid"
                v-model.trim="$v.form.categoria.id.$model"
            ></v-select>

            <v-text-field
                name="ds_resumo"
                label="Título"
                counter
                maxlength="80"
                :error-messages="DsResumoErrors"
                :success="!$v.form.ds_resumo.$invalid"
                v-model.trim="$v.form.ds_resumo.$model"
                :value="form.ds_resumo"
            ></v-text-field>

            <v-label>Texto</v-label>
            <ckeditor :editor="editor" v-model.trim="$v.form.ds_texto.$model" :config="editorConfig"></ckeditor>

            <v-combobox
                multiple
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
                        ref="menuDatePicker"
                        v-model="menuDatePicker"
                        :close-on-content-click="false"
                        :return-value.sync="form.ds_data"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                label="Data"
                                prepend-icon="event"
                                readonly
                                v-on="on"
                                :value="formattedDate"
                            ></v-text-field>
                        </template>
                        <v-date-picker v-model="form.ds_data" locale="pt-br" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menuDatePicker = false">Fechar</v-btn>
                            <v-btn text color="primary" @click="$refs.menuDatePicker.save(form.ds_data)">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>

                <v-col cols="12" sm="12" md="6">
                    <v-menu
                        ref="menuTimePicker"
                        v-model="menuTimePicker"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="form.ds_hora"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="form.ds_hora"
                                label="Horário"
                                prepend-icon="access_time"
                                readonly
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-time-picker
                            v-if="menuTimePicker"
                            v-model="form.ds_hora"
                            full-width
                            @click:minute="$refs.menuTimePicker.save(form.ds_hora)"
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

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
                categoria: {
                    id: null,
                    ds_categoria: null
                },
                ds_resumo: null,
                ds_texto: null,
                ds_palavra_chave: [],
                fl_status: true,
                ds_data: new Date().toISOString().substr(0, 10),
                ds_hora: new Date().getHours() + ":" + new Date().getMinutes(),
            },
            menuDatePicker: false,
            menuTimePicker: false,
            modal: false,
            editor: ClassicEditor,
            editorData: '<p>Content of the editor.</p>',
            editorConfig: {
                // The configuration of the editor.
            },
        }),
        created() {
            this.getDataCategorias

            const route = this.$route.params
            if (!route.id) {
                this.buttonText = 'Cadastrar'
                this.setTitle({title: 'Cadastrar Notícia'})
            } else {
                this.buttonText = 'Editar'
                this.setTitle({title: 'Editar Notícia'})
                this.getNoticiaEditar(route.id)
            }
        },
        destroyed() {
            // destroyed subscribe
            this.subscriptions.forEach(s => s.unsubscribe())
        },
        validations: {
            form: {
                categoria: {
                    id: { required },
                },
                ds_resumo: { required, minLength: minLength(6) },
                ds_texto: { required },
            }
        },
        computed: {
            getDataCategorias() {
                try {
                    this.subscriptions.push(
                        NoticiasService.noticiaCategoriaQuery().subscribe((data) => {
                            this.items = data;
                            // this.loading = false;
                        }))
                } catch (error) {
                    console.log(error)
                } finally {
                    this.loading = false;
                }
            },
            getDataNoticia() {
                let id = this.$route.params
                this.subscriptions.push(
                    NoticiasService.noticiasQuery(id).subscribe((data) => {
                        data[0].ds_palavra_chave = data[0].ds_palavra_chave.toString().split(',')
                        this.form = data[0];
                        // this.loading = false
                    }))
            },
            formattedDate() {
                return moment(this.form.ds_data).format('DD/MM/YYYY')
            },
            formatStatus() {
                return this.form.fl_status ? 'Ativo' : 'Desabilitado'
            },
            DsCategoriaErrors() {
                const errors = []
                const categoria = this.$v.form.categoria.id
                // if (!categoria.$dirty) {
                //     return errors
                // }
                !categoria.required && errors.push('Categoria é obrigatória')
                return errors
            },
            DsResumoErrors() {
                const errors = []
                const item = this.$v.form.ds_resumo
                // if (!item.$dirty) {
                //     return errors
                // }
                !item.required && errors.push('Título é obrigatório')
                !item.minLength && errors.push(`Insira pelo menos ${item.$params.minLength.min} caracteres!`)
                return errors
            },
            DsTextoErrors() {
                const errors = []
                const ds_texto = this.$v.form.ds_texto
                // if (!ds_texto.$dirty) {
                //     return errors
                // }
                !ds_texto.required && errors.push('Texto é obrigatória')
                return errors
            },

        },
        methods: {
            ...mapActions(['setTitle']),
            getNoticiaEditar(id) {
                try {
                    this.loading = true
                    this.subscriptions.push(
                        NoticiasService.noticiasQuery({id: id}).subscribe((data) => {
                            if(data[0].ds_palavra_chave.length === 0) {
                                data[0].ds_palavra_chave = [];
                            } else {
                                data[0].ds_palavra_chave = data[0].ds_palavra_chave.toString().split(',')
                            }
                            this.form = data[0];
                        }))
                } catch (error) {
                    console.log(error)
                } finally {
                    this.loading = false;
                }
            },
            async submit() {
                const id = this.$route.params.id
                if (!id) {
                    // cadastrar
                    try {
                        console.log(this.form)
                        await NoticiasService.noticiaCreate({
                            id_categoria: this.form.categoria.id,
                            ds_resumo: this.form.ds_resumo,
                            ds_texto: this.form.ds_texto,
                            ds_palavra_chave: this.form.ds_palavra_chave,
                            fl_status: this.form.fl_status,
                            ds_data: this.form.ds_data,
                            ds_hora: this.form.ds_hora,
                        });
                        this.snackbarConfig('Criado com Sucesso.', 'success')
                        this.$router.push('/noticias')
                    } catch (error) {
                        console.log(error)
                        //this.snackbarConfig(error.message, 'error')
                    }
                } else {
                    console.log('editar', this.form)
                    // udpdate
                    try {

                        await NoticiasService.noticiaUpdate({
                            id: this.form.id,
                            id_categoria: this.form.categoria.id,
                            ds_resumo: this.form.ds_resumo,
                            ds_texto: this.form.ds_texto,
                            ds_palavra_chave: this.form.ds_palavra_chave,
                            fl_status: this.form.fl_status,
                            ds_data: this.form.ds_data,
                            ds_hora: this.form.ds_hora,
                        });

                        this.snackbarConfig('Alterado com Sucesso.', 'success')
                        this.$router.push('/noticias')
                    } catch (error) {
                        console.log(error)
                        //this.snackbarConfig(error.message, 'error')
                    }
                }

            }
        }
    }
</script>

<style scoped>

</style>
