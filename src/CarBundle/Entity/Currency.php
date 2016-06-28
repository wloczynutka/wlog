<?php

namespace CarBundle\Entity;

/**
 * Container with all Currencies used in CarBundle
 *
 * @author Łza
 */
class Currency
{
    private $currencies = [
        'BGN' => 'BGN',
        'CHF' => 'CHF',
        'CZK' => 'CZK',
        'DKK' => 'DKK',
        'GBP' => 'GBP',
        'HRK' => 'HRK',
        'HUF' => 'HUF',
        'LTL' => 'LTL',
        'NOK' => 'NOK',
        'EUR' => 'EUR',
        'PLN' => 'PLN',
        'RON' => 'RON',
        'RUR' => 'RUR',
        'SEK' => 'SEK',
        'UAH' => 'UAH',
        'USD' => 'USD',
    ];

    public function getCurrencies()
    {
        return $this->currencies;
    }
}
