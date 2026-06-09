<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, watch } from 'vue';

const props = defineProps<{
    recipe: any;
    brewers: any[];
    coffees: any[];
    grindSizes: string[];
    pourTypes: string[];
}>();

const form = useForm({
    name: props.recipe.name,
    brewer_id: props.recipe.brewer_id,
    coffee_id: props.recipe.coffee_id,
    coffee_weight: props.recipe.coffee_weight,
    water_weight: props.recipe.water_weight,
    grind_size: props.recipe.grind_size,
    temperature: props.recipe.temperature,
    total_duration: props.recipe.total_duration,
    is_published: !!props.recipe.is_published,
    description: props.recipe.description,
    steps: props.recipe.recipe_steps || []
});

watch(() => form.steps, (newSteps) => {
    form.total_duration = newSteps.reduce((sum, step) => sum + Number(step.duration || 0), 0);
    form.water_weight = newSteps.reduce((sum, step) => sum + Number(step.pour_volume || 0), 0);
}, { deep: true });

const totalDurationMinutes = computed(() => Math.floor(Number(form.total_duration) / 60) || 0);
const totalDurationSeconds = computed(() => Number(form.total_duration) % 60 || 0);

const getStepMinutes = (index: number) => Math.floor(Number(form.steps[index].duration) / 60) || 0;
const setStepMinutes = (index: number, val: string | number) => {
    const currentSeconds = Number(form.steps[index].duration) % 60;
    form.steps[index].duration = (Number(val) * 60) + currentSeconds;
};

const getStepSeconds = (index: number) => Number(form.steps[index].duration) % 60 || 0;
const setStepSeconds = (index: number, val: string | number) => {
    const currentMinutes = Math.floor(Number(form.steps[index].duration) / 60);
    form.steps[index].duration = (currentMinutes * 60) + Number(val);
};

const addStep = () => form.steps.push({ order: form.steps.length + 1, pour_volume: 0, pour_type: '', duration: 0, note: '' });
const removeStep = (index: number) => {
    form.steps.splice(index, 1);
    form.steps.forEach((step, idx) => step.order = idx + 1);
};

const submit = () => {
    form.put(route('recipes.update', props.recipe.id), {
        preserveScroll: true
    });
};
</script>

