<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

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

    <!-- Botones para usuarios autenticados -->
    <template v-if="auth.user">
        <v-btn @click="() => router.visit('/products/create')" text>Crear Producto</v-btn>
        <v-btn @click="() => router.visit('/products/dashboard')" text>Panel Productos</v-btn>
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