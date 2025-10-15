<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    user: Object as () => any | undefined,
});

const isEdit = computed(() => !!props.user);

const formRef = ref<any>(null);
const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    password: '',
    password_confirmation: '',
});

const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    email: (value: any) => /.+@.+\..+/.test(value) || 'Debe ser un correo electrónico válido.',
    passwordMatch: (value: any) => value === form.password || 'Las contraseñas no coinciden.',
};

async function submit() {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.processing = true;

    const onFinish = () => {
        form.processing = false;
    };

    if (isEdit.value) {
        form.put(route('users.update', props.user.id), {
            preserveScroll: true,
            onSuccess: () => {
                alert('Usuario actualizado exitosamente!');
            },
            onError: (errors) => {
                form.errors = errors;
            },
            onFinish,
        });
    } else {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                alert('Usuario creado exitosamente!');
            },
            onError: (errors) => {
                form.errors = errors;
            },
            onFinish,
        });
    }
}
</script>

<template>
    <v-container>
        <v-card class="mx-auto" max-width="800">
            <v-card-title class="text-h5 pa-4 bg-primary">
                {{ isEdit ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </v-card-title>
            <v-card-text class="pa-5">
                <v-form ref="formRef" @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Nombre del Usuario"
                        variant="outlined"
                        density="compact"
                        :rules="[rules.required]"
                        :error-messages="form.errors.name"
                        class="mb-4"
                    ></v-text-field>

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
                        v-model="form.phone"
                        label="Teléfono"
                        variant="outlined"
                        density="compact"
                        :error-messages="form.errors.phone"
                        class="mb-4"
                    ></v-text-field>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.password"
                                label="Contraseña"
                                type="password"
                                variant="outlined"
                                density="compact"
                                :rules="isEdit ? [] : [rules.required]"
                                :error-messages="form.errors.password"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.password_confirmation"
                                label="Confirmar Contraseña"
                                type="password"
                                variant="outlined"
                                density="compact"
                                :rules="isEdit ? [] : [rules.required, rules.passwordMatch]"
                            ></v-text-field>
                        </v-col>
                    </v-row>

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
                            {{ isEdit ? 'Guardar Cambios' : 'Guardar Usuario' }}
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>