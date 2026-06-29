<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Plus, Check, X, RotateCcw, Calendar, User, Shield } from '@lucide/vue';
import { ref, computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { BadgeVariants } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Borrow / Return', href: '/borrow-requests' }],
    },
});

const props = defineProps<{
    requests: {
        data: Array<{
            id: number;
            asset_id: number;
            user_id: number;
            status: string;
            borrow_date: string | null;
            expected_return_date: string | null;
            return_date: string | null;
            borrow_condition: string | null;
            return_condition: string | null;
            remarks: string | null;
            authorized_by: number | null;
            asset: { name: string; serial_number: string | null } | null;
            user: { name: string } | null;
            authorized_by_user?: { name: string } | null;
        }>;
        links: any[];
        last_page: number;
    };
    assets: Array<{ value: number; label: string }>;
}>();

const page = usePage();
const isAdmin = computed(() => {
    const roles = page.props.auth?.user?.roles as string[] | undefined;

    return roles?.includes('admin') ?? false;
});

// Modals State
const showRequestModal = ref(false);
const showRejectModal = ref(false);
const showReturnModal = ref(false);
const selectedRequest = ref<any>(null);

// Forms
const requestForm = useForm({
    asset_id: '',
    borrow_date: '',
    expected_return_date: '',
    borrow_condition: '',
});

const rejectForm = useForm({
    remarks: '',
});

const returnForm = useForm({
    return_condition: '',
    remarks: '',
});

// Actions
const submitRequest = () => {
    requestForm.post('/borrow-requests', {
        onSuccess: () => {
            showRequestModal.value = false;
            requestForm.reset();
        },
    });
};

const approveRequest = (id: number) => {
    if (confirm('Are you sure you want to approve this borrow request?')) {
        const form = useForm({});
        form.post(`/borrow-requests/${id}/approve`);
    }
};

const openRejectModal = (request: any) => {
    selectedRequest.value = request;
    rejectForm.remarks = '';
    showRejectModal.value = true;
};

const submitReject = () => {
    if (!selectedRequest.value) {
        return;
    }

    rejectForm.post(`/borrow-requests/${selectedRequest.value.id}/reject`, {
        onSuccess: () => {
            showRejectModal.value = false;
            selectedRequest.value = null;
            rejectForm.reset();
        },
    });
};

const openReturnModal = (request: any) => {
    selectedRequest.value = request;
    returnForm.return_condition = request.borrow_condition || '';
    returnForm.remarks = '';
    showReturnModal.value = true;
};

const submitReturn = () => {
    if (!selectedRequest.value) {
        return;
    }

    returnForm.post(`/borrow-requests/${selectedRequest.value.id}/return`, {
        onSuccess: () => {
            showReturnModal.value = false;
            selectedRequest.value = null;
            returnForm.reset();
        },
    });
};

// Filter tab state
const activeTab = ref('all');

const filteredRequests = computed(() => {
    if (activeTab.value === 'all') {
        return props.requests.data;
    }

    return props.requests.data.filter((r) => r.status === activeTab.value);
});

const statusVariant = (status: string): BadgeVariants['variant'] => {
    return (
        (
            {
                pending: 'outline',
                approved: 'default',
                returned: 'secondary',
                rejected: 'destructive',
            } as Record<string, BadgeVariants['variant']>
        )[status] || 'default'
    );
};

