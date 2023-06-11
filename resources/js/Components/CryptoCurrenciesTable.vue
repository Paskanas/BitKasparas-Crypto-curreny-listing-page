<script setup lang="ts">
import { CryptoCurrency } from "@/types";
import { defineProps } from "vue";
import PriceChangePercent from "./PriceChangePercent.vue";

const props = defineProps<{
    cryptoCurrenciesData: CryptoCurrency[];
    currentPage: number;
    perPage: number;
}>();
</script>

<template>
    <table
        class="bg-sky-200 dark:bg-sky-800 rounded-lg w-full table-auto text-right text-sm"
    >
        <thead class="items-center">
            <tr class="w-full px-4">
                <th class="text-left p-4">#</th>
                <th class="text-left p-4">Name</th>
                <th class="p-4">Price</th>
                <th class="p-4">% Change (1h)</th>
                <th class="p-4">% Change (24h)</th>
                <th class="p-4">% Change (7d)</th>
                <th class="p-4">Market Cap</th>
                <th class="p-4">Volume (24h)</th>
                <th class="p-4">Circular Supply</th>
            </tr>
        </thead>
        <tbody class="items-center">
            <tr
                v-for="(currency, index) in cryptoCurrenciesData"
                :key="index"
                :class="
                    index % 2 === 0
                        ? 'dark:bg-sky-950 bg-sky-100'
                        : 'dark:bg-sky-900 bg-sky-200'
                "
            >
                <td class="text-left px-4 py-2">
                    <!-- should be currency.market_rank but value is bad -->
                    {{ (currentPage - 1) * perPage + index + 1 }}
                </td>
                <td class="text-left px-4 py-2">
                    <a
                        :href="
                            route('cryptoCurrency.show', { id: currency.slug })
                        "
                        class="hover:underline"
                        >{{ currency.name + " " + currency.symbol }}</a
                    >
                </td>
                <td class="px-4 py-2">${{ currency.price.toFixed(2) }}</td>
                <td>
                    <PriceChangePercent
                        :change="currency.percent_change_1h"
                        :border="false"
                    />
                </td>
                <td>
                    <PriceChangePercent
                        :change="currency.percent_change_24h"
                        :border="false"
                    />
                </td>
                <td>
                    <PriceChangePercent
                        :change="currency.percent_change_7d"
                        :border="false"
                    />
                </td>
                <td class="px-4 py-2">
                    ${{ (+currency.market_cap).toLocaleString() }}
                </td>
                <td class="px-4 py-2">
                    <a href="">{{
                        (+currency.volume_24h).toLocaleString(undefined, {
                            style: "currency",
                            currency: "USD",
                        })
                    }}</a>
                </td>
                <td class="px-4 py-2">
                    ${{ (+currency.circulating_supply).toLocaleString() }}
                </td>
            </tr>
        </tbody>
    </table>
</template>
