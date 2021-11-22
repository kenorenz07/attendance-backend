import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
Vue.use(Vuetify)

export default new Vuetify({
    theme: {
        options: {
            customProperties: true
        },
        themes: {
            light: {
                primary: '#6938c6', 
                secondary: '#fb7b8e',
                accent: '#9a809d',
                error: '#f53851',
                info: '#2196F3',
                success: '#27b460',
                warning: '#ffc409',
            },
            dark: {
                background: "#6938c6",
                primary: '#6938c6', 
                secondary: '#fb7b8e',
                accent: '#9a809d',
            }
        },
    },
})