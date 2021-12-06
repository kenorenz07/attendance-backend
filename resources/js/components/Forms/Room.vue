<template>
  <v-row justify="center">
    <v-dialog
      v-model="modalState"
      persistent
      max-width="600px"
    >
    
      <v-card>
        <v-card-title>
          <span class="text-h5">{{form.id ?'Edit' :'Create'}} Room</span>
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
                            <v-text-field
                                label="Name*"
                                required
                                v-model="form.name"
                                :rules="nameRule"
                            ></v-text-field>
                            <v-text-field
                                label="Node key*"
                                required
                                type="text"
                                :rules="nodeKeyRule"
                                v-model="form.node_key"
                            ></v-text-field>
                            <v-text-field
                                label="Seats"
                                required
                                type="number"
                                v-model="form.seats"
                                :rules="seatsRule"
                            ></v-text-field>
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
                name:'',
                node_key: '',
                seats: 0,
            }
        }
    },
    data: () => ({
      valid: true,
      show_pass : false,
      dialog: false,
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