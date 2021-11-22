<template>
     <div>
        <v-card
            class="mx-auto px-5 py-5 mt-3"
            elevation="4"
            outlined
        >
            <v-card-title> 
                <v-row>
                    <v-col> 
                        <h3>Teacher Details</h3>
                        <v-row>

                            <v-col cols=1>

                                <v-avatar  size="100" class="mt-5">
                                    <v-img
                                        :src="teacher.image_path ? teacher.image_path : 'https://picsum.photos/id/11/500/300'"
                                    ></v-img>
                                </v-avatar>
                            </v-col>
                            <v-col>
                                <p class="mb-0 mt-2">Name: {{teacher.full_name}}</p>
                                <p class="mb-0 text-subtitle-1">Email: {{teacher.email}}</p>
                                <p class="mb-0 text-subtitle-1">Username: {{teacher.username}}</p>
                                <p class="mb-0 text-subtitle-1">RFID #: {{teacher.rfid_number}}</p>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div class="bg-grey">
                                    <p class="text-h6 text-center"># of Students</p>
                                    <p class="text-h3 text-center ">24</p>
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div class="bg-grey">
                                    <p class="text-h6 text-center"># of Classes</p>
                                    <p class="text-h3 text-center ">24</p>
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div class="bg-grey">
                                    <p class="text-h6 text-center"># of Subjects</p>
                                    <p class="text-h3 text-center">24</p>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-title>
        </v-card>
        <v-row class="mt-2">
            <v-divider class="mt-7" color="secondary"></v-divider>
            <v-col cols=1> 
                <p class="h3">
                    Classes
                </p>
            </v-col>
            <v-divider class="mt-7" color="secondary"></v-divider>
        </v-row>
        <v-row>
            <v-col cols="3" v-for="class_detail in teacher.class_details" :key="class_detail.id">
                <v-card color="primary" class="text-white">
                    <v-card-title>
                        <p class="text-h5"> {{class_detail.subject.name}}</p>
                        <v-spacer></v-spacer>
                        <p class="text-h5"> {{class_detail.students.length}} student/s</p>
                    </v-card-title>
                    <v-card-text class="text-white">
                        <v-row>
                            <v-col>
                                <p class="text-h6 mb-0 ">Room : {{class_detail.room.name}}  </p>
                                <span>Seats available : {{ class_detail.room.seats}} seat/s</span>
                            </v-col>
                            <v-divider vertical color="white" class="mb-5"></v-divider>
                            <v-col>
                                <p class="text-h6 mb-0 ">Schedule :</p>
                                <p class="text-subtitle-1 pl-3"> {{class_detail.schedule.time_start + ' - ' + class_detail.schedule.time_end}}</p>
                            </v-col>
                            <v-divider vertical color="white" class="mb-5"></v-divider>
                            <v-col cols=3 align-self="center" align="center">
                                <v-btn
                                    class="mx-2"
                                    fab
                                    dark
                                    color="success"
                                    small
                                    @click="$router.push('teacher/'+teacher.id)"
                                >
                                    <v-icon >
                                        mdi-eye
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
      
        </v-row>
    </div>
</template>
<script>
export default {
    data : () => ({
        teacher : {}
    }),
    mounted () {
        this.initialize()
    },
    methods : {
        initialize() {
            this.$admin.get('teacher/'+this.$route.params.id).then(({data}) => {
                this.teacher = data
            })
        }
    }

}
</script>
