<script setup lang="ts">
const currentYear = new Date().getFullYear();
</script>

<template>
  <v-footer color="black" class="text-white mt-16">
    <div class="text-center w-100 py-4">
      <div style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 8px;">
        GIROVA
      </div>
      <div style="font-size: 0.875rem; opacity: 0.8;">
        &copy; {{ currentYear }} Todos los derechos reservados.
      </div>
    </div>
  </v-footer>
</template>
