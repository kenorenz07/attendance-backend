import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
Vue.use(Vuetify)

export default new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#5e3984', 
                secondary: '#fb7b8e',
                accent: '#120055',
                error: '#f53851',
                info: '#2196F3',
                success: '#27b460',
                warning: '#ffc409',
            },
        },
    },
})