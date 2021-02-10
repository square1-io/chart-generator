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
     * @param bool   $asSVG
     *
     * @return \Intervention\Image\Image|string|null
     */
    public function createChart(string $type, int $width, int $height, array $datasets, array $options, bool $asSVG = false)
    {
        if (config('chart-generator.url')) {
            $request = Http::withHeaders([
                'X-CHART-ACCOUNT' => config('chart-generator.key'),
                'X-CHART-HASH' => hash('sha512', config('chart-generator.key').config('chart-generator.secret')),
            ]);

            $options['asSVG'] = $asSVG;

            $response = $request->post(rtrim(config('chart-generator.url'), '/').'/draw', [
                'type' => $type,
                'size' => [
                    'width' => $width,
                    'height' => $height,
                ],
                'datasets' => $datasets,
                'options' => $options,
                'format' => 'buffer',
            ]);

            if ($response->ok()) {
                return $asSVG ? $response->body() : Image::make($response->body())->encode();
            }
        }

        return null;
    }

    /**
     * Create the mixed chart using the microservice
     *
     * @param string $title
     * @param int    $width
     * @param int    $height
     * @param array  $datasets
     * @param bool   $asSVG
     *
     * @return \Intervention\Image\Image|string|null
     */
    public function createMixedChart(string $title, int $width, int $height, array $datasets, bool $asSVG = false)
    {
        if (config('chart-generator.url')) {
            $request = Http::withHeaders([
                'X-CHART-ACCOUNT' => config('chart-generator.key'),
                'X-CHART-HASH' => hash('sha512', config('chart-generator.key').config('chart-generator.secret')),
            ]);

            $response = $request->post(rtrim(config('chart-generator.url'), '/').'/draw', [
                'type' => 'mixed',
                'size' => [
                    'width' => $width,
                    'height' => $height,
                ],
                'datasets' => [
                    'mixed' => $datasets,
                ],
                'options' => [
                    'title' => $title,
                    'asSVG' => $asSVG,
                ],
                'format' => 'buffer',
            ]);

            if ($response->ok()) {
                return $asSVG ? $response->body() : Image::make($response->body())->encode();
            }
        }

        return null;
    }
}
