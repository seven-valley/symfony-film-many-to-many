<?php

namespace App\Form;

use App\Entity\Film;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('test', DateType::class, [
                'widget' => 'single_text',
                ])
            ->add('acteurs',null,[ 'choice_label'=>'nom' , 'mapped'=>false]);
            //->add('acteurs');
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
