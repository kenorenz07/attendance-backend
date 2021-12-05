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
                        @click="add_class_dailog = true"
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
    <v-card class="mx-auto px-5 py-5 mt-3" color="primary" elevation="4" outlined>
      <v-card-text>
        <v-row>
            <v-col cols="4">
              <v-data-table
                :footer-props="classDetailTable.footerProps"
                :page="classDetailTable.page"
                :pageCount="classDetailTable.numberOfPages"
                :headers="classDetailTable.headers"
                :options.sync="classDetailTable.options"
                :items="class_details"
                @update:options="initialize"
                :server-items-length="classDetailTable.total"
                :single-select="true"
                item-key="id"
                height="628"
                show-select
                v-model="selected_class"
                checkbox-color="secondary"
                class="elevation-1"
              >
                  <template v-slot:item.class_detail="{ item }">
                    
                    <div class="text-body-2"> Subject : <span class="text-caption">{{item.class_detail.subject.name}},</span>  </div> 
                    <div class="text-body-2">Room : <span class="text-caption">{{item.class_detail.room.name}} ({{item.class_detail.room.seats + ' seat/s'}}),</span> </div> 
                    <div class="text-body-2">Schedule : <span class="text-caption">{{moment(item.class_detail.schedule.time_start, "HH:mm:ss").format("hh:mm a") +" - " +moment(item.class_detail.schedule.time_end, "HH:mm:ss").format("hh:mm a")}}</span> </div> 
                    <div class="text-body-2">Teacher : <span class="text-caption">{{item.class_detail.teacher.full_name}}</span> </div> 
                      
                  </template>
                   <template v-slot:item.actions="{ item }">
                      <v-btn
                        fab
                        class="mx-2"
                        dark
                        color="error"
                        small
                        @click="removeClassDetail(item)"
                      >
                        <v-icon>
                            mdi-delete
                        </v-icon>
                      </v-btn>
                    </template>
              </v-data-table>
            </v-col>
            <v-col cols='8' >
                <calendar v-if="selected_class.length > 0" :class_detail_student="selected_class[0]"  />
            </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <StudentForm
      :form="teacher_edit"
      :dialogState="addition_edition_dailog"
      @close="(addition_edition_dailog = false), initialize()"
      @save="(addition_edition_dailog = false), saveStudent()"
    />
    <AddClassDialog
      :form="add_to_class"
      :student_id="$route.params.id"
      :dialogState="add_class_dailog"
      @close="(add_class_dailog = false), initialize()"
      @save="(add_class_dailog = false), assignToClass()"
    />
  </div>
</template>
<script>
import Calendar from "../../../components/Calendar.vue";
import StudentForm from "../../../components/Forms/Student.vue";
import AddClassDialog from "../../../components/Forms/AddClass.vue";
export default {
  components: {
    StudentForm,AddClassDialog,Calendar,
  },
  data: () => ({
    add_class_dailog: false,
    student: {},
    teacher_edit: {},
    add_to_class: {
      class_detail_id : null
    },
    add_class_dialog: false,
    class_loading: false,
    addition_edition_dailog: false,
    class_details : [],
    selected_class : [],
    classDetailTable : {
      page: 0,
      total: 0,
      numberOfPages: 0,
      footerProps :{
          "items-per-page-options" : [5]
      },
      search_key : '',
      headers: [
          { text: "Details", value: "class_detail" },
          { text: "Actions", value: "actions" },
      ],
      options : {}
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

      this.classDetailsInitialize()
      
    },
    classDetailsInitialize(){
      this.class_loading = true;
      const { page, itemsPerPage,sortBy,sortDesc } = this.classDetailTable.options;


      let params = { 
          page: page,
          per_page: itemsPerPage,
          // sortBy: sortBy,
          // sortDesc: sortDesc,
          search_key: this.classDetailTable.search_key
      }
        
      this.$admin.get("student/classes/" + this.$route.params.id,{ params }).then(({data}) => {
        this.class_details = data.data
        this.classDetailTable.page = data.page
        this.classDetailTable.total =  data.total
        this.classDetailTable.numberOfPages = data.last_page
        this.class_loading = false

          if(this.selected_class.length < 1){
            this.selected_class.push(data.data[0])
          }
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
    assignToClass(){
      this.$admin.post(`student/${this.student.id}/add-class`, this.add_to_class).then(({data}) => {
        this.initialize()
        this.successNotify("Student added to class");

      })
    },
    async removeClassDetail(data) {
      let confirm = await this.deleteRecord("student and attendances");
      if (!confirm) return;

      this.$admin.post(`student/remove-class/${this.$route.params.id}`,data).then(({data}) => {
          this.initialize()
      })
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
