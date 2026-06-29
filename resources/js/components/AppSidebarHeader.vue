<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Bell, ChevronDown, Moon, Sun } from '@lucide/vue';
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useAppearance } from '@/composables/useAppearance';
import { useInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, User } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth.user as User);
const { resolvedAppearance, updateAppearance } = useAppearance();
const { getInitials } = useInitials();

const toggleAppearance = () => {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
};

const showAvatar = computed(
    () => user.value?.avatar && user.value?.avatar !== '',
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-6 dark:border-neutral-800"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <div class="flex items-center gap-5">
            <!-- Theme Toggle -->
            <button
                @click="toggleAppearance"
                class="flex cursor-pointer items-center gap-2 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-neutral-600 transition-colors hover:bg-neutral-100 focus:outline-none dark:text-neutral-300 dark:hover:bg-neutral-800"
            >
                <Sun
                    v-if="resolvedAppearance === 'dark'"
                    class="size-4 animate-spin text-[#FFC300]"
                    style="animation-duration: 10s"
                />
                <Moon v-else class="size-4 text-[#FF8A00]" />
                <span class="hidden sm:inline">{{
                    resolvedAppearance === 'dark' ? 'Light Mode' : 'Dark Mode'
                }}</span>
            </button>

            <!-- Notifications -->
            <button
                class="relative cursor-pointer rounded-lg p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 focus:outline-none dark:text-neutral-400 dark:hover:bg-neutral-800"
            >
                <Bell class="size-5" />
                <span
                    class="absolute top-1 right-1 flex size-4 items-center justify-center rounded-full bg-[#FF8A00] text-[9px] font-bold text-white ring-2 ring-white dark:ring-neutral-900"
                >
                    3
                </span>
            </button>

            <!-- User Menu -->
            <div
                v-if="user"
                class="flex h-6 items-center border-l border-neutral-200 pl-4 dark:border-neutral-800"
            >
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <button
                            class="flex cursor-pointer items-center gap-2 rounded-lg p-1 transition-colors hover:bg-neutral-100 focus:outline-none dark:hover:bg-neutral-800"
                        >
                            <Avatar
                                class="size-7 overflow-hidden rounded-full border border-neutral-200 dark:border-neutral-700"
                            >
                                <AvatarImage
                                    v-if="showAvatar"
                                    :src="user.avatar!"
                                    :alt="user.name"
                                />
                                <AvatarFallback
                                    class="rounded-full text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                >
                                    {{ getInitials(user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <span
                                class="hidden text-xs font-bold text-neutral-700 select-none sm:inline dark:text-neutral-300"
                                >{{ user.name }}</span
                            >
                            <ChevronDown class="size-3.5 text-neutral-400" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        class="w-56 rounded-lg"
                        align="end"
                        :side-offset="4"
                    >
                        <UserMenuContent :user="user" />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
    </header>
</template>
