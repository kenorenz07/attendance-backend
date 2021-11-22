<script>
export default {
    data: vm => ({}),
    methods: {
      
        deleteRecord(name) {
            return new Promise((resolve, reject) => {
                this.$root
                    .$confirm(
                        "Delete "+name,
                        "Are you sure you want to delete this data ?",
                        {
                            color: 'error',
                           
                        },
                        {}
                    )
                    .then(result => {
                        if (result) resolve(result);
                        else reject(result);
                    })
                    .catch(error => {
                        resolve(error);
                        // reject(error);
                    });
            });
        },
        updateRecord() {
            return new Promise((resolve, reject) => {
                this.$root
                    .$confirm(
                        this._lang("dialog.update.title"),
                        this._lang("dialog.update.content"),
                        "",
                        {}
                    )
                    .then(result => {
                        resolve(result);
                    })
                    .catch(error => {
                        resolve(error);
                    });
            });
        },

    }
};
</script>
