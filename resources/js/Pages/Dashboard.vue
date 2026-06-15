<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    publicRecipes: any;
    ownRecipes: any;
}>();

const activeTab = ref<'public' | 'own'>('public');

const averageRating = (recipe: any): string => {
    if (!recipe.ratings || recipe.ratings.length === 0) return '-';
    const avg = recipe.ratings.reduce((sum: number, r: any) => sum + r.score, 0) / recipe.ratings.length;
    return avg.toFixed(1);
};

const ratingCount = (recipe: any): number => recipe.ratings?.length ?? 0;

const publishedOwnRecipes = computed(() =>
    props.ownRecipes.data.filter((r: any) => r.is_published)
);

const draftOwnRecipes = computed(() =>
    props.ownRecipes.data.filter((r: any) => !r.is_published)
);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div class="flex mb-6 border-b border-gray-200">
                    <button
                        @click="activeTab = 'public'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2"
                        :class="activeTab === 'public'
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700'"
                    >
                        Resep Publik
                    </button>
                    <button
                        @click="activeTab = 'own'"
                        class="px-6 py-3 text-sm font-medium transition-colors border-b-2"
                        :class="activeTab === 'own'
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700'"
                    >
                        Resep Saya
                    </button>
                </div>

<div v-if="activeTab === 'public'">

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
            <p class="mt-1 text-sm text-gray-500">oleh {{ recipe.user?.name }}</p>
            <div class="mt-3 space-y-1 text-sm text-gray-600">
                <p>{{ recipe.brewer?.name }} · {{ recipe.coffee?.name }}</p>
                <p>{{ recipe.coffee_weight }}g / {{ recipe.water_weight }}ml · {{ recipe.temperature }}°C</p>
            </div>
            <div class="flex items-center gap-1 mt-3">
                <span class="text-sm text-yellow-400">★</span>
                <span class="text-sm font-medium text-gray-700">{{ averageRating(recipe) }}</span>
                <span class="text-xs text-gray-400">({{ ratingCount(recipe) }} rating)</span>
            </div>
        </Link>
    </div>

    <div class="mt-4">
        <Pagination :pagination="publicRecipes"/>
    </div>
</div>

                <div v-if="activeTab === 'own'">
                    <div class="flex items-center justify-between mb-4">
                    <div class="flex gap-2 mb-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                            Publik <strong>{{ publishedOwnRecipes.length }}</strong>
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full">
                            Draft <strong>{{ draftOwnRecipes.length }}</strong>
                        </span>
                    </div>                        <Link :href="route('recipes.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                            List Semua
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
                            :class="!recipe.is_published ? 'border border-dashed border-gray-300' : ''"
                        >
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-semibold text-gray-900">{{ recipe.name }}</p>
                                <span
                                    class="text-xs px-2 py-0.5 rounded-full"
                                    :class="recipe.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                >
                                    {{ recipe.is_published ? 'Publik' : 'Draft' }}
                                </span>
                            </div>
                            <div class="mt-2 space-y-1 text-sm text-gray-600">
                                <p>{{ recipe.brewer?.name }} · {{ recipe.coffee?.name }}</p>
                                <p>{{ recipe.coffee_weight }}g / {{ recipe.water_weight }}ml · {{ recipe.temperature }}°C</p>
                            </div>
                            <div class="flex items-center gap-1 mt-3">
                                <span class="text-sm text-yellow-400">★</span>
                                <span class="text-sm font-medium text-gray-700">{{ averageRating(recipe) }}</span>
                                <span class="text-xs text-gray-400">({{ ratingCount(recipe) }} rating)</span>
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
