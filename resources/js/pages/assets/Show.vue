<script lang="ts">
import { h } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as assetsIndex, show } from '@/routes/assets';

export default {
    layout: (h: any, page: any) => {
        return h(
            AppLayout,
            {
                breadcrumbs: [
                    { title: 'Assets', href: assetsIndex() },
                    { title: 'Details', href: show(page.props.asset.id) },
                ],
            },
            () => page,
        );
    },
};
</script>

<script setup lang="ts">
import { Deferred, Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Skeleton } from '@/components/ui/skeleton';
import type { AssetDetail, AssetLog, AssetStatus } from '@/types';
import type { BadgeVariants } from '@/components/ui/badge';

const props = defineProps<{
    asset: AssetDetail;
    logs?: AssetLog[];
}>();

const statusVariant = (value: AssetStatus): BadgeVariants['variant'] =>
    ({
        available: 'default',
        in_use: 'secondary',
        under_repair: 'outline',
        disposed: 'destructive',
    })[value];

const specs = (): { label: string; value: string }[] => [
    { label: 'Category', value: props.asset.category },
    { label: 'Brand', value: props.asset.brand ?? '—' },
    { label: 'Serial number', value: props.asset.serial_number ?? '—' },
    { label: 'Model number', value: props.asset.model_number ?? '—' },
    { label: 'Location', value: props.asset.current_location ?? '—' },
    { label: 'Purchase date', value: props.asset.purchase_date ?? '—' },
    { label: 'Cost', value: props.asset.cost ? `$${props.asset.cost}` : '—' },
];

const deleteAsset = (): void => {
    router.delete(destroy(props.asset.id).url, {
        onBefore: () => confirm('Are you sure you want to delete this asset?'),
    });
};

const formatDate = (value: string | null): string =>
    value ? new Date(value).toLocaleString() : '';
</script>

<template>
    <Head :title="asset.name" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-semibold">{{ asset.name }}</h1>
                    <Badge :variant="statusVariant(asset.status)">{{
                        asset.status_label
                    }}</Badge>
                </div>
                <p class="text-sm text-muted-foreground">
                    {{ asset.category }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Button variant="outline" as-child>
                    <Link :href="edit(asset.id)"
                        ><Pencil class="size-4" /> Edit</Link
                    >
                </Button>
                <Button variant="destructive" @click="deleteAsset">
                    <Trash2 class="size-4" /> Delete
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div
                    class="rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                >
                    <h2 class="mb-4 text-sm font-medium text-muted-foreground">
                        Technical specifications
                    </h2>
                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div v-for="spec in specs()" :key="spec.label">
                            <dt class="text-xs text-muted-foreground">
                                {{ spec.label }}
                            </dt>
                            <dd class="text-sm font-medium">
                                {{ spec.value }}
                            </dd>
                        </div>
                    </dl>
                    <div
                        v-if="asset.notes"
                        class="mt-4 border-t border-sidebar-border/70 pt-4 dark:border-sidebar-border"
                    >
                        <dt class="text-xs text-muted-foreground">Notes</dt>
                        <dd class="mt-1 text-sm whitespace-pre-line">
                            {{ asset.notes }}
                        </dd>
                    </div>
                </div>
            </div>

            <div
                class="rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
            >
                <h2 class="mb-4 text-sm font-medium text-muted-foreground">
                    Activity timeline
                </h2>

                <Deferred data="logs">
                    <template #fallback>
                        <div class="space-y-4">
                            <Skeleton
                                v-for="n in 4"
                                :key="n"
                                class="h-12 w-full rounded-md"
                            />
                        </div>
                    </template>

                    <ol
                        v-if="logs && logs.length"
                        class="relative space-y-6 border-l border-sidebar-border/70 pl-5 dark:border-sidebar-border"
                    >
                        <li v-for="log in logs" :key="log.id" class="relative">
                            <span
                                class="absolute top-1 -left-[23px] size-3 rounded-full bg-primary ring-4 ring-background"
                            />
                            <div class="flex items-center gap-2">
                                <Badge variant="outline">{{
                                    log.action_label
                                }}</Badge>
                                <span class="text-xs text-muted-foreground">{{
                                    formatDate(log.created_at)
                                }}</span>
                            </div>
                            <p class="mt-1 text-sm">{{ log.description }}</p>
                            <p
                                v-if="log.user"
                                class="text-xs text-muted-foreground"
                            >
                                by {{ log.user }}
                            </p>
                        </li>
                    </ol>
                    <p v-else class="text-sm text-muted-foreground">
                        No activity recorded yet.
                    </p>
                </Deferred>
            </div>
        </div>
    </div>
</template>
