<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RateService
{
    public function __construct(
        private readonly string $url
    )
    {
    }

    public function getRateFromCurrency(string $currency): float
    {
        return (float) Http::get($this->url, compact('currency'))->json('Rate');
    }
}
