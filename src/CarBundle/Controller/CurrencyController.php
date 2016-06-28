<?php

namespace CarBundle\Controller;

/**
 * Description of CurrencyController
 *
 * @author Łza
 */
class CurrencyController
{

    private $url = 'http://api.nbp.pl/api/exchangerates/rates/C/{currencyCode}/?format=json';

    public function getExchangeRate($currencyFrom, $currencyTo)
    {
        if($currencyFrom === $currencyTo){
            return 1;
        }
        if($currencyTo === 'PLN'){
            return $this->getRateFromNBP($currencyFrom);
        }
        ddd(' Nie można obliczyć kursu - TODO');
    }

    private function getRateFromNBP($currencyFrom)
    {
        $url = str_replace('{currencyCode}', $currencyFrom, $this->url);
        $response = json_decode(file_get_contents($url));
        return $response->rates[0]->ask;
    }
}
