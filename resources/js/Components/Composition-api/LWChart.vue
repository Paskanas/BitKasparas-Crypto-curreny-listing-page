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
    DeepPartial,
} from "lightweight-charts";
import { ChartOptions } from "chart.js";

const props: ChartProps = defineProps({
    type: {
        type: String,
        default: "line",
    },
    data: {
        type: Array as () => Array<{ time: number; value: number }>,
        required: true,
    },
    autosize: {
        default: false,
        type: Boolean,
    },
    chartOptions: {
        type: Object as () => DeepPartial<ChartOptions>,
    },
    seriesOptions: {
        type: Object,
    },
    timeScaleOptions: {
        type: Object,
    },
    priceScaleOptions: {
        type: Object,
    },
    selectedTimeScale: {
        type: String,
        default: 1,
    },
});

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

const chartContainer = ref();

const fitContent = () => {
    if (!chart) return;
    chart.timeScale().fitContent();
};

const getChart = () => {
    return chart;
};

defineExpose({ fitContent, getChart });

// Auto resizes the chart when the browser window is resized.
const resizeHandler = () => {
    if (!chart || !chartContainer.value) return;
    const dimensions = chartContainer.value.getBoundingClientRect();
    chart.resize(dimensions.width, dimensions.height);
};

// Creates the chart series and sets the data.
const addSeriesAndData = (props: ChartProps) => {
    const seriesConstructor = getChartSeriesConstructorName(props.type ?? "");
    //@ts-ignore
    series = chart[seriesConstructor](props.seriesOptions);
    //@ts-ignore
    series.setData(props.data);
};

onMounted(() => {
    // Create the Lightweight Charts Instance using the container ref.
    //@ts-ignore
    chart = createChart(chartContainer.value, props.chartOptions);

    addSeriesAndData(props);

    if (props.priceScaleOptions) {
        //@ts-ignore
        chart.priceScale().applyOptions(props.priceScaleOptions);
    }

    if (props.timeScaleOptions) {
        chart.timeScale().applyOptions(props.timeScaleOptions);
    }

    // const toDate = new Date(); // Current date
    // const fromDate = new Date();
    // fromDate.setDate(fromDate.getDate() - 7); // Subtract 7 days

    // chart.timeScale().setVisibleRange({
    //     from: "2018-06-25",
    //     to: "2018-07-25",
    // });

    // chart.timeScale().applyOptions();
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
    () => props.type,
    (newType) => {
        if (series && chart) {
            chart.removeSeries(series);
        }
        addSeriesAndData(props);
    }
);

watch(
    () => props.data,
    (newData) => {
        if (!series) return;
        //@ts-ignore
        series.setData(newData);
    }
);

watch(
    () => props.chartOptions,
    (newOptions) => {
        if (!chart) return;

        //@ts-ignore
        chart.applyOptions(newOptions);
    }
);

watch(
    () => props.seriesOptions,
    (newOptions) => {
        if (!series) return;
        //@ts-ignore
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
        //@ts-ignore
        chart.timeScale().applyOptions(newOptions);
    }
);
</script>

<template>
    <div class="lw-chart" ref="chartContainer"></div>
</template>

<style scoped>
.lw-chart {
    height: 100%;
}
</style>
