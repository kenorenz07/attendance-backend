<template>
    <div>
        <v-row justify="center">
            <v-dialog v-model="modalState" persistent max-width="600px">
                <v-card>
                <v-card-title>
                    <span class="text-h5">{{form.id ? "Update" : is_assign ? "Assign to" :"Create"}} class</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                        <v-form
                            ref="form"
                            v-model="valid"
                            lazy-validation
                        >
                            <v-select
                                v-if="!is_assign"
                                :items="subjects"
                                v-model="form.subject"
                                label="Select subject*"
                                item-text="name"
                                item-value="name"
                                max-height="auto"
                                autocomplete
                                required
                                :rules="subjectRule"
                                return-object
                            >
                                <template slot="item" slot-scope="data">
                                    <template>
                                    <v-list-item-content>
                                        <v-list-item-title
                                        v-html="data.item.name"
                                        ></v-list-item-title>
                                        <v-list-item-subtitle
                                        v-html="data.item.description"
                                        ></v-list-item-subtitle>
                                    </v-list-item-content>
                                    </template>
                                </template>
                            </v-select>
                            <v-select
                                v-if="!is_assign"
                                :items="rooms"
                                v-model="form.room"
                                label="Select room*"
                                item-text="name"
                                item-value="name"
                                max-height="auto"
                                autocomplete
                                return-object
                                required
                                :rules="roomRule"
                            >
                                <template slot="item" slot-scope="data">
                                    <template>
                                    <v-list-item-content>
                                        <v-list-item-title
                                        v-html="data.item.name"
                                        ></v-list-item-title>
                                        <v-list-item-subtitle
                                        v-html="data.item.seats + ' seat/s'"
                                        ></v-list-item-subtitle>
                                    </v-list-item-content>
                                    </template>
                                </template>
                            </v-select>
                            <v-select
                                v-if="!is_assign"
                                :items="schedules"
                                v-model="form.schedule"
                                label="Select schedule*"
                                item-text="id"
                                item-value="id"
                                max-height="auto"
                                autocomplete
                                return-object
                                required
                                :rules="scheduleRule"
                            >
                                <template slot="selection" slot-scope="data">
                                    <!-- HTML that describe how select should render selected items -->
                                    {{ data.item.day +" "+ moment(data.item.time_start, "HH:mm:ss").format(
                                        "hh:mm a"
                                        ) +
                                        " - " +
                                        moment(data.item.time_end, "HH:mm:ss").format(
                                        "hh:mm a"
                                        )}}
                                </template>
                                <template slot="item" slot-scope="data">
                                    <template>
                                        <v-list-item-content>
                                            <v-list-item-title v-html="data.item.day"></v-list-item-title>
                                            <v-list-item-subtitle>
                                                {{moment(data.item.time_start, "HH:mm:ss").format(
                                                "hh:mm a"
                                                ) +
                                                " - " +
                                                moment(data.item.time_end, "HH:mm:ss").format(
                                                "hh:mm a"
                                                )}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </template>
                            </v-select>
                            <v-select
                                v-if="is_assign"
                                :items="availabe_classes"
                                v-model="form.class_detail"
                                label="Select class to assign*"
                                item-text="id"
                                item-value="id"
                                max-height="auto"
                                autocomplete
                                return-object
                                required
                                chips
                                :rules="classDetailRule"
                            >
                                <!-- <template slot="selection" slot-scope="data">
                                    Subject : {{data.item.subject.name}}, Room : {{data.item.room.name}} {{data.item.room.seats + ' seat/s'}} ,Schedule : {{moment(data.item.schedule.time_start, "HH:mm:ss").format("hh:mm a") +" - " +moment(data.item.schedule.time_end, "HH:mm:ss").format("hh:mm a")}}
                                </template> -->
                                <template slot="item" slot-scope="data">
                                    <template>
                                        <v-list-item-content>
                                            <v-list-item-title > Class # {{data.item.id}}</v-list-item-title>
                                            <v-list-item-subtitle>
                                               Subject : {{data.item.subject.name}}, Room : {{data.item.room.name}} ({{data.item.room.seats + ' seat/s'}}) ,Schedule : {{moment(data.item.schedule.time_start, "HH:mm:ss").format("hh:mm a") +" - " +moment(data.item.schedule.time_end, "HH:mm:ss").format("hh:mm a")}}
                                            </v-list-item-subtitle>
                                        </v-list-item-content>
                                    </template>
                                </template>
                            </v-select>
                            <v-select
                                :items="teachers"
                                v-if="!is_from_teacher && !is_assign"
                                v-model="form.teacher"
                                label="Select teacher"
                                item-text="display_name"
                                item-value="display_name"
                                max-height="auto"
                                autocomplete
                                return-object
                            >
                                <template slot="item" slot-scope="data">
                                    <template>
                                    <v-list-item-content>
                                        <v-list-item-title v-html="data.item.display_name"></v-list-item-title>
                                    </v-list-item-content>
                                    </template>
                                </template>
                            </v-select>
                            <v-menu
                                v-if="!is_assign"
                                ref="menu"
                                v-model="date_menu"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="form.start_end_date"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                <v-text-field

                                    v-model="dateRangeText"
                                    label="Select start to end date"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="form.start_end_date"
                                    range
                                >
                                    <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="date_menu = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(form.start_end_date)"
                                        >
                                            OK
                                        </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-form>
                        </v-col>
                    </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="reset(),$emit('close')"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="validate"
                    >
                        Save
                    </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
 export default {
    props: {
        dialogState: {
            type: Boolean,
            required: true
        },
        is_from_teacher: {
            type: Boolean,
            default : false,
        },
        is_assign: {
            type: Boolean,
            default : false,
        },
        form: {
            type: Object,
            required: true,
            default: {
                id:null,
                subject : null,
                schedule : null,
                room : null,
                teacher : null,
                start_end_date : [(new Date()).toISOString().split('T')[0],(new Date(new Date().getTime()+(14*24*60*60*1000))).toISOString().split('T')[0]],
            }
        }
    },
    data: () => ({
      valid: true,
      show_pass : false,
      dialog: false,
      date_menu : false,
      teachers : [],
      subjects : [],
      rooms : [],
      schedules : [],
      availabe_classes : [],
      dateRangeText:''
    }),
    mounted(){
        this.initialize()
    },
    watch : {
        is_assign () {
            this.initialize()
        },
        'form.start_end_date' : {
            handler(val) {
                if(val.length > 1){
                    this.dateRangeText = val.join(' ~ ')
                }
            }
        } 
    },
    computed: {
    },
    methods : {
        initialize(){
            console.log(this.is_assign,'this/is_assign')
            if(this.is_assign) {
                this.$admin.get("class/available").then(({ data }) => {
                    this.availabe_classes = data;
                });
            }
            else {

                this.$admin.get("subject/index").then(({ data }) => {
                    this.subjects = data;
                });

                this.$admin.get("room/index").then(({ data }) => {
                    this.rooms = data;
                });

                this.$admin.get("schedule/index").then(({ data }) => {
                    this.schedules = data;
                });

                this.$admin.get("teacher/index").then(({ data }) => {
                    this.teachers = data;
                });
            }

        },
        processImage(e){
          var fileReader = new FileReader()
          
          fileReader.readAsDataURL(e.target.files[0])
          fileReader.onload = (e) => {
            this.form.image = e.target.result
          }
        },
        validate () {
            if(this.$refs.form.validate()){
                this.$emit('save')
                this.reset()
                this.initialize()
            }
        },
        reset () {
          this.$refs.form.reset()
        },
        resetValidation () {
          this.$refs.form.resetValidation()
        },
    },
    computed : {
      modalState(){
        return this.dialogState
      }
    }
    
  }
</script>