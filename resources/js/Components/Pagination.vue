<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

defineProps<{
    pagination: {
        current_page: number;
        from: number;
        to: number;
        total: number;
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
}>();
</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link v-if="pagination.links[0].url" :href="pagination.links[0].url" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</Link>
            <Link v-if="pagination.links[pagination.links.length - 1].url" :href="pagination.links[pagination.links.length - 1].url" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</Link>
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ pagination.from }}</span> to 
                    <span class="font-medium">{{ pagination.to }}</span> of 
                    <span class="font-medium">{{ pagination.total }}</span> results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <template v-for="(link, index) in pagination.links" :key="index">
                        
                        <Link 
                            v-if="index === 0 || index === pagination.links.length - 1"
                            :href="link.url ?? '#'"
                            :class="[
                                'relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20',
                                index === 0 ? 'rounded-l-md' : 'rounded-r-md',
                                !link.url ? 'opacity-50 cursor-not-allowed' : ''
                            ]"
                        >
                            <ChevronLeftIcon v-if="index === 0" class="size-5" />
                            <ChevronRightIcon v-else class="size-5" />
                        </Link>

                        <Link
                            v-else-if="link.url"
                            :href="link.url"
                            :class="[
                                link.active 
                                    ? 'relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20' 
                                    : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20'
                            ]"
                            v-html="link.label"
                        />
                        
                        <span v-else class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300" v-html="link.label"></span>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
