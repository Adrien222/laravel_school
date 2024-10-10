<?php

namespace App\Helpers;

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

    private static function getCurrencyRate($currency)
    {
        return crc32($currency) / 10_000_000_000;
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
