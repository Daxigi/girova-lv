<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProductSearch from '@/components/Products/ProductSearch.vue';
import ProductGrid from '@/components/Products/ProductGrid.vue';
import { useProducts } from '../../composables/useProducts';
import type { Product } from '@/types';

const props = defineProps<{
  products: Product[];
  categories: Array<{ id: number; name: string; }>;
  types: Array<{ id: number; name: string; }>;
}>();

const sortOptions = [
    { text: 'Relevancia', value: 'default' },
    { text: 'Precio: Menor a Mayor', value: 'price_asc' },
    { text: 'Precio: Mayor a Menor', value: 'price_desc' },
    { text: 'Nombre: A-Z', value: 'name_asc' },
];

const {
    searchTerm,
    selectedCategory,
    selectedType,
    sortBy,
    processedProducts,
    clearFilters
} = useProducts(props.products);
</script>

<template>
  <Head title="Girova - Productos" />
  <v-container fluid>

    <v-row justify="center">
      <v-col cols="12" md="3" class="pt-4">
        <div style="position: sticky; top: 100px;">
          <div class="mb-6">
            <ProductSearch v-model="searchTerm" />
          </div>

          <v-card class="pa-4" elevation="2">
            <div class="d-flex justify-space-between align-center mb-4">
              <h3 class="text-h6" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #000000;">
                Filtros
              </h3>
              <v-btn
                variant="text"
                color="black"
                @click="clearFilters"
                prepend-icon="mdi-filter-off"
                size="small"
              >
                Limpiar
              </v-btn>
            </div>

            <v-row dense>
              <v-col cols="12" class="mb-2">
                <v-select
                  v-model="selectedCategory"
                  :items="categories"
                  item-title="name"
                  item-value="id"
                  label="Categoría"
                  clearable
                  variant="outlined"
                />
              </v-col>
              <v-col cols="12" class="mb-2">
                <v-select
                  v-model="selectedType"
                  :items="types"
                  item-title="name"
                  item-value="id"
                  label="Tipo"
                  clearable
                  variant="outlined"
                />
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="sortBy"
                  :items="sortOptions"
                  item-title="text"
                  item-value="value"
                  label="Ordenar por"
                  variant="outlined"
                />
              </v-col>
            </v-row>
          </v-card>
        </div>
      </v-col>

      <v-col cols="12" md="9">

        <ProductGrid v-if="processedProducts.length > 0" :products="processedProducts" class="mt-4" />

        <div v-else class="text-center py-16">
          <v-icon size="80" color="grey-lighten-1">mdi-package-variant-closed</v-icon>
          <h3 class="text-h5 mt-4 mb-2" style="color: #424242;">
            No se encontraron productos
          </h3>
          <p style="color: #757575;">
            Intenta ajustar los filtros para ver más resultados
          </p>
          <v-btn
            class="mt-6"
            color="black"
            variant="elevated"
            @click="clearFilters"
          >
            Limpiar Filtros
          </v-btn>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>
