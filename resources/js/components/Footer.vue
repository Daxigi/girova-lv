<script setup lang="ts">
// Podemos ejecutar JavaScript simple aquí.
// Obtenemos el año actual para mostrarlo en el copyright.
const currentYear = new Date().getFullYear();
</script>

<template>
  <footer class="bg-gray-800 text-white mt-12">
    <div class="container mx-auto px-6 py-4 text-center">
      <!-- Usamos la sintaxis de bigotes {{ }} para mostrar el valor de una variable -->
      <p>&copy; {{ currentYear }} MiTienda. Todos los derechos reservados.</p>
    </div>
  </footer>
</template>
