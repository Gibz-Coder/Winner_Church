<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AssetForm from '@/components/assets/AssetForm.vue';
import Heading from '@/components/Heading.vue';
import { index as assetsIndex, create } from '@/routes/assets';
import type { AssetStatus, SelectOption } from '@/types';

defineProps<{
    categories: SelectOption<number>[];
    statuses: SelectOption<AssetStatus>[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Assets', href: assetsIndex() },
            { title: 'Create', href: create() },
        ],
    },
});
</script>

<template>
    <Head title="Add asset" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <Heading
            title="Add asset"
            description="Register a new church asset in the inventory."
        />
        <div class="max-w-3xl">
            <AssetForm :categories="categories" :statuses="statuses" />
        </div>
    </div>
</template>
