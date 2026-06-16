<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
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
    Package,
    CheckCircle2,
    Clock,
    Wrench,
    Trash2,
    Search,
    Plus,
    ChevronDown,
    MoreVertical,
    Camera,
    Music,
    Laptop,
    Mic,
    Armchair,
    ChevronLeft,
    ChevronRight,
    ArrowRightCircle,
    Pencil,
} from '@lucide/vue';
import { dashboard } from '@/routes';
import { index as assetsIndex } from '@/routes/assets';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

// Setup mock assets data mirroring the template table exactly
const assets = ref([
    {
        id: 1,
        name: 'Sony A7iii Camera',
        serial: '1234567890',
        category: 'Media',
        status: 'In Use',
        location: 'Main Sanctuary',
        updatedDate: 'May 18, 2025',
        updatedBy: 'by Admin',
        icon: Camera,
        iconBgClass:
            'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/30 border-indigo-200 dark:border-indigo-900/50',
        badgeClass:
            'text-indigo-700 bg-indigo-50 border-indigo-200/50 dark:text-indigo-300 dark:bg-indigo-950/35 dark:border-indigo-900/30',
        statusDotClass: 'bg-amber-500',
        statusTextClass:
            'text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/20',
    },
    {
        id: 2,
        name: 'Yamaha PSR-E473',
        serial: 'YMH872364',
        category: 'Musical Instruments',
        status: 'Available',
        location: 'Music Room',
        updatedDate: 'May 17, 2025',
        updatedBy: 'by Admin',
        icon: Music,
        iconBgClass:
            'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-900/50',
        badgeClass:
            'text-emerald-700 bg-emerald-50 border-emerald-200/50 dark:text-emerald-300 dark:bg-emerald-950/35 dark:border-emerald-900/30',
        statusDotClass: 'bg-emerald-500',
        statusTextClass:
            'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/20',
    },
    {
        id: 3,
        name: 'MacBook Pro 14"',
        serial: 'C02FG1ABCMD6',
        category: 'Electronics & Gadgets',
        status: 'In Use',
        location: 'Media Room',
        updatedDate: 'May 17, 2025',
        updatedBy: 'by Admin',
        icon: Laptop,
        iconBgClass:
            'text-blue-600 bg-blue-50 dark:text-blue-400 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900/50',
        badgeClass:
            'text-blue-700 bg-blue-50 border-blue-200/50 dark:text-blue-300 dark:bg-blue-950/35 dark:border-blue-900/30',
        statusDotClass: 'bg-amber-500',
        statusTextClass:
            'text-amber-700 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/20',
    },
    {
        id: 4,
        name: 'Shure SM58 Mic',
        serial: 'SH58A91234',
        category: 'Musical Instruments',
        status: 'Under Repair',
        location: 'Audio Room',
        updatedDate: 'May 16, 2025',
        updatedBy: 'by Admin',
        icon: Mic,
        iconBgClass:
            'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-900/50',
        badgeClass:
            'text-emerald-700 bg-emerald-50 border-emerald-200/50 dark:text-emerald-300 dark:bg-emerald-950/35 dark:border-emerald-900/30',
        statusDotClass: 'bg-rose-500',
        statusTextClass:
            'text-rose-700 bg-rose-50 dark:text-rose-400 dark:bg-rose-950/20',
    },
    {
        id: 5,
        name: 'Plastic Church Chair',
        serial: 'CHAIR-0891',
        category: 'General Property',
        status: 'Available',
        location: 'Fellowship Hall',
        updatedDate: 'May 15, 2025',
        updatedBy: 'by Admin',
        icon: Armchair,
        iconBgClass:
            'text-slate-600 bg-slate-50 dark:text-slate-400 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800/50',
        badgeClass:
            'text-slate-700 bg-slate-50 border-slate-200/50 dark:text-slate-300 dark:bg-slate-900/35 dark:border-slate-800/30',
        statusDotClass: 'bg-emerald-500',
        statusTextClass:
            'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/20',
    },
]);

