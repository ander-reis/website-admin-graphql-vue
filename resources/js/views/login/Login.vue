<template>
    <v-container fill-height>
        <v-layout justify-center align-center>

            <v-flex xs12 sm6 md4 lg3>
                <v-card class="elevation-12 something">
                    <v-toolbar dark color="blue-grey">
                        <v-toolbar-title>Entrar</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-progress-circular
                            indeterminate
                            color="white"
                            v-show="isLoading"
                            width="2"
                        ></v-progress-circular>

                    </v-toolbar>

                    <v-card-text>
                        <v-form>
                            <v-text-field
                                prepend-icon="email"
                                name="email"
                                label="Email"
                                placeholder="email"
                                v-model.trim="$v.user.email.$model"
                                counter
                                :error-messages="emailErrors"
                                :success="!$v.user.email.$invalid"
                            ></v-text-field>

                            <v-text-field
                                prepend-icon="lock"
                                name="password"
                                label="Senha"
                                :type="typePasswordText"
                                v-model.trim="$v.user.password.$model"
                                :error-messages="passwordErrors"
                                :success="!$v.user.password.$invalid"
                                :append-icon="passwordIcon"
                                counter
                                @click:append="hidePassword = !hidePassword"
                            ></v-text-field>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" :disabled="$v.$invalid" @click="submit" large>Enviar</v-btn>
                    </v-card-actions>

                    <v-snackbar v-model="showSnackbar" top>
                        {{ error }}
                        <v-btn color="pink" icon @click="showSnackbar = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                    </v-snackbar>

                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>

    import { required, email, minLength } from 'vuelidate/lib/validators'
    import AuthService from '../../../services/auth-service'
    import { formatError } from '../../utils'

    export default {
        name: 'Login',
        data: () => ({
            error: undefined,
            isLoading: false,
            showSnackbar: false,
            hidePassword: true,
            user: {
                email: '',
                password: ''
            },
        }),
        validations () {
            return {
                user: {
                    email: {
                        required: required,
                        email
                    },
                    password: {
                        required,
                        minLength: minLength(6)
                    }
                }
            }
        },
        computed: {
            emailErrors () {
                const errors = []
                const email = this.$v.user.email
                if (!email.$dirty) {
                    return errors
                }
                !email.required && errors.push('Email é obrigatório')
                !email.email && errors.push('Insira um Email válido')
                return errors
            },
            passwordErrors () {
                const errors = []
                const password = this.$v.user.password
                if (!password.$dirty) {
                    return errors
                }
                !password.required && errors.push('Senha é obrigatória')
                // !password.minLength && errors.push(`Insira pelo menos ${password.$params.minLength.type.min} caracteres!`)
                !password.minLength && errors.push(`Insira pelo menos ${password.$params.minLength.min} caracteres!`)
                return errors
            },
            passwordIcon() {
                return this.hidePassword ? 'visibility' : 'visibility_off'
            },
            typePasswordText () {
                return this.hidePassword ? 'password' : 'text'
            }
        },
        methods: {
            async submit () {
                this.isLoading = true
                try {
                    await AuthService.login(this.user)
                    this.$router.push(this.$route.query.redirect || '/dashboard')
                } catch (error) {
                    this.showSnackbar = true
                    this.error = formatError(error.message)
                } finally {
                    this.isLoading = false
                }
            }
        }
    }
</script>

<style scoped>
    .something {
        /*color: #000;*/
        /*background-color: #f8fafc;*/
    }
</style>
