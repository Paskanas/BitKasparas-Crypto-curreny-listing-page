<script setup lang="ts">
import {
    ref,
    onMounted,
    onUnmounted,
    watch,
    defineExpose,
    defineProps,
} from "vue";
import { ChartProps } from "../../types/traidingView";
import {
    IChartApi,
    ISeriesApi,
    createChart,
    WhitespaceData,
    TimeScaleOptions,
    SeriesOptionsCommon,
    PriceScaleOptions,
    CandlestickData,
    LineData,
    AreaData,
    BarData,
    HistogramData,
    BaselineData,
    ChartOptions,
} from "lightweight-charts";

const props = defineProps<{
    type: string | undefined;
    data: (
        | WhitespaceData
        | CandlestickData
        | LineData
        | AreaData
        | BarData
        | HistogramData
        | BaselineData
    )[];
    autosize: boolean;
    chartOptions: ChartOptions;
    seriesOptions: SeriesOptionsCommon;
    timeScaleOptions: TimeScaleOptions;
    priceScaleOptions: PriceScaleOptions;
    selectedTimeScale: string;
}>();

// Function to get the correct series constructor name for the current series type.
function getChartSeriesConstructorName(type: string): keyof IChartApi {
    return `add${
        type.charAt(0).toUpperCase() + type.slice(1)
    }Series` as keyof IChartApi;
}

// Lightweight Chart instances are stored as normal JS variables
// If you need to use a ref, it is recommended that you use `shallowRef` instead
let series: ISeriesApi<
    "Line" | "Area" | "Bar" | "Candlestick" | "Histogram" | "Baseline"
> | null = null;
let chart: IChartApi | null = null;
let timeS: TimeScaleOptions | null = null;
const chartContainer = ref();

const fitContent = () => {
    if (!chart) return;
    chart.timeScale().fitContent();
};

defineExpose({ fitContent });

// Auto resizes the chart when the browser window is resized.
const resizeHandler = () => {
    if (!chart || !chartContainer.value) return;
    const dimensions = chartContainer.value.getBoundingClientRect();
    chart.resize(dimensions.width, dimensions.height);
};

const settingVisibleRange = () => {
    if (props.selectedTimeScale === "max") {
        chart?.timeScale().fitContent();
    } else {
        const currentDate = new Date().getTime() / 1000; //time in seconds
        const from =
            new Date().getTime() / 1000 -
            +props.selectedTimeScale * 24 * 60 * 60; //time in seconds
        chart?.timeScale().setVisibleRange({
            //@ts-ignore
            from: from,
            //@ts-ignore
            to: currentDate,
        });
    }
};

// Creates the chart series and sets the data.
const addSeriesAndData = (props: ChartProps) => {
    const seriesConstructor = getChartSeriesConstructorName(props.type ?? "");

    //@ts-ignore
    series = chart[seriesConstructor](props.seriesOptions);

    series?.setData(props.data);
};

onMounted(() => {
    // Create the Lightweight Charts Instance using the container ref.

    chart = createChart(chartContainer.value, props.chartOptions);
    if (series && chart) {
        chart.removeSeries(series);
    }

    addSeriesAndData(props);

    if (props.timeScaleOptions) {
        chart.timeScale().applyOptions(props.timeScaleOptions);
    }

    chart.timeScale().fitContent();

    if (props.autosize) {
        window.addEventListener("resize", resizeHandler);
    }
});

onUnmounted(() => {
    if (chart) {
        chart.remove();
        chart = null;
    }
    if (series) {
        series = null;
    }
});

/*
 * Watch for changes to any of the component properties.
 *
 * If an options property is changed, then we will apply those options
 * on top of any existing options previously set (since we are using the
 * `applyOptions` method).
 *
 * If there is a change to the chart type, then the existing series is removed
 * and the new series is created and assigned the data.
 */
watch(
    () => props.autosize,
    (enabled) => {
        if (!enabled) {
            window.removeEventListener("resize", resizeHandler);
            return;
        }
        window.addEventListener("resize", resizeHandler);
    }
);

watch(
    () => props.data,
    () => {
        if (series && chart) {
            chart.removeSeries(series);
        }
        addSeriesAndData(props);
        settingVisibleRange();
    }
);

watch(
    () => props.chartOptions,
    (newOptions) => {
        if (!chart) return;
        chart.applyOptions(newOptions);
    }
);

watch(
    () => props.seriesOptions,
    (newOptions) => {
        if (!series) return;
        series.applyOptions(newOptions);
    }
);

watch(
    () => props.priceScaleOptions,
    (newOptions) => {
        if (!chart) return;
        //@ts-ignore
        chart.priceScale().applyOptions(newOptions);
    }
);

watch(
    () => props.timeScaleOptions,
    (newOptions) => {
        if (!chart) return;
        chart.timeScale().applyOptions(newOptions);
    }
);
</script>

<template>
    <div class="h-full" ref="chartContainer"></div>
</template>
