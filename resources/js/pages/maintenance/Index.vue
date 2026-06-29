<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Wrench,
    Shield,
    Check,
    X,
    Calendar,
    DollarSign,
} from '@lucide/vue';
import { ref } from 'vue';
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
        breadcrumbs: [{ title: 'Maintenance Logs', href: '/maintenance' }],
    },
});

const props = defineProps<{
    logs: {
        data: Array<{
            id: number;
            asset_id: number;
            maintenance_type: string;
            description: string;
            cost: string | null;
            start_date: string;
            end_date: string | null;
            status: string;
            performed_by: string | null;
            asset: { name: string; serial_number: string | null } | null;
        }>;
        links: any[];
    };
    assets: Array<{ value: number; label: string }>;
}>();

// Modals State
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedLog = ref<any>(null);

// Forms
const addForm = useForm({
    asset_id: '',
    maintenance_type: '',
    description: '',
    cost: '',
    start_date: new Date().toISOString().split('T')[0],
    performed_by: '',
    status: 'scheduled',
});

const editForm = useForm({
    status: '',
    cost: '',
    end_date: new Date().toISOString().split('T')[0],
    description: '',
    performed_by: '',
});

// Actions
const submitAdd = () => {
    addForm.post('/maintenance', {
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        },
    });
};

const openEditModal = (log: any) => {
    selectedLog.value = log;
    editForm.status = log.status;
    editForm.cost = log.cost || '';
    editForm.end_date = log.end_date || new Date().toISOString().split('T')[0];
    editForm.description = log.description;
    editForm.performed_by = log.performed_by || '';
    showEditModal.value = true;
};

