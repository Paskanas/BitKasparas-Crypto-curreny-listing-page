<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import axios from "axios";
import { watchEffect } from "vue";

const searchQuery = ref("");
const suggestions = ref<Array<{ id: string; name: string; slug: string }>>([]);
const searchInput = ref<HTMLElement | null>(null);

const fetchSuggestions = async () => {
    try {
        const response = await axios.get(
            `/api/search?query=${searchQuery.value}`
        );

        suggestions.value = response.data;
    } catch (error) {
        console.error("Error fetching suggestions:", error);
    }
};

const clearSuggestions = () => {
    suggestions.value = [];
};

const handleOutsideClick = (event: MouseEvent) => {
    const target = event.target;
    if (
        searchInput.value &&
        !searchInput.value.contains(target as Node) &&
        target !== searchInput.value
    ) {
        clearSuggestions();
    }
};

watchEffect(() => {
    document.addEventListener("click", handleOutsideClick);
    return () => {
        document.removeEventListener("click", handleOutsideClick);
    };
});

const navigateToCurrency = (currencyName: string) => {
    const url = `/currency/${currencyName}`;
    // Replace the following line with your actual navigation code
    window.location.href = url;
};

const clearSearch = () => {
    searchQuery.value = "";
};

watch(searchQuery, () => {
    if (searchQuery.value.length) {
        fetchSuggestions();
    } else {
        suggestions.value = [];
    }
});

onMounted(() => {
    // Clear suggestions when component is mounted
    suggestions.value = [];
});
</script>

<template>
    <div class="relative mb-6">
        <input
            v-model="searchQuery"
            placeholder="Search"
            class="w-full px-4 py-2 dark:text-white border border-sky-300 bg-sky-200 dark:border-sky-500 placeholder:text-black dark:placeholder:text-white rounded-md focus:outline-none focus:border-blue-500 dark:bg-sky-800"
            ref="searchInput"
        />
        <button
            v-if="searchQuery"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 border-0 hover:text-lg focus:outline-non bg-inherit"
            @click="clearSearch"
        >
            X
        </button>
        <ul
            v-if="suggestions.length"
            class="absolute z-10 w-full py-2 mt-1 text-black dark:bg-sky-950 dark:text-white bg-sky-200 border dark:border-sky-500 border-sky-300 rounded-md shadow-md"
        >
            <li
                v-for="suggestion in suggestions"
                :key="suggestion.id"
                class="px-4 py-2 cursor-pointer dark:hover:bg-sky-900 hover:bg-sky-300"
                @click="navigateToCurrency(suggestion.slug)"
            >
                {{ suggestion.name }}
            </li>
        </ul>
    </div>
</template>
