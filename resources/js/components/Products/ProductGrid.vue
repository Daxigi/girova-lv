<script setup lang="ts">
import ProductCard from './ProductCard.vue';

defineProps<{
    products: any[];
}>();
</script>

<template>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; letter-spacing: 0.05em; color: #000000; margin-bottom: 1.5rem;">
            Nuestros Productos
        </h2>
        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <ProductCard v-for="product in products" :key="product.id" :product="product"/>
        </div>
    </div>
</template>