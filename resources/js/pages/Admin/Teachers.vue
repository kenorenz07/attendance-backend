<template>
    <div>
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Teachers</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addTeacher"
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
                :items="teachers"
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
                        @click="$router.push('teacher/'+item.id)"
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
                        @click="editTeacher(item)"
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
                        @click="deleteTeacher(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <TeacherForm :form="teacher" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveTeacher()" />

    </div>
</template>
<script>
  import TeacherForm from '../../components/Forms/Teacher.vue'
  export default {
    components: {
      TeacherForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        teachers: [],
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
        teacher: {},
        search_key : '',
      };
    },
    //this one will populate new data set when teacher changes current page. 
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
            this.teacher = {
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
            this.$admin.get('/teacher/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.teachers = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addTeacher(){
            this.teacher = {
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
        editTeacher(teacher){
            this.teacher = {
                id:teacher.id,
                username:teacher.username,
                image : teacher.image_path,
                first_name : teacher.first_name,
                middle_name : teacher.middle_name,
                last_name : teacher.last_name,
                rfid_number : teacher.rfid_number,
                email : teacher.email,
                password:'',
            }

            this.addition_edition_dailog = true
        },
        saveTeacher(){
            if(this.teacher.id){
                    this.$admin.put('/teacher/update/'+this.teacher.id,this.teacher).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated teacher")
                    })
            }
            else{
                this.$admin.post('/teacher/create',this.teacher).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created teacher")
                })
            }
        },
        async deleteTeacher(teacher){
            let confirm = await this.deleteRecord(teacher.full_name);
            if (!confirm) return;

            this.$admin.delete('/teacher/delete/'+ teacher.id).then(({data}) => {
                if(data.error){
                    this.errorNotify(data.error)
                    return
                }
                this.initialize() 
                this.successNotify("Deleted teacher")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>