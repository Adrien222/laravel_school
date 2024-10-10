<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Price;

class PriceCast implements CastsAttributes
{
    /**
     * Convertir la valeur stockée en base de données dans une instance de la classe Price.
     * Deserialise Json -> objet
     * @param  mixed  $value
     * @return Price
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $decoded = json_decode($value, true);
        
        if (is_null($decoded)) {
            throw new InvalidArgumentException("La valeur du champ $key n'est pas un JSON valide.");
        }

        return new Price(
            $decoded['currency'],
            $decoded['amount'],
            $decoded['currency_rate']
        );
    }

    /**
     * Convertir l'instance de la classe Price en JSON pour la base de données.
     * Serialize objet -> Json
     * @param  mixed  $value
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof Price) {
            throw new InvalidArgumentException('La valeur doit être une instance de la classe Price.');
        }

        return $value->toArray();
    }
}
