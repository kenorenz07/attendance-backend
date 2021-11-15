<template>
  <v-row justify="center">
    <v-dialog
      v-model="modalState"
      persistent
      max-width="600px"
    >
    
      <v-card>
        <v-card-title>
          <span class="text-h5">{{form.id ?'Edit' :'Create'}} Admin</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
                <v-col      
                    cols="12"
                    sm="12"
                    md="12"
                >
                    <v-row>
                        <v-col cols=6>
                          <v-text-field
                              label="Name*"
                              required
                              v-model="form.name"
                          ></v-text-field>
                          <v-switch
                            v-model="form.is_super"
                            label="Super Admin"
                          ></v-switch>
                        </v-col>
                        <v-col cols=6>
                            <v-text-field
                                label="Username*"
                                required
                                type="text"
                                v-model="form.username"
                            ></v-text-field>
                            <v-text-field
                                label="Password*"
                                required
                                v-model="form.password"
                                :type="show_pass ? 'text' : 'password'"
                                :append-icon="show_pass ? 'mdi-eye' : 'mdi-eye-off'"
                                @click:append="show_pass = !show_pass"
                            ></v-text-field>
                        </v-col>
                    </v-row>
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
            @click="$emit('save')"
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
                username: '',
                is_super : 0,
                password: '',
            }
        }
    },
    data: () => ({
        show_pass : false,
        dialog: false,
    }),
    methods : {
    },
    computed : {
      modalState(){
          return this.dialogState
      }
    }
    
  }
</script>