<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps<{
    roastLevels: string[];
}>();

const form = useForm({
    name: "",
    roastery: "",
    roast_level: "",
    description: "",
});

const submit = (): void => {
    form.post(route("coffees.store"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Buat Resep Seduh" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Buat Resep Seduh Baru
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900 mb-4 border-b pb-2"
                        >
                            Informasi Dasar
                        </h3>

                        <div
                            class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"
                        >
                            <div class="sm:col-span-6">
                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700"
                                    >Nama Kopi</label
                                >
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Contoh: Strawberry Fields"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <label
                                    for="brewer_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Roastery</label
                                >
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.roastery"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Contoh: Sapce Roastery"
                                />
                                <div
                                    v-if="form.errors.name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.roastery }}
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label
                                    for="coffee_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Roast Level</label
                                >
                                <select v-model="form.roast_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Roast Level --</option>
                                    <option v-for="size in roastLevels" :key="size" :value="size">
                                        {{ size }}
                                    </option>
                                </select>
                                <div
                                    v-if="form.errors.name"
                                    class="text-red-500 text-xs mt-1"
                                >
                                    {{ form.errors.roast_level }}
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi / Catatan Rasa</label>
                                <textarea id="description" v-model="form.description" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Jelaskan aroma, cita rasa, tingkat keasaman, dll..."></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="$inertia.visit(route('coffees.index'))"
                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                        >
                            {{
                                form.processing ? "Menyimpan..." : "Simpan Kopi"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
