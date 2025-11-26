<script setup lang="ts">
import ProductCard from './ProductCard.vue';

defineProps<{
    products: any[];
}>();
</script>

<template>
    <div class="mx-auto max-w-2xl px-4 py-0 sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
            <ProductCard v-for="product in products" :key="product.id" :product="product"/>
        </div>
    </div>
</template>