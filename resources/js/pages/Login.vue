<template>
    <div>
        <v-app id="inspire" class="at-login">
            <v-main>
                <v-container fill-height fluid>
                    <v-row  justify="center" align="center">
                        <v-card elevation="2" class="at-login-form" max-width="900">
                            <v-card-text>
                                <v-row>
                                    <v-col cols="5" align-self="center">
                                        <v-img
                                            lazy-src="images/lenzy_logo_big.png"
                                            max-height="500"
                                            src="images/lenzy_logo_big.png"
                                        ></v-img>
                                    </v-col>
                                    <v-col cols="7">
                                        <div class="ml-10">
                                            <p class="text-h5 mb-0 at-welcome">
                                                Welcome Back!
                                            </p>
                                            <p class="text-body-2">
                                                Login to continue
                                            </p>
                                            <v-form class="mt-7">
                                                <v-text-field
                                                    v-model="form.username"
                                                    label="Login"
                                                    name="login"
                                                    prepend-inner-icon="person"
                                                    type="text"
                                                    filled
                                                ></v-text-field>

                                                <v-text-field
                                                    v-model="form.password"
                                                    id="password"
                                                    label="Password"
                                                    name="password"
                                                    prepend-inner-icon="lock"
                                                    :type="show_pass ? 'text' : 'password'"
                                                    :append-icon="show_pass ? 'mdi-eye' : 'mdi-eye-off'"
                                                    @click:append="show_pass = !show_pass"
                                                    filled
                                                ></v-text-field>
                                            </v-form>
                                            <v-row justify="end" class="mr-1">
                                                <v-btn color="primary" rounded large @click="loginAdmin()">Login</v-btn>
                                            </v-row>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions >
                            
                            </v-card-actions>
                        </v-card>
                    </v-row>
                </v-container>
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
        loginAdmin() {
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
    .at-welcome{
        font-weight: bolder;
        color: #545454;
    }
    .at-login-form {
        background-color:  #ffffff00;
        backdrop-filter: blur(10px);
    }

    .at-login {
        background-image: url("/images/bg_login2.png");
        background-size:     cover;             
        background-repeat:   no-repeat;
    }
</style>