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
        class="bg-inherit rounded shadow p-6 text-cyan-950 dark:text-white font"
        style="font-family: Tahoma, Geneva, Verdana, sans-serif"
    >
        <div class="flex text-xl">
            <a class="link-with-icon" :href="route('index')"
                >Cryptocurrencies
                <font-awesome-icon class="mx-3" :icon="faAngleRight" />
            </a>
            <span>
                {{ metadata.name }}
            </span>
        </div>

        <div class="flex items-center align-middle">
            <div class="m-2">
                <img
                    class="rounded-full mb-3"
                    :src="metadata.logo_url"
                    :alt="metadata.name"
                />
            </div>

            <h2 class="text-4xl font-bold mb-4">{{ metadata.name }}</h2>
            <p
                class="text-gray-600 mb-2 bg-slate-300 rounded-full px-3 py-1 ml-2"
            >
                {{ metadata.symbol }}
            </p>
        </div>
        <div class="my-4">
            {{ `${metadata.name} Price (${metadata.symbol})` }}
        </div>
        <div class="flex flex-wrap items-center mr-1">
            <div class="text-5xl font-bold w-auto">
                <span> ${{ cryptoCurrency.price }} </span>
            </div>

            <span
                class="rounded my-3 p-2 w"
                :class="
                    cryptoCurrency.percent_change_24h > 0
                        ? 'bg-green-500'
                        : 'bg-red-500'
                "
            >
                <font-awesome-icon
                    v-if="cryptoCurrency.percent_change_24h > 0"
                    class="mx-1"
                    :icon="faCaretUp"
                />
                <font-awesome-icon v-else class="mx-1" :icon="faCaretDown" />
                {{ cryptoCurrency.percent_change_24h }}%
            </span>

            <div class="flex flex-col">
                <a v-for="(url, index) in urls" :key="url" :href="url[0]">
                    {{ index }}: {{ url }}
                </a>
            </div>
        </div>
        <p class="mb-4">{{ metadata.description }}</p>
        <div class="flex justify-between mb-4">
            <p class="text-sm text-gray-600">
                {{ formatDate(metadata.date_added) }}
            </p>
            <p class="text-sm text-gray-600">
                {{ formatDate(metadata.date_launched) }}
            </p>
        </div>
        <p class="text-gray-700 mb-4">{{ metadata.category }}</p>
        <!-- <div class="flex space-x-2">
                                                                                                                                                  <span v-for="tag in metadata.tags" :key="tag"
                                                                                                                                                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">{{ tag }}</span>
                                                                                                                                                </div> -->
        <!-- <div class="mt-4">
                                                                                                                                                <a v-for="url in metadata.urls" :key="url" :href="url" target="_blank" class="text-blue-500 hover:underline">{{ url
                                                                                                                                                }}</a>
                                                                                                                                              </div> -->
    </div>
</template>
