<template>
    <input v-model="nombre" placeholder="Escribe tu nombre">
    <p>Hola, {{ nombre }}</p>
  
    <textarea v-model="biografia" placeholder="Cuéntanos sobre ti"></textarea>
    <p>Biografía: {{ biografia }}</p>
  </template>
  
  <script>
  export default {
    data() {
      return {
        nombre: '',
        biografia: ''
      }
    }
  }
  </script>