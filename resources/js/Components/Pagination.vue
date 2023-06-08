<script setup lang="ts">
import { PaginationData } from '@/types';
import { defineProps } from 'vue';


const props = defineProps<{
  pagination: PaginationData
}>();


// function getPageUrl(page: number): string {
//   // Customize this method to generate the URL for each page
//   // based on your specific route structure
//   return `/crypto?page=${page}`;
// }
</script>

<template>
  <div class="flex justify-center items-center text-lg m-4">
    <!-- Previous Link -->
    <a v-if="props.pagination.prev_page_url" :href="props.pagination.prev_page_url"
      class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
      Previous
    </a>

    <!-- First Page Number -->
    <a v-if="props.pagination.current_page != 3" :href="props.pagination.first_page_url"
      class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
      1
    </a>

    <!-- Two Numbers Before Current Page with Dots -->
    <template v-if="props.pagination.current_page > 4">
      <span class="mr-2">...</span>
      <a v-for="page in [props.pagination.current_page - 2, props.pagination.current_page - 1]" :key="page"
        :href="props.pagination.links[page].url"
        class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
        {{ page }}
      </a>
    </template>
    <template v-else-if="props.pagination.current_page > 2">
      <a v-for="page in [props.pagination.current_page - 2, props.pagination.current_page - 1]" :key="page"
        :href="props.pagination.links[page].url"
        class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
        {{ page }}
      </a>
    </template>

    <!-- Current Page Number -->
    <span class="mr-2 px-2 py-1 rounded font-semibold bg-gray-200 dark:bg-gray-800 dark:text-white">
      {{ props.pagination.current_page }}
    </span>

    <!-- Two Numbers After Current Page with Dots -->
    <template v-if="props.pagination.current_page < props.pagination.last_page - 3">
      <a v-for="page in [props.pagination.current_page + 1, props.pagination.current_page + 2]" :key="page"
        :href="props.pagination.links[page].url"
        class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
        {{ page }}
      </a>
      <span class="mr-2">...</span>
    </template>

    <!-- Last Page Number -->
    <a v-if="props.pagination.current_page < props.pagination.last_page - 2" :href="props.pagination.last_page_url"
      class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
      {{ props.pagination.last_page }}
    </a>

    <!-- Next Link -->
    <a v-if="props.pagination.next_page_url" :href="props.pagination.next_page_url"
      class="mr-2 px-2 py-1 rounded font-semibold transition duration-300 ease-in-out hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white">
      Next
    </a>
  </div>
</template>

