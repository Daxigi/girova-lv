<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { Link, router} from '@inertiajs/vue3'

// Define the props
const props = defineProps({
    products: Array as () => any[],
});

// Placeholder functions for actions
const editProduct = (id: string) => {
    router.get(route('products.edit', id));
};

const deleteProduct = (id: string) => {
    // This will require a route to handle the deletion
    if (confirm('¿Estás seguro de que quieres dar de baja este producto?')) {
        router.delete(route('products.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <v-container>
        <h1 class="mb-6 text-h4">Dashboard de Productos</h1>
        <v-row>
            <v-col v-if="!products || products.length === 0" cols="12">
                <p class="text-center grey--text">No hay productos para mostrar.</p>
            </v-col>
            <v-col v-for="product in products" :key="product.id" cols="12">
                <v-card class="d-flex flex-no-wrap justify-space-between mb-4" variant="tonal">
                    <div class="d-flex">
                        <v-avatar class="ma-3" size="120" rounded="lg">
                            <v-img
                                :src="product.imageUrl"
                                :alt="product.name"
                                cover
                            ></v-img>
                        </v-avatar>
                        <div>
                            <v-card-title class="text-h6 font-weight-bold">{{ product.name }}</v-card-title>
                            <v-card-subtitle class="pb-2">
                                <strong>Precio:</strong> ${{ product.price }} | 
                                <strong>Stock:</strong> {{ product.stock }}
                            </v-card-subtitle>
                            <v-card-subtitle class="pb-2">
                                <strong v-if="product.category">Categoría:</strong> {{ product.category?.name }} |
                                <strong v-if="product.type">Tipo:</strong> {{ product.type?.name }}
                            </v-card-subtitle>
                            <v-card-text class="py-0">{{ product.description }}</v-card-text>
                        </div>
                    </div>

                    <v-card-actions class="align-self-center pa-4">
                        <v-btn icon="mdi-pencil" color="blue" variant="text" @click="editProduct(product.id)" title="Editar"></v-btn>
                        <v-btn icon="mdi-delete" color="red" variant="text" class="ml-2" @click="deleteProduct(product.id)" title="Dar de baja"></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
