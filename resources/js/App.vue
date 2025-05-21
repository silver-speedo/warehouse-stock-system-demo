<template>
    <div class="p-4">
        <nav class="mb-4 space-x-4">
            <RouterLink to="/order" class="text-blue-600 hover:underline">Place Order</RouterLink>
            <RouterLink to="/products" class="text-blue-600 hover:underline">Product List</RouterLink>
        </nav>
        <RouterView />
    </div>
</template>
