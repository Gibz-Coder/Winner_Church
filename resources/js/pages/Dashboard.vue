<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Boxes,
    ArrowLeftRight,
    BarChart3,
    Package,
    CheckCircle2,
    Clock,
    Wrench,
    Trash2,
    AlertCircle,
} from '@lucide/vue';
import { computed } from 'vue';
import { index as assetsIndex } from '@/routes/assets';
import { index as borrowIndex } from '@/routes/borrow-requests';
import { index as reportsIndex } from '@/routes/reports';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: '/dashboard',
            },
        ],
    },
});

const props = defineProps<{
    stats: {
        total: number;
        available: number;
        borrowed: number;
        maintenance: number;
        disposed: number;
        lost: number;
        reserved: number;
        pending_requests: number;
    };
    recentActivity: Array<{
        id: number;
        asset_name: string | null;
        user_name: string;
        action: string;
        action_label: string;
        description: string;
        time_ago: string;
    }>;
    categories: Array<{
        name: string;
        count: number;
    }>;
}>();

const statsCards = computed(() => [
    {
        label: 'Total Assets',
        value: props.stats.total,
        sub: 'All registered assets',
        icon: Package,
        color: 'text-[#FF8A00]',
        bg: 'bg-[#FF8A00]/10',
    },
    {
        label: 'Available',
        value: props.stats.available,
        sub: 'Ready to check out',
        icon: CheckCircle2,
        color: 'text-emerald-600 dark:text-emerald-400',
        bg: 'bg-emerald-50/70 dark:bg-emerald-950/20',
    },
    {
        label: 'Borrowed',
        value: props.stats.borrowed,
        sub: 'Currently with members',
        icon: Clock,
        color: 'text-[#FFC300]',
        bg: 'bg-[#FFC300]/10',
    },
    {
        label: 'Maintenance',
        value: props.stats.maintenance,
        sub: 'Scheduled or in repair',
        icon: Wrench,
        color: 'text-rose-600 dark:text-rose-400',
        bg: 'bg-rose-50/70 dark:bg-rose-950/20',
    },
    {
        label: 'Disposed',
        value: props.stats.disposed,
        sub: 'No longer in service',
        icon: Trash2,
        color: 'text-slate-600 dark:text-slate-400',
        bg: 'bg-slate-100/70 dark:bg-slate-900/30',
    },
]);

