<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Shield, Mail, User as UserIcon } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Users & Roles', href: '/users' }],
    },
});

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            roles: Array<{
                id: number;
                name: string;
                display_name: string | null;
            }>;
        }>;
        links: any[];
    };
    roles: Array<{ value: number; label: string }>;
}>();

// Edit State
const showEditModal = ref(false);
const selectedUser = ref<any>(null);

const form = useForm({
    role_ids: [] as number[],
});

const openEditModal = (user: any) => {
    selectedUser.value = user;
    form.role_ids = user.roles.map((r: any) => r.id);
    showEditModal.value = true;
};

const toggleRole = (roleId: number) => {
    const idx = form.role_ids.indexOf(roleId);

    if (idx > -1) {
        form.role_ids.splice(idx, 1);
    } else {
        form.role_ids.push(roleId);
    }
};

const submit = () => {
    if (!selectedUser.value) {
        return;
    }

    form.put(`/users/${selectedUser.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false;
            selectedUser.value = null;
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Users & Roles" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-100 pb-4 dark:border-neutral-800"
        >
            <div>
                <h1
                    class="text-2xl font-black text-neutral-900 dark:text-neutral-50"
                >
                    User Authorization
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Assign administrative roles and permissions to Winner Church
                    staff and members.
                </p>
            </div>
        </div>

        <!-- Users Table -->
        <div
            class="overflow-x-auto rounded-xl border border-neutral-200/80 bg-white shadow-xs dark:border-neutral-800 dark:bg-neutral-900"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-neutral-50/70 text-xs font-bold tracking-wider text-neutral-400 uppercase dark:bg-neutral-950/20"
                >
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Assigned Roles</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-neutral-100 dark:divide-neutral-800"
                >
                    <tr
                        v-for="u in users.data"
                        :key="u.id"
                        class="transition-colors hover:bg-neutral-50/50 dark:hover:bg-neutral-950/10"
                    >
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex size-9 items-center justify-center rounded-full bg-neutral-100 text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400"
                                >
                                    <UserIcon class="size-4.5" />
                                </div>
                                <span
                                    class="font-bold text-neutral-900 dark:text-neutral-100"
                                    >{{ u.name }}</span
                                >
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 font-medium text-neutral-500 dark:text-neutral-400"
                        >
                            <div class="flex items-center gap-1.5">
                                <Mail class="size-4 text-neutral-400" />
                                <span>{{ u.email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <Badge
                                    v-for="r in u.roles"
                                    :key="r.id"
                                    class="border-indigo-200 bg-indigo-50/50 font-bold text-indigo-700 capitalize hover:bg-indigo-50 dark:bg-indigo-950/20 dark:text-indigo-400"
                                >
                                    <Shield class="mr-1 size-3" />
                                    {{ r.display_name || r.name }}
                                </Badge>
                                <span
                                    v-if="u.roles.length === 0"
                                    class="text-xs text-neutral-400 italic"
                                    >No roles assigned</span
                                >
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <Button
                                @click="openEditModal(u)"
                                size="sm"
                                variant="outline"
                                class="h-8 text-xs font-semibold"
                            >
                                Manage Roles
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="users.data.length === 0">
                        <td
                            colspan="4"
                            class="px-6 py-12 text-center text-neutral-500"
                        >
                            No users registered.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Roles Modal -->
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
                    Manage Roles: {{ selectedUser?.name }}
                </h3>
                <form @submit.prevent="submit" class="mt-4 space-y-4">
                    <div class="space-y-3">
                        <Label>Select Roles</Label>
                        <div class="space-y-2">
                            <div
                                v-for="role in roles"
                                :key="role.value"
                                class="flex items-center gap-3 rounded-lg border border-neutral-200 p-3 hover:bg-neutral-50 dark:border-neutral-800 dark:hover:bg-neutral-950/40"
                            >
                                <Checkbox
                                    :id="`role-${role.value}`"
                                    :checked="
                                        form.role_ids.includes(role.value)
                                    "
                                    @update:checked="toggleRole(role.value)"
                                />
                                <Label
                                    :for="`role-${role.value}`"
                                    class="flex-1 cursor-pointer font-bold text-neutral-800 capitalize select-none dark:text-neutral-200"
                                >
                                    {{ role.label }}
                                </Label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <Button
                            type="button"
                            variant="outline"
                            @click="
                                showEditModal = false;
                                selectedUser = null;
                            "
                            >Cancel</Button
                        >
                        <Button
                            type="submit"
                            class="bg-indigo-600 text-white hover:bg-indigo-700"
                            :disabled="form.processing"
                            >Save Changes</Button
                        >
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
