<template>
    <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <h2> Schedules</h2>
                <v-spacer></v-spacer>
                <v-btn
                    @click="addSchedule"
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
                :items="schedules"
                :options.sync="options"
                :server-items-length="total"
                :items-per-page="options.itemsPerPage"
                @update:options="initialize"
                :loading="loading"
                class="elevation-4"
            >
                <template v-slot:item.time_start="{item}">
                    {{moment(item.time_start, "HH:mm:ss").format("hh:mm a")}}
                </template>
                <template v-slot:item.time_end="{item}">
                    {{moment(item.time_end, "HH:mm:ss").format("hh:mm a")}}
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        color="success"
                        small
                        @click="$router.push('schedule/'+item.id)"
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
                        @click="editSchedule(item)"
                    >
                        <v-icon >
                            mdi-pencil
                        </v-icon>
                    </v-btn>
                    <!-- <v-btn
                        fab
                        dark
                        color="error"
                        small
                        @click="deleteSchedule(item)"
                    >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                    </v-btn> -->
                </template>
            </v-data-table>
        </v-card>
        <ScheduleForm :form="schedule" :dialogState="addition_edition_dailog" @close="addition_edition_dailog = false" @save="addition_edition_dailog = false,saveSchedule()" />
    </div>
</template>
<script>
  import ScheduleForm from '../../components/Forms/Schedule.vue'
  export default {
    components: {
      ScheduleForm
    },
    data() {
      return {
        page: 0,
        total: 0,
        numberOfPages: 0,
        schedules: [],
        loading: true,
        options: {
          itemsPerPage: 10
        },
        footerProps :{
          "items-per-page-options" : [5,10,15, 30 ]
        },
        headers: [
            { text: "Day", value: "day" },
            { text: "Time start", value: "time_start" },
            { text: "Time end", value: "time_end" },
            { text: "Actions",  align:"center", value: "actions" },
        ],
        addition_edition_dailog: false,
        schedule: {},
        search_key : '',
      };
    },
    //this one will populate new data set when schedule changes current page. 
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
            this.schedule = {
                id:null,
                time_start: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                time_end: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                day: 'MONDAY',
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
            this.$admin.get('/schedule/all', { params }).then(({data}) => {
                    //Then injecting the result to datatable parameters.
                    this.loading = false;
                    this.schedules = data.data;
                    this.page = data.page;
                    this.total = data.total;
                    this.numberOfPages = data.last_page;
                });
        },
        addSchedule(){
            this.schedule = {
                id:null,
                time_start: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                time_end: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                day: 'MONDAY',
            }

            this.addition_edition_dailog = true
        },
        editSchedule(schedule){
            this.schedule = {
                id:schedule.id,
                time_start: schedule.time_start,
                time_end: schedule.time_end,
                day: schedule.day,
            }

            this.addition_edition_dailog = true
        },
        saveSchedule(){
            if(this.schedule.id){
                    this.$admin.put('/schedule/update/'+this.schedule.id,this.schedule).then(({data}) => {
                        this.initialize()
                        this.successNotify("Updated schedule")
                    })
            }
            else{
                this.$admin.post('/schedule/create',this.schedule).then(({data}) =>{
                    this.initialize()
                    this.successNotify("Created schedule")
                })
            }
        },
        async deleteSchedule(schedule){
            let confirm = await this.deleteRecord(schedule.name);
            if (!confirm) return;

            this.$admin.delete('/schedule/delete/'+ schedule.id).then(({data}) => {

                if(data.error) {
                    this.errorNotify("This schedule belong to a class")
                    return
                }
                this.initialize() 
                this.successNotify("Deleted schedule")
            })
        }
    },  
        
    mounted() {
        this.initialize();
    },
  }
</script>