// Calculate percentages for category bars
const maxCategoryCount = computed(() => {
    if (props.categories.length === 0) {
        return 1;
    }

    return Math.max(...props.categories.map((c) => c.count));
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Dashboard Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="font-display text-2xl font-black tracking-widest text-neutral-900 uppercase dark:text-neutral-50"
                    style="font-family: 'Orbitron', sans-serif"
                >
                    Winner Church Dashboard
                </h1>
                <p class="mt-1 text-xs tracking-wide text-muted-foreground">
                    Real-time status of the church assets and inventory
                    borrowing activity.
                </p>
            </div>
            <div class="flex gap-2">
                <Button
                    as-child
                    variant="outline"
                    class="h-9 font-display text-xs font-bold tracking-wider uppercase"
                    style="font-family: 'Orbitron', sans-serif"
                    v-if="stats.pending_requests > 0"
                >
                    <Link :href="borrowIndex()"
                        ><AlertCircle
                            class="mr-1.5 size-4 animate-pulse text-amber-500"
                        />
                        {{ stats.pending_requests }} Pending Requests</Link
                    >
                </Button>
            </div>
        </div>

        <!-- Statistics grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div
                v-for="stat in statsCards"
                :key="stat.label"
                class="relative overflow-hidden rounded-2xl border border-neutral-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:shadow-md dark:border-neutral-800/80 dark:bg-neutral-900"
            >
                <div class="flex items-center justify-between">
                    <span
                        class="font-display text-xs font-bold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                        style="font-family: 'Orbitron', sans-serif"
                        >{{ stat.label }}</span
                    >
                    <div
                        :class="[
                            'flex size-9 items-center justify-center rounded-xl',
                            stat.bg,
                            stat.color,
                        ]"
                    >
                        <component :is="stat.icon" class="size-5" />
                    </div>
                </div>
                <div class="mt-4">
                    <span
                        class="font-display text-3xl font-black tracking-wider text-neutral-900 dark:text-neutral-100"
                        style="font-family: 'Orbitron', sans-serif"
                        >{{ stat.value }}</span
                    >
                    <p
                        class="mt-1 text-[10px] text-neutral-400 dark:text-neutral-500"
                    >
                        {{ stat.sub }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main section layout -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left 2 columns: Category details & Visuals -->
            <div class="grid gap-6 lg:col-span-2">
                <!-- Categories Bar Charts -->
                <div
                    class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h2
                                class="font-display text-sm font-bold tracking-wider text-neutral-900 uppercase dark:text-neutral-50"
                                style="font-family: 'Orbitron', sans-serif"
                            >
                                Assets by Category
                            </h2>
                            <p
                                class="mt-1 text-xs text-neutral-400 dark:text-neutral-500"
                            >
                                Distribution of items across different
                                ministries and types.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="cat in categories"
                            :key="cat.name"
                            class="space-y-1.5"
                        >
                            <div
                                class="flex items-center justify-between text-xs font-semibold"
                            >
                                <span
                                    class="text-neutral-700 dark:text-neutral-300"
                                    >{{ cat.name }}</span
                                >
                                <span
                                    class="font-bold text-neutral-900 dark:text-neutral-100"
                                    >{{ cat.count }} assets</span
                                >
                            </div>
                            <div
                                class="h-2.5 w-full overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-800"
                            >
                                <div
                                    class="h-full rounded-full bg-gradient-to-r from-[#FF8A00] to-[#FFC300] transition-all duration-500"
                                    :style="{
                                        width: `${(cat.count / maxCategoryCount) * 100}%`,
                                    }"
                                ></div>
                            </div>
                        </div>
                        <div
                            v-if="categories.length === 0"
                            class="py-6 text-center text-sm text-neutral-400"
                        >
                            No categories registered.
                        </div>
                    </div>
                </div>

                <!-- Shortcuts grid -->
                <div
                    class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h2
                        class="mb-4 font-display text-sm font-bold tracking-wider text-neutral-900 uppercase dark:text-neutral-50"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        General Quick Actions
                    </h2>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <Link
                            :href="assetsIndex()"
                            class="group flex flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 text-center transition-all duration-300 hover:border-[#FF8A00]/30 hover:bg-[#FF8A00]/5 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-[#FF8A00]/30 dark:hover:bg-[#FF8A00]/5"
                        >
                            <div
                                class="mb-2.5 flex size-9 items-center justify-center rounded-xl bg-[#FF8A00]/10 text-[#FF8A00] transition-transform group-hover:scale-110"
                            >
                                <Boxes class="size-5" />
                            </div>
                            <span
                                class="font-display text-xs font-bold tracking-wider text-neutral-700 uppercase dark:text-neutral-300"
                                style="font-family: 'Orbitron', sans-serif"
                                >Assets Directory</span
                            >
                        </Link>

                        <Link
                            :href="borrowIndex()"
                            class="group flex flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 text-center transition-all duration-300 hover:border-[#FF8A00]/30 hover:bg-[#FF8A00]/5 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-[#FF8A00]/30 dark:hover:bg-[#FF8A00]/5"
                        >
                            <div
                                class="mb-2.5 flex size-9 items-center justify-center rounded-xl bg-[#FF8A00]/10 text-[#FF8A00] transition-transform group-hover:scale-110"
                            >
                                <ArrowLeftRight class="size-5" />
                            </div>
                            <span
                                class="font-display text-xs font-bold tracking-wider text-neutral-700 uppercase dark:text-neutral-300"
                                style="font-family: 'Orbitron', sans-serif"
                                >Borrow & Return</span
                            >
                        </Link>

                        <Link
                            :href="reportsIndex()"
                            class="group flex flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 text-center transition-all duration-300 hover:border-[#FF8A00]/30 hover:bg-[#FF8A00]/5 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-[#FF8A00]/30 dark:hover:bg-[#FF8A00]/5"
                        >
                            <div
                                class="mb-2.5 flex size-9 items-center justify-center rounded-xl bg-[#FF8A00]/10 text-[#FF8A00] transition-transform group-hover:scale-110"
                            >
                                <BarChart3 class="size-5" />
                            </div>
                            <span
                                class="font-display text-xs font-bold tracking-wider text-neutral-700 uppercase dark:text-neutral-300"
                                style="font-family: 'Orbitron', sans-serif"
                                >Reports Panel</span
                            >
                        </Link>

                        <Link
                            href="/settings/profile"
                            class="group flex flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 text-center transition-all duration-300 hover:border-[#FF8A00]/30 hover:bg-[#FF8A00]/5 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-[#FF8A00]/30 dark:hover:bg-[#FF8A00]/5"
                        >
                            <div
                                class="mb-2.5 flex size-9 items-center justify-center rounded-xl bg-[#FF8A00]/10 text-[#FF8A00] transition-transform group-hover:scale-110"
                            >
                                <Settings class="size-5" />
                            </div>
                            <span
                                class="font-display text-xs font-bold tracking-wider text-neutral-700 uppercase dark:text-neutral-300"
                                style="font-family: 'Orbitron', sans-serif"
                                >Settings</span
                            >
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right 1 column: Recent activities -->
            <div class="flex flex-col gap-6">
                <!-- Activity timeline -->
                <div
                    class="flex-1 rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h2
                        class="mb-4 font-display text-sm font-bold tracking-wider text-neutral-900 uppercase dark:text-neutral-50"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Recent Activities
                    </h2>

                    <div
                        class="relative space-y-5 pl-4 before:absolute before:top-2 before:bottom-2 before:left-1.5 before:w-0.5 before:bg-neutral-100 dark:before:bg-neutral-800"
                    >
                        <div
                            v-for="act in recentActivity"
                            :key="act.id"
                            class="group relative"
                        >
                            <!-- Bullet dot -->
                            <div
                                class="absolute top-1.5 -left-4 size-3.5 rounded-full border-2 border-white bg-[#FF8A00] dark:border-neutral-900"
                            ></div>

                            <div class="flex flex-col">
                                <div
                                    class="flex items-center justify-between text-xs text-neutral-400"
                                >
                                    <span
                                        class="font-bold text-neutral-700 dark:text-neutral-300"
                                        >{{ act.user_name }}</span
                                    >
                                    <span>{{ act.time_ago }}</span>
                                </div>
                                <p
                                    class="mt-0.5 text-xs font-semibold text-neutral-900 dark:text-neutral-100"
                                >
                                    {{ act.action_label }} ({{
                                        act.asset_name
                                    }})
                                </p>
                                <p
                                    class="mt-0.5 text-[11px] leading-tight text-neutral-400 dark:text-neutral-500"
                                >
                                    {{ act.description }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="recentActivity.length === 0"
                            class="py-10 text-center text-xs text-neutral-400"
                        >
                            No activities logged yet.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
