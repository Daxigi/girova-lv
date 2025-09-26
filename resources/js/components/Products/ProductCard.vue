<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    product: {
        name: string;
        description: string;
        price: number;
        image_url: string;
    }
}>()

const formattedPrice = computed(() => {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    }).format(props.product.price);
});
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

        <v-card-subtitle
            class="pt-4">
            {{ formattedPrice }}
        </v-card-subtitle>

        <v-card-text class="flex-grow-1">
            {{ product.description }}
        </v-card-text>

        <v-card-actions>
            <v-btn color="orange" variant="elevated">Agregar al carrito</v-btn>
        </v-card-actions>
    </v-card>
</template>