<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    coffee: any;
}>();
</script>

<template>
    <Head :title="coffee.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Kopi: {{ coffee.name }}</h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Kopi</p>
                            <p class="font-medium text-lg text-gray-900">{{ coffee.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Roastery</p>
                            <p class="font-medium text-lg text-gray-900">{{ coffee.roastery }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Roast Level</p>
                            <p class="font-medium text-lg text-gray-900 capitalize">{{ coffee.roast_level }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Deskripsi</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ coffee.description || 'Tidak ada deskripsi.' }}</p>
                </div>

                <div class="flex justify-end">
                    <Link :href="route('coffees.index')" class="text-indigo-600 hover:text-indigo-900 font-medium">
                        Kembali ke Daftar Kopi
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
