<template>
  <v-app id="inspire" >
    <app-nav-bar :mini="mini" @changeStatusDrawer="mini = !mini"></app-nav-bar>

    <app-bar @changeStatusDrawer="mini = !mini"></app-bar>
    <confirm ref="confirm"></confirm>
    <notification ref="notify"></notification>
    <app-main></app-main>
  </v-app>
</template>

<script>
  import confirm from "../components/Confirm.vue"
  import notification from "../components/Notification.vue"
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
      confirm,
      notification
    },
    mounted() {
      this.$root.$confirm = this.$refs.confirm.open
      this.$root.$notify = this.$refs.notify.open
    }
  }
</script>
<style lang="scss">
  .at-toast-error { 
    background-color: var(--v-error-base) !important;
    font-size: large!important;
    padding: 15px!important;
    padding-left: 25px!important;
    padding-right: 25px!important;
  }
</style>