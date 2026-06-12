<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    rating: any;
}>();
</script>

<template>
    <Head :title="rating.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Detail Rating: {{ rating.name }}</h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ rating.user?.name || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Resep</p>
                            <p class="font-medium">{{ rating.recipe?.name || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Skor</p>
                            <p class="font-medium">{{ rating.score }}/5</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Catatan</p>
                            <p class="font-medium">{{ rating.note }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <Link :href="route('ratings.index')" class="text-gray-600 hover:text-gray-900">Kembali ke Daftar</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
