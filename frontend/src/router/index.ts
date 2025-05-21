import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/Dashboard.vue'
import Categorias from '../pages/Categorias.vue'

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    { path: '/dashboard', component: Dashboard },
    { path: '/categorias', component: Categorias }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
