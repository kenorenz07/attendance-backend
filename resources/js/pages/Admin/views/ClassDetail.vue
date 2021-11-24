<template>
    <div>
        <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
            <v-card-title>
                <v-row>
                    <v-col>
                        <h3>Class ID: {{$route.params.id}}</h3>
                        <v-row>
                            <v-col cols="5">
                                <div> Teacher</div>
                                <div v-if="class_detail.teacher" class="d-flex">
                                    <v-avatar size="100" class="mt-5">
                                        <v-img
                                            :src="
                                                class_detail.teacher
                                                ? class_detail.teacher.image_path
                                                : '/images/bg_login2.png'
                                            "
                                        ></v-img>
                                    </v-avatar>
                                    <!-- </v-col>
                                                    <v-col> -->
                                    <div class="pl-5">
                                        <p class="mb-0 mt-2">Name: {{ class_detail.teacher.full_name }}</p>
                                        <p class="mb-0 text-subtitle-1">
                                        Email: {{ class_detail.teacher.email }}
                                        </p>
                                        <p class="mb-0 text-subtitle-1">
                                        Username: {{ class_detail.teacher.username }}
                                        </p>
                                        <p class="mb-0 text-subtitle-1">
                                        RFID #: {{ class_detail.teacher.rfid_number }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else>
                                    Not assigned
                                </div>
                            </v-col>
                            <v-col>
                                <div>
                                    <p class="text-center">Subject</p>
                                    <p class="text-subtitle-1 text-center">{{class_detail.subject.name}}</p>
                                    <p class="text-subtitle-1 text-center"> {{ class_detail.subject.description }}</p>
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div>
                                    <p class=" text-center">Room</p>
                                    <p class="text-subtitle-1 text-center">{{class_detail.room.name}}</p>
                                    <p class="ttext-subtitle-1 text-center">Seats available :{{ class_detail.room.seats ? class_detail.room.seats : 0 }} seat/s</p>
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div>
                                    <p class=" text-center">Schedule</p>
                                    <p class="text-subtitle-1 text-center">
                                        {{class_detail.schedule.day}} {{
                                            moment(class_detail.schedule.time_start, "HH:mm:ss").format(
                                            "hh:mm a"
                                            ) +
                                            " - " +
                                            moment(class_detail.schedule.time_end, "HH:mm:ss").format(
                                            "hh:mm a"
                                            )
                                        }}
                                    </p>
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <p class="text-h6 text-center">ACTIONS</p>
                                <div class="d-flex justify-content-around">
                                    <v-tooltip bottom color="secondary">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                class="mx-auto"
                                                fab
                                                dark
                                                color="warning"
                                                large
                                                v-bind="attrs"
                                                v-on="on"
                                                @click="editClassDetail"
                                            >
                                                <v-icon> mdi-circle-edit-outline </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Edit profile</span>
                                    </v-tooltip>

                                    <v-tooltip bottom color="secondary">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                class="mx-auto"
                                                fab
                                                dark
                                                v-bind="attrs"
                                                v-on="on"
                                                color="primary"
                                                large
                                                @click="add_class_dialog = true"
                                            >
                                                <v-icon> mdi-plus </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Add class</span>
                                    </v-tooltip>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-title>
        </v-card>
    </div>
</template>
<script>
import ClassDetail from "../../../components/Forms/ClassDetail.vue";
export default {
    components : {
        ClassDetail
    },
    data : () => ({
        class_detail : {},
        students : [],
        student_loading : false,
        addition_edition_dailog : false,  
        studentTable : {
            page: 0,
            total: 0,
            numberOfPages: 0,
            footerProps :{
                "items-per-page-options" : [5,10,15, 30 ]
            },
            search_key : '',
            headers: [
                // { text: "#", align:"start", value: "id" },
                { text: "Image", value: "image_path" },
                { text: "Name", value: "display_name" },
                { text: "RFID #", value: "rfid_number" },
                { text: "username", value: "username" },
                { text: "Actions",  align:"center", value: "actions" },
            ],
            options : {}
        }
    }),
    created () {
        this.initialize()
    },
    methods : {
        initialize () {

            this.$admin.get("class/"+this.$route.params.id).then(({data}) => {
                this.class_detail = data
            })
            this.studentsInitialize()
        },
        studentsInitialize() {
            this.student_loading = true;
            const { page, itemsPerPage,sortBy,sortDesc } = this.studentTable.options;

            let params = { 
                page: page,
                per_page: itemsPerPage,
                sortBy: sortBy,
                sortDesc: sortDesc,
                search_key: this.search_key
            } 
        },
        editClassDetail(){
            
        }
    }
}
</script>
