import Vue from 'vue';
import VueRouter from 'vue-router';

import Login from '../pages/Login.vue';
import Main from '../pages/Main.vue';

import Dashboard from '../pages/Admin/Dashboard.vue';
import Admins from '../pages/Admin/Admins.vue';
import Teachers from '../pages/Admin/Teachers.vue';
import Students from '../pages/Admin/Students.vue';
import ClassDetails from '../pages/Admin/ClassDetails.vue';
import Rooms from '../pages/Admin/Rooms.vue';
import Subjects from '../pages/Admin/Subjects.vue';
import Schedules from '../pages/Admin/Schedules.vue';

// VIEWS 
import Teacher from '../pages/Admin/views/Teacher.vue';
import ClassDetail from '../pages/Admin/views/ClassDetail.vue';
import Student from '../pages/Admin/views/Student.vue';
import Room from '../pages/Admin/views/Room.vue';
import Schedule from '../pages/Admin/views/Schedule.vue';
import Subject from '../pages/Admin/views/Subject.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active-route',
    routes: [
        {
            path: '',
            redirect: '/dashboard'
        },
        {
            path: '',
            name: 'main',
            component: Main,
            meta: {
                requiresAuth: true
            },
            children : [
                {
                    path: '/dashboard',
                    name: 'dashboard',
                    component: Dashboard,
                },
                {
                    path: '/admins',
                    name: 'admins',
                    component: Admins,
                },
                {
                    path: '/teachers',
                    name: 'teachers',
                    component: Teachers,
                },
                {
                    path: '/teacher/:id',
                    name: 'teacher',
                    component: Teacher,
                },
                {
                    path: '/class-details',
                    name: 'class-details',
                    component: ClassDetails,
                },
                {
                    path: '/class-detail/:id',
                    name: 'class-detail',
                    component: ClassDetail,
                },
                {
                    path: '/students',
                    name: 'students',
                    component: Students,
                },
                {
                    path: '/student/:id',
                    name: 'student',
                    component: Student,
                },
                {
                    path: '/schedules',
                    name: 'schedules',
                    component: Schedules,
                },
                {
                    path: '/schedule/:id',
                    name: 'schedule',
                    component: Schedule,
                },
                {
                    path: '/subjects',
                    name: 'subjects',
                    component: Subjects,
                },
                {
                    path: '/subject/:id',
                    name: 'subject',
                    component: Subject,
                },
                {
                    path: '/rooms',
                    name: 'rooms',
                    component: Rooms,
                },
                {
                    path: '/room/:id',
                    name: 'room',
                    component: Room,
                },
            ]
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                requiresAuth: false
            },
        },
    ]
});

router.beforeEach(async (to, from, next) => {
    localStorage.setItem('from', from.fullPath)
    // let user = null
    const user = localStorage.getItem('token')            
    // try {
    //     user = await store.dispatch('updateUser') 
    // } catch (error) {
    //     user = null
    // }

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    
    if (!requiresAuth && user) {
        console.log('not require auth but there is user')
        next(from)
    } else if (requiresAuth && !user) {
        console.log('require auth there is no user')
        next('/login');
    } else {
        console.log('next')
        next();
    }

})

export default router;