<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ProductSearch from '../components/Products/ProductSearch.vue';
import ProductGrid from '../components/Products/ProductGrid.vue';

// Define props to receive products from Inertia
const props = defineProps<{
  products: Array<{
    id: number;
    name: string;
    description: string;
    price: number;
    image_url: string;
  }>;
}>();

const searchTerm = ref('');

const filteredProducts = computed(() => {
  if (!searchTerm.value) {
    return props.products;
  }
  const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
  return props.products.filter(product =>
    product.name.toLowerCase().includes(lowerCaseSearchTerm) ||
    product.description.toLowerCase().includes(lowerCaseSearchTerm)
  );
});
</script>

<template>
  <Head title="Welcome" />
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-8 text-center">¡Bienvenido a MiTienda!</h1>
    <ProductSearch v-model="searchTerm" />
    <ProductGrid :products="filteredProducts" />
  </div>
</template>