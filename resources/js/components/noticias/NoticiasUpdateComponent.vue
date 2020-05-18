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
                :success="!$v.form.categoria.$invalid"
                v-model.trim="$v.form.categoria.$model"
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

            <v-textarea
                autocomplete="email"
                label="Texto"
                :error-messages="DsTextoErrors"
                :success="!$v.form.ds_texto.$invalid"
                v-model.trim="$v.form.ds_texto.$model"
            ></v-textarea>

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
        >Editar</v-btn>

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
    import SnackbarConfigMixin from "../../../mixins/snackbar-config";
    import {minLength, required} from "vuelidate/lib/validators";
    import NoticiasService from "../../../services/noticias-service";
    import moment from "moment";

    export default {
        name: "NoticiasEditarComponent",
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
                    id: undefined,
                    ds_categoria: undefined
                },
                ds_resumo: null,
                ds_texto: null,
                ds_palavra_chave: [],
                fl_status: true,
                ds_data: new Date().toISOString().substr(0, 10),
                ds_hora: new Date().getHours() + ":" + new Date().getMinutes(),
            },
            defaultSelected: {
                id: undefined,
                ds_categoria: undefined
            },
            menuDatePicker: false,
            menuTimePicker: false,
            modal: false,
        }),
        created() {
            this.getDataCategorias
            this.getDataNoticia
        },
        mounted() {
            this.setTitle({title: 'Editar Notícia'})
        },
        destroyed() {
            // destroyed subscribe
            this.subscriptions.forEach(s => s.unsubscribe())
        },
        validations: {
            form: {
                categoria: {required},
                ds_resumo: {required, minLength: minLength(6)},
                ds_texto: {required},
            }
        },
        computed: {
            getDataCategorias() {
                try {
                    this.subscriptions.push(
                        NoticiasService.noticiaCategoriaQuery().subscribe((data) => {
                            this.items = data;
                            this.loading = false;
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
                        this.loading = false
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
                const id_categoria = this.$v.form.categoria
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
            // getNoticiaEditar(id) {
            //     this.subscriptions.push(
            //         NoticiasService.noticiasQuery({id: id}).subscribe((data) => {
            //             const palavra_chave = data[0].ds_palavra_chave.split(",");
            //             console.log(data[0])
            //             data[0].ds_palavra_chave = palavra_chave
            //             this.form = data[0];
            //             this.loading = false
            //         }))
            // },
            async submit() {
                const id = this.$route.params.id
                console.log(this.form.categoria.id)
            }
        }
    }
</script>

<style scoped>

</style>
