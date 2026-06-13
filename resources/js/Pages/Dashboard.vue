<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    publicRecipes: any;
    ownRecipes: any;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Resep Publik</h3>
                        <Link :href="route('recipes.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                            Lihat Semua
                        </Link>
                    </div>

                    <div v-if="publicRecipes.data.length === 0" class="text-sm text-gray-500">
                        Belum ada resep publik.
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="recipe in publicRecipes.data"
                            :key="recipe.id"
                            :href="route('recipes.show', recipe.id)"
                            class="p-4 transition-shadow bg-white rounded-lg shadow hover:shadow-md"
                        >
                            <p class="font-semibold text-gray-900">{{ recipe.name }}</p>
                            <p class="mt-1 text-sm text-gray-500">oleh {{ recipe.user.name }}</p>
                            <div class="mt-3 space-y-1 text-sm text-gray-600">
                                <p>{{ recipe.brewer?.name }} · {{ recipe.coffee?.name }}</p>
                                <p>{{ recipe.coffee_weight }}g / {{ recipe.water_weight }}ml · {{ recipe.temperature }}°C</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Resep Saya</h3>
                        <Link :href="route('recipes.create')" class="text-sm text-indigo-600 hover:text-indigo-900">
                            + Buat Resep
                        </Link>
                    </div>

                    <div v-if="ownRecipes.data.length === 0" class="text-sm text-gray-500">
                        Belum ada resep.
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="recipe in ownRecipes.data"
                            :key="recipe.id"
                            class="p-4 bg-white rounded-lg shadow"
                        >
                            <p class="font-semibold text-gray-900">{{ recipe.name }}</p>
                            <div class="mt-3 space-y-1 text-sm text-gray-600">
                                <p>{{ recipe.brewer?.name }} · {{ recipe.coffee?.name }}</p>
                                <p>{{ recipe.coffee_weight }}g / {{ recipe.water_weight }}ml · {{ recipe.temperature }}°C</p>
                            </div>
                            <div class="flex gap-3 mt-4 text-sm">
                                <Link :href="route('recipes.show', recipe.id)" class="text-indigo-600 hover:text-indigo-900">Detail</Link>
                                <Link :href="route('recipes.edit', recipe.id)" class="text-gray-600 hover:text-gray-900">Edit</Link>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
