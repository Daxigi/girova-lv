<template>
  <AppLayout>
    <v-container class="py-8">
      <v-row justify="center">
        <v-col cols="12" md="6">
          <v-card class="pa-6">
            <v-card-title class="text-h5 text-center mb-4">
              Recuperar Contraseña
            </v-card-title>

            <v-card-text>
              <p class="text-body-2 mb-4">
                ¿Olvidaste tu contraseña? No hay problema. Solo ingresa tu email
                y te enviaremos un link para que puedas crear una nueva.
              </p>

              <v-alert
                v-if="form.recentlySuccessful"
                type="success"
                variant="tonal"
                class="mb-4"
              >
                Te hemos enviado un link de recuperación por email.
              </v-alert>

              <v-form @submit.prevent="submit">
                <v-text-field
                  v-model="form.email"
                  label="Email"
                  type="email"
                  variant="outlined"
                  :error-messages="form.errors.email"
                  required
                />

                <div class="d-flex flex-column gap-2">
                  <v-btn
                    type="submit"
                    color="primary"
                    variant="elevated"
                    block
                    :loading="form.processing"
                  >
                    Enviar Link de Recuperación
                  </v-btn>

                  <v-btn
                    :to="{ name: 'login' }"
                    variant="text"
                    block
                  >
                    Volver al Login
                  </v-btn>
                </div>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const form = useForm({
  email: '',
});

const submit = () => {
  form.post('/forgot-password', {
    preserveScroll: true,
  });
};
</script>
