<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Admins</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addAdmin"
                    class="mx-2"
                    fab
                    dark
                    color="primary"
                >
                    <v-icon dark large>
                        mdi-plus
                    </v-icon>
                </v-btn>
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
                :items="admins"
                :options.sync="options"
                :server-items-length="total"
                :items-per-page="options.itemsPerPage"
                @update:options="initialize"
                :loading="loading"
                class="elevation-4"
            >
                <template v-slot:item.image="{ item }">
                    <v-avatar class="my-2">
                        <img v-if="item.image_path"
                            :src="item.image_path"
                            :alt="item.name"
                        >
                        <v-icon v-else color="primary" large dark>
                            mdi-account-supervisor-circle
                        </v-icon>
                    </v-avatar>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        color="warning"
                        small
                        @click="editAdmin(item)"
                    >
                        <v-icon >
                            mdi-pencil
                        </v-icon>
                    </v-btn>
                    <v-btn
                        fab
                        dark
                        color="error"
                        small
                        @click="deleteAdmin(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <AdminForm :form="admin" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveAdmin()" />


    </div>
</template>
<script>
  import AdminForm from '../../components/Forms/Admin.vue'
  export default {
    components: {
      AdminForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        admins: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Image", align:"start", value: "image" },
            // { text: "#", align:"start", value: "id" },
            { text: "Name", value: "name" },
            { text: "Username", value: "username" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        admin: {},
        search_key : '',
      };
    },
    //this one will populate new data set when admin changes current page. 
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
            // this.admin = {
            //     id:null,
            //     name:'',
            //     username: '',
            //     password: '',
            // }

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
            this.$admin.get('/admin/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.admins = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addAdmin(){
            this.admin = {
                id:null,
                name:'',
                username: '',
                password: '',
                image: ''
            }

            this.addition_edition_dailog = true
        },
        editAdmin(admin){
            this.admin = {
                id:admin.id,
                name:admin.name,
                username:admin.username,
                image : admin.image_path,
                password:'',
            }

            this.addition_edition_dailog = true
        },
        saveAdmin(){
            if(this.admin.id){
                    this.$admin.put('/admin/update/'+this.admin.id,this.admin).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated admin")
                    })
            }
            else{
                this.$admin.post('/admin/create',this.admin).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created admin")
                })
            }
        },
        async deleteAdmin(admin){
            let confirm = await this.deleteRecord(admin.name);
            if (!confirm) return;

            this.$admin.delete('/admin/delete/'+ admin.id).then(({data}) => {
                this.initialize() 
                this.successNotify("Deleted admin")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>