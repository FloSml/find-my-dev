<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Email*",
            ])
            ->add('password', PasswordType::class, [
                'label' => "Mot de passe*",
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => "Confirmation du mot de passe*",
            ])
            ->add('nom', TextType::class, [
                'label' => "Nom*",
            ])
            ->add('prenom', TextType::class, [
                'label' => "Prénom*",
            ])
            ->add('telephone', TextType::class, [
                'label' => "Téléphone",
                'required' => false
            ])
            ->add('ville', TextType::class, [
                'label' => "Ville*",
            ])
            ->add('specialite', TextType::class, [
                'label' => "Profession*",
            ])
            ->add('recherchePoste', CheckboxType::class, [
                'label' => "Recherche un job ?",
                'required' => false
            ])
            ->add('resume', TextareaType::class, [
                'label' => "Présentez-vous en quelques lignes.",
                'required' => false
            ])
            ->add('avatar', FileType::class, [
                'label' => "Importez votre photo",
                'required' => false
            ])
            ->add('enPoste', TextType::class, [
                'label' => "Société",
                'required' => false
            ])
            ->add('experience', ChoiceType::class, [
                'choices' => [
                    'Pas encore d\'expérience' => 'Pas encore',
                    '1 an' => '1 an',
                    '2 ans' => '2 ans',
                    '3 ans' => '3 ans',
                    '4 ans' => '4 ans',
                    '5 ans' => '5 ans',
                    '+5 ans' => '+5 ans',
                ],
                'placeholder' => 'Votre expérience',
                'label' =>  "Expérience",
                'required' => false
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
