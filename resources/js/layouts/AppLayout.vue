<script setup lang="ts">


import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';
import CartDrawer from '@/components/CartDrawer.vue';
</script>

<template>
  <v-app>
    <!-- Navegación superior -->
    <Header />

    <!-- Contenido principal -->
    <v-main>
      <slot />
    </v-main>

    <!-- Pie de página -->
    <Footer />

    <!-- Carrito lateral -->
    <!-- Se muestra en todas las páginas y se controla desde useCart -->
    <CartDrawer />
  </v-app>
</template>