// Summary statistics
const stats = [
    {
        label: 'Total Assets',
        value: '128',
        sub: 'All registered assets',
        icon: Package,
        color: 'text-indigo-600 dark:text-indigo-400',
        bg: 'bg-indigo-50/70 dark:bg-indigo-950/20',
    },
    {
        label: 'Available',
        value: '72',
        sub: 'Ready to use',
        icon: CheckCircle2,
        color: 'text-emerald-600 dark:text-emerald-400',
        bg: 'bg-emerald-50/70 dark:bg-emerald-950/20',
    },
    {
        label: 'In Use',
        value: '34',
        sub: 'Currently deployed',
        icon: Clock,
        color: 'text-amber-500 dark:text-amber-400',
        bg: 'bg-amber-50/70 dark:bg-amber-950/20',
    },
    {
        label: 'Under Repair',
        value: '8',
        sub: 'Maintenance ongoing',
        icon: Wrench,
        color: 'text-rose-600 dark:text-rose-400',
        bg: 'bg-rose-50/70 dark:bg-rose-950/20',
    },
    {
        label: 'Disposed',
        value: '14',
        sub: 'No longer in use',
        icon: Trash2,
        color: 'text-slate-600 dark:text-slate-400',
        bg: 'bg-slate-100/70 dark:bg-slate-900/30',
    },
];

// Interactive Filter & Search states
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');

// Computed filtered asset rows
const filteredAssets = computed(() => {
    return assets.value.filter((asset) => {
        const matchesSearch =
            asset.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            asset.serial
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            asset.location
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase());
        const matchesCategory =
            selectedCategory.value === '' ||
            asset.category === selectedCategory.value;
        const matchesStatus =
            selectedStatus.value === '' ||
            asset.status === selectedStatus.value;
        return matchesSearch && matchesCategory && matchesStatus;
    });
});

// Categories for donut chart
const rawCategories = [
    {
        name: 'Media',
        count: 28,
        percentage: 22,
        color: 'rgb(99, 102, 241)',
        bgClass: 'bg-indigo-500',
    },
    {
        name: 'Musical Instruments',
        count: 36,
        percentage: 28,
        color: 'rgb(16, 185, 129)',
        bgClass: 'bg-emerald-500',
    },
    {
        name: 'Electronics & Gadgets',
        count: 24,
        percentage: 19,
        color: 'rgb(59, 130, 246)',
        bgClass: 'bg-blue-500',
    },
    {
        name: 'General Property',
        count: 40,
        percentage: 31,
        color: 'rgb(148, 163, 184)',
        bgClass: 'bg-slate-400',
    },
];

const r = 40;
const circumference = 2 * Math.PI * r; // ~251.327

let cumulativePercent = 0;
const chartSlices = rawCategories.map((item) => {
    const dasharray = (item.percentage / 100) * circumference;
    const dashoffset = -((cumulativePercent / 100) * circumference);
    cumulativePercent += item.percentage;
    return {
        ...item,
        dasharray,
        dashoffset,
    };
});
</script>

