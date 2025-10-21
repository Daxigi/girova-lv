<template>
  <AppLayout>
    <v-container class="py-8">
      <v-row justify="center">
        <v-col cols="12" md="6">
          <v-card class="pa-6">
            <v-card-title class="text-h5 text-center mb-4">
              Verifica tu Email
            </v-card-title>

            <v-card-text>
              <v-alert
                type="info"
                variant="tonal"
                class="mb-4"
              >
                Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu
                dirección de email haciendo clic en el enlace que acabamos de enviarte?
                Si no recibiste el email, con gusto te enviaremos otro.
              </v-alert>

              <v-alert
                v-if="verificationLinkSent"
                type="success"
                variant="tonal"
                class="mb-4"
              >
                Se ha enviado un nuevo enlace de verificación a tu dirección de email.
              </v-alert>

              <div class="d-flex justify-center gap-4">
                <v-btn
                  color="primary"
                  variant="elevated"
                  @click="resendVerification"
                  :loading="loading"
                >
                  Reenviar Email de Verificación
                </v-btn>

                <v-btn
                  variant="outlined"
                  @click="logout"
                >
                  Cerrar Sesión
                </v-btn>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const loading = ref(false);
const verificationLinkSent = ref(false);

const resendVerification = () => {
  loading.value = true;
  router.post('/email/resend', {}, {
    preserveScroll: true,
    onSuccess: () => {
      verificationLinkSent.value = true;
      loading.value = false;
    },
    onError: () => {
      loading.value = false;
    }
  });
};

const logout = () => {
  router.post('/logout');
};
</script>
