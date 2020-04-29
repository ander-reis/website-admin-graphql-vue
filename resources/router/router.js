import Vue from 'vue';
import VueRouter from 'vue-router';

import routesNoticias from './routes-noticias';

import { AUTH_TOKEN } from '../plugins/apollo'
import AuthService from './../services/auth-service'

Vue.use(VueRouter);

const DashboardPage = () => import('../js/views/dashboard/Dashboard');
const Login = () => import('../js/views/login/Login');

const routes = [
    {
        path: '/login',
        component: Login,
        name: 'login'
    },
    {
        path: '/dashboard',
        component: DashboardPage,
        meta: { requiresAuth: true },
        name: 'dashboard',
    },
    ...routesNoticias
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history'
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(route => route.meta.requiresAuth)) {
        const token = window.localStorage.getItem(AUTH_TOKEN)
        const loginRoute = {
            path: '/login',
            query: { redirect: to.fullPath }
        }
        if (token) {
            try {
                await AuthService.user({ fetchPolicy: 'network-only' })
                return next()
            } catch (error) {
                return next(loginRoute)
            }
        }
        return next(loginRoute)
    }
    next()
})

export default router;
