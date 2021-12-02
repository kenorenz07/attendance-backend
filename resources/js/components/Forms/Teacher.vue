<template>
  <v-row justify="center">
    <v-dialog
      v-model="modalState"
      persistent
      max-width="600px"
    >
    
      <v-card>
        <v-card-title>
          <span class="text-h5">{{form.id ?'Edit' :'Create'}} Teacher</span>
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
                        <v-col cols=6>
                          <div>
                            <v-img
                              lazy-src="images/bg_login.png"
                              max-height="250"
                              max-width="250"
                              height="200"
                              :src="form.image ? form.image : '/images/bg_login2.png'"
                            ></v-img>
                            <v-btn 
                              icon 
                              large
                              color="primary"
                              @click="$refs.inputUpload.click()"
                            >
                                <v-icon>mdi-image-plus</v-icon>
                            </v-btn>
                            <input v-show="false" ref="inputUpload" type="file" @change="processImage" >
                            
                          </div>
                          
                            <v-text-field
                              label="Username*"
                              required class="pt-2"
                              :rules="usernameRule"
                              v-model="form.username"
                            ></v-text-field>
                            <v-text-field
                              label="Password"
                              required class="pt-1"
                              :rules="form.id ? [] : usernameRule"
                              v-model="form.password"
                              :type="show_pass ? 'text' : 'password'"
                              :append-icon="show_pass ? 'mdi-eye' : 'mdi-eye-off'"
                              @click:append="show_pass = !show_pass"
                            ></v-text-field>
                         
                        </v-col>
                        <v-col cols=6>
                            <v-text-field
                              label="First name*"
                              required class="pt-1"
                              v-model="form.first_name"
                              :rules="first_nameRule"
                            ></v-text-field>
                            <v-text-field
                              label="Middle name*"
                              required class="pt-1"
                              v-model="form.middle_name"
                              :rules="middle_nameRule"
                            ></v-text-field>
                            <v-text-field
                              label="Last name*"
                              required class="pt-1"
                              v-model="form.last_name"
                              :rules="last_nameRule"
                            ></v-text-field>
                            <v-text-field
                              label="RFID #*"
                              required class="pt-1"
                              :rules="rfid_numberRule"
                              v-model="form.rfid_number"
                            ></v-text-field>
                             <v-text-field
                              label="Email*"
                              required class="pt-1"
                              v-model="form.email"
                              :rules="emailRule"
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
            @click="$emit('close'),reset()"
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
                username: '',
                password: '',
                image: '',
                first_name : '',
                middle_name : '',
                last_name : '',
                rfid_number : '',
                email : '',
            }
        }
    },
    data: () => ({
      valid: true,
      show_pass : false,
      dialog: false,
    }),
    methods : {
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