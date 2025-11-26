<script setup lang="ts">

import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import {formatDate, formatPrice} from '../../utils/formatters'

const props = defineProps<{
    orders: Array<{
        id: string;
        status: string;
        total: number;
        customer_name: string;
        created_at: string;
        user?: {
            id: string;
            name: string;
            email: string;
        };
        items: Array<{
            id: string;
            quantity: number;
            price: number;
            product: {
                id: string;
                name: string;
                image_url: string;
            };
        }>;
    }>;
    isAdminView: boolean;
}>();

function getStatusColor(status: string): string {
    const colors: Record<string, string> = {
        pending: 'warning',
        processing: 'info',
        shipped: 'primary',
        delivered: 'success',
        cancelled: 'error',
    };
    return colors[status] || 'grey';
}

function getStatusText(status: string): string {
    const texts: Record<string, string> = {
        pending: 'Pendiente',
        processing: 'En Proceso',
        shipped: 'Enviado',
        delivered: 'Entregado',
        cancelled: 'Cancelado',
    };
    return texts[status] || status;
}

function getStatusIcon(status: string): string {
    const icons: Record<string, string> = {
        pending: 'mdi-clock-outline',
        processing: 'mdi-package-variant',
        shipped: 'mdi-truck-delivery',
        delivered: 'mdi-check-circle',
        cancelled: 'mdi-close-circle',
    };
    return icons[status] || 'mdi-help-circle';
}

const hasOrders = computed(() => props.orders && props.orders.length > 0);
</script>

<template>
    <v-container class="py-4 py-md-8">
        <div class="d-flex flex-column flex-sm-row justify-space-between align-start align-sm-center mb-6 ga-3">
            <h1 class="text-h5 text-md-h4">{{ isAdminView ? 'Todas las Órdenes' : 'Mis Órdenes' }}</h1>
            <v-btn
                :to="route('home')"
                color="primary"
                variant="outlined"
                prepend-icon="mdi-arrow-left"
                size="small"
                class="align-self-stretch align-self-sm-auto"
            >
                <span class="d-none d-sm-inline">Volver a la Tienda</span>
                <span class="d-sm-none">Volver</span>
            </v-btn>
        </div>

        <v-card v-if="!hasOrders" class="text-center pa-8">
            <v-icon size="80" color="grey-lighten-1" class="mb-4">
                mdi-cart-outline
            </v-icon>
            <h2 class="text-h5 mb-4">No tienes órdenes aún</h2>
            <p class="text-body-1 text-grey mb-6">
                Explora nuestra tienda y realiza tu primera compra
            </p>
            <v-btn
                :to="route('home')"
                color="primary"
                size="large"
                prepend-icon="mdi-shopping"
            >
                Ir a la Tienda
            </v-btn>
        </v-card>

        <v-row v-else>
            <v-col
                v-for="order in orders"
                :key="order.id"
                cols="12"
            >
                <v-card class="order-card" elevation="2">
                    <v-card-title class="d-flex flex-column flex-sm-row justify-space-between align-start align-sm-center bg-grey-lighten-4 ga-3">
                        <div class="flex-grow-1">
                            <div class="text-subtitle-1 text-md-h6 font-weight-bold">
                                Orden #{{ order.id.substring(0, 8) }}
                            </div>
                            <div class="text-caption text-grey">
                                {{ formatDate(order.created_at) }}
                            </div>
                            <div v-if="isAdminView && order.user" class="text-caption text-primary mt-1">
                                <v-icon size="small" class="mr-1">mdi-account</v-icon>
                                {{ order.user.name }} ({{ order.user.email }})
                            </div>
                        </div>

                        <v-chip
                            :color="getStatusColor(order.status)"
                            :prepend-icon="getStatusIcon(order.status)"
                            variant="flat"
                            size="small"
                            class="align-self-start align-self-sm-center"
                        >
                            {{ getStatusText(order.status) }}
                        </v-chip>
                    </v-card-title>

                    <v-card-text class="pa-3 pa-md-4">
                        <v-row>
                            <v-col cols="12" md="8">
                                <div class="text-subtitle-2 text-md-subtitle-1 mb-3">Productos:</div>
                                <v-list density="compact">
                                    <v-list-item
                                        v-for="item in order.items"
                                        :key="item.id"
                                        class="px-0"
                                    >
                                        <template v-slot:prepend>
                                            <v-avatar :size="$vuetify.display.xs ? 40 : 50" rounded class="mr-2 mr-md-3">
                                                <v-img
                                                    :src="item.product.image_url"
                                                    :alt="item.product.name"
                                                    cover
                                                />
                                            </v-avatar>
                                        </template>

                                        <v-list-item-title class="text-body-2 text-md-body-1">
                                            {{ item.product.name }}
                                        </v-list-item-title>

                                        <v-list-item-subtitle class="text-caption text-md-body-2">
                                            {{ item.quantity }} x {{ formatPrice(item.price) }}
                                        </v-list-item-subtitle>

                                        <template v-slot:append>
                                            <strong class="text-body-2 text-md-body-1">{{ formatPrice(item.price * item.quantity) }}</strong>
                                        </template>
                                    </v-list-item>
                                </v-list>
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-card variant="outlined" class="pa-3 pa-md-4">
                                    <div class="text-subtitle-2 text-md-subtitle-1 mb-2">Total:</div>
                                    <div class="text-h6 text-md-h5 text-primary mb-3 mb-md-4">
                                        {{ formatPrice(order.total) }}
                                    </div>

                                    <Link :href="route('orders.show', order.id)">
                                        <v-btn
                                            block
                                            color="primary"
                                            variant="outlined"
                                            prepend-icon="mdi-eye"
                                            :size="$vuetify.display.xs ? 'small' : 'default'"
                                        >
                                            Ver Detalles
                                        </v-btn>
                                    </Link>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.order-card {
    transition: transform 0.2s;
}

.order-card:hover {
    transform: translateY(-2px);
}
</style>
