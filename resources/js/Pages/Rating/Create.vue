<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps<{
    recipe: { id: string; name: string };
}>();

const form = useForm({
    recipe_id: props.recipe.id,
    score: 0,
    note: '',
});

const submit = (): void => {
    form.post(route('ratings.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Beri Rating" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Beri Rating untuk: {{ recipe.name }}
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="p-6 bg-white shadow sm:rounded-lg">
                        <div class="space-y-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Skor</label>
                                <div class="flex items-center gap-1">
                                    <button
                                        v-for="i in 5"
                                        :key="i"
                                        type="button"
                                        @click="form.score = i"
                                        class="text-3xl focus:outline-none"
                                        :class="i <= form.score ? 'text-yellow-400' : 'text-gray-300'"
                                    >
                                        ★
                                    </button>
                                    <span class="ml-2 text-sm text-gray-500">{{ form.score }} / 5</span>
                                </div>
                                <div v-if="form.errors.score" class="mt-1 text-xs text-red-500">{{ form.errors.score }}</div>
                            </div>

                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                                <textarea
                                    id="note"
                                    v-model="form.note"
                                    rows="4"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Bagaimana rasa kopi dengan resep ini?"
                                ></textarea>
                                <div v-if="form.errors.note" class="mt-1 text-xs text-red-500">{{ form.errors.note }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$inertia.visit(route('recipes.show', { recipe: recipe.id }))"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing || form.score === 0"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Rating' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
