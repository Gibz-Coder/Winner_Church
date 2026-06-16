<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import { ref, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { index as assetsIndex, create, show } from '@/routes/assets';
import type {
    AssetFilters,
    AssetListItem,
    AssetStatus,
    Paginated,
    SelectOption,
} from '@/types';
import type { BadgeVariants } from '@/components/ui/badge';

const props = defineProps<{
    assets: Paginated<AssetListItem>;
    filters: AssetFilters;
    categories: SelectOption<number>[];
    statuses: SelectOption<AssetStatus>[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Assets', href: assetsIndex() }],
    },
});

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? 'all');
const category = ref<number | 'all'>(props.filters.category ?? 'all');

const statusVariant = (value: AssetStatus): BadgeVariants['variant'] =>
    ({
        available: 'default',
        in_use: 'secondary',
        under_repair: 'outline',
        disposed: 'destructive',
    })[value];

let timeout: ReturnType<typeof setTimeout>;

watch([search, status, category], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(
            assetsIndex().url,
            {
                search: search.value || undefined,
                status: status.value === 'all' ? undefined : status.value,
                category: category.value === 'all' ? undefined : category.value,
            },
            { preserveState: true, replace: true, preserveScroll: true },
        );
    }, 300);
});
</script>

<template>
    <Head title="Assets" />

    <div class="flex h-full flex-1 flex-col gap-4 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold">Asset Inventory</h1>
                <p class="text-sm text-muted-foreground">Track and manage all church assets.</p>
            </div>
            <Button as-child>
                <Link :href="create()"><Plus class="size-4" /> Add asset</Link>
            </Button>
        </div>

        <div class="grid gap-3 md:grid-cols-3">
            <Input v-model="search" placeholder="Search by name, serial or brand..." />
            <Select v-model="status">
                <SelectTrigger class="w-full"><SelectValue placeholder="Status" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All statuses</SelectItem>
                    <SelectItem v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</SelectItem>
                </SelectContent>
            </Select>
            <Select v-model="category">
                <SelectTrigger class="w-full"><SelectValue placeholder="Category" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All categories</SelectItem>
                    <SelectItem v-for="c in categories" :key="c.value" :value="c.value">{{ c.label }}</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Location</th>
                        <th class="px-4 py-3 font-medium">Serial</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="asset in assets.data"
                        :key="asset.id"
                        class="cursor-pointer border-t border-sidebar-border/70 transition-colors hover:bg-muted/50 dark:border-sidebar-border"
                        @click="router.visit(show(asset.id).url)"
                    >
                        <td class="px-4 py-3 font-medium">{{ asset.name }}<span class="block text-xs text-muted-foreground">{{ asset.brand }}</span></td>
                        <td class="px-4 py-3">{{ asset.category }}</td>
                        <td class="px-4 py-3">{{ asset.current_location ?? '—' }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ asset.serial_number ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <Badge :variant="statusVariant(asset.status)">{{ asset.status_label }}</Badge>
                        </td>
                    </tr>
                    <tr v-if="assets.data.length === 0">
                        <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">No assets found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="assets.last_page > 1" class="flex flex-wrap items-center gap-1">
            <template v-for="link in assets.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md px-3 py-1.5 text-sm transition-colors hover:bg-muted"
                    :class="{ 'bg-primary text-primary-foreground hover:bg-primary': link.active }"
                    v-html="link.label"
                />
                <span v-else class="px-3 py-1.5 text-sm text-muted-foreground" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
