<template>
  <div>
    <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
      <v-card-title>
        <v-row>
          <v-col>
            <h3>Student Details</h3>
            <v-row>
              <v-col cols="5">
                <div class="d-flex">
                  <v-avatar size="100" class="mt-5">
                    <v-img
                      :src="
                        student.image_path
                          ? student.image_path
                          : '/images/bg_login2.png'
                      "
                    ></v-img>
                  </v-avatar>
                  <!-- </v-col>
                                <v-col> -->
                  <div class="pl-5">
                    <p class="mb-0 mt-2">Name: {{ student.full_name }}</p>
                    <p class="mb-0 text-subtitle-1">
                      Email: {{ student.email }}
                    </p>
                    <p class="mb-0 text-subtitle-1">
                      Username: {{ student.username }}
                    </p>
                    <p class="mb-0 text-subtitle-1">
                      RFID #: {{ student.rfid_number }}
                    </p>
                  </div>
                </div>
              </v-col>
              <v-spacer></v-spacer>
              <v-divider color="gray" vertical></v-divider>
              <v-col>
                <div>
                  <p class="text-h6 text-center"># of Classes</p>
                  <p class="text-h3 text-center">
                    {{
                      student.class_details ? student.class_details.length : 0
                    }}
                  </p>
                </div>
              </v-col>
              <v-divider color="gray" vertical></v-divider>
              <v-col>
                <p class="text-h6 text-center">ACTIONS</p>
                <div class="d-flex justify-content-around">
                  <v-tooltip bottom color="warning">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mx-auto"
                        fab
                        dark
                        color="warning"
                        large
                        v-bind="attrs"
                        v-on="on"
                        @click="editStudent"
                      >
                        <v-icon> mdi-circle-edit-outline </v-icon>
                      </v-btn>
                    </template>
                    <span>Edit profile</span>
                  </v-tooltip>

                  <v-tooltip bottom color="primary">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mx-auto"
                        fab
                        dark
                        v-bind="attrs"
                        v-on="on"
                        color="primary"
                        large
                        @click="assign_class = false,add_class_dialog = true"
                      >
                        <v-icon> mdi-plus </v-icon>
                      </v-btn>
                    </template>
                    <span>Add class</span>
                  </v-tooltip>
                  <v-tooltip bottom color="secondary">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mx-auto"
                        fab
                        dark
                        v-bind="attrs"
                        v-on="on"
                        color="secondary"
                        large
                        @click="assign_class = true,add_class_dialog = true"
                      >
                        <v-icon> mdi-clipboard-text </v-icon>
                      </v-btn>
                    </template>
                    <span>Assign to a class</span>
                  </v-tooltip>
                </div>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-card-title>
    </v-card>
    <div class="d-flex mt-2">
      <v-divider color="secondary"></v-divider>
      <p class="text-h5 mx-2">Classes</p>
      <v-divider color="secondary"></v-divider>
    </div>
    <StudentForm
      :form="teacher_edit"
      :dialogState="addition_edition_dailog"
      @close="(addition_edition_dailog = false), initialize()"
      @save="(addition_edition_dailog = false), saveStudent()"
    />
  </div>
</template>
<script>
import StudentForm from "../../../components/Forms/Student.vue";
export default {
  components: {
    StudentForm,
  },
  data: () => ({
    student: {},
    teacher_edit: {},
    add_class_dialog: false,
    class_loading: false,
    addition_edition_dailog: false,
    class_details : [],
    assign_class : false,
    pagination:{ 
      page : 1,
      number_of_pages : 1
    }
  }),
  mounted() {
    this.initialize();
  },
  watch : {
    "pagination.page" : function (newVal, oldVal){
      console.log(oldVal,newVal)
      this.initialize()
     },
  },
  methods: {
    initialize() {
      this.class_loading = true

      this.$admin.get("student/" + this.$route.params.id).then(({ data }) => {
        this.student = data;
      });

      let params = {
        page: this.pagination.page
      }

      this.$admin.get("student/classes/" + this.$route.params.id,{ params }).then(({data}) => {
        this.class_details = data.data
        this.pagination.page = data.current_page
        this.pagination.number_of_pages = data.last_page
        this.class_loading = false
      })
      
    },
    editStudent() {
      this.teacher_edit = {
        id: this.student.id,
        username: this.student.username,
        image: this.student.image_path,
        first_name: this.student.first_name,
        middle_name: this.student.middle_name,
        last_name: this.student.last_name,
        rfid_number: this.student.rfid_number,
        email: this.student.email,
        password: "",
      };

      this.addition_edition_dailog = true;
    },
    saveStudent() {
      this.$admin
        .put("/student/update/" + this.student.id, this.teacher_edit)
        .then(({ data }) => {
          this.initialize();
          this.successNotify("Updated student");
        });
    },
  },
};
</script>
