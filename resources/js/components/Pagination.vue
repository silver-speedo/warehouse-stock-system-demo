<template>
    <div class="flex flex-wrap gap-2">
        <button
            v-for="link in links"
            :key="link.label"
            v-html="link.label"
            :disabled="!link.url"
            @click="$emit('navigate', link.url)"
            class="px-3 py-1 border rounded"
            :class="{
        'bg-blue-600 text-white': link.active,
        'cursor-not-allowed opacity-50': !link.url,
      }"
        />
    </div>
</template>

<script setup lang="ts">
defineProps({
    links: {
        type: Array,
        default: () => [],
    },
});
defineEmits(['navigate']);
</script>
