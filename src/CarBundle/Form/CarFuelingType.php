<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CarBundle\Entity\TranslationContainer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarFuelingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach (TranslationContainer::Instance()->fuelTypes as $typeId => $typeDetails) {
            $fuelTypes[$typeDetails['name']] = $typeId;
        }
        $currency = new \CarBundle\Entity\Currency();

        $builder
            ->add('dateTime', 'date', ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('litresTanked')
            ->add('pricePerLiter')
            ->add('amount')
            ->add('currency', ChoiceType::class, [
                'choices'  => $currency->getCurrencies(),
                'choices_as_values' => false,
            ])
            ->add('mileage')
            ->add('fuelType', ChoiceType::class, [
                'choices'  => $fuelTypes,
                'choices_as_values' => true,
            ])
            ->add('averageConsumptionByComputer', 'text', array('required' => false))
            ->add('averageSpeed',  'text', array('required' => false))
            ->add('driveTime',  'text', array('required' => false))
            ->add('tripDistance')
            ->add('save', 'submit', array('label' => 'Save'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarBundle\Entity\CarFueling'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carbundle_carfueling';
    }
}
