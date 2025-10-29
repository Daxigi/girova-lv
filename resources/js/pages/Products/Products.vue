<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ProductSearch from '@/components/Products/ProductSearch.vue';
import ProductGrid from '@/components/Products/ProductGrid.vue';

// Define props to receive products from Inertia
const props = defineProps<{
  products: Array<{
    id: number;
    name: string;
    description: string;
    price: number;
    image_url: string;
    stock: number;
    category?: {
      id: number;
      name: string;
    } | null;
    type?: {
      id: number;
      name: string;
    } | null;
  }>;
  categories: Array<{
    id: number;
    name: string;
  }>;
  types: Array<{
    id: number;
    name: string;
  }>;
}>();

// Variables reactivas para los filtros
const searchTerm = ref('');
const selectedCategory = ref<number | null>(null);
const selectedType = ref<number | null>(null);
const minPrice = ref<number | null>(null);
const maxPrice = ref<number | null>(null);

// Computed: filtrar productos según TODOS los criterios
const filteredProducts = computed(() => {
  let filtered = props.products;

  //  filtro búsqueda por texto (nombre o descripción)
  if (searchTerm.value) {
    const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(lowerCaseSearchTerm) ||
      product.description.toLowerCase().includes(lowerCaseSearchTerm)
    );
  }

  //  filtro por categoría
  if (selectedCategory.value !== null) {
    filtered = filtered.filter(product =>
      product.category?.id === selectedCategory.value
    );
  }

  //  filtro por tipo
  if (selectedType.value !== null) {
    filtered = filtered.filter(product =>
      product.type?.id === selectedType.value
    );
  }

  //  filtro por precio mínimo
  if (minPrice.value !== null && minPrice.value > 0) {
    filtered = filtered.filter(product =>
      product.price >= minPrice.value!
    );
  }

  //  filtro por precio máximo
  if (maxPrice.value !== null && maxPrice.value > 0) {
    filtered = filtered.filter(product =>
      product.price <= maxPrice.value!
    );
  }

  return filtered;
});

// Función para limpiar todos los filtros
const clearFilters = () => {
  searchTerm.value = '';
  selectedCategory.value = null;
  selectedType.value = null;
  minPrice.value = null;
  maxPrice.value = null;
};
</script>

<template>
  <Head title="Girova - Productos" />
  <v-container>
    <!-- Título de la página -->
    <div class="text-center mb-8 py-8">
      <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; font-weight: 700; letter-spacing: 0.1em; color: #000000;">
        NUESTROS PRODUCTOS
      </h1>
      <p style="font-size: 1.1rem; color: #424242; letter-spacing: 0.05em; margin-top: 1rem;">
        Descubre toda nuestra colección
      </p>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
      <ProductSearch v-model="searchTerm" />
    </div>

    <!-- Sección de Filtros -->
    <v-card class="mb-8 pa-6" elevation="2">
      <div class="d-flex justify-space-between align-center mb-4">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; color: #000000;">
          Filtros
        </h3>
        <v-btn
          variant="text"
          color="black"
          @click="clearFilters"
          prepend-icon="mdi-filter-off"
        >
          Limpiar Filtros
        </v-btn>
      </div>

      <v-row>
        <!-- Filtro por Categoría -->
        <v-col cols="12" md="3">
          <v-select
            v-model="selectedCategory"
            :items="categories"
            item-title="name"
            item-value="id"
            label="Categoría"
            clearable
            variant="outlined"
            prepend-inner-icon="mdi-shape"
            color="black"
          >
            <template v-slot:prepend-inner>
              <v-icon color="black">mdi-shape</v-icon>
            </template>
          </v-select>
        </v-col>

        <!-- Filtro por Tipo -->
        <v-col cols="12" md="3">
          <v-select
            v-model="selectedType"
            :items="types"
            item-title="name"
            item-value="id"
            label="Tipo"
            clearable
            variant="outlined"
            color="black"
          >
            <template v-slot:prepend-inner>
              <v-icon color="black">mdi-tag</v-icon>
            </template>
          </v-select>
        </v-col>

        <!-- Filtro por Precio Mínimo -->
        <v-col cols="12" md="3">
          <v-text-field
            v-model.number="minPrice"
            label="Precio Mínimo"
            type="number"
            min="0"
            variant="outlined"
            prefix="$"
            color="black"
          >
            <template v-slot:prepend-inner>
              <v-icon color="black">mdi-currency-usd</v-icon>
            </template>
          </v-text-field>
        </v-col>

        <!-- Filtro por Precio Máximo -->
        <v-col cols="12" md="3">
          <v-text-field
            v-model.number="maxPrice"
            label="Precio Máximo"
            type="number"
            min="0"
            variant="outlined"
            prefix="$"
            color="black"
          >
            <template v-slot:prepend-inner>
              <v-icon color="black">mdi-currency-usd</v-icon>
            </template>
          </v-text-field>
        </v-col>
      </v-row>

      <!-- Contador de productos filtrados -->
      <v-divider class="my-4"></v-divider>
      <div class="text-center">
        <v-chip color="black" variant="flat" class="text-white">
          <v-icon start>mdi-package-variant</v-icon>
          {{ filteredProducts.length }} producto{{ filteredProducts.length !== 1 ? 's' : '' }} encontrado{{ filteredProducts.length !== 1 ? 's' : '' }}
        </v-chip>
      </div>
    </v-card>

    <!-- Products Grid -->
    <ProductGrid :products="filteredProducts" />

    <!-- Mensaje cuando no hay resultados -->
    <div v-if="filteredProducts.length === 0" class="text-center py-12">
      <v-icon size="64" color="grey">mdi-package-variant-closed</v-icon>
      <h3 class="text-h5 mt-4 mb-2" style="color: #424242;">
        No se encontraron productos
      </h3>
      <p style="color: #757575;">
        Intenta ajustar los filtros para ver más resultados
      </p>
      <v-btn
        class="mt-4"
        color="black"
        variant="elevated"
        @click="clearFilters"
      >
        Limpiar Filtros
      </v-btn>
    </div>
  </v-container>
</template>