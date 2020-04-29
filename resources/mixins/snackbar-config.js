export default {
    data() {
        return {
            showSnackbar: false,
            snackbarColor: '',
            message: ''
        }
    },
    methods: {
        snackbarConfig (message = '', color = 'success') {
            this.showSnackbar = true
            this.snackbarColor = color
            this.message = message
        }
    }
}
