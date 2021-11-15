<template>
  <v-app id="inspire" >
    <app-nav-bar :mini="mini" @changeStatusDrawer="mini = !mini"></app-nav-bar>

    <app-bar @changeStatusDrawer="mini = !mini"></app-bar>

    <app-main></app-main>
  </v-app>
</template>

<script>
  import AppBar from "../Layouts/Template/AppBar.vue"
  import AppNavBar from "../Layouts/Template/AppNavBar.vue"
  import AppMain from "../Layouts/Template/AppMain.vue"

  export default {
    data: () => ({ 
      mini: true,
    }),
    components : {
      AppBar,
      AppNavBar,
      AppMain,
    }
  }
</script>