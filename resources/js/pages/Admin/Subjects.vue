<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Subjects</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addSubject"
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
                :items="subjects"
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
                        color="warning"
                        small
                        @click="editSubject(item)"
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
                        @click="deleteSubject(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <SubjectForm :form="subject" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveSubject()" />


    </div>
</template>
<script>
  import SubjectForm from '../../components/Forms/Subject.vue'
  export default {
    components: {
      SubjectForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        subjects: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Name", value: "name" },
            { text: "Description", value: "description" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        subject: {},
        search_key : '',
      };
    },
    //this one will populate new data set when subject changes current page. 
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
            this.subject = {
                id:null,
                name:'',
                description: '',
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
            this.$admin.get('/subject/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.subjects = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addSubject(){
            this.subject = {
                 id:null,
                name:'',
                description: '',
            }

            this.addition_edition_dailog = true
        },
        editSubject(subject){
            this.subject = {
                id:subject.id,
                name:subject.name,
                description:subject.description,
            }

            this.addition_edition_dailog = true
        },
        saveSubject(){
            if(this.subject.id){
                    this.$admin.put('/subject/update/'+this.subject.id,this.subject).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated subject")
                    })
            }
            else{
                this.$admin.post('/subject/create',this.subject).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created subject")
                })
            }
        },
        async deleteSubject(subject){
            let confirm = await this.deleteRecord(subject.name);
            if (!confirm) return;

            this.$admin.delete('/subject/delete/'+ subject.id).then(({data}) => {

                if(data.error) {
                    this.errorNotify("This subjects belong to a class")
                    return
                }
                this.initialize() 
                this.successNotify("Deleted subject")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>