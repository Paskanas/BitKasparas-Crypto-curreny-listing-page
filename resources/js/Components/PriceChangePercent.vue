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
        class="rounded my-3 p-2 w"
        :class="
            border
                ? change > 0
                    ? 'bg-green-500'
                    : 'bg-red-500'
                : 'text-red-600'
        "
    >
        <font-awesome-icon v-if="change > 0" class="mx-1" :icon="faCaretUp" />
        <font-awesome-icon v-else class="mx-1" :icon="faCaretDown" />
        {{ change }}%
    </span>
</template>
