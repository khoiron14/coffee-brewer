<script setup lang="ts">
defineProps<{
    show: boolean;
    message: string;
    type: 'success' | 'error';
}>();

const emit = defineEmits(['close']);
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="show" 
            class="fixed top-4 right-4 z-[100] max-w-sm w-full bg-white rounded-lg shadow-xl border-l-4 p-4 flex items-start space-x-3" 
            :class="type === 'success' ? 'border-emerald-500' : 'border-red-500'"
        >
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">{{ type === 'success' ? 'Berhasil' : 'Gagal' }}</p>
                <p class="text-xs text-gray-600 mt-0.5">{{ message }}</p>
            </div>
            <button @click="emit('close')" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
        </div>
    </transition>
</template>
