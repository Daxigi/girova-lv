<script setup lang="ts">
/**
 * CART DRAWER - Panel lateral del carrito
 *
 * Este componente muestra el carrito de compras en un panel lateral
 * que se desliza desde la derecha.
 *
 * Funcionalidades:
 * - Ver productos agregados
 * - Cambiar cantidades
 * - Eliminar productos
 * - Ver total
 * - Vaciar carrito
 */

import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '../composables/useCart';
import {  formatPrice } from '@/utils/formatters';

// Importar el composable del carrito
// Esto nos da acceso a todas las funciones y estado del carrito
const { cart, isCartOpen, toggleCart, removeFromCart, updateQuantity, clearCart } = useCart();

/**
 * Computed: Total formateado
 *
 * Convierte el número del total a formato de moneda
 */
const formattedTotal = computed(() => formatPrice(cart.value.total));

/**
 * Manejar cambio de cantidad
 *
 * Cuando el usuario cambia el input de cantidad
 */
function handleQuantityChange(productId: number | string, newQuantity: number) {
    // Asegurar que la cantidad sea al menos 1
    const quantity = Math.max(1, newQuantity);
    updateQuantity(productId, quantity);
}

/**
 * Confirmar antes de vaciar el carrito
 */
function handleClearCart() {
    if (confirm('¿Estás seguro de que deseas vaciar el carrito?')) {
        clearCart();
    }
}
</script>

