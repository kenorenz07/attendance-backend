<template>
    <div>
        <v-app id="inspire">
            <v-main>
                <v-row no-gutters>
                    <v-col cols="7"> 
                         <img
                            src="/images/05.jpg"
                            class="image-login"
                        ></img>
                    </v-col>
                    <v-col  cols="5"> 
                        <v-container
                            fluid
                            fill-height
                        >
                            <v-layout
                                align-center
                                justify-center
                            >
                                <v-card elevation="6">
                                    <v-toolbar
                                        color="light-green"
                                        dark
                                        flat
                                    >
                                        <v-toolbar-title>Attendance Backoffice</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-form>
                                            <v-text-field
                                                color="light-green"
                                                v-model="form.username"
                                                label="Login"
                                                name="login"
                                                prepend-icon="person"
                                                type="text"
                                            ></v-text-field>

                                            <v-text-field
                                                color="light-green"
                                                v-model="form.password"
                                                id="password"
                                                label="Password"
                                                name="password"
                                                prepend-icon="lock"
                                                :type="show_pass ? 'text' : 'password'"
                                                :append-icon="show_pass ? 'mdi-eye' : 'mdi-eye-off'"
                                                @click:append="show_pass = !show_pass"
                                            ></v-text-field>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions >
                                        <v-spacer></v-spacer>
                                        <v-btn color="light-green" class="text-white" @click="login_admin()">Login</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-layout>
                        </v-container>
                    </v-col>
                </v-row>
            </v-main>
        </v-app>
    </div>
</template>
<script>
export default {
    data: () => ({
        form : {
            username : 'admin_test',
            password : 123123,
        },
        show_pass : false
    }),
    methods : {
        login_admin() {
            axios.post('admin/login', this.form).then(({data}) => {
                localStorage.setItem('token',data.token)
                this.$router.push({name : "main"})  
            })
        }
    }
}
</script>
<style lang="scss" scoped>
 .image-login {
    width: 100%;
    height: 100vh;
 }
</style>