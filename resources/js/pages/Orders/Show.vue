<script setup lang="ts">


import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { formatDate, formatPrice} from '../../utils/formatters';

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
    canUpdateStatus: boolean;
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

function getItemSubtotal(item: any): number {
    return item.price * item.quantity;
}

const selectedStatus = ref(props.order.status);
const isUpdatingStatus = ref(false);

const statusOptions = [
    { value: 'pending', text: 'Pendiente' },
    { value: 'processing', text: 'En Proceso' },
    { value: 'shipped', text: 'Enviado' },
    { value: 'delivered', text: 'Entregado' },
    { value: 'cancelled', text: 'Cancelado' },
];

function updateOrderStatus() {
    if (selectedStatus.value === props.order.status) {
        return; 
    }

    isUpdatingStatus.value = true;

    router.put(
        route('orders.update-status', props.order.id),
        { status: selectedStatus.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                isUpdatingStatus.value = false;
            },
            onError: () => {
                isUpdatingStatus.value = false;
                selectedStatus.value = props.order.status; 
            },
        }
    );
}
</script>

<template>
    <v-container class="py-4 py-md-8">
        <div class="d-flex flex-column flex-sm-row justify-space-between align-start align-sm-center mb-4 mb-md-6 ga-3">
            <div class="flex-grow-1">
                <h1 class="text-h5 text-md-h4">Orden #{{ order.id.substring(0, 8) }}</h1>
                <p class="text-caption text-md-body-1 text-grey">{{ formatDate(order.created_at) }}</p>
            </div>
            <Link :href="route('orders.my-orders')" class="align-self-stretch align-self-sm-auto">
                <v-btn
                    color="primary"
                    variant="outlined"
                    prepend-icon="mdi-arrow-left"
                    :size="$vuetify.display.xs ? 'small' : 'default'"
                    block
                    class="d-sm-inline-block"
                >
                    <span class="d-none d-sm-inline">Volver a Mis Órdenes</span>
                    <span class="d-sm-none">Volver</span>
                </v-btn>
            </Link>
        </div>

        <v-row>
            <v-col cols="12" md="8">
                <v-card class="mb-4 mb-md-6">
                    <v-card-title class="bg-grey-lighten-4 d-flex flex-column flex-sm-row justify-space-between align-start align-sm-center ga-2">
                        <span class="text-subtitle-1 text-md-h6">Productos</span>
                        <v-chip
                            :color="getStatusColor(order.status)"
                            :prepend-icon="getStatusIcon(order.status)"
                            variant="flat"
                            :size="$vuetify.display.xs ? 'small' : 'default'"
                            class="align-self-start align-self-sm-center"
                        >
                            {{ getStatusText(order.status) }}
                        </v-chip>
                    </v-card-title>

                    <v-card-text class="pa-3 pa-md-4">
                        <v-list lines="two">
                            <v-list-item
                                v-for="item in order.items"
                                :key="item.id"
                                class="px-0 mb-2"
                            >
                                <template v-slot:prepend>
                                    <v-avatar :size="$vuetify.display.xs ? 60 : 80" rounded class="mr-2 mr-md-4">
                                        <v-img
                                            :src="item.product.image_url"
                                            :alt="item.product.name"
                                            cover
                                        />
                                    </v-avatar>
                                </template>

                                <v-list-item-title class="text-subtitle-1 text-md-h6 mb-1 mb-md-2">
                                    {{ item.product.name }}
                                </v-list-item-title>

                                <v-list-item-subtitle class="text-caption text-md-body-2">
                                    {{ item.product.description }}
                                </v-list-item-subtitle>

                                <v-list-item-subtitle class="mt-1 mt-md-2 text-caption text-md-body-2">
                                    <strong>Cantidad:</strong> {{ item.quantity }} x {{ formatPrice(item.price) }}
                                </v-list-item-subtitle>

                                <template v-slot:append>
                                    <div class="text-right">
                                        <div class="text-subtitle-1 text-md-h6 text-primary">
                                            {{ formatPrice(getItemSubtotal(item)) }}
                                        </div>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>

                        <v-divider class="my-3 my-md-4"></v-divider>

                        <div class="d-flex justify-space-between align-center">
                            <span class="text-subtitle-1 text-md-h6">Total:</span>
                            <span class="text-h6 text-md-h5 font-weight-bold text-primary">
                                {{ formatPrice(order.total) }}
                            </span>
                        </div>
                    </v-card-text>
                </v-card>

                <v-card v-if="order.notes">
                    <v-card-title class="bg-grey-lighten-4 text-subtitle-1 text-md-h6">
                        <v-icon class="mr-2" :size="$vuetify.display.xs ? 'small' : 'default'">mdi-note-text</v-icon>
                        Notas Adicionales
                    </v-card-title>
                    <v-card-text class="pa-3 pa-md-4">
                        <p class="text-body-2 text-md-body-1">{{ order.notes }}</p>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" md="4">
                <v-card class="mb-4 mb-md-6">
                    <v-card-title class="bg-grey-lighten-4 text-subtitle-1 text-md-h6">
                        <v-icon class="mr-2" :size="$vuetify.display.xs ? 'small' : 'default'">mdi-account</v-icon>
                        Información del Cliente
                    </v-card-title>
                    <v-card-text class="pa-3 pa-md-4">
                        <div class="mb-2 mb-md-3">
                            <div class="text-caption text-grey">Nombre</div>
                            <div class="text-body-2 text-md-body-1">{{ order.customer_name }}</div>
                        </div>

                        <div class="mb-2 mb-md-3">
                            <div class="text-caption text-grey">Email</div>
                            <div class="text-body-2 text-md-body-1">{{ order.customer_email }}</div>
                        </div>

                        <div v-if="order.customer_phone">
                            <div class="text-caption text-grey">Teléfono</div>
                            <div class="text-body-2 text-md-body-1">{{ order.customer_phone }}</div>
                        </div>
                    </v-card-text>
                </v-card>

                <v-card class="mb-4 mb-md-6">
                    <v-card-title class="bg-grey-lighten-4 text-subtitle-1 text-md-h6">
                        <v-icon class="mr-2" :size="$vuetify.display.xs ? 'small' : 'default'">mdi-map-marker</v-icon>
                        Dirección de Envío
                    </v-card-title>
                    <v-card-text class="pa-3 pa-md-4">
                        <p class="text-body-2 text-md-body-1" style="white-space: pre-line;">
                            {{ order.shipping_address }}
                        </p>
                    </v-card-text>
                </v-card>

                <v-card v-if="canUpdateStatus">
                    <v-card-title class="bg-primary text-white text-subtitle-1 text-md-h6">
                        <v-icon class="mr-2" color="white" :size="$vuetify.display.xs ? 'small' : 'default'">mdi-swap-horizontal</v-icon>
                        Actualizar Estado
                    </v-card-title>
                    <v-card-text class="pa-3 pa-md-4">
                        <v-select
                            v-model="selectedStatus"
                            :items="statusOptions"
                            item-title="text"
                            item-value="value"
                            label="Estado de la orden"
                            variant="outlined"
                            density="comfortable"
                            :disabled="isUpdatingStatus"
                        ></v-select>

                        <v-btn
                            @click="updateOrderStatus"
                            :loading="isUpdatingStatus"
                            :disabled="isUpdatingStatus || selectedStatus === order.status"
                            color="primary"
                            block
                            :size="$vuetify.display.xs ? 'default' : 'large'"
                            prepend-icon="mdi-check"
                        >
                            Guardar Cambios
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
/* Estilos específicos si se necesitan */
</style>
