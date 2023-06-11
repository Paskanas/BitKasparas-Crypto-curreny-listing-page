<script setup lang="ts">
import { CryptoCurrencyMetadata } from "@/types";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faAngleRight } from "@fortawesome/free-solid-svg-icons";

defineProps<{
    metadata: CryptoCurrencyMetadata;
}>();
</script>
<template>
    <div class="flex text-xl">
        <a
            class="link-with-icon"
            href="javascript:void(0);"
            onclick="history.back();"
            >Cryptocurrencies
            <font-awesome-icon class="mx-3" :icon="faAngleRight" />
        </a>
        <span>
            {{ metadata.name }}
        </span>
    </div>
</template>
