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
                          : 'https://picsum.photos/id/11/500/300'
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
                  <v-tooltip bottom color="secondary">
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

                  <v-tooltip bottom color="secondary">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mx-auto"
                        fab
                        dark
                        v-bind="attrs"
                        v-on="on"
                        color="primary"
                        large
                        @click="add_class_dialog = true"
                      >
                        <v-icon> mdi-plus </v-icon>
                      </v-btn>
                    </template>
                    <span>Add class</span>
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
    <v-row>
      <v-col
        cols="4"
        v-for="class_detail in teacher.class_details"
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
              @click="$router.push('teacher/' + teacher.id)"
            >
              <v-icon> mdi-eye </v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text class="text-white">
            <p class="text-h6 mb-0">Subject :</p>
            <span class="text-subtitle-1 pl-3"
              >{{ class_detail.subject.name }}, Description :
              {{ class_detail.subject.description }}
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
    <TeacherForm
      :form="teacher_edit"
      :dialogState="addition_edition_dailog"
      @close="(addition_edition_dailog = false), initialize()"
      @save="(addition_edition_dailog = false), saveTeacher()"
    />
    <v-row justify="center">
      <v-dialog v-model="add_class_dialog" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="text-h5">Create a class</span>
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
                      v-model="subject"
                      label="Select subject"
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
                      v-model="room"
                      label="Select room"
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
                      v-model="schedule"
                      label="Select schedule"
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
                            <v-list-item-title
                              v-html="data.item.day"
                            ></v-list-item-title>
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
                  </v-form>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="add_class_dialog = false"> Cancel </v-btn>
            <v-btn text @click="addClass"> Add class details </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>
<script>
import TeacherForm from "../../../components/Forms/Teacher.vue";
export default {
  components: {
    TeacherForm,
  },
  data: () => ({
    valid: false,
    teacher: {},
    teacher_edit: {},
    add_class_dialog: false,
    addition_edition_dailog: false,
    schedules : [],
    schedule : null,
    rooms : [],
    room : null,
    subjects : [],
    subject : null
  }),
  mounted() {
    this.initialize();
  },
  methods: {
    initialize() {
        this.$admin.get("teacher/" + this.$route.params.id).then(({ data }) => {
            this.teacher = data;
        });
        this.$admin.get("class/available").then(({ data }) => {
            this.availabe_classes = data.availabe_classes;
            this.subjects = data.subjects;
            this.rooms = data.rooms;
            this.schedules = data.schedules;
        });
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
      if(!this.$refs.form.validate()) return
      this.$admin.post('class/create',{
        subject_id : this.subject.id,
        room_id : this.room.id,
        schedule_id : this.schedule.id,
        teacher_id : this.teacher.id,
      }).then(({data}) => {
        if(data.error){
          this.errorNotify(data.error)
        }
        else {

          this.add_class_dialog = false
          this.room = {}
          this.subject = {}
          this.schedule = {}
          this.successNotify("Class created")
          this.initialize()
          this.$refs.form.resetValidation()
        }
      })
    }
  },
};
</script>
