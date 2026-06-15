<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps<{
    recipe: any;
    userRating: any;

}>();


const form = useForm({
    recipe_id: props.recipe.id,
    score: 0,
    note: '',
});

const submit = (): void => {
    form.post(route('ratings.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
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
    <h3 class="mb-6 text-lg font-semibold text-gray-800">Rating Resep</h3>

    <div v-if="!userRating" class="mb-8">
        <p class="mb-3 text-sm font-medium text-gray-600">Bagikan pengalamanmu membuat resep ini</p>
        <form @submit.prevent="submit" class="p-5 space-y-5 border border-gray-200 rounded-xl bg-gray-50">

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Skor</label>
                <div class="flex items-center gap-2">
                    <button
                        v-for="i in 5"
                        :key="i"
                        type="button"
                        @click="form.score = i"
                        class="text-4xl transition-transform focus:outline-none hover:scale-110"
                        :class="i <= form.score ? 'text-yellow-400' : 'text-gray-200'"
                    >
                        ★
                    </button>
                    <span class="ml-1 text-sm text-gray-400">
                        {{ form.score === 0 ? 'Pilih skor' : `${form.score} / 5` }}
                    </span>
                </div>
                <div v-if="form.errors.score" class="mt-1 text-xs text-red-500">{{ form.errors.score }}</div>
            </div>

            <div>
                <label for="note" class="block mb-1 text-sm font-medium text-gray-700">
                    Catatan <span class="font-normal text-gray-400">(opsional)</span>
                </label>
                <textarea
                    id="note"
                    v-model="form.note"
                    rows="3"
                    class="block w-full mt-1 text-sm border-gray-300 rounded-lg shadow-sm resize-none focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Bagaimana rasa kopi dengan resep ini?"
                ></textarea>
                <div v-if="form.errors.note" class="mt-1 text-xs text-red-500">{{ form.errors.note }}</div>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing || form.score === 0"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Rating' }}
                </button>
            </div>
        </form>
    </div>

    <div v-if="userRating" class="p-4 mb-6 border border-indigo-200 rounded-xl bg-indigo-50">
        <div class="flex items-start justify-between">
            <div>
                <p class="mb-1 text-xs font-semibold tracking-wide text-indigo-500 uppercase">Rating Anda</p>
                <div class="flex items-center gap-1 mb-2">
                    <span v-for="i in 5" :key="i" class="text-xl" :class="i <= userRating.score ? 'text-yellow-400' : 'text-gray-200'">★</span>
                    <span class="ml-1 text-sm text-gray-500">{{ userRating.score }} / 5</span>
                </div>
                <p v-if="userRating.note" class="text-sm italic text-gray-600">{{ userRating.note }}</p>
            </div>
            <Link
                :href="route('ratings.destroy', userRating.id)"
                method="delete"
                as="button"
                class="text-xs text-red-400 transition-colors hover:text-red-600"
                confirm="Yakin ingin menghapus rating ini?"
            >
                Hapus
            </Link>
        </div>
    </div>

    <div v-if="recipe.ratings?.length > 0" class="mb-4">
        <p class="text-sm font-medium text-gray-500">
            {{ recipe.ratings.length }} Rating
        </p>
    </div>

    <div v-if="recipe.ratings && recipe.ratings.length > 0" class="space-y-4">
        <div
            v-for="rating in recipe.ratings.filter((r: any) => r.id !== userRating?.id)"
            :key="rating.id"
            class="flex items-start gap-3 pb-4 border-b last:border-b-0 last:pb-0"
        >
            <div class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-white bg-indigo-400 rounded-full shrink-0">
                {{ rating.user?.name?.charAt(0).toUpperCase() || '?' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700">{{ rating.user?.name || 'Anonim' }}</p>
                <div class="flex items-center gap-1 my-1">
                    <span v-for="i in 5" :key="i" class="text-sm" :class="i <= rating.score ? 'text-yellow-400' : 'text-gray-200'">★</span>
                </div>
                <p v-if="rating.note" class="text-sm text-gray-500">{{ rating.note }}</p>
            </div>
        </div>
    </div>
    <p v-else class="py-4 text-sm text-center text-gray-400">Belum ada rating untuk resep ini.</p>
</div>

                <div class="flex justify-end">
                    <Link :href="route('recipes.index')" class="text-gray-600 hover:text-gray-900">Kembali ke Daftar</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