const formatDate = (dateStr: string | null) => {
    if (!dateStr) {
        return '—';
    }

    return new Date(dateStr).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Borrow Requests" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    Borrow & Return Workflow
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Track and manage borrowings, status approvals, and returns
                    inspections.
                </p>
            </div>
            <Button
                v-if="!isAdmin"
                @click="showRequestModal = true"
                class="flex h-9 items-center gap-1.5 bg-indigo-600 text-xs text-white hover:bg-indigo-700"
            >
                <Plus class="size-4" /> Request Asset
            </Button>
        </div>

        <!-- Tabs & Filters -->
        <div
            class="flex flex-wrap gap-2 border-b border-neutral-200 pb-2 dark:border-neutral-800"
        >
            <button
                v-for="tab in [
                    'all',
                    'pending',
                    'approved',
                    'returned',
                    'rejected',
                ]"
                :key="tab"
                @click="activeTab = tab"
                class="rounded-lg px-3 py-1.5 text-xs font-semibold tracking-wider uppercase transition-colors"
                :class="
                    activeTab === tab
                        ? 'bg-indigo-600 text-white'
                        : 'text-neutral-500 hover:bg-neutral-100 dark:hover:bg-neutral-800'
                "
            >
                {{ tab }}
            </button>
        </div>

        <!-- Requests Grid/Table -->
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200/80 bg-white shadow-xs dark:border-neutral-800 dark:bg-neutral-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-neutral-50/70 text-xs font-bold tracking-wider text-neutral-400 uppercase dark:bg-neutral-950/20"
                >
                    <tr>
                        <th class="px-6 py-4">Asset Details</th>
                        <th class="px-6 py-4">Requested By</th>
                        <th class="px-6 py-4">Timeline</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Conditions & Remarks</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-neutral-100 dark:divide-neutral-800"
                >
                    <tr
                        v-for="req in filteredRequests"
                        :key="req.id"
                        class="transition-colors hover:bg-neutral-50/50 dark:hover:bg-neutral-950/10"
                    >
                        <td class="px-6 py-4">
                            <span
                                class="font-bold text-neutral-900 dark:text-neutral-100"
                                >{{ req.asset?.name }}</span
                            >
                            <span
                                class="block font-mono text-xs text-neutral-400"
                                >{{
                                    req.asset?.serial_number ?? 'No serial'
                                }}</span
                            >
                        </td>
                        <td
                            class="px-6 py-4 font-medium text-neutral-700 dark:text-neutral-300"
                        >
                            {{ req.user?.name }}
                        </td>
                        <td
                            class="space-y-1 px-6 py-4 text-xs text-neutral-500 dark:text-neutral-400"
                        >
                            <div class="flex items-center gap-1.5">
                                <Calendar class="size-3.5" />
                                <span
                                    >Borrow:
                                    {{ formatDate(req.borrow_date) }}</span
                                >
                            </div>
                            <div
                                class="flex items-center gap-1.5 text-amber-600 dark:text-amber-500"
                            >
                                <Calendar class="size-3.5" />
                                <span
                                    >Expected:
                                    {{
                                        formatDate(req.expected_return_date)
                                    }}</span
                                >
                            </div>
                            <div
                                v-if="req.return_date"
                                class="flex items-center gap-1.5 text-emerald-600"
                            >
                                <Calendar class="size-3.5" />
                                <span
                                    >Returned:
                                    {{ formatDate(req.return_date) }}</span
                                >
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <Badge
                                :variant="statusVariant(req.status)"
                                class="font-bold capitalize"
                                >{{ req.status }}</Badge
                            >
                        </td>
                        <td
                            class="space-y-1 px-6 py-4 text-xs text-neutral-500 dark:text-neutral-400"
                        >
                            <div>
                                <span
                                    class="font-semibold text-neutral-700 dark:text-neutral-300"
                                    >Borrow condition:</span
                                >
                                {{ req.borrow_condition }}
                            </div>
                            <div v-if="req.return_condition">
                                <span
                                    class="font-semibold text-neutral-700 dark:text-neutral-300"
                                    >Return condition:</span
                                >
                                {{ req.return_condition }}
                            </div>
                            <div
                                v-if="req.remarks"
                                class="text-rose-500 italic"
                            >
                                <span
                                    class="font-semibold text-neutral-700 dark:text-neutral-300"
                                    >Remarks:</span
                                >
                                {{ req.remarks }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Approve / Reject buttons for Admin when pending -->
                                <template
                                    v-if="isAdmin && req.status === 'pending'"
                                >
                                    <Button
                                        @click="approveRequest(req.id)"
                                        size="sm"
                                        class="size-8 bg-emerald-600 p-0 text-white hover:bg-emerald-700"
                                        title="Approve"
                                    >
                                        <Check class="size-4" />
                                    </Button>
                                    <Button
                                        @click="openRejectModal(req)"
                                        size="sm"
                                        class="size-8 bg-rose-600 p-0 text-white hover:bg-rose-700"
                                        title="Reject"
                                    >
                                        <X class="size-4" />
                                    </Button>
                                </template>

                                <!-- Process Return button for Admin when approved (borrowed) -->
                                <template
                                    v-if="isAdmin && req.status === 'approved'"
                                >
                                    <Button
                                        @click="openReturnModal(req)"
                                        size="sm"
                                        class="flex h-8 items-center gap-1 bg-indigo-600 px-3 text-xs font-semibold text-white hover:bg-indigo-700"
                                    >
                                        <RotateCcw class="size-3.5" /> Process
                                        Return
                                    </Button>
                                </template>

                                <span
                                    v-if="!isAdmin && req.status !== 'pending'"
                                    class="text-xs text-neutral-400"
                                    >No actions</span
                                >
                            </div>
                        </td>
                    </tr>
                    <tr v-if="filteredRequests.length === 0">
                        <td
                            colspan="6"
                            class="px-6 py-12 text-center text-neutral-500"
                        >
                            No requests found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Request Modal (Member) -->
        <div
            v-if="showRequestModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Submit Borrow Request
                </h3>
                <form @submit.prevent="submitRequest" class="mt-4 space-y-4">
                    <div>
                        <Label for="asset_id">Select Asset</Label>
                        <Select v-model="requestForm.asset_id">
                            <SelectTrigger class="mt-1 w-full"
                                ><SelectValue
                                    placeholder="Choose an available asset"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="a in assets"
                                    :key="a.value"
                                    :value="a.value.toString()"
                                >
                                    {{ a.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <span
                            v-if="requestForm.errors.asset_id"
                            class="text-xs text-rose-600"
                            >{{ requestForm.errors.asset_id }}</span
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label for="borrow_date">Borrow Date</Label>
                            <Input
                                id="borrow_date"
                                type="datetime-local"
                                v-model="requestForm.borrow_date"
                                required
                                class="mt-1"
                            />
                            <span
                                v-if="requestForm.errors.borrow_date"
                                class="text-xs text-rose-600"
                                >{{ requestForm.errors.borrow_date }}</span
                            >
                        </div>
                        <div>
                            <Label for="expected_return_date"
                                >Expected Return</Label
                            >
                            <Input
                                id="expected_return_date"
                                type="datetime-local"
                                v-model="requestForm.expected_return_date"
                                required
                                class="mt-1"
                            />
                            <span
                                v-if="requestForm.errors.expected_return_date"
                                class="text-xs text-rose-600"
                                >{{
                                    requestForm.errors.expected_return_date
                                }}</span
                            >
                        </div>
                    </div>

                    <div>
                        <Label for="borrow_condition">Initial Condition</Label>
                        <Input
                            id="borrow_condition"
                            v-model="requestForm.borrow_condition"
                            placeholder="e.g. Working, fully functional, no damage"
                            required
                            class="mt-1"
                        />
                        <span
                            v-if="requestForm.errors.borrow_condition"
                            class="text-xs text-rose-600"
                            >{{ requestForm.errors.borrow_condition }}</span
                        >
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="showRequestModal = false"
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="requestForm.processing"
                            >Submit</Button
                        >
                    </div>
                </form>
            </div>
        </div>

        <!-- Reject Modal (Admin) -->
        <div
            v-if="showRejectModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Reject Borrow Request
                </h3>
                <form @submit.prevent="submitReject" class="mt-4 space-y-4">
                    <div>
                        <Label for="reject-remarks">Reason for Rejection</Label>
                        <textarea
                            id="reject-remarks"
                            v-model="rejectForm.remarks"
                            rows="3"
                            placeholder="State why this borrow request was rejected..."
                            required
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="
                                showRejectModal = false;
                                selectedRequest = null;
                            "
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-rose-600 text-white hover:bg-rose-700"
                            :disabled="rejectForm.processing"
                            >Reject Request</Button
                        >
                    </div>
                </form>
            </div>
        </div>

        <!-- Return Modal (Admin) -->
        <div
            v-if="showReturnModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Process Asset Return
                </h3>
                <form @submit.prevent="submitReturn" class="mt-4 space-y-4">
                    <div>
                        <Label for="return_condition">Return Condition</Label>
                        <Input
                            id="return_condition"
                            v-model="returnForm.return_condition"
                            placeholder="e.g. Returned in same excellent condition, clean"
                            required
                            class="mt-1"
                        />
                        <span
                            v-if="returnForm.errors.return_condition"
                            class="text-xs text-rose-600"
                            >{{ returnForm.errors.return_condition }}</span
                        >
                    </div>

                    <div>
                        <Label for="return-remarks">Remarks (optional)</Label>
                        <textarea
                            id="return-remarks"
                            v-model="returnForm.remarks"
                            rows="3"
                            placeholder="Add administrative remarks regarding the return..."
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="
                                showReturnModal = false;
                                selectedRequest = null;
                            "
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="returnForm.processing"
                            >Mark Returned</Button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
