<template>
  <v-row justify="center">
    <v-dialog
      v-model="modalState"
      persistent
      max-width="600px"
    >
    
      <v-card>
        <v-card-title>
          <span class="text-h5">Add Student/s</span>
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
                                :items="students"
                                v-model="form.ids"
                                label="Select student/s"
                                item-text="display_name"
                                item-value="id"
                                max-height="auto"
                                :rules="studentRule"
                                required
                                autocomplete
                                multiple
                                chips
                            >
                                <template v-slot:prepend-item>
                                    <v-list-item
                                        ripple
                                        @click="toggle"
                                    >
                                        <v-list-item-action>
                                            <v-icon :color="form.length > 0 ? 'indigo darken-4' : ''">
                                                {{ icon_selected }}
                                            </v-icon>
                                        </v-list-item-action>
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                Select All
                                            </v-list-item-title>
                                        </v-list-item-content>
                                        </v-list-item>
                                    <v-divider class="mt-2"></v-divider>
                                </template>
                            </v-select>
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
        class_id : {
            required: true
        },
        form: {
            required: true,
            type: Object,
            default : {
                ids : []
            }
        }
    },
    data: () => ({
      valid: true,
      dialog: false,
      students : []
    }),
    mounted(){
        this.initialize()
    },
    methods : {
        initialize() {
            let params = { 
                class_id: this.class_id,
            }
            this.$admin.get("student/index",{params}).then(({ data }) => {
                this.students = data;
            });
        },
        toggle () {
            this.$nextTick(() => {
                if (this.selectsAll) {
                    console.log('gsgs')
                    this.form.ids = []
                } else {
                    console.log('aaa')
                    this.form.ids = this.students.map((student) => {
                        return student.id
                    })
                }
            })
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
      },
      selectsAll () {
        return this.form.ids.length === this.students.length
      },
      selectSome () {
        return this.form.ids.length > 0 && !this.selectsAll
      },
      icon_selected () {
        if (this.selectsAll) return 'mdi-close-box'
        if (this.selectSome) return 'mdi-minus-box'
        return 'mdi-checkbox-blank-outline'
      },
    }
    
  }
</script>