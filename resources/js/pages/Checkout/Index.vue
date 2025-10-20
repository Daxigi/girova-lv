<script setup lang="ts">
/**
 * CHECKOUT PAGE - Página de finalización de compra
 *
 * Esta página permite al usuario:
 * - Ver el resumen de su carrito
 * - Ingresar datos de envío
 * - Confirmar la orden
 */

import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useCart } from '../../composables/useCart';
import { useToast } from '../../composables/useToast';

// Obtener datos del usuario autenticado
const page = usePage();
const user = computed(() => page.props.auth.user);

// Obtener el carrito
const { cart, clearCart } = useCart();

// Toast para notificaciones
const toast = useToast();

/**
 * Formulario de checkout
 *
 * useForm de Inertia maneja:
 * - Estado del formulario
 * - Errores de validación
 * - Estado de carga (processing)
 */
const form = useForm({
    customer_name: user.value?.name || '',
    customer_email: user.value?.email || '',
    customer_phone: '',
    shipping_address: '',
    notes: '',
    items: [] as any[], // Se llenará al enviar
});

/**
 * Referencia al formulario de Vuetify para validación
 */
const formRef = ref<any>(null);

/**
 * Reglas de validación de Vuetify
 */
const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    email: (value: any) => /.+@.+\..+/.test(value) || 'Debe ser un correo electrónico válido.',
};

/**
 * Computed: Formatear precio
 */
function formatPrice(price: number): string {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS',
    }).format(price);
}

/**
 * Computed: Verificar si el carrito está vacío
 */
const isEmpty = computed(() => cart.value.items.length === 0);

/**
 * Manejar envío del formulario
 */
async function handleSubmit() {
    // Validar con Vuetify
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    // Verificar que haya items en el carrito
    if (isEmpty.value) {
        toast.error('Tu carrito está vacío. Agrega productos antes de continuar.');
        return;
    }

    // Preparar los items del carrito para enviar
    form.items = cart.value.items.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price,
    }));

    // Enviar el formulario
    form.post(route('checkout.process'), {
        preserveScroll: true,
        onSuccess: () => {
            // Vaciar el carrito después de crear la orden
            clearCart();
            toast.success('¡Orden creada exitosamente!');
        },
        onError: (errors) => {
            console.error('Errores:', errors);
            toast.error('Hubo un error al procesar tu orden. Revisa los datos.');
        },
    });
}
</script>

<template>
    <v-container class="py-8">
        <h1 class="text-h4 mb-6">Finalizar Compra</h1>

        <!-- Mensaje si el carrito está vacío -->
        <v-alert
            v-if="isEmpty"
            type="warning"
            variant="tonal"
            class="mb-6"
        >
            Tu carrito está vacío. Agrega productos antes de continuar.
        </v-alert>

        <v-row v-else>
            <!-- COLUMNA IZQUIERDA: Formulario -->
            <v-col cols="12" md="7">
                <v-card>
                    <v-card-title class="text-h5 pa-4 bg-primary">
                        Información de Envío
                    </v-card-title>

                    <v-card-text class="pa-6">
                        <v-form ref="formRef" @submit.prevent="handleSubmit">
                            <!-- Nombre completo -->
                            <v-text-field
                                v-model="form.customer_name"
                                label="Nombre Completo"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account"
                                :rules="[rules.required]"
                                :error-messages="form.errors.customer_name"
                                class="mb-4"
                            />

                            <!-- Email -->
                            <v-text-field
                                v-model="form.customer_email"
                                label="Correo Electrónico"
                                type="email"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-email"
                                :rules="[rules.required, rules.email]"
                                :error-messages="form.errors.customer_email"
                                class="mb-4"
                            />

                            <!-- Teléfono -->
                            <v-text-field
                                v-model="form.customer_phone"
                                label="Teléfono (Opcional)"
                                type="tel"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-phone"
                                :error-messages="form.errors.customer_phone"
                                class="mb-4"
                                hint="Ej: +54 9 11 1234-5678"
                            />

                            <!-- Dirección de envío -->
                            <v-textarea
                                v-model="form.shipping_address"
                                label="Dirección de Envío"
                                variant="outlined"
                                prepend-inner-icon="mdi-map-marker"
                                :rules="[rules.required]"
                                :error-messages="form.errors.shipping_address"
                                rows="3"
                                class="mb-4"
                                hint="Calle, número, piso, departamento, código postal, ciudad"
                            />

                            <!-- Notas adicionales -->
                            <v-textarea
                                v-model="form.notes"
                                label="Notas Adicionales (Opcional)"
                                variant="outlined"
                                prepend-inner-icon="mdi-note-text"
                                :error-messages="form.errors.notes"
                                rows="2"
                                class="mb-4"
                                hint="Ej: Tocar timbre del 5to piso"
                            />

                            <!-- Botones de acción -->
                            <div class="d-flex justify-space-between">
                                <v-btn
                                    :to="route('home')"
                                    variant="outlined"
                                    color="grey"
                                    size="large"
                                    prepend-icon="mdi-arrow-left"
                                >
                                    Seguir Comprando
                                </v-btn>

                                <v-btn
                                    type="submit"
                                    color="primary"
                                    size="large"
                                    :loading="form.processing"
                                    :disabled="form.processing || isEmpty"
                                    prepend-icon="mdi-check-circle"
                                >
                                    Confirmar Orden
                                </v-btn>
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>

            <!-- COLUMNA DERECHA: Resumen del carrito -->
            <v-col cols="12" md="5">
                <v-card>
                    <v-card-title class="text-h5 pa-4 bg-secondary">
                        Resumen del Pedido
                    </v-card-title>

                    <v-card-text class="pa-4">
                        <!-- Lista de productos -->
                        <v-list lines="two">
                            <v-list-item
                                v-for="item in cart.items"
                                :key="item.id"
                                class="px-0"
                            >
                                <template v-slot:prepend>
                                    <v-avatar size="60" rounded>
                                        <v-img :src="item.image_url" :alt="item.name" cover />
                                    </v-avatar>
                                </template>

                                <v-list-item-title class="text-wrap mb-1">
                                    {{ item.name }}
                                </v-list-item-title>

                                <v-list-item-subtitle>
                                    Cantidad: {{ item.quantity }} x {{ formatPrice(item.price) }}
                                </v-list-item-subtitle>

                                <template v-slot:append>
                                    <div class="text-right">
                                        <strong>{{ formatPrice(item.price * item.quantity) }}</strong>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>

                        <v-divider class="my-4"></v-divider>

                        <!-- Total -->
                        <div class="d-flex justify-space-between align-center mb-4">
                            <span class="text-h6">Total:</span>
                            <span class="text-h5 font-weight-bold text-primary">
                                {{ formatPrice(cart.total) }}
                            </span>
                        </div>

                        <!-- Información adicional -->
                        <v-alert
                            type="info"
                            variant="tonal"
                            density="compact"
                        >
                            <template v-slot:prepend>
                                <v-icon>mdi-information</v-icon>
                            </template>
                            <div class="text-caption">
                                El pago se realizará contra entrega.
                                Recibirás un email de confirmación con los detalles de tu orden.
                            </div>
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.text-wrap {
    white-space: normal !important;
}
</style>
