<script setup lang="ts">
/**
 * PRODUCT CARD - Tarjeta de producto
 *
 * Muestra un producto individual con:
 * - Imagen
 * - Nombre
 * - Precio
 * - Descripción
 * - Botón para agregar al carrito
 */

import { computed } from 'vue';
import { useCart } from '../../composables/useCart';

// Definir las props que recibe este componente
const props = defineProps<{
    product: {
        id: number | string;
        name: string;
        description: string;
        price: number;
        image_url: string;
        stock: number;        // ← IMPORTANTE: Agregamos stock
    }
}>()

// Importar el composable del carrito
const { addToCart } = useCart();

/**
 * Formatear el precio en pesos argentinos
 */
const formattedPrice = computed(() => {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    }).format(props.product.price);
});

/**
 * Computed: Verificar si hay stock disponible
 */
const hasStock = computed(() => props.product.stock > 0);

/**
 * Computed: Mensaje de stock
 */
const stockMessage = computed(() => {
    if (props.product.stock === 0) {
        return 'Sin stock';
    } else if (props.product.stock <= 5) {
        return `¡Solo ${props.product.stock} disponibles!`;
    }
    return `Stock: ${props.product.stock}`;
});

/**
 * Computed: Color del chip de stock (tema blanco y negro)
 */
const stockColor = computed(() => {
    if (props.product.stock === 0) return '#212121';      // Gris muy oscuro
    if (props.product.stock <= 5) return '#757575';       // Gris medio
    return '#424242';                                      // Gris oscuro
});

/**
 * Manejar click en "Agregar al carrito"
 *
 * Toma el producto actual y lo agrega al carrito
 */
function handleAddToCart() {
    // Agregar el producto al carrito con cantidad 1
    addToCart({
        id: props.product.id,
        name: props.product.name,
        price: props.product.price,
        image_url: props.product.image_url,
        stock: props.product.stock,
        description: props.product.description,
    }, 1);
}
</script>

<template>
    <v-card
        class="d-flex flex-column"
        height="450"
    >
        <v-img
            class="align-end text-white"
            height="200"
            :src="product.image_url"
            cover>
            <v-card-title>{{ product.name }}</v-card-title>
        </v-img>

        <v-card-subtitle class="pt-4 d-flex justify-space-between align-center">
            <span class="text-h6">{{ formattedPrice }}</span>
            <!-- Chip de stock -->
            <v-chip
                :color="stockColor"
                size="small"
                variant="flat"
            >
                {{ stockMessage }}
            </v-chip>
        </v-card-subtitle>

        <v-card-text class="flex-grow-1">
            {{ product.description }}
        </v-card-text>

        <v-card-actions>
            <!-- Botón agregar al carrito con evento click -->
            <v-btn
                color="black"
                variant="elevated"
                prepend-icon="mdi-cart-plus"
                :disabled="!hasStock"
                @click="handleAddToCart"
                block
            >
                {{ hasStock ? 'Agregar al carrito' : 'Sin stock' }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>