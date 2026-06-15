<script setup>
import { ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Sidebar from '@/Components/sidebar.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Alert from '@/Components/Alert.vue';
import { useAlert } from '@/Composables/useAlert';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import {
  HomeIcon,
  ClipboardDocumentListIcon,
} from '@heroicons/vue/24/outline'

const { alert, triggerAlert } = useAlert();

const page = usePage();

watch(
    () => [page.props.flash, page.props.errors],
    ([flash, errors]) => {
        if (flash?.success) triggerAlert(flash.success, 'success');
        if (flash?.error) triggerAlert(flash.error, 'error');
        if (errors?.error) triggerAlert(errors.error, 'error');
    },
    { deep: true, immediate: true }
);

const sidebarOpen = ref(false);

const navigation = [
  {
    name: 'Dashboard',
    href: route('dashboard'),
    icon: HomeIcon,
    current: route().current('dashboard'),
  },
  {
    name: 'Daftar Kopi',
    href: route('coffees.index'),
    icon: ClipboardDocumentListIcon,
    current: route().current('coffees.*'),
  },
  {
    name: 'Resep Saya',
    href: route('recipes.index'),
    icon: ClipboardDocumentListIcon,
    current: route().current('recipes.*') || route().current('ratings.*'),
  },
]
</script>

<template>
    <div>
        <Alert 
            :show="alert.show" 
            :message="alert.message" 
            :type="alert.type" 
            @close="alert.show = false" 
        />

        <div class="min-h-screen bg-gray-50">
            <!-- Mobile Sidebar Toggle -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button @click="sidebarOpen = !sidebarOpen" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <Link :href="route('dashboard')" class="flex items-center gap-x-2">
                            <ApplicationLogo class="h-8 w-auto fill-current text-gray-800" />
                            <span class="text-lg font-semibold text-gray-900 hidden sm:block">Coffee Brewer</span>
                        </Link>
                    </div>
                    <div class="flex flex-1 justify-end items-center gap-x-4 lg:gap-x-6">
                        <!-- Settings Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>

            <div class="flex">
                <!-- Static Sidebar for Desktop -->
                <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                    <Sidebar :navigation="navigation" />
                </div>

                <!-- Mobile Sidebar -->
                <div v-if="sidebarOpen" class="relative z-40 lg:hidden">
                    <div class="fixed inset-0 bg-gray-900/80" @click="sidebarOpen = false"></div>
                    <div class="fixed inset-y-0 left-0 z-40 w-72 overflow-y-auto bg-white pt-16">
                        <div class="flex grow flex-col gap-y-5 px-6">
                            <nav class="flex flex-1 flex-col">
                                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                    <li>
                                        <ul role="list" class="-mx-2 space-y-1">
                                            <li v-for="item in navigation" :key="item.name">
                                                <Link v-if="!item.children" :href="item.href" @click="sidebarOpen = false" :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-700']">
                                                    <component :is="item.icon" class="size-6 shrink-0 text-gray-400" aria-hidden="true" />
                                                    {{ item.name }}
                                                </Link>
                                                <Disclosure as="div" v-else v-slot="{ open }">
                                                    <DisclosureButton :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm/6 font-semibold text-gray-700']">
                                                        <component :is="item.icon" class="size-6 shrink-0 text-gray-400" aria-hidden="true" />
                                                        {{ item.name }}
                                                        <ChevronRightIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto size-5 shrink-0']" aria-hidden="true" />
                                                    </DisclosureButton>
                                                    <DisclosurePanel as="ul" class="mt-1 px-2">
                                                        <li v-for="subItem in item.children" :key="subItem.name">
                                                            <DisclosureButton as="a" :href="subItem.href" :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md py-2 pl-9 pr-2 text-sm/6 text-gray-700']">{{ subItem.name }}</DisclosureButton>
                                                        </li>
                                                    </DisclosurePanel>
                                                </Disclosure>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="-mx-6 mt-auto">
                                        <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50">
                                            <img class="size-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                            <span class="sr-only">Your profile</span>
                                            <span aria-hidden="true">{{ $page.props.auth.user.name }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:pl-72 w-full">
                    <!-- Page Heading -->
                    <header
                        class="bg-white shadow"
                        v-if="$slots.header"
                    >
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>
                    
                    <!-- Page Content -->
                    <main class="px-4 sm:px-6 lg:px-8 py-8">
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
