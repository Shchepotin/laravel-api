import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '../components/Home.vue';
import Profile from '../components/auth/Profile.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';
import PasswordEmail from '../components/auth/PasswordEmail.vue';
import ResetPassword from '../components/auth/ResetPassword.vue';
import NotFound from '../components/NotFound.vue';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/profile',
        name: 'auth.profile',
        component: Profile,
        meta: {
            auth: true,
        },
    },
    {
        path: '/login',
        name: 'auth.login',
        component: Login,
        meta: {
            guest: true,
        },
    },
    {
        path: '/register',
        name: 'auth.register',
        component: Register,
        meta: {
            guest: true,
        },
    },
    {
        path: '/password/reset',
        name: 'auth.password.email',
        component: PasswordEmail,
        meta: {
            guest: true,
        },
    },
    {
        path: '/password/reset/:token',
        name: 'auth.reset.password',
        component: ResetPassword,
        meta: {
            guest: true,
        },
    },
    // Must be the last entry in array.
    {
        path: '*',
        component: NotFound,
    },
];

export default new VueRouter({
    routes,
    mode: 'history',
    saveScrollPosition: false,
});
