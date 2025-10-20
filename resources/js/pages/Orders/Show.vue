<script setup lang="ts">
/**
 * ORDER DETAIL PAGE - Página de detalle de orden
 *
 * Muestra todos los detalles de una orden específica:
 * - Información del cliente
 * - Productos ordenados
 * - Estado de la orden
 * - Total
 */

import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

/**
 * Props recibidas del backend
 */
const props = defineProps<{
    order: {
        id: string;
        status: string;
        total: number;
        customer_name: string;
        customer_email: string;
        customer_phone: string | null;
        shipping_address: string;
        notes: string | null;
        created_at: string;
        items: Array<{
            id: string;
            quantity: number;
            price: number;
            product: {
                id: string;
                name: string;
                description: string;
                image_url: string;
            };
        }>;
    };
}>();

/**
 * Formatear precio
 */
function formatPrice(price: number): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    }).format(price);
}

/**
 * Formatear fecha
 */
function formatDate(dateString: string): string {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-AR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
}

/**
 * Obtener color según el estado
 */
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

/**
 * Obtener texto del estado en español
 */
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

/**
 * Obtener icono según el estado
 */
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

/**
 * Computed: Calcular subtotal de un item
 */
function getItemSubtotal(item: any): number {
    return item.price * item.quantity;
}
</script>

<template>
    <v-container class="py-8">
        <!-- Header con título y botón volver -->
        <div class="d-flex justify-space-between align-center mb-6">
            <div>
                <h1 class="text-h4">Orden #{{ order.id.substring(0, 8) }}</h1>
                <p class="text-grey">{{ formatDate(order.created_at) }}</p>
            </div>
            <Link :href="route('orders.my-orders')">
                <v-btn
                    color="primary"
                    variant="outlined"
                    prepend-icon="mdi-arrow-left"
                >
                    Volver a Mis Órdenes
                </v-btn>
            </Link>
        </div>

        <v-row>
            <!-- COLUMNA IZQUIERDA: Productos y detalles -->
            <v-col cols="12" md="8">
                <!-- Card de productos -->
                <v-card class="mb-6">
                    <v-card-title class="bg-grey-lighten-4 d-flex justify-space-between align-center">
                        <span>Productos</span>
                        <v-chip
                            :color="getStatusColor(order.status)"
                            :prepend-icon="getStatusIcon(order.status)"
                            variant="flat"
                        >
                            {{ getStatusText(order.status) }}
                        </v-chip>
                    </v-card-title>

                    <v-card-text class="pa-4">
                        <v-list lines="two">
                            <v-list-item
                                v-for="item in order.items"
                                :key="item.id"
                                class="px-0 mb-2"
                            >
                                <template v-slot:prepend>
                                    <v-avatar size="80" rounded class="mr-4">
                                        <v-img
                                            :src="item.product.image_url"
                                            :alt="item.product.name"
                                            cover
                                        />
                                    </v-avatar>
                                </template>

                                <v-list-item-title class="text-h6 mb-2">
                                    {{ item.product.name }}
                                </v-list-item-title>

                                <v-list-item-subtitle class="text-body-2">
                                    {{ item.product.description }}
                                </v-list-item-subtitle>

                                <v-list-item-subtitle class="mt-2">
                                    <strong>Cantidad:</strong> {{ item.quantity }} x {{ formatPrice(item.price) }}
                                </v-list-item-subtitle>

                                <template v-slot:append>
                                    <div class="text-right">
                                        <div class="text-h6 text-primary">
                                            {{ formatPrice(getItemSubtotal(item)) }}
                                        </div>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>

                        <v-divider class="my-4"></v-divider>

                        <!-- Total -->
                        <div class="d-flex justify-space-between align-center">
                            <span class="text-h6">Total:</span>
                            <span class="text-h5 font-weight-bold text-primary">
                                {{ formatPrice(order.total) }}
                            </span>
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Card de notas (si existen) -->
                <v-card v-if="order.notes">
                    <v-card-title class="bg-grey-lighten-4">
                        <v-icon class="mr-2">mdi-note-text</v-icon>
                        Notas Adicionales
                    </v-card-title>
                    <v-card-text class="pa-4">
                        <p class="text-body-1">{{ order.notes }}</p>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- COLUMNA DERECHA: Información del cliente y envío -->
            <v-col cols="12" md="4">
                <!-- Card de información del cliente -->
                <v-card class="mb-6">
                    <v-card-title class="bg-grey-lighten-4">
                        <v-icon class="mr-2">mdi-account</v-icon>
                        Información del Cliente
                    </v-card-title>
                    <v-card-text class="pa-4">
                        <div class="mb-3">
                            <div class="text-caption text-grey">Nombre</div>
                            <div class="text-body-1">{{ order.customer_name }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-caption text-grey">Email</div>
                            <div class="text-body-1">{{ order.customer_email }}</div>
                        </div>

                        <div v-if="order.customer_phone">
                            <div class="text-caption text-grey">Teléfono</div>
                            <div class="text-body-1">{{ order.customer_phone }}</div>
                        </div>
                    </v-card-text>
                </v-card>

                <!-- Card de dirección de envío -->
                <v-card>
                    <v-card-title class="bg-grey-lighten-4">
                        <v-icon class="mr-2">mdi-map-marker</v-icon>
                        Dirección de Envío
                    </v-card-title>
                    <v-card-text class="pa-4">
                        <p class="text-body-1" style="white-space: pre-line;">
                            {{ order.shipping_address }}
                        </p>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
/* Estilos específicos si se necesitan */
</style>
