<template>
  <v-row justify="center">
    <v-dialog
      v-model="modalState"
      persistent
      max-width="600px"
    >
    
      <v-card>
        <v-card-title>
          <span class="text-h5">{{form.id ?'Edit' :'Create'}} Schedule</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
                <v-col      
                    cols="12"
                    sm="12"
                    md="12"
                >
                  <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                  >
                    <v-row>
                        <v-col cols=12>
                            <v-select
                                :items="days"
                                label="Day*"
                                required
                                v-model="form.day"
                                :rules="dayRule"
                            ></v-select>
                            <v-menu
                                ref="time_start_pick"
                                v-model="time_start_pick"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="form.time_start"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.time_start"
                                        label="Select start time"
                                        prepend-icon="mdi-alarm"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="time_start_pick"
                                    v-model="form.time_start"
                                    full-width
                                    @click:minute="$refs.time_start_pick.save(form.time_start)"
                                ></v-time-picker>
                            </v-menu>
                             <v-menu
                                ref="time_end_pick"
                                v-model="time_end_pick"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="form.time_end"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.time_end"
                                        label="Select end time "
                                        prepend-icon="mdi-alarm"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="time_end_pick"
                                    v-model="form.time_end"
                                    full-width
                                    @click:minute="$refs.time_end_pick.save(form.time_end)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
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
</template>
<script>
  export default {
    props: {
        dialogState: {
            type: Boolean,
            required: true
        },
        form: {
            type: Object,
            required: true,
            default: {
                id:null,
                time_start: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                time_end: (new Date()).getHours()+ ':' + (new Date()).getMinutes(),
                day: 'MONDAY',
            }
        }
    },
    data: () => ({
        valid: true,
        show_pass : false,
        dialog: false,
        time_start_pick: false,
        time_end_pick: false,
        days : ['MONDAY', 'TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY'],
    }),
    methods : {
        validate () {
          if(this.$refs.form.validate()){
            this.$emit('save')
            this.reset()
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