import { ref, computed } from 'vue';
import type { Product } from '@/types';


export function useProducts(initialProducts: Product[]) {

    const searchTerm = ref('');
    const selectedCategory = ref<number | null>(null);
    const selectedType = ref<number | null>(null);

    const sortBy = ref('default'); 


    const processedProducts = computed(() => {
        let products = [...initialProducts]; 

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

        const sortOption = sortBy.value;
        if (sortOption === 'price_asc') {
            products.sort((a, b) => a.price - b.price);
        } else if (sortOption === 'price_desc') {
            products.sort((a, b) => b.price - a.price);
        } else if (sortOption === 'name_asc') {
            products.sort((a, b) => a.name.localeCompare(b.name));
        }

        return products;
    });


    const clearFilters = () => {
        searchTerm.value = '';
        selectedCategory.value = null;
        selectedType.value = null;
        sortBy.value = 'default'; 
    };


    return {
        // Estado
        searchTerm,
        selectedCategory,
        selectedType,
        sortBy, 

        // Datos procesados
        processedProducts,

        // Métodos
        clearFilters,
    };
}