<template>
    <Head title="Dashboard" />

    <div
        class="flex min-h-screen flex-1 flex-col gap-6 bg-neutral-50/40 p-6 md:p-8 dark:bg-neutral-950/10"
    >
        <!-- Dashboard Header -->
        <div class="flex flex-col gap-1">
            <h1
                class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-neutral-50"
            >
                Dashboard
            </h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                Overview of church assets and their status.
            </p>
        </div>

        <!-- Summary Statistics Row -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="flex items-center gap-4 rounded-xl border border-neutral-200/80 bg-white p-4 shadow-xs transition-all duration-300 hover:scale-[1.02] hover:shadow-md dark:border-neutral-800/80 dark:bg-neutral-900"
            >
                <div
                    :class="[
                        'flex size-12 shrink-0 items-center justify-center rounded-xl border border-neutral-100 dark:border-neutral-800/60',
                        stat.bg,
                    ]"
                >
                    <component
                        :is="stat.icon"
                        :class="['size-6', stat.color]"
                    />
                </div>
                <div class="flex flex-col">
                    <span
                        class="text-xs font-medium text-neutral-400 dark:text-neutral-500"
                        >{{ stat.label }}</span
                    >
                    <span
                        class="text-2xl font-extrabold text-neutral-900 dark:text-neutral-50"
                        >{{ stat.value }}</span
                    >
                    <span
                        class="mt-0.5 text-[10px] text-neutral-400 dark:text-neutral-500"
                        >{{ stat.sub }}</span
                    >
                </div>
            </div>
        </div>

        <!-- Content Columns -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Side: Recent Assets Table -->
            <div
                class="flex flex-col justify-between overflow-hidden rounded-xl border border-neutral-200/80 bg-white shadow-xs lg:col-span-2 dark:border-neutral-800/80 dark:bg-neutral-900"
            >
                <div>
                    <!-- Table Actions Header -->
                    <div
                        class="flex flex-col gap-4 border-b border-neutral-100 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-neutral-800/50"
                    >
                        <h2
                            class="text-lg font-bold text-neutral-900 dark:text-neutral-50"
                        >
                            Recent Assets
                        </h2>

                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Search -->
                            <div class="relative w-full sm:w-48">
                                <Search
                                    class="absolute top-2.5 left-3 size-4 text-neutral-400 dark:text-neutral-500"
                                />
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search assets..."
                                    class="w-full rounded-lg border border-neutral-200 bg-white py-1.5 pr-3 pl-9 text-xs text-neutral-900 placeholder:text-neutral-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none dark:border-neutral-800 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder:text-neutral-500"
                                />
                            </div>

                            <!-- Category Filter -->
                            <select
                                v-model="selectedCategory"
                                class="rounded-lg border border-neutral-200 bg-white px-2 py-1.5 text-xs text-neutral-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none dark:border-neutral-800 dark:bg-neutral-950 dark:text-neutral-300"
                            >
                                <option value="">All Categories</option>
                                <option value="Media">Media</option>
                                <option value="Musical Instruments">
                                    Musical Instruments
                                </option>
                                <option value="Electronics & Gadgets">
                                    Electronics & Gadgets
                                </option>
                                <option value="General Property">
                                    General Property
                                </option>
                            </select>

                            <!-- Status Filter -->
                            <select
                                v-model="selectedStatus"
                                class="rounded-lg border border-neutral-200 bg-white px-2 py-1.5 text-xs text-neutral-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none dark:border-neutral-800 dark:bg-neutral-950 dark:text-neutral-300"
                            >
                                <option value="">All Status</option>
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="Under Repair">
                                    Under Repair
                                </option>
                            </select>

                            <!-- Add Asset Button -->
                            <Link
                                href="/assets/create"
                                class="inline-flex cursor-pointer items-center gap-1 rounded-lg bg-indigo-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-xs transition-colors hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <Plus class="size-3.5" />
                                Add Asset
                            </Link>
                        </div>
                    </div>

                    <!-- Assets List Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left">
                            <thead>
                                <tr
                                    class="border-b border-neutral-100 bg-neutral-50/50 text-xs font-semibold text-neutral-400 dark:border-neutral-800/40 dark:bg-neutral-900/30"
                                >
                                    <th class="p-4 pl-6">Asset Name</th>
                                    <th class="p-4">Category</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Location</th>
                                    <th class="p-4 pr-6">Last Updated</th>
                                    <th class="p-4 text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="asset in filteredAssets"
                                    :key="asset.id"
                                    class="border-b border-neutral-100 text-sm transition-colors hover:bg-neutral-50/40 dark:border-neutral-800/40 dark:hover:bg-neutral-900/20"
                                >
                                    <!-- Asset Name with Thumbnail -->
                                    <td class="p-4 pl-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                :class="[
                                                    'flex size-10 shrink-0 items-center justify-center rounded-lg border',
                                                    asset.iconBgClass,
                                                ]"
                                            >
                                                <component
                                                    :is="asset.icon"
                                                    class="size-5"
                                                />
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-neutral-800 dark:text-neutral-200"
                                                    >{{ asset.name }}</span
                                                >
                                                <span
                                                    class="font-mono text-xs text-neutral-400 dark:text-neutral-500"
                                                    >SN:
                                                    {{ asset.serial }}</span
                                                >
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category Badge -->
                                    <td class="p-4">
                                        <span
                                            :class="[
                                                'inline-flex items-center rounded-md border px-2 py-1 text-xs font-semibold',
                                                asset.badgeClass,
                                            ]"
                                        >
                                            {{ asset.category }}
                                        </span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                :class="[
                                                    'size-2 rounded-full',
                                                    asset.statusDotClass,
                                                ]"
                                            ></span>
                                            <span
                                                class="text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                                                >{{ asset.status }}</span
                                            >
                                        </div>
                                    </td>

                                    <!-- Location -->
                                    <td
                                        class="p-4 text-xs font-medium text-neutral-600 dark:text-neutral-400"
                                    >
                                        {{ asset.location }}
                                    </td>

                                    <!-- Last Updated Info -->
                                    <td
                                        class="p-4 pr-6 text-xs text-neutral-500 dark:text-neutral-400"
                                    >
                                        <div class="flex flex-col">
                                            <span
                                                class="font-semibold text-neutral-700 dark:text-neutral-300"
                                                >{{ asset.updatedDate }}</span
                                            >
                                            <span
                                                class="text-[10px] text-neutral-400 dark:text-neutral-500"
                                                >{{ asset.updatedBy }}</span
                                            >
                                        </div>
                                    </td>

                                    <!-- Action Menu Button -->
                                    <td class="p-4 text-right">
                                        <button
                                            class="rounded-lg p-1.5 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-700 dark:text-neutral-500 dark:hover:bg-neutral-800 dark:hover:text-neutral-300"
                                        >
                                            <MoreVertical class="size-4" />
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredAssets.length === 0">
                                    <td
                                        colspan="6"
                                        class="p-12 text-center text-sm text-neutral-400 dark:text-neutral-500"
                                    >
                                        No assets found matching the filter
                                        criteria.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Table Pagination Footer -->
                <div
                    class="flex items-center justify-between border-t border-neutral-100 p-4 px-6 dark:border-neutral-800/50"
                >
                    <span class="text-xs text-neutral-400 dark:text-neutral-500"
                        >Showing 1 to {{ filteredAssets.length }} of 128
                        assets</span
                    >

                    <div class="flex items-center gap-1">
                        <button
                            class="rounded-lg border border-neutral-200 p-1 text-neutral-400 hover:bg-neutral-50 hover:text-neutral-800 dark:border-neutral-800 dark:text-neutral-500 dark:hover:bg-neutral-900/60 dark:hover:text-neutral-300"
                        >
                            <ChevronLeft class="size-4" />
                        </button>
                        <button
                            class="flex size-7 items-center justify-center rounded-lg bg-indigo-600 text-xs font-bold text-white shadow-xs"
                        >
                            1
                        </button>
                        <button
                            class="flex size-7 items-center justify-center rounded-lg border border-transparent text-xs font-semibold text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-900/60 dark:hover:text-neutral-300"
                        >
                            2
                        </button>
                        <button
                            class="flex size-7 items-center justify-center rounded-lg border border-transparent text-xs font-semibold text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-900/60 dark:hover:text-neutral-300"
                        >
                            3
                        </button>
                        <span
                            class="px-1 text-xs text-neutral-400 dark:text-neutral-500"
                            >...</span
                        >
                        <button
                            class="flex size-7 items-center justify-center rounded-lg border border-transparent text-xs font-semibold text-neutral-600 hover:bg-neutral-50 dark:text-neutral-400 dark:hover:bg-neutral-900/60 dark:hover:text-neutral-300"
                        >
                            26
                        </button>
                        <button
                            class="rounded-lg border border-neutral-200 p-1 text-neutral-400 hover:bg-neutral-50 hover:text-neutral-800 dark:border-neutral-800 dark:text-neutral-500 dark:hover:bg-neutral-900/60 dark:hover:text-neutral-300"
                        >
                            <ChevronRight class="size-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column Cards -->
            <div class="flex flex-col gap-6">
                <!-- Assets by Category Donut Chart -->
                <div
                    class="rounded-xl border border-neutral-200/80 bg-white p-5 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h2
                        class="mb-5 text-base font-bold text-neutral-900 dark:text-neutral-50"
                    >
                        Assets by Category
                    </h2>

                    <div
                        class="flex flex-col items-center gap-6 sm:flex-row sm:justify-around lg:flex-col lg:items-center"
                    >
                        <!-- Chart Donut Ring -->
                        <div class="relative flex items-center justify-center">
                            <svg
                                viewBox="0 0 100 100"
                                class="size-38 -rotate-90 select-none"
                            >
                                <circle
                                    v-for="(slice, i) in chartSlices"
                                    :key="i"
                                    cx="50"
                                    cy="50"
                                    r="40"
                                    fill="transparent"
                                    :stroke="slice.color"
                                    stroke-width="11"
                                    :stroke-dasharray="`${slice.dasharray} 251.327`"
                                    :stroke-dashoffset="slice.dashoffset"
                                    stroke-linecap="round"
                                    class="transition-all duration-300 hover:stroke-[13px]"
                                />
                            </svg>
                            <div
                                class="absolute flex flex-col items-center text-center"
                            >
                                <span
                                    class="text-2xl leading-none font-extrabold text-neutral-900 dark:text-neutral-50"
                                    >128</span
                                >
                                <span
                                    class="mt-1 text-[9px] font-semibold tracking-wider text-neutral-400 uppercase dark:text-neutral-500"
                                    >Total</span
                                >
                            </div>
                        </div>

                        <!-- Chart Legend List -->
                        <div class="w-full flex-1 space-y-2.5">
                            <div
                                v-for="category in rawCategories"
                                :key="category.name"
                                class="flex items-center justify-between text-xs font-semibold"
                            >
                                <div class="flex items-center gap-2">
                                    <span
                                        :class="[
                                            'size-2.5 shrink-0 rounded-full',
                                            category.bgClass,
                                        ]"
                                    ></span>
                                    <span
                                        class="text-neutral-700 dark:text-neutral-300"
                                        >{{ category.name }}</span
                                    >
                                </div>
                                <span
                                    class="text-neutral-400 dark:text-neutral-500"
                                >
                                    {{ category.count }}
                                    <span
                                        class="text-[10px] font-normal text-neutral-400/80 dark:text-neutral-500"
                                        >({{ category.percentage }}%)</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div
                    class="rounded-xl border border-neutral-200/80 bg-white p-5 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h2
                        class="mb-5 text-base font-bold text-neutral-900 dark:text-neutral-50"
                    >
                        Recent Activity
                    </h2>

                    <div class="space-y-4">
                        <!-- Checked In activity -->
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400"
                            >
                                <CheckCircle2 class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div
                                    class="flex items-center justify-between gap-1.5"
                                >
                                    <span
                                        class="truncate text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        >Checked In</span
                                    >
                                    <span
                                        class="shrink-0 text-[10px] whitespace-nowrap text-neutral-400 dark:text-neutral-500"
                                        >2h ago</span
                                    >
                                </div>
                                <p
                                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    Shure SM58 Mic
                                </p>
                                <p
                                    class="text-[9px] font-medium text-neutral-400 dark:text-neutral-500"
                                >
                                    by Admin
                                </p>
                            </div>
                        </div>

                        <!-- Checked Out activity -->
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/20 dark:text-blue-400"
                            >
                                <ArrowRightCircle class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div
                                    class="flex items-center justify-between gap-1.5"
                                >
                                    <span
                                        class="truncate text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        >Checked Out</span
                                    >
                                    <span
                                        class="shrink-0 text-[10px] whitespace-nowrap text-neutral-400 dark:text-neutral-500"
                                        >5h ago</span
                                    >
                                </div>
                                <p
                                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    Sony A7iii Camera
                                </p>
                                <p
                                    class="text-[9px] font-medium text-neutral-400 dark:text-neutral-500"
                                >
                                    by John Doe
                                </p>
                            </div>
                        </div>

                        <!-- Updated activity -->
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400"
                            >
                                <Pencil class="size-3.5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div
                                    class="flex items-center justify-between gap-1.5"
                                >
                                    <span
                                        class="truncate text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        >Updated Asset</span
                                    >
                                    <span
                                        class="shrink-0 text-[10px] whitespace-nowrap text-neutral-400 dark:text-neutral-500"
                                        >1d ago</span
                                    >
                                </div>
                                <p
                                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    Yamaha PSR-E473
                                </p>
                                <p
                                    class="text-[9px] font-medium text-neutral-400 dark:text-neutral-500"
                                >
                                    by Admin
                                </p>
                            </div>
                        </div>

                        <!-- New asset activity -->
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400"
                            >
                                <Plus class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div
                                    class="flex items-center justify-between gap-1.5"
                                >
                                    <span
                                        class="truncate text-xs font-bold text-neutral-800 dark:text-neutral-200"
                                        >New Asset Added</span
                                    >
                                    <span
                                        class="shrink-0 text-[10px] whitespace-nowrap text-neutral-400 dark:text-neutral-500"
                                        >2d ago</span
                                    >
                                </div>
                                <p
                                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                                >
                                    LED Studio Light
                                </p>
                                <p
                                    class="text-[9px] font-medium text-neutral-400 dark:text-neutral-500"
                                >
                                    by Admin
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Block -->
                <div
                    class="rounded-xl border border-neutral-200/80 bg-white p-5 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900"
                >
                    <h2
                        class="mb-4 text-base font-bold text-neutral-900 dark:text-neutral-50"
                    >
                        Quick Links
                    </h2>

                    <div class="grid grid-cols-2 gap-3">
                        <Link
                            :href="assetsIndex()"
                            class="group flex cursor-pointer flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 transition-all duration-300 hover:border-indigo-500/30 hover:bg-indigo-50/20 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-indigo-500/30 dark:hover:bg-indigo-950/10"
                        >
                            <FileText
                                class="mb-2 size-5 text-indigo-600 transition-transform group-hover:scale-110 dark:text-indigo-400"
                            />
                            <span
                                class="text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                                >View All Assets</span
                            >
                        </Link>

                        <button
                            class="group flex cursor-pointer flex-col items-center justify-center rounded-xl border border-neutral-200 bg-neutral-50/50 p-4 transition-all duration-300 hover:border-indigo-500/30 hover:bg-indigo-50/20 dark:border-neutral-800 dark:bg-neutral-950/40 dark:hover:border-indigo-500/30 dark:hover:bg-indigo-950/10"
                        >
                            <BarChart3
                                class="mb-2 size-5 text-indigo-600 transition-transform group-hover:scale-110 dark:text-indigo-400"
                            />
                            <span
                                class="text-xs font-semibold text-neutral-700 dark:text-neutral-300"
                                >Generate Report</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
