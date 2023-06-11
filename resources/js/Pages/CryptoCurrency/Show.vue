<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import TradingViewChart from "@/Components/TradingViewChart.vue";
import Top from "@/Components/Show/Top.vue";
import { CryptoCurrency } from "@/types";
import PriceStatistics from "@/Components/Show/PriceStatistics.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps<{
    cryptoCurrency: CryptoCurrency;
    symbol: string;
}>();
</script>

<template>
    <GuestLayout>
        <Head :title="cryptoCurrency.name" />

        <div
            class="relative min-h-screen bg-center selection:bg-red-500 selection:text-white"
        >
            <div class="max-w-screen-2xl mx-auto">
                <div>
                    <Top :cryptoCurrency="cryptoCurrency" />
                    <div class="h-96 mb-12">
                        <TradingViewChart :cryptoCurrency="cryptoCurrency" />
                    </div>
                    <PriceStatistics :cryptoCurrency="props.cryptoCurrency" />
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
