<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CarBundle\Entity;

/**
 * Description of TranslationContainer
 *
 * @author Łza
 */
final class TranslationContainer
{
    public $carCostTypes;

    /**
     * Call this method to get singleton
     *
     * @return TranslationContainer
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new TranslationContainer();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    {
        $this->prepareCostTypeTranslations();
    }

    private function prepareCostTypeTranslations()
    {
        $this->carCostTypes = [
            1 => [
                'name' => _('reperair'),
            ],
            2 => [
                'name' => _('period service'),
            ],
            3 => [
                'name' => _('accesory'),
            ],
            4 => [
                 'name' => _('parts'),
            ],
            5 => [
                'name' => _('insurance'),
            ],
            6 => [
               'name' => _('tax'),
            ],
            7 => [
               'name' => _('other'),
            ],
            8 => [
               'name' => _('camper conversion'),
            ],
            9 => [
               'name' => _('technical examination'),
            ],
        ];
    }

}
