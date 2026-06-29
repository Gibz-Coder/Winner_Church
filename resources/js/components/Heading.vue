<script setup lang="ts">
type Props = {
    title: string;
    description?: string;
    variant?: 'default' | 'small';
};

withDefaults(defineProps<Props>(), {
    variant: 'default',
});
</script>

<template>
    <header :class="variant === 'small' ? '' : 'mb-8 space-y-1'">
        <h2
            :class="
                variant === 'small'
                    ? 'mb-0.5 font-display text-sm font-bold tracking-wider text-neutral-800 uppercase dark:text-neutral-100'
                    : 'font-display text-xl font-black tracking-widest text-neutral-900 uppercase dark:text-white'
            "
        >
            {{ title }}
        </h2>
        <p
            v-if="description"
            class="text-xs tracking-wide text-muted-foreground"
        >
            {{ description }}
        </p>
    </header>
</template>
