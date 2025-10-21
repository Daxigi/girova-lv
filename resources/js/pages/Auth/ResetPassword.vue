<template>
  <AppLayout>
    <v-container class="py-8">
      <v-row justify="center">
        <v-col cols="12" md="6">
          <v-card class="pa-6">
            <v-card-title class="text-h5 text-center mb-4">
              Restablecer Contraseña
            </v-card-title>

            <v-card-text>
              <v-form @submit.prevent="submit">
                <v-text-field
                  v-model="form.email"
                  label="Email"
                  type="email"
                  variant="outlined"
                  :error-messages="form.errors.email"
                  required
                  class="mb-2"
                />

                <v-text-field
                  v-model="form.password"
                  label="Nueva Contraseña"
                  :type="showPassword ? 'text' : 'password'"
                  variant="outlined"
                  :error-messages="form.errors.password"
                  :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                  @click:append-inner="showPassword = !showPassword"
                  required
                  class="mb-2"
                />

                <v-text-field
                  v-model="form.password_confirmation"
                  label="Confirmar Contraseña"
                  :type="showPasswordConfirm ? 'text' : 'password'"
                  variant="outlined"
                  :append-inner-icon="showPasswordConfirm ? 'mdi-eye-off' : 'mdi-eye'"
                  @click:append-inner="showPasswordConfirm = !showPasswordConfirm"
                  required
                  class="mb-4"
                />

                <v-btn
                  type="submit"
                  color="primary"
                  variant="elevated"
                  block
                  :loading="form.processing"
                >
                  Restablecer Contraseña
                </v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
  token: string;
  email: string;
}>();

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/reset-password');
};
</script>
