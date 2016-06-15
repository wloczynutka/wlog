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

    private function loadTranslatorService()
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        return $kernel->getContainer()->get('translator');
    }

    private function prepareCostTypeTranslations()
    {
        $translator = $this->loadTranslatorService();
        $this->carCostTypes = [
            1 => [
                'name' => $translator->trans('reperair'),
            ],
            2 => [
                'name' => $translator->trans('period service'),
            ],
            3 => [
                'name' => $translator->trans('accesory'),
            ],
            4 => [
                 'name' => $translator->trans('parts'),
            ],
            5 => [
                'name' => $translator->trans('insurance'),
            ],
            6 => [
               'name' => $translator->trans('tax'),
            ],
            7 => [
               'name' => $translator->trans('other'),
            ],
            8 => [
               'name' => $translator->trans('camper conversion'),
            ],
            9 => [
               'name' => $translator->trans('technical examination'),
            ],
        ];
    }

}
