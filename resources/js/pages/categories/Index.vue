<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Folder } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Categories', href: '/categories' }],
    },
});

const props = defineProps<{
    categories: Array<{
        id: number;
        name: string;
        slug: string;
        description: string | null;
        assets_count: number;
    }>;
}>();

// State for modal
const showAddModal = ref(false);
const showEditModal = ref(false);
const editingCategory = ref<any>(null);

const addForm = useForm({
    name: '',
    description: '',
});

const editForm = useForm({
    name: '',
    description: '',
});

const submitAdd = () => {
    addForm.post('/categories', {
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        },
    });
};

const openEditModal = (category: any) => {
    editingCategory.value = category;
    editForm.name = category.name;
    editForm.description = category.description || '';
    showEditModal.value = true;
};

const submitEdit = () => {
    if (!editingCategory.value) {
        return;
    }

    editForm.put(`/categories/${editingCategory.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
            editingCategory.value = null;
            editForm.reset();
        },
    });
};

const deleteCategory = (id: number) => {
    if (
        confirm(
            'Are you sure you want to delete this category? All assets inside it will be affected.',
        )
    ) {
        const form = useForm({});
        form.delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Categories" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    Category Management
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Organize assets by types and departments.
                </p>
            </div>
            <Button
                @click="showAddModal = true"
                class="flex h-9 items-center gap-1.5 bg-indigo-600 text-xs text-white hover:bg-indigo-700"
            >
                <Plus class="size-4" /> Add Category
            </Button>
        </div>

        <!-- Category Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="cat in categories"
                :key="cat.id"
                class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-neutral-200/80 bg-white p-5 shadow-xs transition-all duration-300 hover:shadow-md dark:border-neutral-800/80 dark:bg-neutral-900"
            >
                <div>
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex size-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400"
                            >
                                <Folder class="size-5" />
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-neutral-950 dark:text-neutral-50"
                                >
                                    {{ cat.name }}
                                </h3>
                                <span
                                    class="font-mono text-[10px] text-neutral-400"
                                    >{{ cat.slug }}</span
                                >
                            </div>
                        </div>
                        <span
                            class="rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs font-bold text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400"
                        >
                            {{ cat.assets_count }} items
                        </span>
                    </div>
                    <p
                        class="mt-4 text-xs leading-relaxed text-neutral-500 dark:text-neutral-400"
                    >
                        {{ cat.description || 'No description provided.' }}
                    </p>
                </div>

                <div
                    class="mt-6 flex items-center justify-end gap-2 border-t border-neutral-100 pt-3 dark:border-neutral-800"
                >
                    <Button
                        @click="openEditModal(cat)"
                        variant="outline"
                        class="size-8 p-0"
                        title="Edit"
                    >
                        <Pencil class="size-4 text-neutral-500" />
                    </Button>
                    <Button
                        @click="deleteCategory(cat.id)"
                        variant="outline"
                        class="size-8 border-rose-100 bg-rose-50/20 p-0 hover:border-rose-300 hover:bg-rose-50"
                        title="Delete"
                    >
                        <Trash2 class="size-4 text-rose-600" />
                    </Button>
                </div>
            </div>
        </div>

        <!-- Add Category Modal -->
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
                    Create New Category
                </h3>
                <form @submit.prevent="submitAdd" class="mt-4 space-y-4">
                    <div>
                        <Label for="add-name">Category Name</Label>
                        <Input
                            id="add-name"
                            v-model="addForm.name"
                            placeholder="e.g. Media Equipment"
                            required
                            class="mt-1"
                        />
                        <span
                            v-if="addForm.errors.name"
                            class="text-xs text-rose-600"
                            >{{ addForm.errors.name }}</span
                        >
                    </div>
                    <div>
                        <Label for="add-description">Description</Label>
                        <textarea
                            id="add-description"
                            v-model="addForm.description"
                            rows="3"
                            placeholder="Describe what kind of assets fall into this category..."
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
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
                            >Save</Button
                        >
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
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
                    Edit Category
                </h3>
                <form @submit.prevent="submitEdit" class="mt-4 space-y-4">
                    <div>
                        <Label for="edit-name">Category Name</Label>
                        <Input
                            id="edit-name"
                            v-model="editForm.name"
                            required
                            class="mt-1"
                        />
                        <span
                            v-if="editForm.errors.name"
                            class="text-xs text-rose-600"
                            >{{ editForm.errors.name }}</span
                        >
                    </div>
                    <div>
                        <Label for="edit-description">Description</Label>
                        <textarea
                            id="edit-description"
                            v-model="editForm.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border border-neutral-300 bg-transparent px-3 py-2 text-sm text-neutral-900 shadow-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-neutral-800 dark:text-neutral-100"
                        ></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="
                                showEditModal = false;
                                editingCategory = null;
                            "
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="editForm.processing"
                            >Update</Button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
