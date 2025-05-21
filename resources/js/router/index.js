import { createRouter, createWebHistory } from 'vue-router';
import PlaceOrder from '../pages/PlaceOrder.vue';
import ProductList from '../pages/ProductList.vue';

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/order' },
        { path: '/order', component: PlaceOrder },
        { path: '/products', component: ProductList },
    ],
});
