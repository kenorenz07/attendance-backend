<script>
export default {
    data: vm => ({}),
    methods: {
      
        successNotify(event) {
            this.$root.$notify(
                event+" successfully",
                {
                     left : false, right : true,bottom : true, top : false
                },
                "success"
            )
        },
        errorNotify(message) { 
            this.$root.$notify(
                message,
                {
                    left : false, right : true,bottom : false, top : true
                },
                "error"
            )
        }
    }
};
</script>
