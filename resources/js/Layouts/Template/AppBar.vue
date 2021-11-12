<template>
    <v-app-bar app absolute  color="primary" class="text-white">
        <v-app-bar-nav-icon @click.stop="$emit('changeStatusDrawer')" color="white"></v-app-bar-nav-icon>
        <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>
</template>
