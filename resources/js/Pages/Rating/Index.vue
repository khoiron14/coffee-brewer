<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Paginated } from '@/Cores/types/pagination';

defineProps<{
    ratings: Paginated<any>;
}>();

const deleteRating = (id: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus rating ini?')) {
        router.delete(route('ratings.destroy', id));
    }
};

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }).format(new Date(dateString));
};
</script>

<template>

    <Head title="Daftar Resep" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Rating Resep</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium">Semua Rating Resep</h3>
                            <Link :href="route('ratings.create')"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                + Tambah Rating Resep
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Resep</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Skor</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(rating, index) in ratings.data" :key="rating.id">
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ (ratings.current_page - 1) * ratings.per_page + index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{
                                            rating.user.name
                                            }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{
                                            formatDate(rating.created_at) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 capitalize whitespace-nowrap">{{
                                            rating.recipe.name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ rating.score }}/5
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            {{ rating.note }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-3">
                                                <Link :href="route('ratings.show', rating.id)"
                                                    class="text-gray-600 transition-colors hover:text-indigo-600"
                                                    title="Lihat Detail">
                                                    Lihat
                                                </Link>

                                                <button @click="deleteRating(rating.id)"
                                                    class="font-medium text-red-600 transition-colors hover:text-red-900"
                                                    title="Hapus Rating">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination :pagination="ratings" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
