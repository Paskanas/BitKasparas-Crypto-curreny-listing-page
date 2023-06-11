<script setup lang="ts">
import { ref, onMounted } from "vue";
import LWChart from "@/Components/Composition-api/LWChart.vue";
import axios from "axios";
import { ColorsTypeMap } from "./../types/traidingView";
import { reactive } from "vue";
import { CryptoCurrency } from "@/types";
import {
    AreaData,
    BarData,
    BaselineData,
    CandlestickData,
    ChartOptions,
    ColorType,
    HistogramData,
    LineData,
    LineStyle,
    OhlcData,
    SeriesOptionsCommon,
    TickMarkType,
    UTCTimestamp,
    WhitespaceData,
} from "lightweight-charts";
import { TimeScaleOptions } from "chart.js";

const theme = reactive({
    mode: "light", // set default theme type
});

//catch pc theme mode
if (
    window.matchMedia &&
    window.matchMedia("(prefers-color-scheme: dark)").matches
) {
    theme.mode = "dark";
} else {
    theme.mode = "light";
}

const data = ref<
    (
        | WhitespaceData
        | CandlestickData
        | LineData
        | AreaData
        | BarData
        | HistogramData
        | BaselineData
    )[]
>([]);

//@ts-ignore
const seriesOptions = ref<SeriesOptionsCommon>({});

//@ts-ignore
const chartOptions = ref<ChartOptions>({
    layout: {
        background: {
            type: ColorType.Solid,
            color:
                theme.mode == "light" ? "rgb(204, 234, 255)" : "rgb(12 74 110)",
        },
        fontSize: 11,
        fontFamily:
            "`-apple-system, BlinkMacSystemFont, 'Trebuchet MS', Roboto, Ubuntu, sans-serif`",
        textColor: theme.mode == "light" ? "#000" : "rgb(255 255 255)",
    },
    grid: {
        vertLines: {
            color:
                theme.mode == "light" ? "rgb(191, 213, 255)" : "rgb(8 47 73)",
            style: LineStyle.Solid,
            visible: true,
        },
        horzLines: {
            color:
                theme.mode == "light" ? "rgb(191, 213, 255)" : "rgb(8 47 73)",
            style: LineStyle.Solid,
            visible: true,
        },
    },
    autoSize: false,
    //@ts-ignore
    localization: {
        locale: "en-US",
    },
});

const timeScalesOptions = ref<TimeScaleOptions>({
    //@ts-ignore
    borderColor: theme.mode == "light" ? "#000" : "rgb(127 187 219)",
    timeVisible: true,
    tickMarkFormatter: (
        time: number,
        tickMarkType: TickMarkType,
        locale: string
    ) => {
        const date = new Date(time * 1000);
        switch (tickMarkType) {
            case TickMarkType.Year:
                return date.getFullYear();

            case TickMarkType.Month:
                const monthFormatter = new Intl.DateTimeFormat(locale, {
                    month: "short",
                });

                return monthFormatter.format(date);

            case TickMarkType.DayOfMonth:
                return date.getDate();

            case TickMarkType.Time:
                const timeFormatter = new Intl.DateTimeFormat(locale, {
                    hour: "numeric",
                    minute: "numeric",
                });
                return timeFormatter.format(date);
        }
    },
});

const priceScaleOptions = ref({});
const chartType = ref("line");
const lwChart = ref();

