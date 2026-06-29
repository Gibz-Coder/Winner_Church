<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="relative overflow-x-hidden">
            <!-- Technical glow background -->
            <div
                class="pointer-events-none absolute inset-0 -z-10 overflow-hidden"
            >
                <!-- Glowing orange flame orb -->
                <div
                    class="absolute -top-40 left-1/2 h-[500px] w-[500px] -translate-x-1/2 rounded-full bg-gradient-to-br from-[#FF8A00]/10 to-[#FFC300]/5 blur-[100px] dark:from-[#FF8A00]/15 dark:to-[#FFC300]/10"
                ></div>
                <!-- Glowing cyan/tech orb on the bottom right -->
                <div
                    class="absolute right-10 bottom-10 h-[350px] w-[350px] rounded-full bg-[#00D4FF]/5 blur-[90px] dark:bg-[#00D4FF]/10"
                ></div>
            </div>

            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <div class="relative z-10 flex flex-1 flex-col">
                <slot />
            </div>
        </AppContent>
        <Toaster />
    </AppShell>
</template>
