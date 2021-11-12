<template>
  <v-navigation-drawer
        app
        v-model="drawer"
        :mini-variant.sync="mini"
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
                link
                :to="item.route"
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
                @click="logoutAdmin"
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
            { title: 'Admin', icon: 'mdi-account-supervisor', route: '/admin' },
            { title: 'Dashboard', icon: 'mdi-view-dashboard', route: '/dashboard' },
        ],
    }),
    props : {
        mini : {
            require: true,
            type : Boolean
        }
    },
    methods : {
        logoutAdmin(){
            this.$admin.post('/logout').then(({data}) => {
                localStorage.removeItem("token")
                this.$router.push('/login')
            })
        }
    },
    watch : {
        "mini" : {
            handler(val) {
                this.$emit('changeStatusDrawer')
            }
        }
    }
    
}
</script>
