import {
    SeriesOptionsCommon,
    CandlestickData,
    WhitespaceData,
    LineData,
    AreaData,
    BarData,
    HistogramData,
    BaselineData,
    TimeScaleOptions,
    PriceScaleOptions,
    ChartOptions,
} from "lightweight-charts";

export type ColorsTypeMap = Record<string, Array<[string, number]>>;

export interface DataPoint {
    time: number;
    value: number;
}

export interface ChartProps {
    type?: string | undefined;
    data: (
        | WhitespaceData
        | CandlestickData
        | LineData
        | AreaData
        | BarData
        | HistogramData
        | BaselineData
    )[];
    autosize?: boolean;
    chartOptions?: ChartOptions;
    seriesOptions?: SeriesOptionsCommon;
    timeScaleOptions?: TimeScaleOptions;
    priceScaleOptions?: PriceScaleOptions;
}
