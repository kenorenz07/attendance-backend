<template>
  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar
          flat
        >
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="prev"
          >
            <v-icon small>
              mdi-chevron-left
            </v-icon>
          </v-btn>
          <v-btn
            fab
            text
            small
            color="grey darken-2"
            @click="next"
          >
            <v-icon small>
              mdi-chevron-right
            </v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="500" v-show="!calendar_loading">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @change="updateRange"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card
            color="grey lighten-4"
            min-width="150px"
            flat
          >
            <v-toolbar
              :color="selectedEvent.color"
              dark
            >
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <div class="text-subtitle-1"> Time in : {{moment(selectedEvent.time_in, "HH:mm:ss").format("hh:mm a")}}</div>
              <div class="text-subtitle-1"> Time out: {{moment(selectedEvent.time_out, "HH:mm:ss").format("hh:mm a")}}</div>
            </v-card-text>
            <v-card-actions>
                <v-icon
                    large
                    :color="selectedEvent.color"
                    @click="selectedOpen = false"
                >
                    mdi-close-circle-outline
                </v-icon>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
  </v-row>
</template>
<script>
  export default {
    props : {
        class_detail_student : {
            required: true,
            type : Object
        },
    },
    data: () => ({
      focus: '',
      type: 'month',
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
      calendar_loading : false,
      events: [],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
      colors: ['primary', 'secondary', 'accent', 'error', 'warning', ],
    }),
    mounted () {
        this.$refs.calendar.checkChange()
    },
    watch : {
        'class_detail_student.id' : {
            handler (val){
                console.log(this.$refs.calendar)
                if(this.$refs.calendar){
                    this.updateRange({
                        start : this.$refs.calendar.lastStart,
                        end : this.$refs.calendar.lastEnd
                    })
                }
            }
        }
    },
    methods: {
        getEventColor (event) {
            return event.color
        },
        setToday () {
            this.focus = ''
        },
        prev () {
            this.$refs.calendar.prev()
        },
        next () {
            this.$refs.calendar.next()
        },
        showEvent ({ nativeEvent, event }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                requestAnimationFrame(() => requestAnimationFrame(() => open()))
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        updateRange ({ start, end }) {
            console.log(start,end,'calendar')

            this.calendar_loading = true

            let params = {
                start_date : start.date,
                end_date : end.date
            }
            
            this.$admin.get(`/class-detail-student/${this.class_detail_student.id}/attendances`,{params}).then(({data}) => {
                this.events = data
                this.calendar_loading = false
            })



            // const events = []

            // const min = new Date(`${start.date}T00:00:00`)
            // const max = new Date(`${end.date}T23:59:59`)
            // const days = (max.getTime() - min.getTime()) / 86400000
            // const eventCount = this.rnd(days, days + 20)

            // for (let i = 0; i < eventCount; i++) {
            //     const allDay = this.rnd(0, 3) === 0
            //     const firstTimestamp = this.rnd(min.getTime(), max.getTime())
            //     const first = new Date(firstTimestamp - (firstTimestamp % 900000))
            //     const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
            //     const second = new Date(first.getTime() + secondTimestamp)

            //     events.push({
            //     name: this.names[this.rnd(0, this.names.length - 1)],
            //     start: first,
            //     end: first,
            //     color: this.colors[this.rnd(0, this.colors.length - 1)],
            //     timed: false,
            //     })
            // }

            // this.events = events
        },
    },
  }
</script>