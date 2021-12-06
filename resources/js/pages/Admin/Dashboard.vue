<template>
     <div class="m-2">
        <v-card
            class="mx-auto px-5 py-5"
            outlined
        >
            <v-card-title>
                <v-row>
                    <v-col>
                        <v-card elevation="3" >
                            <v-card-text>
                                <div class="d-flex justify-space-between mx-2">
                                    <div >
                                        <v-icon color="primary" style="font-size:100px">
                                            mdi-account-multiple
                                        </v-icon>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right text-subtitle-1">
                                            Teachers
                                        </div>
                                        <p class="text-right text-h4">
                                            {{statistics_totals.teachers}}
                                        </p>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card elevation="3" >
                            <v-card-text>
                                <div class="d-flex justify-space-between mx-2">
                                    <div >
                                        <v-icon color="secondary" style="font-size:100px">
                                            mdi-account
                                        </v-icon>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right text-subtitle-1">
                                            Students
                                        </div>
                                        <p class="text-right text-h4">
                                            {{statistics_totals.students}}
                                        </p>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card elevation="3" >
                            <v-card-text>
                                <div class="d-flex justify-space-between mx-2">
                                    <div >
                                        <v-icon color="accent" style="font-size:100px">
                                            mdi-book-variant
                                        </v-icon>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right text-subtitle-1">
                                            Classes
                                        </div>
                                        <p class="ttext-right text-h4">
                                            {{statistics_totals.class_details}}
                                        </p>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col>
                        <v-card elevation="3" >
                            <v-card-text>
                                <div class="d-flex justify-space-between mx-2">
                                    <div >
                                        <v-icon color="info" style="font-size:100px">
                                            mdi-calendar-month
                                        </v-icon>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right text-subtitle-1">
                                            Attendances
                                        </div>
                                        <p class="text-right text-h4">
                                            {{statistics_totals.attendances}}
                                        </p>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="5">
                            <v-card-text class="statistic-card">
                                <div class="chart-select">
                                    <div>
                                        <v-select
                                            v-model="year"
                                            :items="years"
                                            label="Year"
                                            dense
                                            solo
                                        ></v-select>
                                    </div>
                                </div>
                                <apexchart
                                    type="bar"
                                    height="450"
                                    :options="chartOptions"
                                    :series="statistics"
                                ></apexchart>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>    
            </v-card-title>
        </v-card>
    </div>
</template>

<script>
export default {
    data: (vm) => ({
        year : new Date().getFullYear() ,
        statistics: [
            {
                name: "Absent",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },
            {
                name: "Present",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },
            {
                name: "Late",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },
            {
                name: "Excuse",
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            },
        ],
        statistics_totals : {
            teachers : 0,
            students : 0,
            class_details : 0,
            attendances : 0,
        },
        chartOptions: {
            colors: ["#f53851", "#27b460", "#ffc409", "#2196F3", "#a7bfd6", "#9bcce0", "#9997c5", "#a3b3d1"],
            chart: {
                width: "100%",
                toolbar: {
                    show: false
                },
                height: 450,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0.2,
                colors: ["#74fb00"],
                curve: "smooth"
            },
            title: {
                text: "Attendance statistics"
            },
            xaxis: {
                categories: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                ]
            },
            yaxis: {
                opposite: true,
                yaxis: {
                    lines: {
                        show: false,
                        offsetX: 0,
                        offsetY: 0
                    }
                }
            },
            legend: {
                horizontalAlign: "left"
            }
        },
        stat: true
    }),
    watch : {
        year(){
            this.initialize()
        }
    },
    computed : {
        years () {
            var years = [];
            var currentYear = new Date().getFullYear()
            let max = currentYear

            for (var year = 2020 ; year <= max; year++) {
                years.push(year);   
            }

            return years;
        }
    },
    mounted() {
        // this.forceRerender();
        this.initialize()

    },
    methods: {
        initialize(){
            this.$admin.get('statistics/'+this.year).then(({data}) => {
                this.statistics_totals.teachers = data.teachers
                this.statistics_totals.students = data.students
                this.statistics_totals.class_details = data.class_details
                this.statistics_totals.attendances = data.attendances_total

                this.statistics = [
                    {
                        name: "Absents",
                        data: data.attendances[0]
                    },
                    {
                        name: "Present",
                        data: data.attendances[1]
                    },
                    {
                        name: "Late",
                        data: data.attendances[2]
                    },
                    {
                        name: "Excuse",
                        data: data.attendances[3]
                    },
                ]
            })
        },
    },
   
};
</script>
<style scoped>
    .statistics-card {
        position: relative;
    }
    .chart-select{
        display: flex;
        justify-content: end;
        position: absolute;
        right: 10px;
        top: 10px;
        z-index: 107!important;
    }
</style>