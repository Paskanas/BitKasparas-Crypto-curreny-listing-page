<script setup lang="ts">
import { ref, onMounted } from "vue";
import LWChart from "@/Components/Composition-api/LWChart.vue";
import axios from "axios";
import {
    DataPoint,
    SeriesOptions,
    ColorsTypeMap,
    ApiData,
} from "./../types/traidingView";
import { watch } from "vue";
const chartOptions = ref({});
const data = ref<DataPoint[]>([]);
const seriesOptions = ref<SeriesOptions>({
    color: "rgb(45, 77, 205)",
});
const chartType = ref("line");
const lwChart = ref();

const props = defineProps<{
    coinName: string;
}>();

const timeScales: Array<{ title: string; value: string }> = [
    { title: "1D", value: "1" },
    { title: "1W", value: "7" },
    { title: "1M", value: "30" },
    { title: "3M", value: "90" },
    { title: "6M", value: "120" },
    { title: "1Y", value: "365" },
    { title: "ALL", value: "max" },
];

const selectedTimeScale = ref(timeScales[0].value);

function randomShade() {
    return Math.round(Math.random() * 255);
}

const randomColor = (alpha = 1) => {
    return `rgba(${randomShade()}, ${randomShade()}, ${randomShade()}, ${alpha})`;
};

const colorsTypeMap: ColorsTypeMap = {
    area: [
        ["topColor", 0.4],
        ["bottomColor", 0],
        ["lineColor", 1],
    ],
    bar: [
        ["upColor", 1],
        ["downColor", 1],
    ],
    baseline: [
        ["topFillColor1", 0.28],
        ["topFillColor2", 0.05],
        ["topLineColor", 1],
        ["bottomFillColor1", 0.28],
        ["bottomFillColor2", 0.05],
        ["bottomLineColor", 1],
    ],
    candlestick: [
        ["upColor", 1],
        ["downColor", 1],
        ["borderUpColor", 1],
        ["borderDownColor", 1],
        ["wickUpColor", 1],
        ["wickDownColor", 1],
    ],
    histogram: [["color", 1]],
    line: [["color", 1]],
};

// Set a random colour for the series as an example of how
// to apply new options to series. A similar appraoch will work on the
// option properties.
const changeColors = () => {
    const options: Record<string, string> = {};
    const colorsToSet = colorsTypeMap[chartType.value];
    colorsToSet.forEach((c) => {
        options[c[0]] = randomColor(c[1]);
    });
    seriesOptions.value = options;
};

const changeType = () => {
    const types = [
        "line",
        "area",
        "baseline",
        "histogram",
        "candlestick",
        "bar",
    ].filter((t) => t !== chartType.value);
    const randIndex = Math.round(Math.random() * (types.length - 1));
    chartType.value = types[randIndex];
    fetchData();

    // call a method on the component.
    lwChart.value.fitContent();
};

watch(selectedTimeScale, () => {
    fetchData(selectedTimeScale.value);
    lwChart.value.getChart();
    lwChart.value.fitContent();
});

// Fetch data from the API
const fetchData = async (days: string = "1") => {
    try {
        console.log(
            `https://api.coingecko.com/api/v3/coins/${props.coinName}/market_chart?vs_currency=usd&days=${days}`
        );

        const response = await axios.get(
            `https://api.coingecko.com/api/v3/coins/${props.coinName}/market_chart?vs_currency=usd&days=${days}`
        );
        const apiData = response.data;

        // Extracting prices array from the API response
        const prices = (apiData as ApiData).prices || [];

        // Parsing the prices array and creating data points
        data.value = prices.map(
            ([time, price]): DataPoint => ({
                time: time / 1000, // Convert time to seconds
                value: price,
            })
        );
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

// function processChartData(responseData) {
//   const chartData = [];
//   const ohlc = true; // Set to true if OHLC data is expected

//   const numberOfPoints = ohlc ? responseData.length : responseData.prices.length;
//   for (let i = 0; i < numberOfPoints; ++i) {
//     const time = responseData[i][0] / 1000;
//     const value = responseData[i][1];

//     if (ohlc) {
//       const randomRanges = [
//         -1 * Math.random(),
//         Math.random(),
//         Math.random(),
//       ].map(i => i * 10);
//       const sign = Math.sin(Math.random() - 0.5);
//       chartData.push({
//         time,
//         low: value + randomRanges[0],
//         high: value + randomRanges[1],
//         open: value + sign * randomRanges[2],
//         close: responseData[i + 1][1], // Use next value for close price
//       });
//     } else {
//       chartData.push({
//         time,
//         value,
//       });
//     }
//   }

//   return chartData;
// }

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="h-96 rounded">
        <span> to chart </span>
        <div
            class="w-full flex justify-between dark:bg-sky-800 rounded-xl px-1 mb-5 dark:text-white"
        >
            <button
                v-for="(scale, index) in timeScales"
                :class="
                    scale.value == selectedTimeScale
                        ? 'dark:bg-sky-950'
                        : 'dark:bg-sky-800'
                "
                :key="index"
                @click="() => (selectedTimeScale = scale.value)"
                class="w-full my-1 ml-0 rounded-xl hover:dark:bg-sky-900 transition-colors"
            >
                {{ scale.title }}
            </button>
        </div>
        <LWChart
            :type="chartType"
            :data="data"
            :autosize="true"
            :chart-options="chartOptions"
            :series-options="seriesOptions"
            :selectedTimeScale="selectedTimeScale"
            ref="lwChart"
        />
    </div>
    <button type="button" @click="changeColors">Set Random Colors</button>
    <button type="button" @click="changeType">Change Chart Type</button>
    <!-- <button type="button" @click="fetchData">Fetch Data</button> -->
</template>

<style scoped>
.chart-container {
    height: calc(100% - 3.2em);
}
</style>
