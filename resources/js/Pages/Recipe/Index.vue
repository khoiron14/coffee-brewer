<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Paginated } from '@/Cores/types/pagination';

defineProps<{
    recipes: Paginated<any>;
}>();

const deleteRecipe = (id: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus resep ini?')) {
        router.delete(route('recipes.destroy', id));
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Resep</h2>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium">Semua Resep</h3>
                            <Link :href="route('recipes.create')"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                                + Tambah Resep
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Resep</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gilingan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Durasi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(recipe, index) in recipes.data" :key="recipe.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ (recipes.current_page - 1) * recipes.per_page + index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{
                                            recipe.name
                                            }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                            formatDate(recipe.created_at) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{
                                            recipe.grind_size }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ Math.floor(recipe.total_duration / 60) }}m {{ recipe.total_duration % 60
                                            }}s
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="recipe.is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ recipe.is_published ? 'Public' : 'Private' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-3">
                                                <Link :href="route('recipes.show', recipe.id)"
                                                    class="text-gray-600 hover:text-indigo-600 transition-colors"
                                                    title="Lihat Detail">
                                                    Lihat
                                                </Link>

                                                <Link :href="route('recipes.edit', recipe.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                    title="Edit Resep">
                                                    Edit
                                                </Link>

                                                <button @click="deleteRecipe(recipe.id)"
                                                    class="text-red-600 hover:text-red-900 transition-colors font-medium"
                                                    title="Hapus Resep">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <Pagination :pagination="recipes" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
