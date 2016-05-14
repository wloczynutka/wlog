<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $fuelChoices = [];
        foreach (\CarBundle\Entity\Car::$fuelTypes as $fuelId => $details) {
            $fuelChoices[$details['name']] = $fuelId;
        }

        $builder
            ->add('make', EntityType::class, [
                'class' => 'CarBundle:CarDictionaryMake',
                'choice_label' => function ($make) {
                    return $make->getName();
                }
            ])
            ->add('model', EntityType::class, array(
                'class' => 'CarBundle:CarDictionaryModel',
                'choice_label' => function ($model) {
                    return $model->getName();
                }
            ))
            ->add('user')
            ->add('manufactureDate')
            ->add('color')
            ->add('fuel', ChoiceType::class, [
                'choices'  => $fuelChoices,
                'choices_as_values' => true,
            ])
            ->add('save', 'submit', array('label' => 'Save'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarBundle\Entity\Car'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'carbundle_car';
    }
}
