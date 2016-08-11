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
    public $fuelTypes;
    public $errorMessages;

    private $translator;

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
        $this->loadTranslatorService();
        $this->prepareCostTypeTranslations();
        $this->prepareFuelTypeTranslations();
        $this->prepareErrorTranslations();
    }

    private function loadTranslatorService()
    {
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $this->translator = $kernel->getContainer()->get('translator');
    }

    private function prepareErrorTranslations()
    {
        $this->errorMessages = [
            1 => $this->translator->trans('Sory, You have no privileages to add/edit this item. Please check if you are logged in.')
        ];
    }

    private function prepareCostTypeTranslations()
    {
        $this->carCostTypes = [
            1 => [
                'name' => $this->translator->trans('reperair'),
            ],
            2 => [
                'name' => $this->translator->trans('period service'),
            ],
            3 => [
                'name' => $this->translator->trans('accesory'),
            ],
            4 => [
                 'name' => $this->translator->trans('parts'),
            ],
            5 => [
                'name' => $this->translator->trans('insurance'),
            ],
            6 => [
               'name' => $this->translator->trans('tax'),
            ],
            7 => [
               'name' => $this->translator->trans('other'),
            ],
            8 => [
               'name' => $this->translator->trans('camper conversion'),
            ],
            9 => [
               'name' => $this->translator->trans('technical examination'),
            ],
            10 => [
               'name' => $this->translator->trans('tool roads'),
            ]
        ];
    }

     private function prepareFuelTypeTranslations()
     {
         $this->translator->trans('Fuel type');
         $this->fuelTypes = [
            1 => [
                'name' => $this->translator->trans('petrol'),
            ],
            2 => [
                'name' => $this->translator->trans('diesel'),
            ],
            3 => [
                'name' => $this->translator->trans('LPG'),
            ],
         ];
     }

}
