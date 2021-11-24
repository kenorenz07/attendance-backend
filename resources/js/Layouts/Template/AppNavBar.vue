<template>
  <v-navigation-drawer
        app
        v-model="drawer"
        :mini-variant.sync="mini_status"
        permanent
    >
        <v-list-item class="px-2 pt-2">
            <v-img class="at-logo" src="/images/lenzy_logo_small.png"></v-img>
        </v-list-item>
        <v-list >
            <v-list-item
                v-for="item in items"
                :key="item.title"
                color="secondary"
                :class="{
                    'v-list-item--active': checkIfActive(item.route)
                }"
                @click.stop="$router.push(item.route)"
            >
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <template v-slot:append>
            <v-list-item
                color="secondary"
                @click.stop="logoutAdmin"
            >
                <v-list-item-icon>
                    <v-icon>mdi-logout-variant</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>Log-out</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </template>
    </v-navigation-drawer>
</template>
<script>
export default {
    data: () => ({
        drawer: true,
        items: [
            { title: 'Dashboard', icon: 'mdi-view-dashboard', route: '/dashboard' },
            { title: 'Admins', icon: 'mdi-account-supervisor', route: '/admins' },
            { title: 'Teachers', icon: 'mdi-account-multiple', route: '/teachers' },
            { title: 'Classes', icon: 'mdi-book-variant', route: '/class-details' },
            { title: 'Students', icon: 'mdi-account', route: '/students' },
        ],
    }),
    props : {
        mini : {
            require: true,
            type : Boolean
        }
    },
    computed : {
        activeRoute () {
            return this.$route
        },
        mini_status: {
            get: function() {
                return this.mini
            },
            set: function(value) {
                console.log(value,"status")
                this.$emit('changeStatusDrawer')
            }
        }
    },
    methods : {
        logoutAdmin(){
            this.$admin.post('/logout').then(({data}) => {
                localStorage.removeItem("token")
                this.$router.push('/login')
            })
        },
        checkIfActive(route){
            let route_text = route.split("/")
            return route_text[1].includes(this.activeRoute.name)
        }
    },
    
}
</script>