import { ref, computed } from 'vue';
// Asumiremos que tienes un tipo Product, si no, podemos definirlo.
// Por ahora, usaré `any` para simplificar.
import type { Product } from '@/types';


export function useProducts(initialProducts: Product[]) {
    // --- ESTADO DE FILTROS Y ORDEN ---

    // Filtros existentes
    const searchTerm = ref('');
    const selectedCategory = ref<number | null>(null);
    const selectedType = ref<number | null>(null);

    // NUEVO: Estado para el ordenamiento
    const sortBy = ref('default'); // Valor por defecto

    // --- LÓGICA COMPUTADA ---

    const processedProducts = computed(() => {
        let products = [...initialProducts]; // Copiamos para no mutar el original

        // 1. LÓGICA DE FILTRADO (como antes, pero sin precios)
        if (searchTerm.value) {
            const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
            products = products.filter(p =>
                p.name.toLowerCase().includes(lowerCaseSearchTerm) ||
                p.description.toLowerCase().includes(lowerCaseSearchTerm)
            );
        }
        if (selectedCategory.value !== null) {
            products = products.filter(p => p.category?.id === selectedCategory.value);
        }
        if (selectedType.value !== null) {
            products = products.filter(p => p.type?.id === selectedType.value);
        }

        // 2. NUEVA LÓGICA DE ORDENAMIENTO
        const sortOption = sortBy.value;
        if (sortOption === 'price_asc') {
            products.sort((a, b) => a.price - b.price);
        } else if (sortOption === 'price_desc') {
            products.sort((a, b) => b.price - a.price);
        } else if (sortOption === 'name_asc') {
            products.sort((a, b) => a.name.localeCompare(b.name));
        }
        // No necesitamos un caso para 'default', se mantendrá el orden original.

        return products;
    });

    // --- MÉTODOS ---

    const clearFilters = () => {
        searchTerm.value = '';
        selectedCategory.value = null;
        selectedType.value = null;
        sortBy.value = 'default'; // También reseteamos el orden
    };

    // --- EXPORTACIÓN ---

    return {
        // Estado
        searchTerm,
        selectedCategory,
        selectedType,
        sortBy, // Exponemos el nuevo estado

        // Datos procesados
        processedProducts,

        // Métodos
        clearFilters,
    };
}