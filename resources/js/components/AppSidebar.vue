<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
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
import { computed } from 'vue';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as assetsIndex } from '@/routes/assets';
import { index as auditIndex } from '@/routes/audit-logs';
import { index as borrowIndex } from '@/routes/borrow-requests';
import { index as categoriesIndex } from '@/routes/categories';
import { index as maintenanceIndex } from '@/routes/maintenance';
import { edit as profileEdit } from '@/routes/profile';
import { index as reportsIndex } from '@/routes/reports';
import { index as usersIndex } from '@/routes/users';
import type { NavItem } from '@/types';

const page = usePage();
const isAdmin = computed(() => {
    const roles = page.props.auth?.user?.roles as string[] | undefined;

    return roles?.includes('admin') ?? false;
});

const navItems = computed(() => {
    const items: NavItem[] = [
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
            title: 'Borrow / Return',
            href: borrowIndex(),
            icon: ArrowLeftRight,
        },
    ];

    if (isAdmin.value) {
        items.push(
            {
                title: 'Categories',
                href: categoriesIndex(),
                icon: Folder,
            },
            {
                title: 'Maintenance',
                href: maintenanceIndex(),
                icon: MapPin,
            },
            {
                title: 'Users',
                href: usersIndex(),
                icon: Users,
            },
            {
                title: 'Reports',
                href: reportsIndex(),
                icon: BarChart3,
            },
            {
                title: 'Audit Logs',
                href: auditIndex(),
                icon: FileText,
            },
        );
    }

    items.push({
        title: 'Settings',
        href: profileEdit(),
        icon: Settings,
    });

    return items;
});
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
                    src="/brand_name_dark.png"
                    alt="Winner Church"
                    class="h-11 self-start object-contain"
                />
                <span
                    class="mt-1 truncate pl-1 text-[10px] font-bold tracking-widest text-neutral-400 uppercase dark:text-neutral-500"
                    style="font-family: 'Orbitron', sans-serif"
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
            <NavMain :items="navItems" />
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
                        class="flex flex-col text-[11px] leading-tight font-bold tracking-tight text-neutral-400"
                    >
                        <span
                            class="text-sm font-black text-[#FF8A00]"
                            style="font-family: 'Orbitron', sans-serif"
                            >Winner Church</span
                        >
                        <span
                            class="text-[10px] font-semibold tracking-tight text-neutral-500"
                            >Asset Inventory System</span
                        >
                    </div>
                </div>
                <span class="text-[10px] font-medium text-neutral-500">
                    © 2025 All rights reserved.
                </span>
            </div>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
