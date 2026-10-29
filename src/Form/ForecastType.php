<?php

namespace App\Form;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperatura', NumberType::class, [
                'scale' => 2,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('humidity', NumberType::class, [
                'scale' => 2,
            ])
            ->add('pressure', NumberType::class, [
                'scale' => 2,
            ])
            ->add('wind', NumberType::class, [
                'scale' => 2,
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}
