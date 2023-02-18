import Vue from 'vue'
import VueRouter from 'vue-router'

import accounts from './accounts'
import items from './items'
import main from '../pages/adminhome'
import login from '../pages/login'
import purchase from './purchase'
import cashier from './cashier'
import settings from './settings'


Vue.use(VueRouter)

const routess = [
    ...accounts,
    ...items,
    ...purchase,
    ...settings,
    ...cashier,
]
const router = new VueRouter({
        base: '/',
        mode: 'history',
        routes: [{
                path: '',
                name: 'main',
                component: main,
                meta: {
                    requiresAuth: true
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

// router.beforeEach((to, from, next) => {
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//       if (!store.getters.authenticated) {
//         next('/login');
//       } else {
//         next()
//       }
//     } else if (to.matched.some(record => record.meta.redirectIfLogged)) {
//       if (store.getters.authenticated) {
//         next({
//           path: '/'
//         })
//       } else {
//         next()
//       }
//     } else {
//       next()
//     }
//   })


export default router