const props = defineProps<{
    cryptoCurrency: CryptoCurrency;
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

const colorsTypeMap: ColorsTypeMap = {
    candlestick: [
        ["upColor", 1],
        ["downColor", 1],
        ["borderUpColor", 1],
        ["borderDownColor", 1],
        ["wickUpColor", 1],
        ["wickDownColor", 1],
    ],
    line: [["color", 1]],
};

const chartTypes = [
    { name: "Line chart", type: "line" },
    { name: "Candlestick chart", type: "candlestick" },
];

const setSeriesOptions = () => {
    const colorsToSet = colorsTypeMap[chartType.value];
    //@ts-ignore
    seriesOptions.value = colorsToSet;
    //@ts-ignore
    seriesOptions.value = {
        color:
            theme.mode == "light" ? "rgb(16, 78, 119)" : "rgb(255, 255, 255)",
        priceFormat: {
            type: "price",
            precision: 4,
            minMove: 0.0000001,
        },
    };
};

const handleChangeType = (type: string) => {
    chartType.value = type;

    fetchData(selectedTimeScale.value);

    lwChart.value.fitContent();
};

const handleChangeTimeScale = (value: string) => {
    selectedTimeScale.value = value;

    fetchData(selectedTimeScale.value);

    lwChart.value.fitContent();
};

// Fetch data from the API
const fetchData = async (days: string = "1") => {
    try {
        const fetchDays: Record<string, string> = {
            1: "1",
            7: "14",
            30: "30",
            90: "180",
            120: "365",
            365: "max",
            max: "max",
        };
        if (days == "1") {
        }
        if (chartType.value == "line") {
            const response = await axios.get(
                `https://api.coingecko.com/api/v3/coins/${props.cryptoCurrency.coin_gecko_id}/market_chart?vs_currency=usd&days=${fetchDays[days]}`
            );

            // Parsing the prices array and creating data points
            data.value = response.data.prices.map(
                (item: number[]): LineData => ({
                    time: (item[0] / 1000) as UTCTimestamp, // Convert time to seconds
                    value: item[1],
                })
            );
        } else if (chartType.value == "candlestick") {
            const response = await axios.get(
                `https://api.coingecko.com/api/v3/coins/${props.cryptoCurrency.coin_gecko_id}/ohlc?vs_currency=usd&days=${fetchDays[days]}`
            );

            // Parsing the prices array and creating data points
            data.value = response.data.map(
                ([time, open, high, low, close]: [
                    number,
                    number,
                    number,
                    number,
                    number
                ]): OhlcData => ({
                    time: (time / 1000) as UTCTimestamp, // Convert time to seconds
                    open: +open,
                    high: +high,
                    low: +low,
                    close: +close,
                })
            );
        }
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};

onMounted(() => {
    fetchData(selectedTimeScale.value);
    setSeriesOptions();
});
</script>

<template>
    <div class="h-96 rounded">
        <div class="flex justify-between">
            <h2 class="text-3xl my-4">
                {{ cryptoCurrency.name + " (" + cryptoCurrency.symbol + ")" }}
                to USD chart
            </h2>
            <div
                class="flex justify-items-center p-2 px-2 my-2 bg-sky-200 dark:bg-sky-900 rounded-xl"
            >
                <button
                    v-for="(type, index) in chartTypes"
                    :key="index"
                    @click="() => handleChangeType(type.type)"
                    class="px-3 mx-2 dark:hover:bg-sky-800 hover:bg-sky-300"
                    :disabled="type.type == chartType"
                    :class="
                        type.type == chartType
                            ? 'bg-sky-100 dark:bg-sky-950'
                            : 'bg-sky-200 dark:bg-sky-900'
                    "
                >
                    {{ type.name }}
                </button>
            </div>
        </div>
        <div
            class="w-full flex justify-between bg-sky-200 dark:bg-sky-800 rounded-xl px-1 mb-5"
        >
            <button
                v-for="(scale, index) in timeScales"
                :class="
                    scale.value == selectedTimeScale
                        ? 'bg-sky-100 dark:bg-sky-950'
                        : 'bg-sky-200 dark:bg-sky-800'
                "
                :key="index"
                :disabled="scale.value == selectedTimeScale"
                @click="() => handleChangeTimeScale(scale.value)"
                class="w-full my-1 ml-0 rounded-xl hover:bg-sky-200 hover:dark:bg-sky-900 transition-colors"
            >
                {{ scale.title }}
            </button>
        </div>
        <div class="chart-container">
            <LWChart
                :type="chartType"
                :data="data"
                :autosize="true"
                :chartOptions="chartOptions"
                :seriesOptions="seriesOptions"
                :timeScaleOptions="timeScalesOptions"
                :priceScaleOptions="priceScaleOptions"
                :selectedTimeScale="selectedTimeScale"
                ref="lwChart"
            />
        </div>
    </div>
</template>

<style scoped>
.chart-container {
    height: calc(100% - 3.2em);
}
</style>
