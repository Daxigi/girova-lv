<script setup lang="ts">
defineProps<{
    modelValue: string;
}>();

const emit = defineEmits(['update:modelValue']);

const onInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};
</script>

<template>
    <div class="flex justify-center">
        <input 
            type="text"
            :value="modelValue"
            @input="onInput"
            placeholder="Buscar productos..."
            class="mb-8 w-full max-w-lg rounded-md border-gray-300 px-4 py-2 shadow-sm
            focus:border-indigo-500 sm:text-sm"
            />
    </div>
</template>