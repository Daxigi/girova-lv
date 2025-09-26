<script setup lang="ts">
const currentYear = new Date().getFullYear();
</script>

<template>
  <v-footer app>
    <div class="text-center w-100">
      &copy; {{ currentYear }} MiTienda. Todos los derechos reservados.
    </div>
  </v-footer>
</template>
