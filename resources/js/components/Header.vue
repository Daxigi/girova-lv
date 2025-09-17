<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

// Ahora importamos el componente 'Link' de Inertia.
// Este componente es una versión mejorada de la etiqueta <a>
// que evita recargas completas de la página, manteniendo la sensación de una SPA.
</script>

<template>
  <header class="bg-gray-800 text-white shadow-md">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
      
      <!-- Logo del sitio -->
      <div>
        <Link href="/" class="text-xl font-bold">MiTienda</Link>
      </div>

      <!-- Menú de Navegación con <Link> -->
      <div class="space-x-4">
        <Link href="/" class="px-3 py-2 rounded-md hover:bg-gray-700">Inicio</Link>
        <Link href="/products/create" class="px-3 py-2 rounded-md hover:bg-gray-700">Crear Producto</Link>
        <Link href="#" class="px-3 py-2 rounded-md hover:bg-gray-700">Acerca de</Link>
      </div>

    </nav>
  </header>
</template>