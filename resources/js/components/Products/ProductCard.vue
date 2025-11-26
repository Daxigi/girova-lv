<script setup lang="ts">


import { computed } from 'vue';
import { useCart } from '../../composables/useCart';

const props = defineProps<{
    product: {
        id: number | string;
        name: string;
        description: string;
        price: number;
        image_url: string;
        stock: number;      
    }
}>()

const { addToCart } = useCart();

const formattedPrice = computed(() => {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    }).format(props.product.price);
});

const hasStock = computed(() => props.product.stock > 0);

const stockMessage = computed(() => {
    if (props.product.stock === 0) {
        return 'Sin stock';
    } else if (props.product.stock <= 5) {
        return `¡Solo ${props.product.stock} disponibles!`;
    }
    return `Stock: ${props.product.stock}`;
});

const stockColor = computed(() => {
    if (props.product.stock === 0) return '#212121';
    if (props.product.stock <= 5) return '#757575';
    return '#424242';
});

function handleAddToCart() {
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