<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faCaretDown, faCaretUp } from "@fortawesome/free-solid-svg-icons";

defineProps<{
    change: number;
    border: boolean;
}>();
</script>

<template>
    <span
        class="rounded p-2 w"
        :class="
            border
                ? change >= 0
                    ? 'bg-green-500'
                    : 'bg-red-500'
                : change >= 0
                ? 'text-green-500'
                : 'text-red-600'
        "
    >
        <font-awesome-icon v-if="change > 0" class="mx-1" :icon="faCaretUp" />
        <font-awesome-icon
            v-else-if="change < 0"
            class="mx-1"
            :icon="faCaretDown"
        />
        {{ change.toFixed(2) }}%
    </span>
</template>
