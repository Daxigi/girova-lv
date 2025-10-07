<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';

import { computed } from 'vue';

// Props
const props = defineProps({
    categories: Array as () => any[],
    types: Array as () => any[],
    product: Object as () => any | undefined,
});

// Cloudinary config
const cloudinaryCloudName = import.meta.env.VITE_CLOUDINARY_CLOUD_NAME;
const cloudinaryUploadPreset = import.meta.env.VITE_UPLOAD_PRESET;

// Determine if it's an edit form
const isEdit = computed(() => !!props.product);

// Form state
const formRef = ref<any>(null);
const form = useForm({
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price || null,
    purchasePrice: props.product?.purchasePrice || null,
    stock: props.product?.stock || null,
    imageUrl: props.product?.imageUrl || '',
    status: props.product?.status ?? true,
    category_id: props.product?.category_id || null,
    type_id: props.product?.type_id || null,
});

// Image handling state
const imageFile = ref<File | null>(null);
const imageUrl = ref(props.product?.imageUrl || ''); // Set initial image for preview
const isUploading = ref(false);

// Validation rules
const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    number: (value: any) => !isNaN(parseFloat(value)) && isFinite(value) || 'Debe ser un número.'
};

// Watch for image selection to update the preview
watch(imageFile, (newFile) => {
    if (newFile) {
        imageUrl.value = URL.createObjectURL(newFile);
    } else {
        imageUrl.value = '';
    }
});

// Form submission logic
async function submit() {
    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.processing = true;

    // If a new image file is selected, upload it to Cloudinary first.
    if (imageFile.value) {
        isUploading.value = true;
        const cloudFormData = new FormData();
        cloudFormData.append('file', imageFile.value);
        cloudFormData.append('upload_preset', cloudinaryUploadPreset);

        try {
            const response = await axios.post(
                `https://api.cloudinary.com/v1_1/${cloudinaryCloudName}/image/upload`,
                cloudFormData
            );
            // Update form's imageUrl with the new URL
            form.imageUrl = response.data.secure_url;
        } catch (error: any) {
            console.error('Error al subir la imagen', error);
            form.setError('imageUrl', 'Error al subir la imagen a Cloudinary.');
            isUploading.value = false;
            form.processing = false;
            return;
        } finally {
            isUploading.value = false;
        }
    }

    if (isEdit.value) {
        // UPDATE logic
        form.put(route('products.update', props.product.id), {
            preserveScroll: true,
            onSuccess: () => {
                alert('Producto actualizado exitosamente!');
            },
            onError: (errors) => {
                form.errors = errors;
            },
            onFinish: () => {
                form.processing = false;
            }
        });
    } else {
        // CREATE logic
        form.post(route('products.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                imageFile.value = null;
                imageUrl.value = '';
                alert('Producto creado exitosamente!');
            },
            onError: (errors) => {
                form.errors = errors;
            },
            onFinish: () => {
                form.processing = false;
            }
        });
    }
}

function forceDeleteProduct() {
    if (confirm('¿Estás seguro de que quieres eliminar este producto permanentemente? Esta acción no se puede deshacer.')) {
        form.delete(route('products.force-destroy', props.product.id), {
            preserveScroll: true,
            onSuccess: () => {
                alert('Producto eliminado permanentemente.');
            }
        });
    }
}
</script>

<template>
    <v-container>
        <v-card class="mx-auto" max-width="800">
                            <v-card-title class="text-h5 pa-4 bg-primary">
                                {{ isEdit ? 'Editar Producto' : 'Crear Nuevo Producto' }}
                            </v-card-title>            <v-card-text class="pa-5">
                <v-form ref="formRef" @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Nombre del Producto"
                        variant="outlined"
                        density="compact"
                        :rules="[rules.required]"
                        :error-messages="form.errors.name"
                        class="mb-4"
                    ></v-text-field>

                    <v-textarea
                        v-model="form.description"
                        label="Descripción"
                        variant="outlined"
                        density="compact"
                        :error-messages="form.errors.description"
                        class="mb-4"
                    ></v-textarea>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model.number="form.price"
                                label="Precio de Venta"
                                type="number"
                                prefix="$"
                                variant="outlined"
                                density="compact"
                                :rules="[rules.required, rules.number]"
                                :error-messages="form.errors.price"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model.number="form.purchasePrice"
                                label="Precio de Compra"
                                type="number"
                                prefix="$"
                                variant="outlined"
                                density="compact"
                                :rules="[rules.required, rules.number]"
                                :error-messages="form.errors.purchasePrice"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model.number="form.stock"
                                label="Stock"
                                type="number"
                                variant="outlined"
                                density="compact"
                                :rules="[rules.required, rules.number]"
                                :error-messages="form.errors.stock"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-file-input
                                v-model="imageFile"
                                label="Imagen del Producto"
                                accept="image/*"
                                variant="outlined"
                                density="compact"
                                :error-messages="form.errors.imageUrl"
                                prepend-icon="mdi-camera"
                            ></v-file-input>
                        </v-col>
                    </v-row>
                    
                    <v-img v-if="imageUrl" :src="imageUrl" width="128" height="128" class="mb-4 rounded border"></v-img>

                    <v-row>
                        <v-col cols="12" md="6">
                                                            <v-select
                                                                v-model="form.category_id"
                                                                :items="props.categories"
                                                                item-title="name"
                                                                item-value="id"
                                                                label="Categoría"
                                                                variant="outlined"
                                                                density="compact"
                                                                :rules="[rules.required]"
                                                                :error-messages="form.errors.category_id"
                                                            ></v-select>
                                                        </v-col>
                                                        <v-col cols="12" md="6">
                                                            <v-select
                                                                v-model="form.type_id"
                                                                :items="props.types"
                                                                item-title="name"
                                                                item-value="id"
                                                                label="Tipo"
                                                                variant="outlined"
                                                                density="compact"
                                                                :rules="[rules.required]"
                                                                :error-messages="form.errors.type_id"
                                                            ></v-select>                        </v-col>
                    </v-row>

                    <v-checkbox v-model="form.status" label="Activo" density="compact"></v-checkbox>

                    <v-divider class="my-4"></v-divider>

                    <div class="d-flex justify-space-between">
                        <v-btn
                            v-if="isEdit"
                            @click="forceDeleteProduct"
                            :disabled="form.processing"
                            color="red"
                            size="large"
                            variant="tonal"
                        >
                            Eliminación Definitiva
                        </v-btn>

                        <v-btn
                            type="submit"
                            :loading="form.processing || isUploading"
                            :disabled="form.processing || isUploading"
                            color="primary"
                            size="large"
                            variant="tonal"
                            class="flex-grow-1 ml-4"
                        >
                            <span v-if="isUploading">Subiendo imagen...</span>
                            <span v-else>{{ isEdit ? 'Guardar Cambios' : 'Guardar Producto' }}</span>
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>