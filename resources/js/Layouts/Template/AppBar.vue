<template>
    <v-app-bar app absolute  color="primary" class="text-white">
        <v-app-bar-nav-icon @click.stop="$emit('changeStatusDrawer')" color="white"></v-app-bar-nav-icon>
        <v-toolbar-title>Attendance Backoffice</v-toolbar-title>
        <v-spacer></v-spacer>
         <div>

            <v-tooltip v-if="!$vuetify.theme.dark" bottom>
                <template v-slot:activator="{ on }">
                <v-btn v-on="on" color="primary" small fab @click="darkMode">
                    <v-icon class="mr-1" color="black">mdi-weather-night</v-icon>
                </v-btn>
                </template>
                <span>Dark Mode On</span>
            </v-tooltip>

            <v-tooltip v-else bottom>
                <template v-slot:activator="{ on }">
                <v-btn v-on="on" color="primary" small fab @click="darkMode">
                    <v-icon color="yellow">mdi-white-balance-sunny</v-icon>
                </v-btn>
                </template>
                <span>Dark Mode Off</span>
            </v-tooltip>

        </div>
    </v-app-bar>
</template>
<script>
export default {
    data : () => ({
        items: [
            { title: 'Click Me' },
            { title: 'Click Me' },
            { title: 'Click Me' },
            { title: 'Click Me 2' },
        ],
    }),
    mounted() {
        if(localStorage.getItem('theme_dark')) {
            this.$vuetify.theme.dark = JSON.parse(localStorage.getItem('theme_dark'));
        }
        else {
            localStorage.setItem('theme_dark', this.$vuetify.theme.dark)
            this.$vuetify.theme.dark = JSON.parse(localStorage.getItem('theme_dark'));
        }
    },
    methods : {
        darkMode() {
            
            console.log(this.$vuetify.theme.dark)

            this.$vuetify.theme.dark = !JSON.parse(localStorage.getItem('theme_dark'));
            localStorage.setItem('theme_dark', this.$vuetify.theme.dark)
        }
    }
}
</script>