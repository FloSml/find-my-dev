<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article"
            ])
            ->add('shortDescription', TextType::class, [
                'label' => "Résumé de l'article"
            ])
            ->add('description', CKEditorType::class, [
                'label' => "Contenu de l'article"
            ])
            ->add('user', EntityType::class, [
                // je sélectionne l'entité User car ma relation fait référence aux membres
                'class' => User::class,
                'choice_label' => function(User $user){
                    return $user->getFirstname().' '.$user->getLastName();
                },
                'label' => 'Auteur',
                'placeholder' => 'Choisissez un auteur',
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => "Catégorie",
                'placeholder' => 'Choisissez une catégorie',
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-fiche'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
