<script setup lang="ts">

import { useCart } from '../composables/useCart';
import {Link, router} from '@inertiajs/vue3';
import {ref}  from 'vue'; 
import {useAuth} from '../composables/useAuth';

const {user, hasRole, hasPermission} = useAuth();

// Composable del carrito
const { itemCount, toggleCart } = useCart();

// Control del menú móvil
const drawer = ref(false);

function logout() {
    router.post(route('logout'));
}

function navigateAndClose(url: string) {
    drawer.value = false;
    router.visit(url);
}
</script>

<template>
  <!-- Navigation Drawer para móvil -->
  <v-navigation-drawer v-model="drawer" temporary location="right">
    <v-list>
      <v-list-item>
        <v-list-item-title class="text-h6">
          <span style="font-family: 'Playfair Display', serif; font-weight: 700;">GIROVA</span>
        </v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>

      <!-- Navegación principal -->
      <v-list-item @click="navigateAndClose('/')">
        <template v-slot:prepend>
          <v-icon>mdi-home</v-icon>
        </template>
        <v-list-item-title>Inicio</v-list-item-title>
      </v-list-item>

      <v-list-item @click="navigateAndClose('/products/show')">
        <template v-slot:prepend>
          <v-icon>mdi-shopping</v-icon>
        </template>
        <v-list-item-title>Productos</v-list-item-title>
      </v-list-item>

      <!-- Si está autenticado -->
      <template v-if="user">
        <v-divider class="my-2"></v-divider>

        <v-list-item @click="navigateAndClose('/profile/edit')">
          <template v-slot:prepend>
            <v-icon>mdi-account-circle</v-icon>
          </template>
          <v-list-item-title>Mi Perfil</v-list-item-title>
        </v-list-item>

        <v-list-item
          v-if="hasRole('employee') || hasRole('admin')"
          @click="navigateAndClose('/products/dashboard')"
        >
          <template v-slot:prepend>
            <v-icon>mdi-view-dashboard</v-icon>
          </template>
          <v-list-item-title>Panel de Productos</v-list-item-title>
        </v-list-item>

        <v-list-item @click="navigateAndClose('/my-orders')">
          <template v-slot:prepend>
            <v-icon>mdi-package-variant</v-icon>
          </template>
          <v-list-item-title>
            {{ hasRole('customer') ? 'Mis Órdenes' : 'Órdenes' }}
          </v-list-item-title>
        </v-list-item>

        <v-list-item @click="logout">
          <template v-slot:prepend>
            <v-icon>mdi-logout</v-icon>
          </template>
          <v-list-item-title>Cerrar Sesión</v-list-item-title>
        </v-list-item>
      </template>

      <!-- Si NO está autenticado -->
      <template v-else>
        <v-divider class="my-2"></v-divider>

        <v-list-item @click="navigateAndClose('/login')">
          <template v-slot:prepend>
            <v-icon>mdi-login</v-icon>
          </template>
          <v-list-item-title>Iniciar Sesión</v-list-item-title>
        </v-list-item>

        <v-list-item @click="navigateAndClose('/users/create')">
          <template v-slot:prepend>
            <v-icon>mdi-account-plus</v-icon>
          </template>
          <v-list-item-title>Registrarme</v-list-item-title>
        </v-list-item>
      </template>
    </v-list>
  </v-navigation-drawer>

  <!-- App Bar -->
  <v-app-bar color="primary" elevation="2">
    <!-- Logo -->
    <v-toolbar-title class="pl-2 pl-md-4">
        <Link href="/" class="text-white" style="text-decoration: none; font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 800; letter-spacing: 0.05em;">
            GIROVA
        </Link>
    </v-toolbar-title>

    <v-spacer></v-spacer>

    <!-- Botones Desktop (ocultos en móvil) -->
    <div class="d-none d-md-flex align-center">
      <v-btn @click="() => router.visit('/')" text>Inicio</v-btn>
      <v-btn @click="() => router.visit('/products/show')" text>Productos</v-btn>

      <!-- Usuario autenticado -->
      <template v-if="user">
        <v-menu>
          <template v-slot:activator="{ props }">
            <v-btn v-bind="props" text>
              {{ user.name }}
              <v-icon right>mdi-chevron-down</v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item @click="() => router.visit('/profile/edit')">
              <v-list-item-title>Información de la cuenta</v-list-item-title>
            </v-list-item>

            <v-list-item
              v-if="hasRole('employee') || hasRole('admin')"
              @click="() => router.visit('/products/dashboard')"
            >
              <v-list-item-title>Panel de Productos</v-list-item-title>
            </v-list-item>

            <v-list-item @click="() => router.visit('/my-orders')">
              <v-list-item-title>
                {{ hasRole('customer') ? 'Mis Órdenes' : 'Órdenes' }}
              </v-list-item-title>
            </v-list-item>

            <v-list-item @click="logout">
              <v-list-item-title>Cerrar Sesión</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>

      <!-- Usuario no autenticado -->
      <template v-else>
        <v-btn @click="() => router.visit('/login')" text>Iniciar Sesión</v-btn>
        <v-btn @click="() => router.visit('/users/create')" text>Registrarme</v-btn>
      </template>
    </div>

    <!-- Botón del Carrito (siempre visible) -->
    <v-btn icon @click="toggleCart" class="mr-1 mr-md-2">
      <v-badge
        :content="itemCount"
        :model-value="itemCount > 0"
        color="error"
        overlap
      >
        <v-icon>mdi-cart</v-icon>
      </v-badge>
    </v-btn>

    <!-- Botón menú hamburguesa (solo móvil) -->
    <v-btn icon @click="drawer = !drawer" class="d-md-none">
      <v-icon>mdi-menu</v-icon>
    </v-btn>
  </v-app-bar>
</template>