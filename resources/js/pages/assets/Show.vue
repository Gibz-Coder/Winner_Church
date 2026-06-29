<script setup lang="ts">
import {
    Deferred,
    Head,
    Link,
    router,
    usePage,
    useForm,
} from '@inertiajs/vue3';
import { Pencil, Trash2, ArrowLeftRight } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { BadgeVariants } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Skeleton } from '@/components/ui/skeleton';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as assetsIndex, show, edit, destroy } from '@/routes/assets';
import type { AssetDetail, AssetLog } from '@/types';

defineOptions({
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
});

const props = defineProps<{
    asset: AssetDetail & {
        assigned_ministry: string | null;
        description: string | null;
        qr_code: string | null;
        image: string | null;
    };
    logs?: AssetLog[];
}>();

const page = usePage();
const isAdmin = computed(() => {
    const roles = page.props.auth?.user?.roles as string[] | undefined;

    return roles?.includes('admin') ?? false;
});

const statusVariant = (value: string): BadgeVariants['variant'] =>
    (
        ({
            available: 'default',
            borrowed: 'secondary',
            reserved: 'outline',
            under_maintenance: 'destructive',
            lost: 'destructive',
            disposed: 'destructive',
        }) as Record<string, BadgeVariants['variant']>
    )[value] || 'default';

