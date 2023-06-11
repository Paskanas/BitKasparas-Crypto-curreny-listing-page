<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import Pagination from "@/Components/Pagination.vue";
import CryptoCurrenciesTable from "@/Components/CryptoCurrenciesTable.vue";
import { PaginationData } from "@/types";
import Search from "@/Components/Search.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
const props = defineProps<{
    cryptoCurrencies: PaginationData;
}>();

const currentPage = props.cryptoCurrencies.current_page;

const cryptoCurrencies = computed(() => {
    const data = props.cryptoCurrencies.data;
    return data;
});
</script>

<template>
    <GuestLayout>
        <Head title="Home" />

        <div class="bg-sky-100 dark:bg-sky-950">
            <div
                class="relative min-h-screen bg-center selection:bg-red-500 selection:text-white"
            >
                <div class="max-w-9xl mx-auto">
                    <div>
                        <Search />
                        <CryptoCurrenciesTable
                            :cryptoCurrenciesData="cryptoCurrencies"
                            :currentPage="currentPage"
                            :perPage="props.cryptoCurrencies.per_page"
                        />
                        <Pagination :pagination="props.cryptoCurrencies" />
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
