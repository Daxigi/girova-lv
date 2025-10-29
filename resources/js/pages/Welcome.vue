<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ProductSearch from '../components/Products/ProductSearch.vue';
import ProductGrid from '../components/Products/ProductGrid.vue';
import Carousel from '@/components/commons/Carousel.vue';

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
  <Head title="Girova - Inicio" />
  <v-container>
    <!-- Hero Section -->
    <div class="text-center mb-8 py-8">
      <h1 style="font-family: 'Playfair Display', serif; font-size: 3.5rem; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 1rem; color: #000000;">
        GIROVA
      </h1>
      <p style="font-size: 1.25rem; color: #424242; letter-spacing: 0.05em;">
        Elegancia y calidad en cada detalle
      </p>
    </div>

    <!-- Carrusel de imágenes -->
    <Carousel />

    <!-- Search Bar -->
    <div class="mb-8">
      <ProductSearch v-model="searchTerm" />
    </div>

    <!-- Products Grid -->
    <ProductGrid :products="filteredProducts" />
  </v-container>
</template>