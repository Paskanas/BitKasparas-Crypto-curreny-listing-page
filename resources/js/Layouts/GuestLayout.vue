<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="bg-sky-100 dark:bg-sky-950 dark:text-white">
        <div class="p-3 lg:px-2 max-w-screen-2xl mx-auto">
            <Link href="/">
                <ApplicationLogo class="text-gray-500" />
            </Link>
        </div>
        <div
            class="relative min-h-screen bg-center selection:bg-red-500 selection:text-white"
        >
            <div
                class="bg-sky-100 dark:bg-sky-950"
                style="font-family: Tahoma, Geneva, Verdana, sans-serif"
            >
                <div
                    class="bg-sky-100 dark:bg-sky-950 max-w-screen-2xl mx-auto"
                >
                    <div class="w-full px-4 py-2 overflow-hidden sm:rounded-lg">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
