<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarFuelingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateTime', 'date', ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('litresTanked')
            ->add('pricePerLiter')
            ->add('amount')
            ->add('currency')
            ->add('mileage')
            ->add('fuelType')
            ->add('averageConsumptionByComputer',  'text', array('required' => false))
            ->add('averageSpeed',  'text', array('required' => false))
            ->add('driveTime',  'text', array('required' => false))
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
