<?php

namespace CarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CarBundle\Entity\TranslationContainer;

class CarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $fuelTypes = [];
        foreach (TranslationContainer::Instance()->fuelTypes as $typeId => $typeDetails) {
            $fuelTypes[$typeDetails['name']] = $typeId;
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
            ->add('manufactureDate', 'date', ['widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('color')
            ->add('fuel', ChoiceType::class, [
                'choices'  => $fuelTypes,
                'choices_as_values' => true,
            ])
            ->add('purchasePrice')
            ->add('ownName')
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
