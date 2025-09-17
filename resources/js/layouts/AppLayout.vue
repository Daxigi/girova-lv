<script setup lang="ts">
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';

// Importamos los dos componentes que creamos.
// Usamos '@/' como un atajo para la carpeta 'resources/js'.
// Esto es un alias común en proyectos de Vue.
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    
    <Header />

    <!-- Contenido Principal -->
    <main>
      <!--
        La etiqueta <slot /> es la clave de los layouts.
        Vue reemplazará este <slot /> con el contenido del componente
        de la página que se esté visitando en ese momento.
      -->
      <slot />
    </main>

    <Footer />

  </div>
</template>
