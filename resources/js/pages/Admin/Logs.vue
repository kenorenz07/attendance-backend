<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Logs</h2>
                <v-spacer></v-spacer>

                <div class="w-10">
                    <v-text-field
                        v-model="search_key"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </div>
            </v-card-title>
            <v-data-table
                color="primary"
                :footer-props="footerProps"
                :page="page"
                :pageCount="numberOfPages"
                :headers="headers"
                :items="logs"
                :options.sync="options"
                :server-items-length="total"
                :items-per-page="options.itemsPerPage"
                @update:options="initialize"
                :loading="loading"
                class="elevation-4"
            >
                <template v-slot:item.loggable="{ item }">
                    {{item.loggable.name || item.loggable.full_name}}
                </template>
                <template v-slot:item.created_at="{ item }">
                    {{moment(item.created_at).format("MMMM Do, YYYY hh:mm a")}}
                </template>
            </v-data-table>
        </v-card>


    </div>
</template>
<script>
  export default {
    components: {
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        logs: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Loggged User", value: "loggable" },
            { text: "Log", value: "message" },
            { text: "Time stamp", value: "created_at" },
        ],
        search_key : '',
      };
    },
    //this one will populate new data set when room changes current page. 
    watch: {
        search_key : {
            handler(val){
                this.initialize()
            }
        }
    },
    methods: {
        //Reading data from API method. 
        initialize() {

            this.loading = true;

            const { page, itemsPerPage,sortBy,sortDesc } = this.options;

            console.log(this.options.sortBy,this.options.sortDesc)

            let params = { 
                page: page,
                per_page: itemsPerPage,
                sortBy: sortBy,
                sortDesc: sortDesc,
                search_key: this.search_key
            } 
            this.$admin.get('/logs', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.logs = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
       
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>