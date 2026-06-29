<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Moon,
    Sun,
    ArrowRight,
    Shield,
    Wrench,
    RotateCcw,
    BarChart3,
    Database,
    Calendar,
    Flame,
    BookOpen,
    Users,
    Compass,
    Globe,
} from '@lucide/vue';
import { useAppearance } from '@/composables/useAppearance';
import { dashboard, login } from '@/routes';
import { register } from '@/routes';

const { resolvedAppearance, updateAppearance } = useAppearance();

const toggleAppearance = () => {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
};
</script>

<template>
    <Head title="Winner Church Asset Inventory System">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div
        class="relative min-h-screen bg-[#0B1220] font-sans text-[#E0E6ED] transition-colors duration-300 selection:bg-[#FF8A00] selection:text-white"
    >
        <!-- Technical glow background -->
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <!-- Glowing orange flame orb -->
            <div
                class="absolute -top-40 left-1/2 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-gradient-to-r from-[#FF8A00]/25 to-[#FFC300]/15 blur-[120px]"
            ></div>
            <!-- Glowing cyan tech grid helper -->
            <div
                class="absolute top-[400px] right-10 h-[400px] w-[400px] rounded-full bg-[#00D4FF]/10 blur-[100px]"
            ></div>
        </div>

        <!-- Navigation -->
        <header class="mx-auto max-w-7xl px-6 lg:px-8">
            <nav class="flex items-center justify-between py-6">
                <!-- Logo Group Inspired by Guidelines -->
                <div class="flex items-center gap-3.5">
                    <!-- Flame Globe Graphic Badge -->
                    <div
                        class="relative flex size-10 items-center justify-center rounded-full bg-gradient-to-br from-[#FF8A00] to-[#FFC300] shadow-[0_0_15px_rgba(255,138,0,0.5)]"
                    >
                        <Flame class="size-6 fill-[#0B1220] text-[#0B1220]" />
                        <div
                            class="absolute inset-0 animate-pulse rounded-full border border-white/20"
                        ></div>
                    </div>
                    <div>
                        <span
                            class="block font-display text-xl leading-none font-black tracking-widest text-white uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                        >
                            WINNER <span class="text-[#FF8A00]">CHURCH</span>
                        </span>
                        <span
                            class="mt-1 block text-[9px] font-bold tracking-[0.18em] text-neutral-400 uppercase"
                        >
                            Winning Souls &bull; Building Lives
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button
                        @click="toggleAppearance"
                        class="inline-flex size-9 items-center justify-center rounded-xl border border-neutral-800 bg-[#131D31] text-neutral-300 shadow-sm transition-all hover:bg-neutral-800 focus:outline-none"
                        aria-label="Toggle theme"
                    >
                        <Sun
                            v-if="resolvedAppearance === 'dark'"
                            class="size-4 text-[#FFC300]"
                        />
                        <Moon v-else class="size-4 text-[#00D4FF]" />
                    </button>

                    <!-- Nav Links -->
                    <div class="flex items-center gap-2">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="inline-flex items-center gap-1 rounded-xl bg-gradient-to-r from-[#FF8A00] to-[#FFC300] px-4 py-2 font-display text-xs font-bold text-[#0B1220] transition-all hover:shadow-[0_0_20px_rgba(255,138,0,0.4)] hover:brightness-110"
                            style="font-family: 'Orbitron', sans-serif"
                        >
                            DASHBOARD <ArrowRight class="size-3.5" />
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="px-4 py-2 font-display text-xs font-bold tracking-wider text-[#E0E6ED] uppercase transition-colors hover:text-white"
                                style="font-family: 'Orbitron', sans-serif"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="register()"
                                class="inline-flex items-center gap-1 rounded-xl bg-gradient-to-r from-[#FF8A00] to-[#FFC300] px-4 py-2 font-display text-xs font-bold text-[#0B1220] transition-all hover:shadow-[0_0_15px_rgba(255,138,0,0.4)] hover:brightness-110"
                                style="font-family: 'Orbitron', sans-serif"
                            >
                                REGISTER
                            </Link>
                        </template>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="mx-auto max-w-7xl px-6 py-12 text-center lg:px-8 lg:py-20">
            <div class="mx-auto max-w-3xl space-y-6">
                <!-- System Badge -->
                <div
                    class="inline-flex items-center gap-1.5 rounded-full border border-[#FF8A00]/20 bg-[#FF8A00]/5 px-3 py-1.5 font-display text-xs font-bold tracking-widest text-[#FFC300] uppercase"
                    style="font-family: 'Orbitron', sans-serif"
                >
                    <Shield class="size-3.5 text-[#FF8A00]" /> Assets &
                    Inventory Control
                </div>

                <!-- Main Slogan -->
                <h1
                    class="font-display text-4xl leading-tight font-black tracking-tight text-white uppercase sm:text-6xl"
                    style="font-family: 'Orbitron', sans-serif"
                >
                    WINNER <span class="text-[#FF8A00]">CHURCH</span> <br />
                    <span
                        class="bg-gradient-to-r from-white via-[#FFC300] to-[#FF8A00] bg-clip-text text-3xl tracking-normal text-transparent sm:text-5xl"
                    >
                        INVENTORY MANAGEMENT SYSTEM
                    </span>
                </h1>

                <!-- Tagline directly under the main heading -->
                <p
                    class="mx-auto max-w-xl pt-2 text-xs font-bold tracking-[0.25em] text-neutral-400 uppercase sm:text-sm"
                >
                    WINNING SOULS. BUILDING LIVES. IMPACTING NATIONS.
                </p>

                <!-- Description -->
                <p
                    class="mx-auto max-w-xl text-sm leading-relaxed text-[#6B7280] dark:text-neutral-400"
                >
                    A digital stewardship ledger. Optimize resource allocations
                    across Winner Church ministries. Track high-value media,
                    vehicles, and instrumentation with integrated audit logs.
                </p>

                <!-- Call to action -->
                <div
                    class="flex flex-wrap items-center justify-center gap-4 pt-4"
                >
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#FF8A00] to-[#FFC300] px-8 py-3.5 font-display text-sm font-bold tracking-wider text-[#0B1220] transition-all hover:shadow-[0_0_25px_rgba(255,138,0,0.5)]"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        GO TO DASHBOARD <ArrowRight class="size-4" />
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#FF8A00] to-[#FFC300] px-8 py-3.5 font-display text-sm font-bold tracking-wider text-[#0B1220] transition-all hover:shadow-[0_0_25px_rgba(255,138,0,0.5)]"
                            style="font-family: 'Orbitron', sans-serif"
                        >
                            STAFF SIGN IN <ArrowRight class="size-4" />
                        </Link>
                        <Link
                            :href="register()"
                            class="inline-flex items-center gap-2 rounded-xl border border-neutral-800 bg-[#131D31] px-8 py-3.5 font-display text-sm font-bold tracking-wider text-neutral-300 transition-all hover:bg-[#1b2a47]"
                            style="font-family: 'Orbitron', sans-serif"
                        >
                            CREATE ACCOUNT
                        </Link>
                    </template>
                </div>
            </div>

            <!-- Five Church Pillars Section Inspired by Guidelines -->
            <div class="mx-auto mt-20 max-w-4xl">
                <h3
                    class="mb-6 font-display text-xs font-bold tracking-[0.3em] text-neutral-500 uppercase"
                    style="font-family: 'Orbitron', sans-serif"
                >
                    SUPPORTING OUR 5 CORE PILLARS
                </h3>
                <div
                    class="grid grid-cols-2 gap-4 rounded-2xl border-y border-neutral-800/80 bg-[#131D31]/20 p-4 py-8 md:grid-cols-5"
                >
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="flex size-10 items-center justify-center rounded-full bg-[#FF8A00]/10 text-[#FF8A00]"
                        >
                            <Flame class="size-5" />
                        </div>
                        <span
                            class="font-display text-xs font-bold tracking-widest uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Worship</span
                        >
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="flex size-10 items-center justify-center rounded-full bg-[#FF8A00]/10 text-[#FF8A00]"
                        >
                            <BookOpen class="size-5" />
                        </div>
                        <span
                            class="font-display text-xs font-bold tracking-widest uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Word</span
                        >
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="flex size-10 items-center justify-center rounded-full bg-[#FF8A00]/10 text-[#FF8A00]"
                        >
                            <Compass class="size-5" />
                        </div>
                        <span
                            class="font-display text-xs font-bold tracking-widest uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Discipleship</span
                        >
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="flex size-10 items-center justify-center rounded-full bg-[#FF8A00]/10 text-[#FF8A00]"
                        >
                            <Users class="size-5" />
                        </div>
                        <span
                            class="font-display text-xs font-bold tracking-widest uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Fellowship</span
                        >
                    </div>
                    <div
                        class="col-span-2 flex flex-col items-center gap-2 md:col-span-1"
                    >
                        <div
                            class="flex size-10 items-center justify-center rounded-full bg-[#FF8A00]/10 text-[#FF8A00]"
                        >
                            <Globe class="size-5" />
                        </div>
                        <span
                            class="font-display text-xs font-bold tracking-widest uppercase"
                            style="font-family: 'Orbitron', sans-serif"
                            >Missions</span
                        >
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div
                class="mx-auto mt-24 grid max-w-5xl gap-8 text-left sm:grid-cols-2 lg:grid-cols-3"
            >
                <!-- Feature 1 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <Database class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Dynamic Ledger
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Classified database matching Winner Church's inventory.
                        Log serial numbers, ministries assigned, images,
                        purchase values, and live locations.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <RotateCcw class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Checkout System
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Seamless checkout pipeline. Staff and members submit
                        booking times; administrators approve request lists and
                        process returns with checklist logs.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <Wrench class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Service Tracking
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Track preventative inspections, scheduled checkups, and
                        technical repairs. Automatically mark assets offline and
                        record resolution costs.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <BarChart3 class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Valuation Reports
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Generate formatted printable reports detailing top
                        checked-out assets, category valuation counts, and
                        export custom logs in Excel/CSV format.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <Shield class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Role Auth (RBAC)
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Granular Laravel capability gates. Members submit
                        bookings, while Admins manage inventory, user roles,
                        approve requests, and review reports.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="group rounded-2xl border border-neutral-800/80 bg-[#131D31]/40 p-6 transition-all hover:-translate-y-1 hover:border-[#FF8A00]/40"
                >
                    <div
                        class="flex size-10 items-center justify-center rounded-xl bg-[#00D4FF]/10 text-[#00D4FF] shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:bg-[#00D4FF] group-hover:text-[#0B1220]"
                    >
                        <Calendar class="size-5" />
                    </div>
                    <h3
                        class="mt-4 font-display text-base font-bold text-white uppercase"
                        style="font-family: 'Orbitron', sans-serif"
                    >
                        Security Audit Logs
                    </h3>
                    <p class="mt-2 text-xs leading-relaxed text-neutral-400">
                        Track administrative modifications. Maintains a
                        complete, non-editable record of who changed which asset
                        state, including IP addresses.
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer
            class="mx-auto max-w-7xl border-t border-neutral-800 px-6 py-10 text-center lg:px-8"
        >
            <p class="text-xs text-neutral-500">
                &copy; {{ new Date().getFullYear() }} Winner Church. All rights
                reserved. Asset Tracking & Monitoring System.
            </p>
        </footer>
    </div>
</template>
