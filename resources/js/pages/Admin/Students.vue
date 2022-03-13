<template>
    <div>
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Students</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addStudent"
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
                :items="students"
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
                            :alt="item.full_name"
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
                        color="success"
                        small
                        @click="$router.push('student/'+item.id)"
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
                        @click="editStudent(item)"
                    >
                        <v-icon >
                            mdi-pencil
                        </v-icon>
                    </v-btn>
                    <!-- <v-btn
                        fab
                        class="mx-2"
                        dark
                        color="error"
                        small
                        @click="deleteStudent(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn> -->
                </template>
            </v-data-table>
        </v-card>
        <StudentForm :form="student" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveStudent()" />

    </div>
</template>
<script>
  import StudentForm from '../../components/Forms/Student.vue'
  export default {
    components: {
      StudentForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        students: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Image", align:"start", value: "image" },
            { text: "Name", value: "display_name" },
            { text: "Username", value: "username" },
            { text: "RFID #", value: "rfid_number" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        student: {},
        search_key : '',
      };
    },
    //this one will populate new data set when student changes current page. 
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
            this.student = {
                id:null,
                username: '',
                password: '',
                image: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                rfid_number : '',
                email : '',
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
            this.$admin.get('/student/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.students = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addStudent(){
            this.student = {
                id:null,
                username: '',
                password: '',
                image: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                rfid_number : '',
                email : '',
            }

            this.addition_edition_dailog = true
        },
        editStudent(student){
            this.student = {
                id:student.id,
                username:student.username,
                image : student.image_path,
                first_name : student.first_name,
                middle_name : student.middle_name,
                last_name : student.last_name,
                rfid_number : student.rfid_number,
                email : student.email,
                password:'',
            }

            this.addition_edition_dailog = true
        },
        saveStudent(){
            if(this.student.id){
                    this.$admin.put('/student/update/'+this.student.id,this.student).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated student")
                    })
            }
            else{
                this.$admin.post('/student/create',this.student).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created student")
                })
            }
        },
        async deleteStudent(student){
            let confirm = await this.deleteRecord(student.full_name);
            if (!confirm) return;

            this.$admin.delete('/student/delete/'+ student.id).then(({data}) => {
                if(data.error){
                    this.errorNotify(data.error)
                    return
                }
                this.initialize() 
                this.successNotify("Deleted student")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>