<script setup lang="ts">
/**
 * APP LAYOUT - Layout principal de la aplicación
 *
 * Este componente envuelve todas las páginas y contiene:
 * - Header (navegación superior)
 * - Footer (pie de página)
 * - CartDrawer (carrito lateral)
 * - Slot principal (contenido de cada página)
 */

import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import CartDrawer from '@/components/CartDrawer.vue';
</script>

<template>
  <v-app>
    <!-- Navegación superior -->
    <Header />

    <!-- Contenido principal (cada página se renderiza aquí) -->
    <v-main>
      <slot />
    </v-main>

    <!-- Pie de página -->
    <Footer />

    <!-- Carrito lateral (drawer) -->
    <!-- Se muestra en todas las páginas y se controla desde useCart -->
    <CartDrawer />
  </v-app>
</template>
