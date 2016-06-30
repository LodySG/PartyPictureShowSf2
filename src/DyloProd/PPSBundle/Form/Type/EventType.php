<?php

namespace DyloProd\PPSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("titre", TextType::class, array("label" => "Titre : "))
            ->add("hDebut",DateTimeType::class, array(
                "label" => "Heure de début : ",
                "format" => "dd/MM/yyyy H:mm"
                ))
            ->add("hFin",DateTimeType::class,array(
                "label" => "Heure de fin : ",
                "format" => "dd/MM/yyyy H:mm"
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DyloProd\PPSBundle\Entity\Event',
        ));
    }
}