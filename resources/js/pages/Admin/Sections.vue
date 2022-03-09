<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Sections</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addSection"
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
                :items="sections"
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
                        @click="$router.push('section/'+item.id)"
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
                        @click="editSection(item)"
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
                        @click="deleteSection(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <SectionForm :form="section" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveSection()" />


    </div>
</template>
<script>
  import SectionForm from '../../components/Forms/Section.vue'
  export default {
    components: {
      SectionForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        sections: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Name", value: "name" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        section: {},
        search_key : '',
      };
    },
    //this one will populate new data set when section changes current page. 
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
            this.section = {
                id:null,
                name:'',
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
            this.$admin.get('/section/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.sections = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addSection(){
            this.section = {
                id:null,
                name:'',
            }

            this.addition_edition_dailog = true
        },
        editSection(section){
            this.section = {
                id:section.id,
                name: section.name,
            }

            this.addition_edition_dailog = true
        },
        saveSection(){
            if(this.section.id){
                    this.$admin.put('/section/update/'+this.section.id,this.section).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated section")
                    })
            }
            else{
                this.$admin.post('/section/create',this.section).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created section")
                })
            }
        },
        async deleteSection(section){
            let confirm = await this.deleteRecord(section.name);
            if (!confirm) return;

            this.$admin.delete('/section/delete/'+ section.id).then(({data}) => {

                if(data.error) {
                    this.errorNotify("This section belong to a class")
                    return
                }
                this.initialize() 
                this.successNotify("Deleted section")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>