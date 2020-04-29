import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
// import 'roboto-fontface/css/roboto/roboto-fontface.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import colors from 'vuetify/lib/util/colors'
import pt from 'vuetify/src/locale/pt.ts'

Vue.use(Vuetify);

const opts = {
    lang: {
        locales: { pt },
        current: 'pt',
    },
    icons: {
        iconfont: 'md'
    },
    theme: {
        dark: false,
        default: 'light',
        themes: {
            light: {
                primary: colors.red.darken1, // #E53935
                secondary: colors.red.lighten4, // #FFCDD2
                accent: colors.indigo.base, // #3F51B5
                info: colors.blue,
                error: colors.pink.darken2,
                success: colors.teal.lighten1,
                warning: colors.purple.darken1,
                test: colors.shades.white
            },
            dark: {
                primary: colors.teal.darken1,
                accent: colors.indigo.darken1,
                info: colors.blue,
                error: colors.pink.darken2,
                success: colors.teal.lighten1,
                warning: colors.purple.darken1
            }
        }
    }
}

export default new Vuetify(opts)