<template>
    <!--
        v-navigation-drawer: Componente de Vuetify para panel lateral

        Props importantes:
        - v-model: Controla si está abierto/cerrado (two-way binding)
        - location="right": Se desliza desde la derecha
        - temporary: Se cierra al hacer click fuera
        - width: Ancho del drawer (responsivo: 90% en móvil, 400px en desktop)
    -->
    <v-navigation-drawer
        v-model="isCartOpen"
        location="right"
        temporary
        :width="$vuetify.display.xs ? '90%' : 400"
    >
        <!-- HEADER DEL CARRITO -->
        <v-toolbar color="primary" dark>
            <v-toolbar-title class="text-subtitle-1 text-md-h6">
                <!-- Icono + Título + Badge con cantidad -->
                <v-icon start :size="$vuetify.display.xs ? 'small' : 'default'">mdi-cart</v-icon>
                Mi Carrito
                <v-badge
                    v-if="cart.itemCount > 0"
                    :content="cart.itemCount"
                    color="error"
                    inline
                    class="ml-2"
                />
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <!-- Botón para cerrar el drawer -->
            <v-btn icon @click="toggleCart" :size="$vuetify.display.xs ? 'small' : 'default'">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-toolbar>

        <!-- CONTENIDO DEL CARRITO -->
        <v-container class="pa-3 pa-md-4">
            <!-- CASO 1: Carrito vacío -->
            <div v-if="cart.items.length === 0" class="text-center py-6 py-md-8">
                <v-icon :size="$vuetify.display.xs ? 60 : 80" color="grey-lighten-1">mdi-cart-outline</v-icon>
                <p class="text-subtitle-1 text-md-h6 mt-3 mt-md-4 text-grey">Tu carrito está vacío</p>
                <p class="text-caption text-md-body-2 text-grey">¡Agrega productos para comenzar!</p>
            </div>

            <!-- CASO 2: Carrito con productos -->
            <div v-else>
                <!-- LISTA DE PRODUCTOS -->
                <v-list lines="three">
                    <!--
                        v-for: Iterar sobre cada producto del carrito
                        :key: Identificador único para Vue (optimización)
                    -->
                    <v-list-item
                        v-for="item in cart.items"
                        :key="item.id"
                        class="px-0 mb-3 mb-md-4"
                    >
                        <template v-slot:prepend>
                            <!-- IMAGEN DEL PRODUCTO -->
                            <v-avatar :size="$vuetify.display.xs ? 60 : 80" rounded class="mr-2 mr-md-3">
                                <v-img :src="item.image_url" :alt="item.name" cover />
                            </v-avatar>
                        </template>

                        <!-- INFORMACIÓN DEL PRODUCTO -->
                        <v-list-item-title class="text-wrap mb-1 mb-md-2 text-body-2 text-md-body-1">
                            {{ item.name }}
                        </v-list-item-title>

                        <v-list-item-subtitle class="mb-1 mb-md-2 text-caption text-md-body-2">
                            <strong>{{ formatPrice(item.price) }}</strong> c/u
                        </v-list-item-subtitle>

                        <!-- CONTROLES DE CANTIDAD -->
                        <div class="d-flex align-center mt-1 mt-md-2">
                            <!-- Botón decrementar -->
                            <v-btn
                                :size="$vuetify.display.xs ? 'x-small' : 'small'"
                                icon
                                variant="outlined"
                                @click="updateQuantity(item.id, item.quantity - 1)"
                            >
                                <v-icon :size="$vuetify.display.xs ? 'small' : 'default'">mdi-minus</v-icon>
                            </v-btn>

                            <!-- Input de cantidad -->
                            <v-text-field
                                :model-value="item.quantity"
                                @update:model-value="(val) => handleQuantityChange(item.id, Number(val))"
                                type="number"
                                min="1"
                                density="compact"
                                hide-details
                                variant="outlined"
                                class="mx-1 mx-md-2"
                                :style="$vuetify.display.xs ? 'width: 50px' : 'width: 60px'"
                            />

                            <!-- Botón incrementar -->
                            <v-btn
                                :size="$vuetify.display.xs ? 'x-small' : 'small'"
                                icon
                                variant="outlined"
                                @click="updateQuantity(item.id, item.quantity + 1)"
                            >
                                <v-icon :size="$vuetify.display.xs ? 'small' : 'default'">mdi-plus</v-icon>
                            </v-btn>
                        </div>

                        <!-- SUBTOTAL DEL PRODUCTO -->
                        <v-list-item-subtitle class="mt-1 mt-md-2 text-caption text-md-body-2">
                            <strong>Subtotal:</strong> {{ formatPrice(item.price * item.quantity) }}
                        </v-list-item-subtitle>

                        <template v-slot:append>
                            <!-- BOTÓN ELIMINAR -->
                            <v-btn
                                icon
                                :size="$vuetify.display.xs ? 'x-small' : 'small'"
                                variant="text"
                                color="error"
                                @click="removeFromCart(item.id)"
                            >
                                <v-icon :size="$vuetify.display.xs ? 'small' : 'default'">mdi-delete</v-icon>
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-list>

                <v-divider class="my-3 my-md-4"></v-divider>

                <!-- TOTAL -->
                <div class="d-flex justify-space-between align-center mb-3 mb-md-4">
                    <span class="text-subtitle-1 text-md-h6">Total:</span>
                    <span class="text-h6 text-md-h5 font-weight-bold text-primary">
                        {{ formattedTotal }}
                    </span>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <Link :href="route('checkout')">
                    <v-btn
                        block
                        color="primary"
                        :size="$vuetify.display.xs ? 'default' : 'large'"
                        class="mb-2 mb-md-3"
                        prepend-icon="mdi-credit-card"
                    >
                        Proceder al Pago
                    </v-btn>
                </Link>

                <v-btn
                    block
                    color="error"
                    variant="outlined"
                    :size="$vuetify.display.xs ? 'default' : 'large'"
                    prepend-icon="mdi-delete-sweep"
                    @click="handleClearCart"
                >
                    Vaciar Carrito
                </v-btn>
            </div>
        </v-container>
    </v-navigation-drawer>
</template>

<style scoped>
/**
 * Estilos personalizados para el carrito
 */

/* Asegurar que el texto del nombre del producto se ajuste */
.v-list-item-title {
    white-space: normal !important;
}

/* Centrar el input de cantidad */
.v-text-field {
    text-align: center;
}
</style>
