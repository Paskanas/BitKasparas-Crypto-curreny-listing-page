<script setup lang="ts">
import { CryptoCurrencyMetadata } from "@/types";

const props = defineProps<{
    metadata: CryptoCurrencyMetadata;
    price: number;
    percent_change_24h: number;
}>();

const urls = props.metadata ? JSON.parse(props.metadata.urls) : {};
</script>

<template>
    <div class="flex items-center align-middle">
        <div class="m-2">
            <img
                class="rounded-full mb-3 max-h-20"
                :src="metadata.logo_url"
                :alt="metadata.name"
            />
        </div>

        <h2 class="text-4xl font-bold mb-4">{{ metadata.name }}</h2>
        <p class="mb-2 bg-sky-800 rounded-full px-3 py-1 ml-2">
            {{ metadata.symbol }}
        </p>
    </div>
    <div class="my-4">
        {{ `${metadata.name} Price (${metadata.symbol})` }}
    </div>
    <div class="flex flex-wrap items-center mr-1">
        <div class="text-5xl font-bold w-auto">
            <span> ${{ price }} </span>
        </div>

        <PriceChangePercent
            :change="percent_change_24h"
            :border="true"
            class="ml-2"
        />
    </div>
    <div
        class="bg-sky-200 dark:bg-sky-800 inline-block w-auto p-2 rounded-lg my-3"
    >
        <a v-if="urls.website" :href="urls.website[0]" target="_blank">
            {{
                urls.website[0]
                    .replace("https://", "")
                    .replace("www.", "")
                    .replace(/\/$/, "")
            }}
        </a>
    </div>
    <p class="mb-4">{{ metadata.description }}</p>
    <p class="mb-4">
        Category:
        <span
            class="bg-sky-200 dark:bg-sky-800 px-1 py-2 rounded-full"
            style="line-height: 14px"
            >{{ metadata.category }}</span
        >
    </p>
</template>
