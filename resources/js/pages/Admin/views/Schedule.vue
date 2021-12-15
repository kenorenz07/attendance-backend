<template>
    <div>
        <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
        <v-card-title>
            <v-row>
                <v-col>
                    <h3>Schedule Details</h3>
                    <v-row>
                    <v-col cols="5">
                        <div class="d-flex">
                            <div class="pl-5">
                                <p class="mb-0 mt-2">Day: {{ schedule.day }}</p>
                                <p class="mb-0 text-subtitle-1">Starting time: {{moment(schedule.time_start, "HH:mm:ss").format("hh:mm a")}} </p>
                                <p class="mb-0 text-subtitle-1">Ending time: {{moment(schedule.time_end, "HH:mm:ss").format("hh:mm a")}}</p>
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
                                schedule.class_details ? schedule.class_details.length : 0
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
                            <span>Edit Schedule</span>
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
            display_to="schedule"
        />
        <ScheduleForm :form="schedule" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveSchedule()" />
    </div>
</template>
<script>
import ScheduleForm from '../../../components/Forms/Schedule.vue'
import ClassDetailList from "../../../components/ClassDetailList.vue";
export default {
    data: () => ({
        schedule : {},
        addition_edition_dailog: false  ,
        pagination :{ 
            page : 1,
            number_of_pages : 1
        },
        class_loading : false,
        class_details : [],

    }),
    components : {
        ScheduleForm,
        ClassDetailList
    },
    mounted () {
        this.initialize()
    },
    methods : {
        initialize(){
            this.$admin.get('/schedule/show/'+this.$route.params.id).then(({data}) => {
                this.schedule = data
            })

            let params = {
                page: this.pagination.page
            }

            this.$admin.get("schedule/classes/" + this.$route.params.id,{ params }).then(({data}) => {
                this.class_details = data.data
                this.pagination.page = data.current_page
                this.pagination.number_of_pages = data.last_page
                this.class_loading = false
            })
      
        },
        saveSchedule(){
            this.$admin.put('/schedule/update/'+this.schedule.id,this.schedule).then(({data}) => {
                this.initialize()
                this.successNotify("Updated schedule")
            })
        },
    }
}
</script>
