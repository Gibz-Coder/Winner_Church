<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Monitor, Shield, Database } from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Audit Logs', href: '/audit-logs' }],
    },
});

const props = defineProps<{
    logs: {
        data: Array<{
            id: number;
            user_id: number | null;
            action: string;
            auditable_type: string | null;
            auditable_id: number | null;
            old_values: any;
            new_values: any;
            ip_address: string | null;
            created_at: string;
            user: { name: string } | null;
        }>;
        links: any[];
    };
}>();

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleString();
};

const getEntityName = (typeStr: string | null) => {
    if (!typeStr) {
        return '—';
    }

    const parts = typeStr.split('\\');

    return parts[parts.length - 1];
};
</script>

<template>
    <Head title="Audit Logs" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    Audit Trails
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Chronological system audit logs of administrative actions,
                    edits, creations, and deletes.
                </p>
            </div>
        </div>

        <!-- Audit Table -->
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200/80 bg-white shadow-xs dark:border-neutral-800 dark:bg-neutral-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-neutral-50/70 text-xs font-bold tracking-wider text-neutral-400 uppercase dark:bg-neutral-950/20"
                >
                    <tr>
                        <th class="px-6 py-4">Actor</th>
                        <th class="px-6 py-4">Action Performing</th>
                        <th class="px-6 py-4">Target Entity</th>
                        <th class="px-6 py-4">Database ID</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Timestamp</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-neutral-100 dark:divide-neutral-800"
                >
                    <tr
                        v-for="log in logs.data"
                        :key="log.id"
                        class="transition-colors hover:bg-neutral-50/50 dark:hover:bg-neutral-950/10"
                    >
                        <td class="px-6 py-4">
                            <span
                                class="font-bold text-neutral-900 dark:text-neutral-100"
                                >{{
                                    log.user?.name ?? 'System / Anonymous'
                                }}</span
                            >
                        </td>
                        <td
                            class="px-6 py-4 font-semibold text-neutral-700 dark:text-neutral-300"
                        >
                            {{ log.action }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <Database class="size-3.5 text-neutral-400" />
                                <span
                                    class="text-xs text-neutral-600 dark:text-neutral-400"
                                    >{{
                                        getEntityName(log.auditable_type)
                                    }}</span
                                >
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 font-mono text-xs text-neutral-500 dark:text-neutral-400"
                        >
                            {{ log.auditable_id ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <div
                                class="flex items-center gap-1.5 text-xs text-neutral-500"
                            >
                                <Monitor class="size-3.5 text-neutral-400" />
                                <span>{{ log.ip_address ?? '—' }}</span>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 text-xs font-medium text-neutral-500 dark:text-neutral-400"
                        >
                            <div class="flex items-center gap-1.5">
                                <Calendar class="size-3.5" />
                                <span>{{ formatDate(log.created_at) }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="logs.data.length === 0">
                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-neutral-500"
                        >
                            No audit trails recorded.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
