<script lang="ts">
import { h } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as assetsIndex, edit, show } from '@/routes/assets';

export default {
    layout: (h: any, page: any) => {
        return h(
            AppLayout,
            {
                breadcrumbs: [
                    { title: 'Assets', href: assetsIndex() },
                    { title: 'Details', href: show(page.props.asset.id!) },
                    { title: 'Edit', href: edit(page.props.asset.id!) },
                ],
            },
            () => page,
        );
    },
};
</script>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AssetForm from '@/components/assets/AssetForm.vue';
import Heading from '@/components/Heading.vue';
import type { AssetFormData, AssetStatus, SelectOption } from '@/types';

const props = defineProps<{
    asset: AssetFormData;
    categories: SelectOption<number>[];
    statuses: SelectOption<AssetStatus>[];
}>();
</script>

<template>
    <Head title="Edit asset" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <Heading
            :title="`Edit ${asset.name}`"
            description="Update the details of this asset."
        />
        <div class="max-w-3xl">
            <AssetForm
                :asset="asset"
                :categories="categories"
                :statuses="statuses"
            />
        </div>
    </div>
</template>
