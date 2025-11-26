<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps, ref} from 'vue';
import { Head, Link, router} from '@inertiajs/vue3'
import {useAuth} from '../../composables/useAuth';

const { hasRole, hasPermission } = useAuth();

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
    <Head title="Girova - Dashboard de Productos" />
    <v-container>
        <!-- Header del Dashboard con título y botón -->
        <div class="d-flex flex-column flex-md-row justify-space-between align-start align-md-center mb-8 ga-4">
            <div>
                <h1 class="text-h4" style="font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 0.05em; color: #000000;">
                    Dashboard de Productos
                </h1>
                <p class="text-subtitle-1 mt-2" style="color: #757575;">
                    Gestiona tu catálogo de productos
                </p>
            </div>

            <!-- Botón de crear producto con permisos -->
            <template v-if="hasRole('admin') || hasPermission('create products')">
                <v-btn
                    color="black"
                    variant="elevated"
                    size="large"
                    prepend-icon="mdi-plus-circle"
                    @click="() => router.visit('/products/create')"
                    class="text-white align-self-stretch align-self-md-center"
                    elevation="4"
                    style="min-width: 200px;"
                >
                    Crear Producto
                </v-btn>
            </template>
        </div>

        <!-- Tarjeta con estadísticas rápidas -->
        <v-card class="mb-8 pa-6" elevation="2" v-if="products && products.length > 0">
            <v-row>
                <v-col cols="12" md="4">
                    <div class="text-center">
                        <v-icon size="48" color="black">mdi-package-variant</v-icon>
                        <h3 class="text-h4 mt-2" style="font-family: 'Playfair Display', serif; color: #000000;">
                            {{ products.length }}
                        </h3>
                        <p class="text-subtitle-2" style="color: #757575;">
                            Productos totales
                        </p>
                    </div>
                </v-col>
                <v-col cols="12" md="4">
                    <div class="text-center">
                        <v-icon size="48" color="#424242">mdi-check-circle</v-icon>
                        <h3 class="text-h4 mt-2" style="font-family: 'Playfair Display', serif; color: #000000;">
                            {{ products.filter(p => p.stock > 0).length }}
                        </h3>
                        <p class="text-subtitle-2" style="color: #757575;">
                            Con stock
                        </p>
                    </div>
                </v-col>
                <v-col cols="12" md="4">
                    <div class="text-center">
                        <v-icon size="48" color="#757575">mdi-alert-circle</v-icon>
                        <h3 class="text-h4 mt-2" style="font-family: 'Playfair Display', serif; color: #000000;">
                            {{ products.filter(p => p.stock === 0).length }}
                        </h3>
                        <p class="text-subtitle-2" style="color: #757575;">
                            Sin stock
                        </p>
                    </div>
                </v-col>
            </v-row>
        </v-card>

        <!-- Título de sección de productos -->
        <div class="mb-4" v-if="products && products.length > 0">
            <h2 class="text-h5" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #000000;">
                Lista de Productos
            </h2>
            <v-divider class="mt-2" style="border-color: #000000;"></v-divider>
        </div>

        <v-row>
            <v-col v-if="!products || products.length === 0" cols="12">
                <v-card class="pa-12 text-center" elevation="2">
                    <v-icon size="80" color="#BDBDBD">mdi-package-variant-closed</v-icon>
                    <h3 class="text-h5 mt-4 mb-2" style="font-family: 'Playfair Display', serif; color: #424242;">
                        No hay productos para mostrar
                    </h3>
                    <p class="mb-6" style="color: #757575;">
                        Comienza creando tu primer producto para el catálogo
                    </p>
                    <template v-if="hasRole('admin') || hasPermission('create products')">
                        <v-btn
                            color="black"
                            variant="elevated"
                            size="large"
                            prepend-icon="mdi-plus-circle"
                            @click="() => router.visit('/products/create')"
                        >
                            Crear Primer Producto
                        </v-btn>
                    </template>
                </v-card>
            </v-col>
            <v-col v-for="product in products" :key="product.id" cols="12">
                <v-card
                    variant="tonal"
                    :color="product.stock === 0 ? 'red-lighten-4' : undefined"
                    :class="{ 'out-of-stock-card': product.stock === 0 }"
                >
                    <!-- Layout en móvil: columna -->
                    <div class="d-flex d-md-none flex-column">
                        <div class="d-flex justify-space-between align-center pa-3">
                            <v-avatar size="80" rounded="lg">
                                <v-img
                                    :src="product.imageUrl"
                                    :alt="product.name"
                                    cover
                                ></v-img>
                            </v-avatar>
                            <div class="flex-grow-1 pl-3">
                                <v-card-title class="text-subtitle-1 font-weight-bold pa-0">
                                    {{ product.name }}
                                    <v-chip
                                        v-if="product.stock === 0"
                                        color="red"
                                        size="x-small"
                                        class="ml-1"
                                    >
                                        SIN STOCK
                                    </v-chip>
                                </v-card-title>
                                <v-card-subtitle class="pa-0 text-caption">
                                    <div><strong>Precio:</strong> ${{ product.price }}</div>
                                    <div>
                                        <strong :class="{ 'text-red': product.stock === 0 }">Stock:</strong>
                                        <span :class="{ 'text-red font-weight-bold': product.stock === 0 }">
                                            {{ product.stock }}
                                        </span>
                                    </div>
                                </v-card-subtitle>
                            </div>
                        </div>
                        <v-card-actions class="justify-end pa-2">
                            <v-btn icon="mdi-pencil" size="small" color="black" variant="text" @click="editProduct(product.id)" title="Editar"></v-btn>
                            <v-btn icon="mdi-delete" size="small" color="#424242" variant="text" @click="deleteProduct(product.id)" title="Dar de baja"></v-btn>
                        </v-card-actions>
                    </div>

                    <!-- Layout en desktop: fila -->
                    <div class="d-none d-md-flex justify-space-between">
                        <div class="d-flex">
                            <v-avatar class="ma-3" size="120" rounded="lg">
                                <v-img
                                    :src="product.imageUrl"
                                    :alt="product.name"
                                    cover
                                ></v-img>
                            </v-avatar>
                            <div>
                                <v-card-title class="text-h6 font-weight-bold">
                                    {{ product.name }}
                                    <v-chip
                                        v-if="product.stock === 0"
                                        color="red"
                                        size="small"
                                        class="ml-2"
                                        prepend-icon="mdi-alert-circle"
                                    >
                                        SIN STOCK
                                    </v-chip>
                                </v-card-title>
                                <v-card-subtitle class="pb-2">
                                    <strong>Precio:</strong> ${{ product.price }} |
                                    <strong :class="{ 'text-red': product.stock === 0 }">Stock:</strong>
                                    <span :class="{ 'text-red font-weight-bold': product.stock === 0 }">
                                        {{ product.stock }}
                                    </span>
                                </v-card-subtitle>
                                <v-card-subtitle class="pb-2">
                                    <strong v-if="product.category">Categoría:</strong> {{ product.category?.name }} |
                                    <strong v-if="product.type">Tipo:</strong> {{ product.type?.name }}
                                </v-card-subtitle>
                                <v-card-text class="py-0">{{ product.description }}</v-card-text>
                            </div>
                        </div>

                        <v-card-actions class="align-self-center pa-4">
                            <v-btn icon="mdi-pencil" color="black" variant="text" @click="editProduct(product.id)" title="Editar"></v-btn>
                            <v-btn icon="mdi-delete" color="#424242" variant="text" class="ml-2" @click="deleteProduct(product.id)" title="Dar de baja"></v-btn>
                        </v-card-actions>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.out-of-stock-card {
    border: 3px solid #ef5350 !important;
    box-shadow: 0 4px 12px rgba(239, 83, 80, 0.3) !important;
}

.out-of-stock-card:hover {
    box-shadow: 0 6px 16px rgba(239, 83, 80, 0.4) !important;
}
</style>
