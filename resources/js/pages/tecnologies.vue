<script setup>
import {ref} from 'vue'

const mensaje = ref('Esperando el mensaje')

function manejarAccion(payload){
  console.log("El padre recibio el evento de accion")
  mensaje.value = payload
  }
</script>

<template>
  <div class="parent-container">
    <h1>Componente Padre</h1>

    <p>{{ mensaje }}</p>

  </div>
</template>

<style>
.parent-container {
  width: 400px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #aaa;
  border-radius: 8px;
  text-align: center;
  font-family: sans-serif;
}
</style>