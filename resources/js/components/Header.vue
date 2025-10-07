<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
</script>

<template>
  <v-app-bar color="primary">
    <v-toolbar-title>
        <Link href="/" class="text-white" style="text-decoration: none;">MiTienda</Link>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-btn @click="() => router.visit('/')" text>Inicio</v-btn>
    <v-btn @click="() => router.visit('/products/create')" text>Crear Producto</v-btn>
    <v-btn @click="() => router.visit('/products/dashboard')" text>Panel Productos</v-btn>
    <v-btn @click="() => router.visit('#')" text>Acerca de</v-btn>
  </v-app-bar>
</template>