const submitEdit = () => {
    if (!selectedLog.value) {
        return;
    }

    editForm.put(`/maintenance/${selectedLog.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
            selectedLog.value = null;
            editForm.reset();
        },
    });
};

const deleteLog = (id: number) => {
    if (confirm('Are you sure you want to delete this maintenance record?')) {
        const form = useForm({});
        form.delete(`/maintenance/${id}`);
    }
};

const statusVariant = (status: string): BadgeVariants['variant'] => {
    return (
        (
            {
                scheduled: 'outline',
                in_progress: 'default',
                completed: 'secondary',
                cancelled: 'destructive',
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
    <Head title="Maintenance Logs" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    Maintenance & Servicing
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Schedule checkups, record costs, and log repairs for church
                    assets.
                </p>
            </div>
            <Button
                @click="showAddModal = true"
                class="flex h-9 items-center gap-1.5 bg-indigo-600 text-xs text-white hover:bg-indigo-700"
            >
                <Plus class="size-4" /> Schedule Maintenance
            </Button>
        </div>

        <!-- Maintenance Logs List -->
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200/80 bg-white shadow-xs dark:border-neutral-800 dark:bg-neutral-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-neutral-50/70 text-xs font-bold tracking-wider text-neutral-400 uppercase dark:bg-neutral-950/20"
                >
                    <tr>
                        <th class="px-6 py-4">Asset Details</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Timeline</th>
                        <th class="px-6 py-4">Status & Cost</th>
                        <th class="px-6 py-4">Performed By</th>
                        <th class="px-6 py-4 text-right">Actions</th>
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
                                >{{ log.asset?.name }}</span
                            >
                            <span
                                class="block font-mono text-xs text-neutral-400"
                                >{{
                                    log.asset?.serial_number ?? 'No serial'
                                }}</span
                            >
                        </td>
                        <td
                            class="px-6 py-4 font-semibold text-neutral-700 capitalize dark:text-neutral-300"
                        >
                            {{ log.maintenance_type }}
                        </td>
                        <td
                            class="max-w-xs truncate px-6 py-4 text-neutral-500 dark:text-neutral-400"
                        >
                            {{ log.description }}
                        </td>
                        <td
                            class="space-y-1 px-6 py-4 text-xs text-neutral-500 dark:text-neutral-400"
                        >
                            <div class="flex items-center gap-1.5">
                                <Calendar class="size-3.5" />
                                <span
                                    >Start:
                                    {{ formatDate(log.start_date) }}</span
                                >
                            </div>
                            <div
                                v-if="log.end_date"
                                class="flex items-center gap-1.5 text-emerald-600"
                            >
                                <Calendar class="size-3.5" />
                                <span>End: {{ formatDate(log.end_date) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <Badge
                                    :variant="statusVariant(log.status)"
                                    class="w-fit font-bold capitalize"
                                    >{{ log.status }}</Badge
                                >
                                <span
                                    class="text-xs font-semibold text-neutral-600 dark:text-neutral-300"
                                >
                                    {{ log.cost ? `$${log.cost}` : '—' }}
                                </span>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 font-medium text-neutral-600 dark:text-neutral-300"
                        >
                            {{ log.performed_by ?? 'Internal Staff' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <Button
                                    @click="openEditModal(log)"
                                    size="sm"
                                    class="flex h-8 items-center gap-1 bg-indigo-600 px-3 text-xs font-semibold text-white hover:bg-indigo-700"
                                >
                                    Update Status
                                </Button>
                                <Button
                                    @click="deleteLog(log.id)"
                                    variant="outline"
                                    class="size-8 border-rose-100 bg-rose-50/20 p-0 hover:border-rose-300 hover:bg-rose-50"
                                >
                                    <X class="size-4 text-rose-600" />
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="logs.data.length === 0">
                        <td
                            colspan="7"
                            class="px-6 py-12 text-center text-neutral-500"
                        >
                            No maintenance logs recorded.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Schedule Maintenance Modal -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Schedule Asset Maintenance
                </h3>
                <form @submit.prevent="submitAdd" class="mt-4 space-y-4">
                    <div>
                        <Label for="asset_id">Select Asset</Label>
                        <Select v-model="addForm.asset_id">
                            <SelectTrigger class="mt-1 w-full"
                                ><SelectValue
                                    placeholder="Choose asset to inspect/repair"
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
                            v-if="addForm.errors.asset_id"
                            class="text-xs text-rose-600"
                            >{{ addForm.errors.asset_id }}</span
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label for="maintenance_type">Service Type</Label>
                            <Select v-model="addForm.maintenance_type">
                                <SelectTrigger class="mt-1 w-full"
                                    ><SelectValue placeholder="Type"
                                /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="preventive"
                                        >Preventive</SelectItem
                                    >
                                    <SelectItem value="repair"
                                        >Repair</SelectItem
                                    >
                                    <SelectItem value="calibration"
                                        >Calibration</SelectItem
                                    >
                                    <SelectItem value="inspection"
                                        >Inspection</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                            <span
                                v-if="addForm.errors.maintenance_type"
                                class="text-xs text-rose-600"
                                >{{ addForm.errors.maintenance_type }}</span
                            >
                        </div>
                        <div>
                            <Label for="status">Initial Status</Label>
                            <Select v-model="addForm.status">
                                <SelectTrigger class="mt-1 w-full"
                                    ><SelectValue placeholder="Status"
                                /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="scheduled"
                                        >Scheduled</SelectItem
                                    >
                                    <SelectItem value="in_progress"
                                        >In Progress</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                            <span
                                v-if="addForm.errors.status"
                                class="text-xs text-rose-600"
                                >{{ addForm.errors.status }}</span
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label for="start_date">Start Date</Label>
                            <Input
                                id="start_date"
                                type="date"
                                v-model="addForm.start_date"
                                required
                                class="mt-1"
                            />
                            <span
                                v-if="addForm.errors.start_date"
                                class="text-xs text-rose-600"
                                >{{ addForm.errors.start_date }}</span
                            >
                        </div>
                        <div>
                            <Label for="cost">Estimated Cost ($)</Label>
                            <Input
                                id="cost"
                                type="number"
                                step="0.01"
                                v-model="addForm.cost"
                                placeholder="0.00"
                                class="mt-1"
                            />
                            <span
                                v-if="addForm.errors.cost"
                                class="text-xs text-rose-600"
                                >{{ addForm.errors.cost }}</span
                            >
                        </div>
                    </div>

                    <div>
                        <Label for="performed_by">Serviced By</Label>
                        <Input
                            id="performed_by"
                            v-model="addForm.performed_by"
                            placeholder="e.g. Service agency or staff name"
                            class="mt-1"
                        />
                        <span
                            v-if="addForm.errors.performed_by"
                            class="text-xs text-rose-600"
                            >{{ addForm.errors.performed_by }}</span
                        >
                    </div>

                    <div>
                        <Label for="description"
                            >Maintenance Goal / Description</Label
                        >
                        <textarea
                            id="description"
                            v-model="addForm.description"
                            rows="3"
                            placeholder="Describe what repair or service needs to be performed..."
                            required
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
                        <span
                            v-if="addForm.errors.description"
                            class="text-xs text-rose-600"
                            >{{ addForm.errors.description }}</span
                        >
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="showAddModal = false"
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="addForm.processing"
                            >Create Log</Button
                        >
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Maintenance Status Modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div
                class="w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
            >
                <h3
                    class="text-lg font-bold text-neutral-950 dark:text-neutral-50"
                >
                    Update Servicing Status
                </h3>
                <form @submit.prevent="submitEdit" class="mt-4 space-y-4">
                    <div>
                        <Label for="edit-status">Current Status</Label>
                        <Select v-model="editForm.status">
                            <SelectTrigger class="mt-1 w-full"
                                ><SelectValue placeholder="Status"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem value="scheduled"
                                    >Scheduled</SelectItem
                                >
                                <SelectItem value="in_progress"
                                    >In Progress</SelectItem
                                >
                                <SelectItem value="completed"
                                    >Completed</SelectItem
                                >
                                <SelectItem value="cancelled"
                                    >Cancelled</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <span
                            v-if="editForm.errors.status"
                            class="text-xs text-rose-600"
                            >{{ editForm.errors.status }}</span
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label for="edit-cost">Final Cost ($)</Label>
                            <Input
                                id="edit-cost"
                                type="number"
                                step="0.01"
                                v-model="editForm.cost"
                                class="mt-1"
                            />
                            <span
                                v-if="editForm.errors.cost"
                                class="text-xs text-rose-600"
                                >{{ editForm.errors.cost }}</span
                            >
                        </div>
                        <div>
                            <Label for="edit-end_date">End Date</Label>
                            <Input
                                id="edit-end_date"
                                type="date"
                                v-model="editForm.end_date"
                                class="mt-1"
                            />
                            <span
                                v-if="editForm.errors.end_date"
                                class="text-xs text-rose-600"
                                >{{ editForm.errors.end_date }}</span
                            >
                        </div>
                    </div>

                    <div>
                        <Label for="edit-performed_by"
                            >Contractor / Technician</Label
                        >
                        <Input
                            id="edit-performed_by"
                            v-model="editForm.performed_by"
                            class="mt-1"
                        />
                        <span
                            v-if="editForm.errors.performed_by"
                            class="text-xs text-rose-600"
                            >{{ editForm.errors.performed_by }}</span
                        >
                    </div>

                    <div>
                        <Label for="edit-description"
                            >Description / Resolution Remarks</Label
                        >
                        <textarea
                            id="edit-description"
                            v-model="editForm.description"
                            rows="3"
                            required
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
                        <span
                            v-if="editForm.errors.description"
                            class="text-xs text-rose-600"
                            >{{ editForm.errors.description }}</span
                        >
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="
                                showEditModal = false;
                                selectedLog = null;
                            "
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="editForm.processing"
                            >Save Changes</Button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
