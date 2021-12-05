<template>
    <div>
        <v-row justify="center">
            <v-dialog v-model="modalState" persistent max-width="600px">
                <v-card>
                <v-card-title>
                    <span class="text-h5">Add to a class</span>
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
                                :items="availabe_classes"
                                v-model="form.class_detail_id"
                                label="Select class to assign*"
                                item-text="id"
                                item-value="id"
                                max-height="auto"
                                autocomplete
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
        student_id : {
            required : true
        },
        form: {
            type: Object,
            required: true,
            default: {
                class_detail_id : null
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
      availabe_classes : []
    }),
    mounted(){
        this.initialize()
    },
    watch : {
    },
    methods : {
        initialize(){
            let params = {
                student_id : this.student_id
            }
            this.$admin.get("class/available",{params}).then(({ data }) => {
                this.availabe_classes = data;
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