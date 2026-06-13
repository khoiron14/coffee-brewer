<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    recipe: any;
    userRating: any;

}>();
</script>

<template>
    <Head :title="recipe.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Detail Resep: {{ recipe.name }}</h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div class="p-6 bg-white rounded-lg shadow">
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

                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Langkah Seduh</h3>
                    <div class="space-y-3">
                        <div v-for="step in recipe.recipe_steps" :key="step.id" class="py-2 pl-4 border-l-4 border-indigo-500 bg-gray-50">
                            <p class="text-sm font-semibold">Langkah {{ step.order }}: {{ step.pour_type }}</p>
                            <p class="text-sm text-gray-600">{{ step.pour_volume }}ml selama {{ step.duration }} detik</p>
                            <p v-if="step.note" class="text-xs italic text-gray-400">Catatan: {{ step.note }}</p>
                        </div>
                    </div>
                </div>

<div class="p-6 bg-white rounded-lg shadow">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium">Rating</h3>
        <Link
            v-if="!userRating"
            :href="route('ratings.create', recipe.id)"
            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded hover:bg-indigo-700"
        >
            Beri Rating
        </Link>
    </div>

    <div v-if="userRating" class="p-3 mb-4 border border-indigo-200 rounded-md bg-indigo-50">
        <p class="mb-1 text-xs font-medium text-indigo-600">Rating Anda</p>
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-1">
                    <span v-for="i in 5" :key="i" class="text-lg text-yellow-400">
                        {{ i <= userRating.score ? '★' : '☆' }}
                    </span>
                </div>
                <p v-if="userRating.note" class="mt-1 text-sm text-gray-600">{{ userRating.note }}</p>
            </div>

            <Link
                :href="route('ratings.destroy', userRating.id)"
                method="delete"
                as="button"
                class="text-sm text-red-500 hover:text-red-700"
                confirm="Yakin ingin menghapus rating ini?"
            >
                Hapus
            </Link>
        </div>
    </div>

    <div v-if="recipe.ratings && recipe.ratings.length > 0" class="space-y-3">
        <div v-for="rating in recipe.ratings.filter((r: any )=> r.id !== userRating?.id)" :key="rating.id" class="pb-3 border-b last:border-b-0">
            <div class="flex items-center gap-1">
                <span v-for="i in 5" :key="i" class="text-lg text-yellow-400">
                    {{ i <= rating.score ? '★' : '☆' }}
                </span>
            </div>
            <p v-if="rating.note" class="mt-1 text-sm text-gray-600">{{ rating.note }}</p>
            <p class="mt-1 text-xs text-gray-400">oleh {{ rating.user?.name || 'Anonim' }}</p>
        </div>
    </div>
    <p v-else class="text-sm text-gray-500">Belum ada rating untuk resep ini.</p>
</div>

                <div class="flex justify-end">
                    <Link :href="route('recipes.index')" class="text-gray-600 hover:text-gray-900">Kembali ke Daftar</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
