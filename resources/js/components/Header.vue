<script setup lang="ts">
/**
 * HEADER - Navegación principal
 *
 * Barra de navegación superior que contiene:
 * - Logo/Nombre de la tienda
 * - Menú de navegación
 * - Opciones según autenticación y roles
 * - Botón del carrito con badge
 */

import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useCart } from '../composables/useCart';

const page = usePage();
const auth = computed(() => page.props.auth);

// Importar el composable del carrito
const { itemCount, toggleCart } = useCart();

// Verificar si el usuario tiene un rol específico
const hasRole = (role: string) => {
    return auth.value.roles?.includes(role) || false;
};

// Verificar si el usuario tiene un permiso específico
const hasPermission = (permission: string) => {
    return auth.value.permissions?.includes(permission) || false;
};

function logout() {
    router.post(route('logout'));
}
</script>

<template>
  <v-app-bar color="primary">
    <v-toolbar-title>
        <Link href="/" class="text-white" style="text-decoration: none;">MiTienda</Link>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-btn @click="() => router.visit('/')" text>Inicio</v-btn>

    <!-- Botón del Carrito con Badge -->
    <v-btn icon @click="toggleCart" class="mr-2">
        <v-badge
            :content="itemCount"
            :model-value="itemCount > 0"
            color="error"
            overlap
        >
            <v-icon>mdi-cart</v-icon>
        </v-badge>
    </v-btn>

    <!-- Botones para usuarios autenticados -->
    <template v-if="auth.user">
        <!-- Botones solo para admin -->
        <template v-if="hasRole('admin')">
            <v-btn @click="() => router.visit('/products/create')" text>Crear Producto</v-btn>
            <v-btn @click="() => router.visit('/products/dashboard')" text>Panel Productos</v-btn>
        </template>

        <!-- Menú de usuario -->
        <v-menu>
            <template v-slot:activator="{ props }">
                <v-btn v-bind="props" text>
                    {{ auth.user.name }}
                    <v-icon right>mdi-chevron-down</v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item @click="logout">
                    <v-list-item-title>Cerrar Sesión</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </template>

    <!-- Botones para usuarios no autenticados -->
    <template v-else>
        <v-btn @click="() => router.visit('/login')" text>Iniciar Sesión</v-btn>
        <v-btn @click="() => router.visit('/users/create')" text>Registrarme</v-btn>
    </template>
  </v-app-bar>
</template>