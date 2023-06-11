<script setup lang="ts">
import { defineProps } from "vue";
import { PaginationData } from "@/types";

defineProps<{
    pagination: PaginationData;
}>();
</script>

<template>
    <div class="flex justify-center items-center text-lg m-4">
        <a
            v-for:="page in pagination.links"
            :href="page.url"
            v-html="page.label"
            class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-sky-200 dark:hover:bg-sky-900 dark:text-white"
            :class="
                page.active
                    ? 'font-semibold bg-sky-300 dark:bg-sky-800 dark:text-white'
                    : ''
            "
        >
        </a>
    </div>
</template>
