<script setup lang="ts">
import { defineProps } from "vue";
import { CryptoCurrencyMetadata, CryptoCurrency } from "../../types";
import PriceChangePercent from "../PriceChangePercent.vue";

const props = defineProps<{
    cryptoCurrency: CryptoCurrency;
}>();

const metadata: CryptoCurrencyMetadata = props.cryptoCurrency.metadata;
</script>

<template>
    <div class="bg-inherit rounded mt-32 w-full mb-7">
        <div class="bg-sky-200 dark:bg-sky-900 rounded-xl p-6">
            <h2 class="text-3xl font-bold my-4">
                {{ metadata.name }} Price Statistics
            </h2>
            <div class="text-lg pb-3">
                <div
                    class="flex justify-between items-center border-b-2 border-sky-950 dark:border-sky-100"
                >
                    <div class="my-6">{{ cryptoCurrency.name }} Price</div>
                    <div>${{ cryptoCurrency.price }}</div>
                </div>
                <div
                    class="flex justify-between items-center border-b-2 border-sky-950 dark:border-sky-100"
                >
                    <div class="my-6">
                        Price Change
                        <span class="bg-sky-300 dark:bg-sky-950 rounded p-1"
                            >24h</span
                        >
                    </div>
                    <div class="flex flex-col justify-around items-end">
                        <div>
                            {{
                                (
                                    (cryptoCurrency.price *
                                        +cryptoCurrency.percent_change_24h) /
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
                            class="-m-2"
                        />
                    </div>
                </div>
                <div
                    class="flex justify-between items-center border-b-2 border-sky-950 dark:border-sky-100"
                >
                    <div class="my-6">Market Cap</div>
                    <div>
                        <div>
                            ${{ (+cryptoCurrency.market_cap).toLocaleString() }}
                        </div>
                    </div>
                </div>
                <div
                    class="flex justify-between items-center border-b-2 border-sky-950 dark:border-sky-100"
                >
                    <div class="my-6">Market Dominance</div>
                    <div>
                        <div>{{ cryptoCurrency.market_cap_dominance }}%</div>
                    </div>
                </div>
                <div
                    class="flex justify-between items-center border-b-2 border-sky-950 dark:border-sky-100"
                >
                    <div class="my-6">CoinMarketCap Rank</div>
                    <div>
                        <div>#{{ cryptoCurrency.market_rank }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
