<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AssetController from '@/actions/App/Http/Controllers/AssetController';
import InputError from '@/components/InputError.vue';
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
import type { AssetFormData, AssetStatus, SelectOption } from '@/types';

const props = defineProps<{
    asset?: AssetFormData;
    categories: SelectOption<number>[];
    statuses: SelectOption<AssetStatus>[];
}>();

const isEditing = Boolean(props.asset?.id);

const form = useForm<AssetFormData>({
    category_id: props.asset?.category_id ?? null,
    name: props.asset?.name ?? '',
    serial_number: props.asset?.serial_number ?? null,
    model_number: props.asset?.model_number ?? null,
    brand: props.asset?.brand ?? null,
    purchase_date: props.asset?.purchase_date ?? null,
    cost: props.asset?.cost ?? null,
    status: props.asset?.status ?? 'available',
    current_location: props.asset?.current_location ?? null,
    notes: props.asset?.notes ?? null,
});

const submit = (): void => {
    if (isEditing && props.asset?.id) {
        form.submit(AssetController.update(props.asset.id));
        return;
    }

    form.submit(AssetController.store());
};
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="grid gap-2">
                <Label for="name">Asset name</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    required
                    placeholder="e.g. Sony A7 III"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="category_id">Category</Label>
                <Select v-model="form.category_id">
                    <SelectTrigger id="category_id" class="w-full">
                        <SelectValue placeholder="Select a category" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in categories"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.category_id" />
            </div>

            <div class="grid gap-2">
                <Label for="brand">Brand</Label>
                <Input
                    id="brand"
                    v-model="form.brand"
                    placeholder="e.g. Sony"
                />
                <InputError :message="form.errors.brand" />
            </div>

            <div class="grid gap-2">
                <Label for="status">Status</Label>
                <Select v-model="form.status">
                    <SelectTrigger id="status" class="w-full">
                        <SelectValue placeholder="Select a status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="option in statuses"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.status" />
            </div>

            <div class="grid gap-2">
                <Label for="serial_number">Serial number</Label>
                <Input
                    id="serial_number"
                    v-model="form.serial_number"
                    placeholder="Optional"
                />
                <InputError :message="form.errors.serial_number" />
            </div>

            <div class="grid gap-2">
                <Label for="model_number">Model number</Label>
                <Input
                    id="model_number"
                    v-model="form.model_number"
                    placeholder="Optional"
                />
                <InputError :message="form.errors.model_number" />
            </div>

            <div class="grid gap-2">
                <Label for="purchase_date">Purchase date</Label>
                <Input
                    id="purchase_date"
                    v-model="form.purchase_date"
                    type="date"
                />
                <InputError :message="form.errors.purchase_date" />
            </div>

            <div class="grid gap-2">
                <Label for="cost">Cost</Label>
                <Input
                    id="cost"
                    v-model="form.cost"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                />
                <InputError :message="form.errors.cost" />
            </div>

            <div class="grid gap-2 md:col-span-2">
                <Label for="current_location">Current location</Label>
                <Input
                    id="current_location"
                    v-model="form.current_location"
                    placeholder="e.g. Main Sanctuary"
                />
                <InputError :message="form.errors.current_location" />
            </div>

            <div class="grid gap-2 md:col-span-2">
                <Label for="notes">Notes</Label>
                <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="4"
                    class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 dark:bg-input/30"
                    placeholder="Any additional details about this asset"
                />
                <InputError :message="form.errors.notes" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <Button type="submit" :disabled="form.processing">
                {{ isEditing ? 'Update asset' : 'Create asset' }}
            </Button>
        </div>
    </form>
</template>
