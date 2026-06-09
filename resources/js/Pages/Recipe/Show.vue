<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    recipe: any;
}>();
</script>

<template>
    <Head :title="recipe.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Resep: {{ recipe.name }}</h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Alat Seduh</p>
                            <p class="font-medium">{{ recipe.brewer?.name || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Biji Kopi</p>
                            <p class="font-medium">{{ recipe.coffee?.name || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Berat Kopi / Air</p>
                            <p class="font-medium">{{ recipe.coffee_weight }}g / {{ recipe.water_weight }}ml</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Suhu / Gilingan</p>
                            <p class="font-medium">{{ recipe.temperature }}°C / {{ recipe.grind_size }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Langkah Seduh</h3>
                    <div class="space-y-3">
                        <div v-for="step in recipe.recipe_steps" :key="step.id" class="border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50">
                            <p class="font-semibold text-sm">Langkah {{ step.order }}: {{ step.pour_type }}</p>
                            <p class="text-sm text-gray-600">{{ step.pour_volume }}ml selama {{ step.duration }} detik</p>
                            <p v-if="step.note" class="text-xs text-gray-400 italic">Catatan: {{ step.note }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <Link :href="route('recipes.index')" class="text-gray-600 hover:text-gray-900">Kembali ke Daftar</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