<template>

    <Head title="Edit Resep" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Resep Seduh
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-8">

                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4 border-b pb-2">Informasi Dasar</h3>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Resep</label>
                                <input type="text" id="name" v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Contoh: V60 Japanese Iced Coffee">
                                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="brewer_id" class="block text-sm font-medium text-gray-700">Alat Seduh
                                    (Brewer)</label>
                                <select id="brewer_id" v-model="form.brewer_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Alat --</option>
                                    <option v-for="brewer in brewers" :key="brewer.id" :value="brewer.id">{{ brewer.name
                                        }}
                                    </option>
                                </select>
                                <div v-if="form.errors.brewer_id" class="text-red-500 text-xs mt-1">{{
                                    form.errors.brewer_id }}
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="coffee_id" class="block text-sm font-medium text-gray-700">Biji Kopi
                                    (Coffee)</label>
                                <select id="coffee_id" v-model="form.coffee_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Kopi --</option>
                                    <option v-for="coffee in coffees" :key="coffee.id" :value="coffee.id">{{ coffee.name
                                        }}
                                    </option>
                                </select>
                                <div v-if="form.errors.coffee_id" class="text-red-500 text-xs mt-1">{{
                                    form.errors.coffee_id }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4 border-b pb-2">Parameter Seduh</h3>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Berat Kopi (gram)</label>
                                <input type="number" step="1" v-model="form.coffee_weight"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <div v-if="form.errors.coffee_weight" class="text-red-500 text-xs mt-1">{{
                                    form.errors.coffee_weight }}</div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Total Air (ml)</label>
                                <input type="number" step="1" disabled :value="form.water_weight"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm sm:text-sm cursor-not-allowed">
                                <div v-if="form.errors.water_weight" class="text-red-500 text-xs mt-1">{{
                                    form.errors.water_weight }}</div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Ukuran Gilingan</label>
                                <select v-model="form.grind_size"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Grind Size --</option>
                                    <option v-for="size in grindSizes" :key="size" :value="size">
                                        {{ size }}
                                    </option>
                                </select>
                                <div v-if="form.errors.grind_size" class="text-red-500 text-xs mt-1">{{
                                    form.errors.grind_size
                                    }}</div>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Suhu Air (°C)</label>
                                <input type="number" step="1" v-model="form.temperature"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <div v-if="form.errors.temperature" class="text-red-500 text-xs mt-1">{{
                                    form.errors.temperature
                                    }}</div>
                            </div>

                            <div class="sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Total Durasi</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <div class="relative w-full">
                                        <input type="number" disabled :value="totalDurationMinutes"
                                            class="block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm sm:text-sm pr-12 cursor-not-allowed">
                                        <span class="absolute right-3 top-2 text-gray-500 text-sm">menit</span>
                                    </div>
                                    <span class="text-gray-500 font-bold">:</span>
                                    <div class="relative w-full">
                                        <input type="number" disabled :value="totalDurationSeconds"
                                            class="block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 shadow-sm sm:text-sm pr-12 cursor-not-allowed">
                                        <span class="absolute right-3 top-2 text-gray-500 text-sm">detik</span>
                                    </div>
                                </div>
                                <div v-if="form.errors.total_duration" class="text-red-500 text-xs mt-1">{{
                                    form.errors.total_duration }}</div>
                            </div>

                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi / Catatan
                                    Tambahan</label>
                                <textarea v-model="form.description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{
                                    form.errors.description
                                    }}</div>
                            </div>

                            <div class="sm:col-span-6 flex items-center">
                                <input id="is_published" type="checkbox" v-model="form.is_published"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="is_published" class="ml-2 block text-sm text-gray-900">Publikasikan resep
                                    ini agar
                                    bisa dilihat orang lain</label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4 border-b pb-2">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Langkah Seduh (Steps)</h3>
                            <button type="button" @click="addStep"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-100 px-3 py-2 text-sm font-medium leading-4 text-indigo-700 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                + Tambah Langkah
                            </button>
                        </div>

                        <div v-if="form.errors.steps" class="mb-4 p-3 bg-red-50 text-red-700 rounded-md text-sm">
                            {{ form.errors.steps }}
                        </div>

                        <div class="space-y-4">
                            <div v-for="(step, index) in form.steps" :key="index"
                                class="p-4 border rounded-md bg-gray-50 relative">
                                <button type="button" @click="removeStep(index)"
                                    class="absolute top-2 right-2 text-red-500 hover:text-red-700 font-bold"
                                    title="Hapus Langkah">
                                    &times;
                                </button>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                    <div class="sm:col-span-1">
                                        <label class="block text-xs font-medium text-gray-500">Ke-</label>
                                        <input type="number" readonly :value="index + 1"
                                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm text-center">
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700">Tipe Tuangan</label>
                                        <select v-model="step.pour_type"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">-- Pilih --</option>
                                            <option v-for="type in pourTypes" :key="type" :value="type">
                                                {{ type }}
                                            </option>
                                        </select>
                                        <div v-if="(form.errors as any)[`steps.${index}.pour_type`]"
                                            class="text-red-500 text-xs mt-1">Wajib</div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700">Air (ml)</label>
                                        <input type="number" step="1" v-model="step.pour_volume"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <div v-if="(form.errors as any)[`steps.${index}.pour_volume`]"
                                            class="text-red-500 text-xs mt-1">Wajib</div>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label class="block text-xs font-medium text-gray-700">Durasi</label>
                                        <div class="mt-1 flex items-center gap-1">
                                            <input type="number" min="0" placeholder="mnt"
                                                :value="getStepMinutes(index)"
                                                @input="e => setStepMinutes(index, (e.target as HTMLInputElement).value)"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <span class="text-gray-500 font-bold">:</span>
                                            <input type="number" min="0" max="59" placeholder="dtk"
                                                :value="getStepSeconds(index)"
                                                @input="e => setStepSeconds(index, (e.target as HTMLInputElement).value)"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div v-if="(form.errors as any)[`steps.${index}.duration`]"
                                            class="text-red-500 text-xs mt-1">Wajib</div>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-xs font-medium text-gray-700">Catatan</label>
                                        <input type="text" v-model="step.note"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Opsional">
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.steps.length === 0"
                                class="text-center py-6 text-gray-500 text-sm border-2 border-dashed rounded-md">
                                Belum ada langkah seduh. Klik tombol "Tambah Langkah" di atas.
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="$inertia.visit(route('recipes.index'))"
                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Batal
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Resep' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
