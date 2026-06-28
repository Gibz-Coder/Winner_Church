<script setup lang="ts">
import {
    LayoutGrid,
    Boxes,
    Folder,
    FileText,
    ArrowLeftRight,
    BarChart3,
    MapPin,
    Users,
    Settings,
} from '@lucide/vue';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as assetsIndex } from '@/routes/assets';
import { edit as profileEdit } from '@/routes/profile';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Assets',
        href: assetsIndex(),
        icon: Boxes,
    },
    {
        title: 'Categories',
        href: '#',
        icon: Folder,
    },
    {
        title: 'Logs',
        href: '#',
        icon: FileText,
    },
    {
        title: 'Borrow / Return',
        href: '#',
        icon: ArrowLeftRight,
    },
    {
        title: 'Reports',
        href: '#',
        icon: BarChart3,
    },
    {
        title: 'Locations',
        href: '#',
        icon: MapPin,
    },
    {
        title: 'Users',
        href: '#',
        icon: Users,
    },
    {
        title: 'Settings',
        href: profileEdit(),
        icon: Settings,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <!-- Sidebar Brand Header -->
        <SidebarHeader
            class="flex-row items-center gap-2.5 border-b border-sidebar-border/40 px-4 py-3.5 group-data-[collapsible=icon]:justify-center group-data-[collapsible=icon]:p-2"
        >
            <!-- Expanded Brand Name (Contains Logo and Text) -->
            <div
                class="flex min-w-0 flex-col group-data-[collapsible=icon]:hidden"
            >
                <img
                    src="/brand_name_light.png"
                    alt="Winner Church"
                    class="h-11 self-start object-contain dark:hidden"
                />
                <img
                    src="/brand_name_dark.png"
                    alt="Winner Church"
                    class="hidden h-11 self-start object-contain dark:block"
                />
                <span
                    class="mt-1 truncate pl-1 text-[10px] font-bold tracking-widest text-neutral-400 uppercase dark:text-neutral-500"
                >
                    Asset Inventory System
                </span>
            </div>
            <!-- Collapsed Brand Logo -->
            <img
                src="/brand_logo.png"
                alt="Winner Church Logo"
                class="hidden size-8 shrink-0 object-contain group-data-[collapsible=icon]:block"
            />
        </SidebarHeader>

        <SidebarContent class="py-4">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border/40">
            <!-- Sidebar Brand Footer -->
            <div
                class="flex flex-col gap-2.5 p-3.5 group-data-[collapsible=icon]:hidden"
            >
                <div class="flex items-center gap-3">
                    <img
                        src="/brand_logo.png"
                        alt="Winner Church Logo"
                        class="size-9 shrink-0 animate-pulse object-contain"
                    />
                    <div
                        class="flex flex-col text-[11px] leading-tight font-bold tracking-tight text-neutral-600 dark:text-neutral-300"
                    >
                        <span
                            class="text-sm font-black text-indigo-600 dark:text-indigo-400"
                            >Winner Church</span
                        >
                        <span
                            class="text-[10px] font-semibold tracking-tight text-neutral-400 dark:text-neutral-500"
                            >Asset Inventory System</span
                        >
                    </div>
                </div>
                <span
                    class="text-[10px] font-medium text-neutral-400 dark:text-neutral-500"
                >
                    © 2025 All rights reserved.
                </span>
            </div>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
