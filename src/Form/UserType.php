<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email*",
            ])
            ->add('lastName', TextType::class, [
                'label' => "Nom*",
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom*",
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone",
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => "Ville*",
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Code postal*",
            ])
            ->add('speciality', TextType::class, [
                'label' => "Profession*"
            ])
            ->add('lookingForJob', CheckboxType::class, [
                'label' => "Vous recherchez un job ?",
                'required' => false
            ])
            ->add('resume', TextareaType::class, [
                'label' => "Présentez-vous en quelques lignes.",
                'required' => false
            ])
            ->add('currentJob', TextType::class, [
                'label' => "Société",
                'required' => false
            ])
            ->add('experience', ChoiceType::class, [
                'choices' => [
                    '< 1 an' => '< 1 an',
                    '1 an' => '1 an',
                    '2 ans' => '2 ans',
                    '3 ans' => '3 ans',
                    '4 ans' => '4 ans',
                    '5 ans' => '5 ans',
                    '+5 ans' => '+5 ans'
                ],
                'placeholder' => 'Votre expérience à ce poste',
                'label' =>  "Expérience*",
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme'
                ],
                'placeholder' => 'Sexe',
                'label' =>  "Sexe*",
                'expanded' => false,
                'required' => true
            ])
            ->add('imageFile', FileType::class, [
                'label' =>  "Ajoutez votre photo",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}