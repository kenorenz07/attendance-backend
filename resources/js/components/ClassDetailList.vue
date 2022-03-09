<template>
  <div>
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
            <div v-if="display_to != 'teacher'">
              <p class="text-h6 mb-0">Teacher :</p>
              <span class="text-subtitle-1 pl-3">
                {{class_detail.teacher.full_name}}
              </span>
            </div>
            <div v-if="display_to != 'subject'">
              <p class="text-h6 mb-0">Subject :</p>
              <span class="text-subtitle-1 pl-3">
                {{ class_detail.subject.name }} - 
                <span class="text-subtitle-2">
                  {{ class_detail.subject.description }}
                </span>
              </span>
            </div>
            <div v-if="display_to != 'section'">
              <p class="text-h6 mb-0">Section :</p>
              <span class="text-subtitle-1 pl-3">
                {{ class_detail.section.name }}
              </span>
            </div>
            <div v-if="display_to != 'room'">
              <p class="text-h6 mb-0">Room :</p>
              <span class="text-subtitle-1 pl-3"
                >{{ class_detail.room.name }}, Seats available :
                {{ class_detail.room.seats ? class_detail.room.seats : 0 }} seat/s</span
              >
            </div>
            <div v-if="display_to != 'schedule'">
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
            </div>
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
  </div>
</template>
<script>
  export default {
    props : {
      pagination : {
        required : true,
        type : Object,
        default : {
          page : 1,
          number_of_pages : 1
        }
      },
      class_loading: {
        required : true,
        type : Boolean,
        default : false
      },
      class_details : {
        required : true,
        type : Array,
        default : []
      },
      display_to : {
        required : true,
        type : String
      }
    }
  }
</script>