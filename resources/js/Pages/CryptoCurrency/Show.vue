<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import TradingViewChart from "@/Components/TradingViewChart.vue";
import Top from "@/Components/Show/Top.vue";
import Bottom from "@/Components/Show/Bottom.vue";
import { CryptoCurrency, CryptoCurrencyWithMetadata } from "@/types";

// const chartData = reactive({
//   labels: ['Label 1', 'Label 2', 'Label 3'], // Replace with your own labels
//   data: [10, 20, 15, 12, 13, 1, 5, 85, 6, 231, 31, 3, 2], // Replace with your own data
// });

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    cryptoCurrency: CryptoCurrencyWithMetadata;
    // chartData: {
    //   type: [];
    //   required: true;
    // };
    symbol: string;
}>();

console.log(props.cryptoCurrency);
</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-center bg-gray-100 dark:bg-sky-950 selection:bg-red-500 selection:text-white"
    >
        <div
            v-if="canLogin"
            class="sm:fixed sm:top-0 sm:right-0 p-6 text-right"
        >
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >
                Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >
                    Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >
                    Register</Link
                >
            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="mt-16">
                <Top :cryptoCurrency="props.cryptoCurrency" />
                <!-- <div v-if="symbol" id="app">
                                                <CryptoChart :coinName="symbol" />
                                              </div> -->
                <div class="h-96 mb-12">
                    <TradingViewChart :coinName="symbol" />
                </div>
                <Bottom :cryptoCurrency="props.cryptoCurrency" />
            </div>
        </div>
    </div>
</template>

<style>
/* @media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
} */
</style>
