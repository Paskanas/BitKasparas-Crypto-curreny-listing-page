<script setup lang="ts">
import { defineProps } from 'vue';

interface CryptoCurrency {
  id: number;
  name: string;
  slug: string;
  symbol: string;
  price: number;
  percent_change_1h: number;
  percent_change_24h: number;
  market_cap: number;
  percent_change_7d: number;
  volume_change_24h: number;
  volume_change_24h_btc: number;
  circular_supply: number;
  links: {
    self: string;
  }
}

defineProps<{
  cryptoCurrenciesData: CryptoCurrency[];
  currentPage: number;
  perPage: number;
}>();
</script>

<template>
  <table class="bg-slate-200 rounded-lg shadow-lg w-full table-auto text-right text-sm">
    <thead class="items-center">
      <tr class="w-full px-4">
        <th class="text-left p-4">#</th>
        <th class="text-left p-4">Name</th>
        <th class="p-4">Price</th>
        <th class="p-4">% Change (1h)</th>
        <th class="p-4">% Change (24h)</th>
        <th class="p-4">% Change (7d)</th>
        <th class="p-4">Market Cap</th>
        <th class="p-4">Volume Change (24h)</th>
        <th class="p-4">Circular Supply</th>
        <th class="p-4">Last 7 days</th>
      </tr>
    </thead>
    <tbody class="items-center">
      <tr v-for="(currency, index) in cryptoCurrenciesData" :key="index"
        :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
        <td class="text-left px-4 py-2">{{ ((currentPage - 1) * perPage + 1
          + index)
        }}
        </td>
        <td class="text-left px-4 py-2">
          <a :href="route('cryptoCurrency.show', { id: currency.slug })">{{ currency.name + ' ' + currency.symbol }}</a>
        </td>
        <td class="px-4 py-2">{{ currency.price }}</td>
        <td class="px-4 py-2" :style="{ color: currency.percent_change_1h >= 0 ? 'green' : 'red' }">{{
          currency.percent_change_1h + '%' }}</td>
        <td class="px-4 py-2" :style="{ color: currency.percent_change_24h >= 0 ? 'green' : 'red' }">{{
          currency.percent_change_24h + '%' }}</td>
        <td class="px-4 py-2" :style="{ color: currency.percent_change_7d >= 0 ? 'green' : 'red' }">{{
          currency.percent_change_7d + '%' }}</td>
        <td class="px-4 py-2">{{ currency.market_cap }}</td>
        <td class="px-4 py-2">
          <a href="">{{ '$' + currency.volume_change_24h }}</a>
          <div>{{ currency.volume_change_24h_btc + ' BTC' }}</div>
        </td>
        <td class="px-4 py-2">{{ currency.circular_supply }}</td>
        <td class="px-4 py-2">{{ 'Atvaizduoti graph' }}</td>
      </tr>
    </tbody>
  </table>
</template>
