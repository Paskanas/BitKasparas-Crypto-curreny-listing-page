<script setup lang="ts">
import { defineProps } from "vue";
import { CryptoCurrencyMetadata, CryptoCurrency } from "../../types";
import PriceChangePercent from "../PriceChangePercent.vue";
import PageHistory from "./PageHistory.vue";
import MainInfo from "./MainInfo.vue";

const props = defineProps<{
    cryptoCurrency: CryptoCurrency;
}>();

const metadata: CryptoCurrencyMetadata = props.cryptoCurrency.metadata;
</script>

<template>
    <div class="bg-inherit py-6 border-b-2 border-sky-400">
        <PageHistory :metadata="metadata" />
        <MainInfo
            :metadata="metadata"
            :price="cryptoCurrency.price"
            :percent_change_24h="cryptoCurrency.percent_change_24h"
        />
    </div>
</template>
