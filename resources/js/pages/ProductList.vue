<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Product List</h1>
        <table class="min-w-full border">
            <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Total</th>
                <th class="p-2 border">Allocated</th>
                <th class="p-2 border">Physical</th>
                <th class="p-2 border">Threshold</th>
                <th class="p-2 border">Immediate</th>
                <th class="p-2 border">Warehouses</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products" :key="product.uuid">
                <td class="p-2 border">{{ product.title }}</td>
                <td class="p-2 border">{{ product.total_quantity }}</td>
                <td class="p-2 border">{{ product.allocated_to_orders }}</td>
                <td class="p-2 border">{{ product.physical_quantity }}</td>
                <td class="p-2 border">{{ product.total_threshold }}</td>
                <td class="p-2 border">{{ product.immediate_dispatch }}</td>
                <td class="p-2 border">
                    <ul>
                        <li v-for="wh in product.warehouses" :key="wh.slug">
                            {{ wh.name }}: {{ wh.quantity }} (Threshold: {{ wh.threshold }})
                        </li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>

        <Pagination :links="links" @navigate="fetchPage" class="mt-4" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../api';
import Pagination from '../components/Pagination.vue';

const products = ref([]);
const links = ref([]);

const fetchPage = async (url = '/products') => {
    const parsedUrl = new URL(url, window.location.origin);
    const apiPath = parsedUrl.pathname.replace(/^\/v1/, '') + parsedUrl.search;

    const { data } = await api.get(apiPath);

    products.value = data.data;
    links.value = data.links;
};

onMounted(() => fetchPage());
</script>
