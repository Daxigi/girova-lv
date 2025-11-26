<script setup lang="ts">
import { ref } from 'vue';

const carouselItems = ref([
    {
        title: 'Bienvenido a Girova',
        image: "/storage/products/anillo1.jpg",
    },
    {
        title: 'Nuevas Colecciones',
        image: "/storage/products/anillo2.jpg",
    },
    {
        title: 'Ofertas Especiales',
        image: "/storage/products/anillo3.jpg",
    },
    {
        title: 'Girova',
        image: "/storage/products/aro1.jpg",
    },
]);
</script>

<template>
        <v-card class="mb-12" elevation="8">
      <v-carousel
        cycle
        height="500"
        show-arrows="hover"
        delimiter-icon="mdi-circle"
        hide-delimiter-background
        :interval="5000"
      >
        <v-carousel-item
          v-for="(item, i) in carouselItems"
          :key="i"
          :src="item.image"
          cover
        >
          <div
            class="d-flex align-center justify-center"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4);"
          >
            <div class="text-center pa-8">
              <h2 class="text-h2 font-weight-bold mb-6" style="font-family: 'Playfair Display', serif; color: white; text-shadow: 2px 2px 8px rgba(0,0,0,0.9); letter-spacing: 0.1em;">
                {{ item.title }}
              </h2>
            </div>
          </div>
        </v-carousel-item>
      </v-carousel>
    </v-card>
</template>