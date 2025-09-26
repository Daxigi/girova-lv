<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue: string;
}>();

const emit = defineEmits(['update:modelValue']);

const internalSearchTerm = ref(props.modelValue);

watch(internalSearchTerm, (newValue) => {
    emit('update:modelValue', newValue);
});

watch(() => props.modelValue, (newValue) => {
    if (newValue !== internalSearchTerm.value) {
        internalSearchTerm.value = newValue;
    }
});
</script>

<template>
    <v-text-field
        v-model="internalSearchTerm"
        label="Buscar productos..."
        variant="solo"
        prepend-inner-icon="mdi-magnify"
        class="mb-8"
    ></v-text-field>
</template>