<?php


namespace Square1\ChartGenerator;

use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

class ChartGenerator
{
    /**
     * Create the chart using the microservice
     *
     * @param string $type
     * @param int    $width
     * @param int    $height
     * @param array  $datasets
     * @param array  $options
     *
     * @return \Intervention\Image\Image|null
     */
    public function createChart(string $type, int $width, int $height, array $datasets, array $options): ?\Intervention\Image\Image
    {
        if (config('chart-generator.url')) {
            $request = Http::withHeaders([
                'X-CHART-ACCOUNT' => config('chart-generator.key'),
                'X-CHART-HASH' => hash('sha512', config('chart-generator.key').config('chart-generator.secret')),
            ]);

            $response = $request->post(rtrim(config('chart-generator.url'), '/').'/draw', [
                'type' => $type,
                'size' => [
                    'width' => $width,
                    'height' => $height,
                ],
                'datasets' => $datasets,
                'options' => $options,
                'format' => 'buffer'
            ]);

            if ($response->ok()) {
                return Image::make($response->body())->encode();
            }
        }

        return null;
    }
}
