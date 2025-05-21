<template>
    <div class="max-w-md space-y-4">
        <h1 class="text-2xl font-bold">Place Order</h1>
        <form @submit.prevent="submitOrder">
            <label class="block">
                <span class="text-gray-700">Product</span>
                <select v-model="selectedProduct" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    <option disabled value="">Select a product</option>
                    <option v-for="(name, id) in products" :key="id" :value="id">{{ name }}</option>
                </select>
            </label>

            <label class="block mt-4">
                <span class="text-gray-700">Quantity</span>
                <input type="number" v-model.number="quantity" min="1" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
            </label>

            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Place Order</button>
        </form>

        <div v-if="success" class="text-green-600 mt-4">{{ successMessage }}</div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../api';

const products = ref<Record<string, string>>({});
const selectedProduct = ref('');
const successMessage = ref('');
const quantity = ref(1);
const success = ref(false);

onMounted(async () => {
    const { data } = await api.get('/products/index');

    products.value = data;
});

const submitOrder = async () => {
    const response = await api.post('/orders', {
        product_uuid: [selectedProduct.value],
        quantity: [quantity.value],
    });

    successMessage.value = response.data.message ?? 'No response from server!';
    success.value = true;
    selectedProduct.value = '';
    quantity.value = 1;
};
</script>
