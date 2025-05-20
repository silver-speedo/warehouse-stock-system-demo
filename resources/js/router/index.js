import { createRouter, createWebHistory } from 'vue-router'

import HelloWorld from '../pages/HelloWorld.vue'

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: HelloWorld },
    ],
})
