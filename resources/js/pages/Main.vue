<template>
  <v-app id="inspire" >
    <v-navigation-drawer
        app
        v-model="drawer"
        :mini-variant.sync="mini"
        permanent
    >
      <v-list-item class="px-2 pt-2">
          <v-img class="at-logo" src="/images/lenzy_logo_small.png"></v-img>
      </v-list-item>

      <v-divider></v-divider>
      <v-list    >
      <v-list-item-group
          v-model="set"
          color="primary"
      >
          <v-list-item
            v-for="item in items"
            :key="item.title"
            color="secondary"
            link
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
      <template v-slot:append>
        <v-list-item
            color="secondary"
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

    <v-app-bar app absolute  color="primary" class="text-white">
      <v-app-bar-nav-icon @click.stop="mini = !mini"></v-app-bar-nav-icon>

      <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>

    <v-main
        class="bg-gray-200 h-full overflow-auto no-scrollbar"
        style="height: 100vh; max-height: 100vh;"
    >
        <v-container>
            <router-view></router-view>
        </v-container>
    </v-main>
  </v-app>
</template>

<script>
  export default {
    data: () => ({ 
        drawer: true,
        items: [
          { title: 'Home', icon: 'mdi-home-city' },
          { title: 'My Account', icon: 'mdi-account' },
          { title: 'Users', icon: 'mdi-account-group-outline' },
        ],
        mini: true,
        set: 1,
    }),
  }
</script>