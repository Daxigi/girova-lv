<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const cloudinaryCloudName = import.meta.env.VITE_CLOUDINARY_CLOUD_NAME;
const cloudinaryUploadPreset = import.meta.env.VITE_UPLOAD_PRESET;

const imageFile = ref(null);
const imageUrl = ref('');
const isUploading = ref(false);

function handleImageSelected(event) {
    const file = event.target.files[0];
    if (!file) return;

    imageFile.value = file;
    // Crear una URL local para la vista previa
    imageUrl.value = URL.createObjectURL(file);
}

const props = defineProps({
    categories: Array,
    types: Array,
});

const form = useForm({
    name: '',
    description: '',
    price: null,
    purchasePrice: null,
    stock: null,
    imageUrl: '',
    status: true,
    CategoryId: null,
    TypeId: null,
});

async function submit() {
    form.processing = true; // Activar el estado de procesamiento
    try {
        if (imageFile.value) {
            isUploading.value = true;
            const formData = new FormData();
            formData.append('file', imageFile.value);
            formData.append('upload_preset', cloudinaryUploadPreset);

            try {
                const response = await axios.post(
                    `https://api.cloudinary.com/v1_1/${cloudinaryCloudName}/image/upload`,
                    formData
                );
                form.imageUrl = response.data.secure_url;
            } catch (error) {
                console.error('Error al subir la imagen', error);
                alert('Hubo un error al subir la imagen. El producto no se guardará.');
                isUploading.value = false;
                form.processing = false; // Desactivar procesamiento en caso de error
                return; // Detener el envío del formulario si la imagen falla
            } finally {
                isUploading.value = false;
            }
        }

        // Enviar el formulario a la API
        await axios.post('/api/products', form.data());

        // Si todo fue bien, navegar y mostrar mensaje de éxito
        router.visit('/products/create', {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                form.reset();
                imageUrl.value = '';
                imageFile.value = null;
                alert('Producto creado exitosamente!'); // O usar un flash message de Inertia
            },
            onError: (errors) => {
                form.errors = errors; // Asignar errores si los hay
            }
        });

    } catch (error) {
        console.error('Error al crear el producto', error);
        // Manejar errores de validación o de la API
        if (error.response && error.response.data && error.response.data.errors) {
            form.errors = error.response.data.errors; // Asignar errores de validación
        } else {
            alert('Hubo un error al crear el producto.');
        }
    } finally {
        form.processing = false; // Desactivar el estado de procesamiento al finalizar
    }
}
</script>

<template>
    <div class="max-w-2xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-10">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Crear Nuevo Producto</h1>
        
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Producto:</label>
                <input v-model="form.name" type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <textarea v-model="form.description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                <input v-model.number="form.price" type="number" id="price" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
            </div>

            <div class="mb-4">
                <label for="purchasePrice" class="block text-gray-700 text-sm font-bold mb-2">Precio de compra:</label>
                <input v-model.number="form.purchasePrice" type="number" id="purchasePrice" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div v-if="form.errors.purchasePrice" class="text-red-500 text-xs mt-1">{{ form.errors.purchasePrice }}</div>
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock:</label>
                <input v-model.number="form.stock" type="number" id="stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div v-if="form.errors.stock" class="text-red-500 text-xs mt-1">{{ form.errors.stock }}</div>
            </div>

            <div class="mb-6">
                <label for="imageFile" class="block text-gray-700 text-sm font-bold mb-2">Imagen del Producto:</label>
                <input 
                    type="file"
                    id="imageFile"
                    @input="handleImageSelected"
                    accept="image/*"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                />
                <div v-if="form.errors.imageUrl" class="text-red-500 text-xs mt-1">{{ form.errors.imageUrl }}</div>

                <div v-if="imageUrl" class="mt-4">
                  <img :src="imageUrl" alt="Vista previa de la imagen" class="w-32 h-32 object-cover rounded">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Categoría:</label>
                    <select v-model="form.category_id" id="category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option :value="null" disabled>Selecciona una categoría</option>
                        <option v-for="category in props.categories" :key="category.id" :value="category.id">
                            {{ category.description }}
                        </option>
                    </select>
                    <div v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</div>
                </div>
                <div>
                    <label for="type_id" class="block text-gray-700 text-sm font-bold mb-2">Tipo:</label>
                    <select v-model="form.type_id" id="type_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option :value="null" disabled>Selecciona un tipo</option>
                        <option v-for="type in props.types" :key="type.id" :value="type.id">
                            {{ type.description }}
                        </option>
                    </select>
                    <div v-if="form.errors.type_id" class="text-red-500 text-xs mt-1">{{ form.errors.type_id }}</div>
                </div>
            </div>

            <div class="mb-6 flex items-center">
                <input v-model="form.status" type="checkbox" id="status" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="status" class="ml-2 block text-sm text-gray-900">Activo</label>
            </div>
            
            <div>
                <button type="submit" :disabled="form.processing || isUploading" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <span v-if="isUploading">Subiendo imagen...</span>
                    <span v-else>Guardar Producto</span>
                </button>
            </div>
        </form>
    </div>
</template>

