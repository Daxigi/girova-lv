<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const formRef = ref<any>(null);
const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    email: (value: any) => /.+@.+\..+/.test(value) || 'Debe ser un correo electrónico válido.',
};

async function submit() {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.processing = true;

    const onFinish = () => {
        form.processing = false;
    };

    form.post(route('login'), {
        preserveScroll: true,
        onSuccess: () => {
        },
        onError: (errors) => {
            form.errors = errors;
        },
        onFinish,
    });
}
</script>

<template>
    <v-container>
        <v-card class="mx-auto" max-width="800">
            <v-card-title class="text-h5 pa-4 bg-primary">
                Iniciar Sesión
            </v-card-title>
            <v-card-text class="pa-5">
                <v-form ref="formRef" @submit.prevent="submit">
                    <v-text-field
                        v-model="form.email"
                        label="Correo Electrónico"
                        variant="outlined"
                        density="compact"
                        :rules="[rules.required, rules.email]"
                        :error-messages="form.errors.email"
                        class="mb-4"
                    ></v-text-field>

                    <v-text-field
                        v-model="form.password"
                        label="Contraseña"
                        :type="showPassword ? 'text' : 'password'"
                        variant="outlined"
                        density="compact"
                        :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        @click:append-inner="showPassword = !showPassword"
                        :rules="[rules.required]"
                        :error-messages="form.errors.password"
                        class="mb-4"
                    ></v-text-field>

                    <v-checkbox
                        v-model="form.remember"
                        label="Recordarme"
                        color="primary"
                        density="compact"
                    ></v-checkbox>

                    <div class="text-right mb-4">
                        <a :href="route('password.request')" class="text-primary text-decoration-none">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <v-divider class="my-4"></v-divider>

                    <div class="d-flex justify-end">
                        <v-btn
                            type="submit"
                            :loading="form.processing"
                            :disabled="form.processing"
                            color="primary"
                            size="large"
                            variant="tonal"
                        >
                            Iniciar Sesión
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>
