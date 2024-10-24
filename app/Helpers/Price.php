<?php

namespace App\Helpers;

use App\Services\RateService;
use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;
use Stringable;

class Price implements Arrayable, Stringable
{
    private readonly float $currency_rate;

    public function __construct(
        private readonly string $currency,
        private readonly float $amount,
        float $currency_rate = null,
    )
    {
        if (! $this->currencyExists($currency)) {
            throw new InvalidArgumentException('The currency doesn\'t exist');
        }

        $this->currency_rate = $currency_rate ?? $this->getCurrencyRate($this->currency);
    }

    private static function currencyExists($currency)
    {
        return str($currency)->length() === 3;
    }

    private function getCurrencyRate($currency)
    {
        // On récupère l'instance du service RateService via le service container
        $rateService = app(RateService::class);
        
        // On retourne le taux de change obtenu depuis le service
        return $rateService->getRateFromCurrency($currency);
    }

    public static function fromEuros(float $price)
    {
        return new self('EUR', $price);
    }

    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'currency_rate' => $this->currency_rate,
        ];
    }

    public function __toString()
    {
        return "$this->amount $this->currency";
    }
}
