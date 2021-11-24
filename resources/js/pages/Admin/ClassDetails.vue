<template>
    <div>
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Classes</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addClassDetail"
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
                :items="class_details"
                :options.sync="options"
                :server-items-length="total"
                :items-per-page="options.itemsPerPage"
                @update:options="initialize"
                :loading="loading"
                class="elevation-4"
            >
                <template v-slot:item.room_id="{ item }">
                    {{item.room.name}}
                </template>
                <template v-slot:item.subject_id="{ item }">
                    {{item.subject.name}}
                </template>
                <template v-slot:item.schedule_id="{ item }">
                    {{item.schedule.day}} {{
                        moment(item.schedule.time_start, "HH:mm:ss").format(
                        "hh:mm a"
                        ) +
                        " - " +
                        moment(item.schedule.time_end, "HH:mm:ss").format(
                        "hh:mm a"
                        )
                    }}
                </template>
                <template v-slot:item.teacher_id="{ item }">
                    {{item.teacher_id ? item.teacher.display_name : 'Not assigned'}}
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        color="success"
                        small
                        @click="$router.push('class-detail/'+item.id)"
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
                        @click="editClassDetail(item)"
                    >
                        <v-icon >
                            mdi-pencil
                        </v-icon>
                    </v-btn>
                    <v-btn
                        fab
                        class="mx-2"
                        dark
                        color="error"
                        small
                        @click="deleteClassDetail(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <ClassDetail 
            :form="class_detail"  
            :dialogState="addition_edition_dailog"     
            @close="addition_edition_dailog = false"
            @save="addition_edition_dailog = false, saveClassDetail()"
        />
    </div>
</template>
<script>
  import ClassDetail from '../../components/Forms/ClassDetail.vue'
  export default {
    components: {
      ClassDetail
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        class_details: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            // { text: "#", align:"start", value: "id" },
            { text: "Subject", value: "subject_id" },
            { text: "Room", value: "room_id" },
            { text: "Schedule", value: "schedule_id" },
            { text: "Teacher", value: "teacher_id" },
            { text: "# of students",  align:"center", value: "student_count" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        class_detail: {},
        search_key : '',
      };
    },
    //this one will populate new data set when class_detail changes current page. 
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
            this.class_detail = {
                id:null,
                subject : null,
                schedule : null,
                room : null,
                teacher : null
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
            this.$admin.get('/class/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.class_details = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addClassDetail(){
            this.class_detail = {
                id:null,
                subject : null,
                schedule : null,
                room : null,
                teacher : null
            }

            this.addition_edition_dailog = true
        },
        editClassDetail(class_detail){
            this.class_detail = {
                id:class_detail.id,
                subject : class_detail.subject,
                schedule : class_detail.schedule,
                room : class_detail.room,
                teacher : class_detail.teacher
            }

            this.addition_edition_dailog = true
        },
        saveClassDetail(){

            let class_det = {
                subject_id : this.class_detail.subject.id,
                room_id : this.class_detail.room.id,
                schedule_id : this.class_detail.schedule.id,
                teacher_id : this.class_detail.teacher ? this.class_detail.teacher.id : null,
            }

            if(this.class_detail.id){
                    this.$admin.put('/class/update/'+this.class_detail.id,class_det).then(({data}) => {
                        if(data.error){
                            this.errorNotify(data.error)
                            return
                        }
                        this.initialize()
                        this.successNotify("Updated class_detail")
                    })
            }
            else{
                this.$admin.post('/class/create',class_det).then(({data}) =>{
                    if(data.error){
                        this.errorNotify(data.error)
                        return
                    }
                    this.initialize()
                    this.successNotify("Created class_detail")
                })
            }
        },
        async deleteClassDetail(class_detail){
            let confirm = await this.deleteRecord(class_detail.full_name);
            if (!confirm) return;

            this.$admin.delete('/class/delete/'+ class_detail.id).then(({data}) => {
                if(data.error){
                    this.errorNotify(data.error)
                    return
                }
                this.initialize() 
                this.successNotify("Deleted class_detail")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>