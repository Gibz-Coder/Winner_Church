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
    Crown,
} from '@lucide/vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
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
        <SidebarContent class="py-4">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border/40">
            <div
                class="flex flex-col gap-2 p-3 group-data-[collapsible=icon]:hidden"
            >
                <div class="flex items-center gap-2.5">
                    <Crown
                        class="size-6 shrink-0 animate-pulse text-indigo-600 dark:text-indigo-400"
                    />
                    <div
                        class="flex flex-col text-[10px] leading-tight font-bold tracking-wider text-neutral-600 dark:text-neutral-300"
                    >
                        <span>KINGDOM OF</span>
                        <span>JESUS CHRIST</span>
                        <span>CHURCH</span>
                    </div>
                </div>
                <span
                    class="text-[9px] font-medium text-neutral-400 dark:text-neutral-500"
                >
                    © 2025 All rights reserved.
                </span>
            </div>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
