import Vue from 'vue'
import VueRouter from 'vue-router'

import accounts from './accounts'
import items from './items'
import main from '../pages/adminhome'
import login from '../pages/login'
import purchase from './purchase'


Vue.use(VueRouter)

const routess = [
    ...accounts,
    ...items,
    ...purchase,
]
const router = new VueRouter({
        base: '/',
        mode: 'history',
        routes: [{
                path: '',
                name: 'main',
                component: main,
                meta: {
                    requiresAuth: false
                },
                children: routess
            },
            {
                path: '/login',
                name: 'login',
                component: login,
                meta: {
                    requiresAuth: false
                },
            }
        ]
    })

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some((route) => route.meta.requiresAuth)
    const isAuthenticated = localStorage.getItem('token')

    if (!requiresAuth && isAuthenticated) {
        next(from);
    } else if (requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }

    // next();
});


export default router