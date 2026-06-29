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
    Flame,
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
                class="flex items-center gap-3 group-data-[collapsible=icon]:hidden"
            >
                <!-- Brand Icon (Flame) in a polished gradient badge -->
                <div
                    class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#FF8A00] to-[#FFC300] shadow-[0_0_12px_rgba(255,138,0,0.3)]"
                >
                    <Flame class="size-4.5 fill-[#0B1220] text-[#0B1220]" />
                </div>
                <!-- Written Branding Name & Subtitle -->
                <div class="flex flex-col leading-tight">
                    <span
                        class="text-sm font-black tracking-widest text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                        >Winner <span class="text-[#FF8A00]">Church</span></span
                    >
                    <span
                        class="mt-0.5 text-[9px] font-bold tracking-[0.18em] text-neutral-400 uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                        >Inventory System</span
                    >
                </div>
            </div>
            <!-- Collapsed Brand Logo -->
            <div
                class="hidden size-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#FF8A00] to-[#FFC300] group-data-[collapsible=icon]:flex"
            >
                <Flame class="size-4.5 fill-[#0B1220] text-[#0B1220]" />
            </div>
        </SidebarHeader>

        <SidebarContent class="py-4">
            <NavMain :items="navItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border/40">
            <!-- Sidebar Brand Footer -->
            <div
                class="flex flex-col gap-2 p-3.5 group-data-[collapsible=icon]:hidden"
            >
                <div class="flex items-center gap-2.5">
                    <!-- Brand Icon (Flame) in a polished gradient badge -->
                    <div
                        class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#FF8A00] to-[#FFC300]"
                    >
                        <Flame class="size-4 fill-[#0B1220] text-[#0B1220]" />
                    </div>
                    <!-- Written Branding Name & Subtitle -->
                    <div class="flex flex-col text-[11px] leading-tight">
                        <span
                            class="text-xs font-black tracking-widest text-[#FF8A00] uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Winner Church</span
                        >
                        <span
                            class="text-[9px] font-semibold tracking-wider text-neutral-500 uppercase"
                            >Inventory System</span
                        >
                    </div>
                </div>
                <span
                    class="text-[9px] font-medium tracking-wide text-neutral-500"
                >
                    © 2026 Winner Church. All rights reserved.
                </span>
            </div>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
