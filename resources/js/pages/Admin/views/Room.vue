<template>
    <div>
        <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
        <v-card-title>
            <v-row>
                <v-col>
                    <h3>Room Details</h3>
                    <v-row>
                    <v-col cols="5">
                        <div class="d-flex">
                            <div class="pl-5">
                                <p class="mb-0 mt-2">Name: {{ room.name }}</p>
                                <p class="mb-0 text-subtitle-1">Node key: {{ room.node_key }} </p>
                                <p class="mb-0 text-subtitle-1">Seats: {{ room.seats }}</p>
                            </div>
                        </div>
                    </v-col>
                    <v-spacer></v-spacer>
                    <v-divider color="gray" vertical></v-divider>
                    <v-col>
                        <div>
                            <p class="text-h6 text-center"># of Classes</p>
                            <p class="text-h3 text-center">
                                {{
                                room.class_details ? room.class_details.length : 0
                                }}
                            </p>
                        </div>
                    </v-col>
                    <v-divider color="gray" vertical></v-divider>
                    <v-col>
                        <p class="text-h6 text-center">ACTIONS</p>
                        <div class="d-flex justify-content-around">
                        <v-tooltip bottom color="warning">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="mx-auto"
                                    fab
                                    dark
                                    color="warning"
                                    large
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="addition_edition_dailog = true"
                                >
                                    <v-icon> mdi-circle-edit-outline </v-icon>
                                </v-btn>
                            </template>
                            <span>Edit Room</span>
                        </v-tooltip>
                        </div>
                    </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-card-title>
        </v-card>    
        <ClassDetailList 
            :pagination="pagination"
            :class_loading="class_loading"
            :class_details="class_details"
            display_to="room"
        />
        <RoomForm :form="room" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveRoom()" />
    </div>
</template>
<script>
import RoomForm from '../../../components/Forms/Room.vue'
import ClassDetailList from "../../../components/ClassDetailList.vue";
export default {
    data: () => ({
        room : {},
        addition_edition_dailog: false  ,
        pagination :{ 
            page : 1,
            number_of_pages : 1
        },
        class_loading : false,
        class_details : [],

    }),
    components : {
        RoomForm,
        ClassDetailList
    },
    watch : {
        'pagination.page' : {
            deep:true,
            handler(val) {
                this.initialize()
            }
        }
    },
    mounted () {
        this.initialize()
    },
    methods : {
        initialize(){
            this.$admin.get('/room/show/'+this.$route.params.id).then(({data}) => {
                this.room = data
            })

            let params = {
                page: this.pagination.page
            }

            this.$admin.get("room/classes/" + this.$route.params.id,{ params }).then(({data}) => {
                this.class_details = data.data
                this.pagination.page = data.current_page
                this.pagination.number_of_pages = data.last_page
                this.class_loading = false
            })
      
        },
        saveRoom(){
            this.$admin.put('/room/update/'+this.room.id,this.room).then(({data}) => {
                this.initialize()
                this.successNotify("Updated room")
            })
        },
    }
}
</script>
