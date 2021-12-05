<template>
    <div>
        <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
            <v-card-title>
                <v-row>
                    <v-col>
                        <h3>Class Details: # {{$route.params.id}}</h3>
                        <v-row>
                            <v-col cols="6">
                                <div> Teacher</div>
                                <div class="d-flex" v-if="class_detail.teacher">
                                    <v-avatar size="100" class="mt-5">
                                        <v-img
                                            :src="
                                                class_detail.teacher.image_path
                                                ? class_detail.teacher.image_path
                                                : '/images/bg_login2.png'
                                            "
                                        ></v-img>
                                    </v-avatar>
                                    <div class="pl-5">
                                        <p class="mb-0 mt-2">Name: {{ class_detail.teacher.full_name }}</p>
                                        <p class="mb-0 text-subtitle-1">Email: {{ class_detail.teacher.email }}</p>
                                        <p class="mb-0 text-subtitle-1">Username: {{ class_detail.teacher.username }}</p>
                                        <p class="mb-0 text-subtitle-1"> RFID #: {{ class_detail.teacher.rfid_number }}</p>
                                    </div>
                                </div>
                                <div v-else>
                                    Not assigned
                                </div>
                            </v-col>
                            <v-divider color="gray" vertical></v-divider>
                            <v-col>
                                <div v-if="class_detail.subject" >
                                    <div class="">Subject</div>
                                    <div class="text-subtitle-1 ml-2">{{class_detail.subject.name}} - {{ class_detail.subject.description }}</div>
                                </div>
                                <div v-if="class_detail.room" >
                                    <div class="">Room</div>
                                    <div class="text-subtitle-1 ml-2">{{class_detail.room.name}} ({{ class_detail.room.seats ? class_detail.room.seats : 0 }} seat/s)</div>
                                </div>
                                <div v-if="class_detail.schedule" >
                                    <div class="">Schedule</div>
                                    <div class="text-subtitle-2 ml-2">
                                        {{class_detail.schedule.day}} {{
                                            moment(class_detail.schedule.time_start, "HH:mm:ss").format(
                                            "hh:mm a"
                                            ) +
                                            " - " +
                                            moment(class_detail.schedule.time_end, "HH:mm:ss").format(
                                            "hh:mm a"
                                            )
                                        }}
                                    </div>
                                </div>
                            </v-col>
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
                                                @click="addition_edition_dailog = true"
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
                                                @click="add_student_dialog = true"
                                            >
                                                <v-icon> mdi-account-plus </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Add student</span>
                                    </v-tooltip>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-title>
        </v-card>
        <div class="d-flex mt-4">
            <v-divider color="secondary"></v-divider>
                <p class="text-h5 mx-2">Attendances</p>
            <v-divider color="secondary"></v-divider>
        </div>
        <v-card class="mx-auto px-5 py-5 mt-3" color="primary" elevation="4" outlined>
            <v-card-text>
                <v-row>
                    <v-col cols="4">
                        <v-data-table
                            :footer-props="studentTable.footerProps"
                            :page="studentTable.page"
                            :pageCount="studentTable.numberOfPages"
                            :headers="studentTable.headers"
                            :options.sync="studentTable.options"
                            :items="students"
                            @update:options="studentsInitialize"
                            :server-items-length="studentTable.total"
                            :single-select="true"
                            item-key="id"
                            show-select
                            height="628"
                            v-model="selected_student"
                            checkbox-color="secondary"
                            class="elevation-1"
                        >
                            <template v-slot:item.display_name="{ item }">
                                {{item.student.full_name}}
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <v-btn
                                    fab
                                    class="mx-2"
                                    dark
                                    color="error"
                                    small
                                    @click="removeStudent(item)"
                                >
                                    <v-icon>
                                        mdi-delete
                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-col>
                    <v-col cols='8'>
                        <calendar v-if="selected_student.length > 0" :class_detail_student="selected_student[0]"  />
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <ClassDetail 
            :form="class_detail"  
            :dialogState="addition_edition_dailog"     
            @close="addition_edition_dailog = false"
            @save="addition_edition_dailog = false, saveClassDetail()"
        />
        <AddStudent 
            :form="chosen_students"
            :dialogState="add_student_dialog"
            :class_id ="$route.params.id"
            @close="add_student_dialog = false"
            @save="add_student_dialog =false,addStudentsToClass()"
        /> 
    </div>
</template>
<script>
import ClassDetail from "../../../components/Forms/ClassDetail.vue";
import AddStudent from "../../../components/Forms/AddStudent.vue";
import Calendar from "../../../components/Calendar.vue";
export default {
    components : {
        ClassDetail,
        Calendar,
        AddStudent
    },
    data : () => ({
        chosen_students: {
            ids : []
        },
        add_student_dialog: false,
        class_detail : {},
        students : [],
        student_loading : false,
        addition_edition_dailog : false,  
        selected_student : [],
        studentTable : {
            page: 0,
            total: 0,
            numberOfPages: 0,
            footerProps :{
                "items-per-page-options" : [12]
            },
            search_key : '',
            headers: [
                { text: "Name", value: "display_name" },
                { text: "Actions", value: "actions" },
            ],
            options : {}

        }
    }),
    created () {
        this.initialize()
    },
    watch : {
        'studentTable.search_key' : {
            handler(val){
                this.initialize()
            }
        }
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
                // sortBy: sortBy,
                // sortDesc: sortDesc,
                search_key: this.studentTable.search_key
            }

            console.log(params,'ss')

            this.$admin.get(`class/${this.$route.params.id}/students`,{params}).then(({data}) => {
                this.student_loading = false
                this.studentTable.page = data.page
                this.studentTable.total =  data.total
                this.studentTable.numberOfPages = data.last_page

                this.students = data.data

                if(this.selected_student.length < 1){
                    this.selected_student.push(data.data[0])
                }
            })
        },
        saveClassDetail(){

            let class_det = {
                subject_id : this.class_detail.subject.id,
                room_id : this.class_detail.room.id,
                schedule_id : this.class_detail.schedule.id,
                teacher_id : this.class_detail.teacher ? this.class_detail.teacher.id : null,
            }

            this.$admin.put('/class/update/'+this.class_detail.id,class_det).then(({data}) => {
                if(data.error){
                    this.errorNotify(data.error)
                }
                else { 
                    this.successNotify("Updated class_detail")
                }
                this.initialize()
            })
        },
        addStudentsToClass(){
            this.$admin.post(`class/${this.$route.params.id}/add-students`,{student_ids:this.chosen_students.ids}).then(({data}) => {
                this.successNotify("Successfully added students")
                this.chosen_students.ids = []
                this.initialize()
            })
        },
        async removeStudent(data) {
            let confirm = await this.deleteRecord("student and attendances");
            if (!confirm) return;

            this.$admin.post(`class/remove-student/${this.$route.params.id}`,data).then(({data}) => {
                this.initialize()
            })
        }
    }
}
</script>
