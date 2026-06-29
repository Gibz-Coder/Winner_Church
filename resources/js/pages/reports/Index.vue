<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    FileDown,
    Printer,
    BarChart3,
    Wrench,
    ArrowLeftRight,
    HardDrive,
} from '@lucide/vue';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Reports & Analytics', href: '/reports' }],
    },
});

const props = defineProps<{
    categorySummary: Array<{
        name: string;
        count: number;
        total_cost: string | number;
    }>;
    utilization: Array<{
        name: string;
        serial_number: string | null;
        borrow_count: number;
    }>;
    maintenanceSummary: {
        total_cost: string | number;
        count: number;
        pending_count: number;
    };
}>();

const printReport = () => {
    window.print();
};
</script>

<template>
    <Head title="Reports & Analytics" />

    <div
        class="flex h-full flex-1 flex-col gap-6 p-6 print:bg-white print:p-0 print:text-black"
    >
        <!-- Header (hidden in print) -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800 print:hidden"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    Reports & Analytics
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Export system records or generate printable summaries.
                </p>
            </div>
            <Button
                @click="printReport"
                variant="outline"
                class="flex h-9 items-center gap-1.5 text-xs"
            >
                <Printer class="size-4" /> Print PDF Report
            </Button>
        </div>

        <!-- Print-only Report Header -->
        <div
            class="mb-6 hidden border-b-2 border-neutral-900 pb-4 text-center print:block"
        >
            <h1 class="text-3xl font-black tracking-tight uppercase">
                Winner Church
            </h1>
            <h2 class="text-xl font-bold text-neutral-600">
                Asset Management & Inventory Audit Report
            </h2>
            <p class="mt-2 text-xs text-neutral-400">
                Generated on: {{ new Date().toLocaleString() }}
            </p>
        </div>

        <!-- Export Cards (hidden in print) -->
        <div class="grid gap-4 sm:grid-cols-3 print:hidden">
            <div
                class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 dark:border-neutral-800 dark:bg-neutral-900"
            >
                <div>
                    <div
                        class="flex size-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400"
                    >
                        <HardDrive class="size-5" />
                    </div>
                    <h3
                        class="mt-3 font-bold text-neutral-950 dark:text-neutral-50"
                    >
                        Inventory Export
                    </h3>
                    <p class="mt-1 text-xs text-neutral-500">
                        Download complete lists of all church assets, including
                        current locations, cost details, and status.
                    </p>
                </div>
                <Button
                    as-child
                    variant="outline"
                    class="mt-4 h-8 w-full text-xs font-semibold"
                >
                    <a href="/reports/export/inventory"
                        ><FileDown class="mr-1.5 size-4" /> CSV / Excel
                        Export</a
                    >
                </Button>
            </div>

            <div
                class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 dark:border-neutral-800 dark:bg-neutral-900"
            >
                <div>
                    <div
                        class="flex size-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400"
                    >
                        <ArrowLeftRight class="size-5" />
                    </div>
                    <h3
                        class="mt-3 font-bold text-neutral-950 dark:text-neutral-50"
                    >
                        Borrow History Export
                    </h3>
                    <p class="mt-1 text-xs text-neutral-500">
                        Download chronological logs of checked out and returned
                        assets, user borrow details, and return inspection
                        comments.
                    </p>
                </div>
                <Button
                    as-child
                    variant="outline"
                    class="mt-4 h-8 w-full text-xs font-semibold"
                >
                    <a href="/reports/export/borrowing"
                        ><FileDown class="mr-1.5 size-4" /> CSV / Excel
                        Export</a
                    >
                </Button>
            </div>

            <div
                class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 dark:border-neutral-800 dark:bg-neutral-900"
            >
                <div>
                    <div
                        class="flex size-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400"
                    >
                        <Wrench class="size-5" />
                    </div>
                    <h3
                        class="mt-3 font-bold text-neutral-950 dark:text-neutral-50"
                    >
                        Maintenance Records
                    </h3>
                    <p class="mt-1 text-xs text-neutral-500">
                        Download repair logs, servicing descriptions, costs, and
                        technicians who resolved technical breakdowns.
                    </p>
                </div>
                <Button
                    as-child
                    variant="outline"
                    class="mt-4 h-8 w-full text-xs font-semibold"
                >
                    <a href="/reports/export/maintenance"
                        ><FileDown class="mr-1.5 size-4" /> CSV / Excel
                        Export</a
                    >
                </Button>
            </div>
        </div>

        <!-- Print-friendly Tables section -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Category Summary -->
            <div
                class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900 print:border-none print:p-0 print:shadow-none"
            >
                <h3
                    class="mb-3 border-b pb-2 text-base font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Asset Distribution Summary
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr
                                class="border-b font-bold tracking-wider text-neutral-400 uppercase"
                            >
                                <th class="py-2">Category Name</th>
                                <th class="py-2 text-right">Items Count</th>
                                <th class="py-2 text-right">
                                    Acquisition Cost
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y text-neutral-700 dark:text-neutral-300"
                        >
                            <tr v-for="cat in categorySummary" :key="cat.name">
                                <td
                                    class="py-2.5 font-semibold text-neutral-900 dark:text-neutral-100"
                                >
                                    {{ cat.name }}
                                </td>
                                <td class="py-2.5 text-right font-medium">
                                    {{ cat.count }}
                                </td>
                                <td class="py-2.5 text-right font-bold">
                                    {{
                                        cat.total_cost
                                            ? `$${Number(cat.total_cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                                            : '—'
                                    }}
                                </td>
                            </tr>
                            <tr v-if="categorySummary.length === 0">
                                <td
                                    colspan="3"
                                    class="py-4 text-center text-neutral-400"
                                >
                                    No category breakdown details.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Asset Utilization (Top Borrowed) -->
            <div
                class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900 print:border-none print:p-0 print:shadow-none"
            >
                <h3
                    class="mb-3 border-b pb-2 text-base font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Top Borrowed Assets
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr
                                class="border-b font-bold tracking-wider text-neutral-400 uppercase"
                            >
                                <th class="py-2">Asset Name</th>
                                <th class="py-2">Serial Number</th>
                                <th class="py-2 text-right">Times Borrowed</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y text-neutral-700 dark:text-neutral-300"
                        >
                            <tr v-for="item in utilization" :key="item.name">
                                <td
                                    class="py-2.5 font-semibold text-neutral-900 dark:text-neutral-100"
                                >
                                    {{ item.name }}
                                </td>
                                <td class="py-2.5 font-mono text-neutral-500">
                                    {{ item.serial_number ?? '—' }}
                                </td>
                                <td
                                    class="py-2.5 text-right font-bold text-indigo-600 dark:text-indigo-400 print:text-black"
                                >
                                    {{ item.borrow_count }}
                                </td>
                            </tr>
                            <tr v-if="utilization.length === 0">
                                <td
                                    colspan="3"
                                    class="py-4 text-center text-neutral-400"
                                >
                                    No utilization data available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Maintenance Cost metrics -->
        <div
            class="rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs dark:border-neutral-800/80 dark:bg-neutral-900 print:border-none print:p-0 print:shadow-none"
        >
            <h3
                class="mb-3 border-b pb-2 text-base font-bold text-neutral-950 dark:text-neutral-50"
            >
                Maintenance Metrics Summary
            </h3>
            <div class="mt-4 grid gap-6 text-center sm:grid-cols-3">
                <div
                    class="rounded-xl bg-neutral-50 p-4 dark:bg-neutral-950/20 print:border print:bg-white"
                >
                    <span class="text-xs font-bold text-neutral-400 uppercase"
                        >Total Servicing Costs</span
                    >
                    <p class="mt-1 text-2xl font-black text-rose-600">
                        {{
                            maintenanceSummary.total_cost
                                ? `$${Number(props.maintenanceSummary.total_cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                                : '$0.00'
                        }}
                    </p>
                </div>
                <div
                    class="rounded-xl bg-neutral-50 p-4 dark:bg-neutral-950/20 print:border print:bg-white"
                >
                    <span class="text-xs font-bold text-neutral-400 uppercase"
                        >Maintenance Logs Count</span
                    >
                    <p
                        class="mt-1 text-2xl font-black text-neutral-800 dark:text-neutral-100"
                    >
                        {{ maintenanceSummary.count }} logs
                    </p>
                </div>
                <div
                    class="rounded-xl bg-neutral-50 p-4 dark:bg-neutral-950/20 print:border print:bg-white"
                >
                    <span class="text-xs font-bold text-neutral-400 uppercase"
                        >Currently Under Repair</span
                    >
                    <p class="mt-1 text-2xl font-black text-amber-500">
                        {{ maintenanceSummary.pending_count }} active
                    </p>
                </div>
            </div>
        </div>

        <!-- Print-only Report Footer -->
        <div
            class="mt-12 hidden border-t pt-4 text-center text-xs text-neutral-400 print:block"
        >
            <p>
                © {{ new Date().getFullYear() }} Winner Church. This document is
                a confidential inventory record.
            </p>
        </div>
    </div>
</template>

<style scoped>
@media print {
    /* Hide scrollbars and elements */
    .print\:hidden {
        display: none !important;
    }
}
</style>
