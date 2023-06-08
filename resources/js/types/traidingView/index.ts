import { ChartOptions } from "chart.js";
import { DeepPartial, LineStyleOptions ,SeriesOptionsCommon} from "lightweight-charts";

export interface SeriesOptions {
  color?: string;
  // Add other properties if necessary
}

export type ColorsTypeMap = Record<string, Array<[string, number]>>;

export interface ApiData {
  prices?: [number, number][]; // Array of tuples containing [time, price]
}

export interface DataPoint {
  time: number;
  value: number;
}

export interface ChartProps {
  type?: string|undefined;
  data: { time: number; value: number }[];
  autosize?: boolean;
  chartOptions?: DeepPartial<ChartOptions>;
  seriesOptions?: LineSeriesOptions | Record<string, any>;
  timeScaleOptions?: Record<string, any>;
  priceScaleOptions?: Record<string, any>;
}

export interface IChartApi {
  // Existing properties and methods of the IChartApi interface

  [key: string]: any; // Index signature to allow any other properties/methods
}

export interface LineSeriesOptions extends DeepPartial<LineStyleOptions & SeriesOptionsCommon> {
  // Additional properties specific to line series options
  // ...
}