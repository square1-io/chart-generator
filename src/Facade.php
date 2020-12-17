<?php

namespace Square1\ChartGenerator;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return ChartGenerator::class;
    }
}
