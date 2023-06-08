<script setup lang="ts">
import { onMounted, defineProps, nextTick, onUnmounted } from "vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

// const REFRESH_INTERVAL = 1000 * 1; // Refresh interval in milliseconds (e.g., 1 minute)

let chartInstance: any = null;
// let refreshTimer: any = null;

const initializeChart = (chartData: [][]) => {
    const canvas = document.getElementById("chartCanvas");
    if (!canvas || !(canvas instanceof HTMLCanvasElement)) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    if (chartInstance) {
        chartInstance.destroy(); // Destroy the existing chart instance
    }

    chartInstance = new Chart(ctx, {
        type: "line",
        data: {
            labels: chartData.map((dataPoint: any) =>
                new Date(dataPoint[0]).toLocaleDateString()
            ),
            datasets: [
                {
                    label: "Cryptocurrency Prices",
                    data: chartData.map((dataPoint: any) => dataPoint[1]),
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: "Time",
                    },
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: "Price",
                    },
                },
            },
        },
    });
};

const props = defineProps<{
    coinName: string;
}>();

const fetchData = async () => {
    console.log(
        `https://api.coingecko.com/api/v3/coins/${props.coinName}/market_chart?vs_currency=usd&days=30`
    );

    const response = await fetch(
        `https://api.coingecko.com/api/v3/coins/${props.coinName}/market_chart?vs_currency=usd&days=30`
    );
    const data = await response.json();
    const chartData = data.prices || [];
    initializeChart(chartData);
};

onMounted(async () => {
    await nextTick();
    fetchData(); // Fetch initial data

    // Set up refresh timer
    // refreshTimer = setInterval(fetchData, REFRESH_INTERVAL);
});

// onUnmounted(() => {
//   clearInterval(refreshTimer); // Clear the refresh timer when the component is unmounted
// });
</script>

<template>
    <div class="h-96 bg-slate-200 rounded">
        <canvas id="chartCanvas"></canvas>
    </div>
</template>
