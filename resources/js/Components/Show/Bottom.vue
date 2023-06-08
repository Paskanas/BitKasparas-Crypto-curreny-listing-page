<script setup lang="ts">
import { defineProps } from "vue";
import {
    CryptoCurrencyMetadata,
    CryptoCurrencyWithMetadata,
} from "../../types";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faAngleRight,
    faCaretDown,
    faCaretUp,
} from "@fortawesome/free-solid-svg-icons";
import PriceChangePercent from "../PriceChangePercent.vue";

const props = defineProps<{
    cryptoCurrency: CryptoCurrencyWithMetadata;
}>();

const metadata: CryptoCurrencyMetadata = props.cryptoCurrency.metadata;
const urls = JSON.parse(metadata.urls);

const formatDate = (date: string) => {
    // Implement your custom date formatting logic here
    return date;
};
</script>

<template>
    <div
        class="bg-inherit rounded shadow mt-32 text-cyan-950 dark:text-white font w-full"
        style="font-family: Tahoma, Geneva, Verdana, sans-serif"
    >
        <div class="m-3">
            <h2 class="text-3xl font-bold mb-4">
                {{ metadata.name }} Price Statistics
            </h2>
            <div class="flex justify-between">
                <div>{{ cryptoCurrency.name }} Price</div>
                <div>${{ cryptoCurrency.price }}</div>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    Price Change
                    <span class="bg-slate-600 rounded p-1">24h</span>
                </div>
                <div class="flex flex-col justify-end">
                    <div class="flex justify-end">
                        {{
                            (
                                (cryptoCurrency.price *
                                    cryptoCurrency.percent_change_24h) /
                                100
                            ).toLocaleString(undefined, {
                                style: "currency",
                                currency: "USD",
                            })
                        }}
                    </div>
                    <PriceChangePercent
                        :change="cryptoCurrency.percent_change_24h"
                        :border="false"
                    />
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div>Market Cap</div>
                <div>
                    <div>${{ cryptoCurrency.market_cap }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
