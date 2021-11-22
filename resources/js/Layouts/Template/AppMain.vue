<template>
    <v-main
        class="bg-gray-200 h-full overflow-auto no-scrollbar"
        style="height: 100vh; max-height: 100vh;"
    >
        <v-container >
            <transition 
                name="custom-classes-transition"
                enter-active-class="animated slideInRight"
                leave-active-class="animated slideOutLeft"
                mode="out-in"
            >
                <router-view> </router-view>
            </transition>
        </v-container>
    </v-main>
</template>
<style lang="scss">
    @import "https://cdn.jsdelivr.net/npm/animate.css@3.5.1";

</style>