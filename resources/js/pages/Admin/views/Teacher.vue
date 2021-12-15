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
    <ClassDetailList 
      :pagination="pagination"
      :class_loading="class_loading"
      :class_details="class_details"
      display_to="teacher"
    />
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
      @save="add_class_dialog = false,assign_class ? assignClass() : addClass()"
    />
  </div>
</template>
<script>
import ClassDetailList from "../../../components/ClassDetailList.vue";
import TeacherForm from "../../../components/Forms/Teacher.vue";
import ClassDetail from "../../../components/Forms/ClassDetail.vue";
export default {
  components: {
    TeacherForm,
    ClassDetail,
    ClassDetailList
  },
  data: () => ({
    teacher: {},
    teacher_edit: {},
    add_class_dialog: false,
    addition_edition_dailog: false,
    assign_class : false,
    class_detail : {
      subject : null,
      schedule : null,
      room : null,
      teacher : null,
      class_detail : null
    },
    class_details : [],
    class_loading: false,
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
    assignClass(){
      let class_detail = {
        subject_id : this.class_detail.class_detail.subject_id,
        room_id : this.class_detail.class_detail.room_id,
        schedule_id : this.class_detail.class_detail.schedule_id,
        teacher_id : this.teacher.id,
      }
      this.$admin.put('/class/update/'+this.class_detail.class_detail.id,class_detail).then(({data}) => {
        if(data.error){
            this.errorNotify(data.error)
        }
        else { 
            this.successNotify("Class assigned")
        }
        this.initialize()
      })

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
        }
      })
    }
  },
};
</script>
