<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    user: {
        id: string;
        name: string;
        email: string;
    };
}>();

const formRef = ref<any>(null);
const showPasswordFields = ref(false);

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    current_password: '',
    password: '',
    password_confirmation: '',
});

const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    email: (value: any) => /.+@.+\..+/.test(value) || 'Email inválido.',
    min: (min: number) => (value: any) => !value || value.length >= min || `Mínimo ${min} caracteres.`,
    confirmed: (value: any) => value === form.password || 'Las contraseñas no coinciden.',
};

async function submit() {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.put(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.current_password = '';
            form.password = '';
            form.password_confirmation = '';
            showPasswordFields.value = false;
        },
    });
}
</script>

<template>
    <v-container class="py-8">
        <v-row justify="center">
            <v-col cols="12" md="8" lg="6">
                <v-card elevation="3">
                    <v-card-title class="text-h5 pa-6 bg-primary text-white">
                        <v-icon class="mr-2" color="white">mdi-account-edit</v-icon>
                        Editar Perfil
                    </v-card-title>

                    <v-card-text class="pa-6">
                        <v-alert
                            v-if="$page.props.flash?.success"
                            type="success"
                            variant="tonal"
                            class="mb-4"
                        >
                            {{ $page.props.flash.success }}
                        </v-alert>

                        <v-form ref="formRef" @submit.prevent="submit">
                            <v-text-field
                                v-model="form.name"
                                label="Nombre completo"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account"
                                :rules="[rules.required]"
                                :error-messages="form.errors.name"
                                class="mb-4"
                            ></v-text-field>

                            <v-text-field
                                v-model="form.email"
                                label="Correo electrónico"
                                type="email"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-email"
                                :rules="[rules.required, rules.email]"
                                :error-messages="form.errors.email"
                                class="mb-4"
                            ></v-text-field>

                            <v-divider class="my-6"></v-divider>

                            <v-btn
                                v-if="!showPasswordFields"
                                @click="showPasswordFields = true"
                                color="grey-darken-1"
                                variant="outlined"
                                prepend-icon="mdi-lock-reset"
                                block
                                class="mb-4"
                            >
                                Cambiar Contraseña
                            </v-btn>

                            <div v-if="showPasswordFields">
                                <p class="text-subtitle-2 mb-4 text-grey-darken-1">
                                    Cambiar contraseña (opcional)
                                </p>

                                <v-text-field
                                    v-model="form.current_password"
                                    label="Contraseña actual"
                                    type="password"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-lock"
                                    :error-messages="form.errors.current_password"
                                    class="mb-4"
                                ></v-text-field>

                                <v-text-field
                                    v-model="form.password"
                                    label="Nueva contraseña"
                                    type="password"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-lock-plus"
                                    :rules="form.password ? [rules.min(8)] : []"
                                    :error-messages="form.errors.password"
                                    hint="Mínimo 8 caracteres"
                                    class="mb-4"
                                ></v-text-field>

                                <v-text-field
                                    v-model="form.password_confirmation"
                                    label="Confirmar nueva contraseña"
                                    type="password"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-lock-check"
                                    :rules="form.password ? [rules.confirmed] : []"
                                    class="mb-4"
                                ></v-text-field>

                                <v-btn
                                    @click="showPasswordFields = false; form.current_password = ''; form.password = ''; form.password_confirmation = ''"
                                    color="grey"
                                    variant="text"
                                    prepend-icon="mdi-close"
                                    class="mb-4"
                                >
                                    Cancelar cambio de contraseña
                                </v-btn>
                            </div>

                            <v-divider class="my-6"></v-divider>

                            <div class="d-flex ga-4">
                                <v-btn
                                    type="submit"
                                    :loading="form.processing"
                                    :disabled="form.processing"
                                    color="primary"
                                    size="large"
                                    variant="elevated"
                                    prepend-icon="mdi-content-save"
                                    block
                                >
                                    Guardar Cambios
                                </v-btn>
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
