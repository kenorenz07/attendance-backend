<template>
  <div>
    <v-card class="mx-auto px-5 py-5 mt-3" elevation="4" outlined>
      <v-card-title>
        <v-row>
          <v-col>
            <h3>Teacher Details</h3>
            <v-row>
              <v-col cols="5">
                <div class="d-flex">
                  <v-avatar size="100" class="mt-5">
                    <v-img
                      :src="
                        teacher.image_path
                          ? teacher.image_path
                          : '/images/bg_login2.png'
                      "
                    ></v-img>
                  </v-avatar>
                  <!-- </v-col>
                                <v-col> -->
                  <div class="pl-5">
                    <p class="mb-0 mt-2">Name: {{ teacher.full_name }}</p>
                    <p class="mb-0 text-subtitle-1">
                      Email: {{ teacher.email }}
                    </p>
                    <p class="mb-0 text-subtitle-1">
                      Username: {{ teacher.username }}
                    </p>
                    <p class="mb-0 text-subtitle-1">
                      RFID #: {{ teacher.rfid_number }}
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
                      teacher.class_details ? teacher.class_details.length : 0
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
                        @click="editTeacher"
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
    <v-row v-if="class_loading">
      <v-col
        cols="4"
        v-for="i in 6"
        :key="i"
      >
        <v-skeleton-loader
          type="card"
          height="300"
        ></v-skeleton-loader>
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col
        cols="4"
        v-for="class_detail in class_details"
        :key="class_detail.id"
      >
        <v-card color="primary" class="text-white">
          <v-card-title>
            <p class="text-h5">Class # {{ class_detail.id }}</p>
            <v-spacer></v-spacer>
            <v-btn
              class="mx-2"
              fab
              dark
              color="success"
              small
              @click="$router.push('/class-detail/' + class_detail.id)"
            >
              <v-icon> mdi-eye </v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text class="text-white">
            <p class="text-h6 mb-0">Subject :</p>
            <span class="text-subtitle-1 pl-3">
              {{ class_detail.subject.name }} - 
              <span class="text-subtitle-2">
                {{ class_detail.subject.description }}
              </span>
            </span>
             
            <p class="text-h6 mb-0">Room :</p>
            <span class="text-subtitle-1 pl-3"
              >{{ class_detail.room.name }}, Seats available :
              {{ class_detail.room.seats ? class_detail.room.seats : 0 }} seat/s</span
            >
            <p class="text-h6 mb-0">Schedule :</p>
            <p class="text-subtitle-1 pl-3">
              {{class_detail.schedule.day}} {{
                moment(class_detail.schedule.time_start, "HH:mm:ss").format(
                  "hh:mm a"
                ) +
                " - " +
                moment(class_detail.schedule.time_end, "HH:mm:ss").format(
                  "hh:mm a"
                )
              }}
            </p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <div class="text-center">
      <v-pagination
        color="secondary"
        v-model="pagination.page"
        :length="pagination.number_of_pages"
        circle
      ></v-pagination>
    </div>
    <TeacherForm
      :form="teacher_edit"
      :dialogState="addition_edition_dailog"
      @close="(addition_edition_dailog = false), initialize()"
      @save="(addition_edition_dailog = false), saveTeacher()"
    />
    <ClassDetail 
      :form="class_detail"
      :dialogState="add_class_dialog"     
      :is_from_teacher="true"
      :is_assign="assign_class"
      @close="add_class_dialog = false, initialize()"
      @save="add_class_dialog = false, addClass()"
    />
  </div>
</template>
<script>
import TeacherForm from "../../../components/Forms/Teacher.vue";
import ClassDetail from "../../../components/Forms/ClassDetail.vue";
export default {
  components: {
    TeacherForm,
    ClassDetail
  },
  data: () => ({
    teacher: {},
    teacher_edit: {},
    add_class_dialog: false,
    class_loading: false,
    addition_edition_dailog: false,
    class_details : [],
    assign_class : false,
    class_detail : {
      subject : null,
      schedule : null,
      room : null,
      teacher : null,
      class_detail : null
    },
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

      this.$admin.get("teacher/" + this.$route.params.id).then(({ data }) => {
        this.teacher = data;
      });

      let params = {
        page: this.pagination.page
      }

      this.$admin.get("teacher/classes/" + this.$route.params.id,{ params }).then(({data}) => {
        this.class_details = data.data
        this.pagination.page = data.current_page
        this.pagination.number_of_pages = data.last_page
        this.class_loading = false
      })
      
    },
    editTeacher() {
      this.teacher_edit = {
        id: this.teacher.id,
        username: this.teacher.username,
        image: this.teacher.image_path,
        first_name: this.teacher.first_name,
        middle_name: this.teacher.middle_name,
        last_name: this.teacher.last_name,
        rfid_number: this.teacher.rfid_number,
        email: this.teacher.email,
        password: "",
      };

      this.addition_edition_dailog = true;
    },
    saveTeacher() {
      this.$admin
        .put("/teacher/update/" + this.teacher.id, this.teacher_edit)
        .then(({ data }) => {
          this.initialize();
          this.successNotify("Updated teacher");
        });
    },
    addClass(){
      this.$admin.post('class/create',{
        subject_id : this.class_detail.subject.id,
        room_id : this.class_detail.room.id,
        schedule_id : this.class_detail.schedule.id,
        teacher_id : this.teacher.id,
      }).then(({data}) => {
        if(data.error){
          this.errorNotify(data.error)
        }
        else {

          this.class_detail = {
            subject : null,
            schedule : null,
            room : null,
            teacher : null,
            class_detail : null
          }
          this.successNotify("Class created")
          this.initialize()
          this.$refs.form.resetValidation()
        }
      })
    }
  },
};
</script>