const specs = (): { label: string; value: string }[] => [
    { label: 'Category', value: props.asset.category },
    { label: 'Brand', value: props.asset.brand ?? '—' },
    { label: 'Serial number', value: props.asset.serial_number ?? '—' },
    { label: 'Model number', value: props.asset.model_number ?? '—' },
    { label: 'Ministry Assigned', value: props.asset.assigned_ministry ?? '—' },
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

// Borrow Request Form modal for member
const showBorrowModal = ref(false);
const borrowForm = useForm({
    asset_id: props.asset.id,
    borrow_date: '',
    expected_return_date: '',
    borrow_condition: 'Excellent',
});

const submitBorrowRequest = () => {
    borrowForm.post('/borrow-requests', {
        onSuccess: () => {
            showBorrowModal.value = false;
            borrowForm.reset();
        },
    });
};
</script>

<template>
    <Head :title="asset.name" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h1
                        class="text-xl font-semibold text-neutral-900 dark:text-neutral-50"
                    >
                        {{ asset.name }}
                    </h1>
                    <Badge :variant="statusVariant(asset.status)">{{
                        asset.status_label
                    }}</Badge>
                </div>
                <p class="text-sm text-muted-foreground">
                    {{ asset.category }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Request to Borrow (Members) -->
                <Button
                    v-if="!isAdmin && asset.status === 'available'"
                    @click="showBorrowModal = true"
                    class="flex items-center gap-1 bg-indigo-600 text-xs text-white hover:bg-indigo-700"
                >
                    <ArrowLeftRight class="size-4" /> Request to Borrow
                </Button>

                <!-- Admin Controls -->
                <template v-if="isAdmin">
                    <Button variant="outline" as-child>
                        <Link :href="edit(asset.id)"
                            ><Pencil class="size-4" /> Edit</Link
                        >
                    </Button>
                    <Button variant="destructive" @click="deleteAsset">
                        <Trash2 class="size-4" /> Delete
                    </Button>
                </template>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <!-- Specifications Card -->
                <div
                    class="rounded-xl border border-sidebar-border/70 bg-white p-5 dark:border-sidebar-border dark:bg-neutral-900"
                >
                    <h2
                        class="mb-4 text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                    >
                        Asset Specifications
                    </h2>
                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div
                            v-for="spec in specs()"
                            :key="spec.label"
                            class="border-b border-neutral-100 pb-2 dark:border-neutral-800/60"
                        >
                            <dt class="text-xs text-muted-foreground">
                                {{ spec.label }}
                            </dt>
                            <dd
                                class="mt-0.5 text-sm font-bold text-neutral-900 dark:text-neutral-100"
                            >
                                {{ spec.value }}
                            </dd>
                        </div>
                    </dl>

                    <div
                        v-if="asset.description"
                        class="mt-4 border-t border-sidebar-border/70 pt-4 dark:border-sidebar-border"
                    >
                        <dt class="text-xs text-muted-foreground">
                            Description
                        </dt>
                        <dd
                            class="mt-1 text-sm text-neutral-700 dark:text-neutral-300"
                        >
                            {{ asset.description }}
                        </dd>
                    </div>

                    <div
                        v-if="asset.notes"
                        class="mt-4 border-t border-sidebar-border/70 pt-4 dark:border-sidebar-border"
                    >
                        <dt class="text-xs text-muted-foreground">Notes</dt>
                        <dd
                            class="mt-1 text-sm whitespace-pre-line text-neutral-600 dark:text-neutral-400"
                        >
                            {{ asset.notes }}
                        </dd>
                    </div>
                </div>

                <!-- QR Code Card -->
                <div class="grid gap-6 sm:grid-cols-2">
                    <div
                        class="flex flex-col items-center justify-center rounded-xl border border-sidebar-border/70 bg-white p-5 text-center dark:border-sidebar-border dark:bg-neutral-900"
                    >
                        <h3
                            class="mb-3 text-xs font-semibold tracking-wider text-neutral-400 uppercase"
                        >
                            Asset QR Code
                        </h3>
                        <img
                            v-if="asset.qr_code"
                            :src="asset.qr_code"
                            alt="Asset QR Code"
                            class="size-36 rounded-lg border bg-white p-2"
                        />
                        <span v-else class="text-xs text-neutral-400 italic"
                            >No QR code generated.</span
                        >
                        <p class="mt-2 text-[10px] text-neutral-400">
                            Scan QR code to open details page on any mobile
                            device.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Activity Logs Panel -->
            <div
                class="rounded-xl border border-sidebar-border/70 bg-white p-5 dark:border-sidebar-border dark:bg-neutral-900"
            >
                <h2
                    class="mb-4 text-sm font-semibold text-neutral-800 dark:text-neutral-200"
                >
                    Activity history
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
                        <li
                            v-for="log in logs"
                            :key="log.id"
                            class="group relative"
                        >
                            <span
                                class="absolute top-1 -left-[23px] size-3 rounded-full bg-indigo-600 ring-4 ring-white dark:ring-neutral-900"
                            />
                            <div class="flex items-center gap-2">
                                <Badge
                                    variant="outline"
                                    class="text-[10px] font-bold capitalize"
                                    >{{ log.action_label }}</Badge
                                >
                                <span
                                    class="text-[10px] text-muted-foreground"
                                    >{{ formatDate(log.created_at) }}</span
                                >
                            </div>
                            <p
                                class="mt-1 text-xs text-neutral-700 dark:text-neutral-300"
                            >
                                {{ log.description }}
                            </p>
                            <p
                                v-if="log.user"
                                class="mt-0.5 text-[9px] font-semibold text-muted-foreground"
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

        <!-- Request to Borrow Modal (Members) -->
        <div
            v-if="showBorrowModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Request to Borrow
                </h3>
                <form
                    @submit.prevent="submitBorrowRequest"
                    class="mt-4 space-y-4"
                >
                    <div>
                        <Label>Asset Name</Label>
                        <div
                            class="mt-1 rounded-lg border border-neutral-200/60 bg-neutral-50 p-2.5 text-sm font-semibold text-neutral-700 dark:border-neutral-800 dark:bg-neutral-950/20 dark:text-neutral-300"
                        >
                            {{ asset.name }} ({{
                                asset.serial_number ?? 'No serial'
                            }})
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label for="borrow_date">Borrow Date</Label>
                            <Input
                                id="borrow_date"
                                type="datetime-local"
                                v-model="borrowForm.borrow_date"
                                required
                                class="mt-1"
                            />
                            <span
                                v-if="borrowForm.errors.borrow_date"
                                class="text-xs text-rose-600"
                                >{{ borrowForm.errors.borrow_date }}</span
                            >
                        </div>
                        <div>
                            <Label for="expected_return_date"
                                >Expected Return</Label
                            >
                            <Input
                                id="expected_return_date"
                                type="datetime-local"
                                v-model="borrowForm.expected_return_date"
                                required
                                class="mt-1"
                            />
                            <span
                                v-if="borrowForm.errors.expected_return_date"
                                class="text-xs text-rose-600"
                                >{{
                                    borrowForm.errors.expected_return_date
                                }}</span
                            >
                        </div>
                    </div>

                    <div>
                        <Label for="borrow_condition">Initial Condition</Label>
                        <Input
                            id="borrow_condition"
                            v-model="borrowForm.borrow_condition"
                            required
                            class="mt-1"
                        />
                        <span
                            v-if="borrowForm.errors.borrow_condition"
                            class="text-xs text-rose-600"
                            >{{ borrowForm.errors.borrow_condition }}</span
                        >
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="showBorrowModal = false"
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="borrowForm.processing"
                            >Submit Request</Button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
