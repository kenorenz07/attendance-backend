<template>
    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
      :left="left"
      :right="right"
      :top="top"
      :bottom="bottom"
      :color="color"
      transition="scroll-x-reverse-transition"
      elevation="5"
      rounded="pill"
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
            <v-btn
                icon
                @click="snackbar = false"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
      </template>
    </v-snackbar>
</template>

<script>
export default {
    data : () => ({
        snackbar: false,
        left : false,
        right : false,
        bottom : false,
        top : false,
        timeout : 1000,
        color: 'primary',
        text : 'This is a snackbar'
    }),
    methods : {
        open(text, position ,color) {
            this.snackbar = true
            this.text = text
            this.left = position.left
            this.right = position.right
            this.bottom = position.bottom
            this.top = position.top
            this.color = color
        },
    }
}
</script>