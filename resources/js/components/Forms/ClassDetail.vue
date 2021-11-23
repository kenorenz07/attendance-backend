<template>
    <div>
        <v-row justify="center">
            <v-dialog v-model="modalState" persistent max-width="600px">
                <v-card>
                <v-card-title>
                    <span class="text-h5">{{form.id ? "Update" : "Create"}} class</span>
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
                                :items="teachers"
                                v-if="!is_from_teacher"
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
                        @click="$emit('close')"
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
        form: {
            type: Object,
            required: true,
            default: {
                id:null,
                subject : null,
                schedule : null,
                room : null,
                teacher : null,
            }
        }
    },
    data: () => ({
      valid: true,
      show_pass : false,
      dialog: false,
      teachers : [],
      subjects : [],
      rooms : [],
      schedules : [],
    }),
    mounted(){
        this.initialize()
    },
    methods : {
        initialize(){
            // this.$admin.get("class/available").then(({ data }) => {
            //     this.availabe_classes = data;
            // });

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
            this.resetValidation
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