<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Rooms</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addRoom"
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
                :items="rooms"
                :options.sync="options"
                :server-items-length="total"
                :items-per-page="options.itemsPerPage"
                @update:options="initialize"
                :loading="loading"
                class="elevation-4"
            >
                <template v-slot:item.actions="{ item }">
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        color="success"
                        small
                        @click="$router.push('room/'+item.id)"
                    >
                        <v-icon >
                            mdi-eye
                        </v-icon>
                    </v-btn>
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        color="warning"
                        small
                        @click="editRoom(item)"
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
                        @click="deleteRoom(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <RoomForm :form="room" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveRoom()" />


    </div>
</template>
<script>
  import RoomForm from '../../components/Forms/Room.vue'
  export default {
    components: {
      RoomForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        rooms: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Name", value: "name" },
            { text: "Node Key", value: "node_key" },
            { text: "Seats", value: "seats" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        room: {},
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
            this.room = {
                id:null,
                name:'',
                node_key: null,
                seats: 0,
            }

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
            this.$admin.get('/room/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.rooms = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addRoom(){
            this.room = {
                id:null,
                name:'',
                node_key: null,
                seats: 0,
            }

            this.addition_edition_dailog = true
        },
        editRoom(room){
            this.room = {
                id:room.id,
                name: room.name,
                node_key: room.node_key,
                seats: room.seats,
            }

            this.addition_edition_dailog = true
        },
        saveRoom(){
            if(this.room.id){
                    this.$admin.put('/room/update/'+this.room.id,this.room).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated room")
                    })
            }
            else{
                this.$admin.post('/room/create',this.room).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created room")
                })
            }
        },
        async deleteRoom(room){
            let confirm = await this.deleteRecord(room.name);
            if (!confirm) return;

            this.$admin.delete('/room/delete/'+ room.id).then(({data}) => {

                if(data.error) {
                    this.errorNotify("This room belong to a class")
                    return
                }
                this.initialize() 
                this.successNotify("Deleted room")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>