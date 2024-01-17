<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'form-control font-semibold'],
                'attr' => ['class' => 'input input-bordered input-accent w-full']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'label_attr' => ['class' => 'form-control font-semibol '],
                'attr' => ['class' => 'textarea w-full textarea-accent textarea-bordered'],
                'required' => false,
            ])
            ->add('image',FileType::class, [
                'label' => 'Image',
                'label_attr' => ['class' => 'form-control font-semibold'],
                'attr' => ['class' => 'file-input file-input-bordered file-input-accent w-full max-w-xs'],
                'required' => false,
            ])
            ->add('published', CheckboxType::class, [
                'label' => 'Publié',
                'label_attr' =>['class' => 'form-control font-semibold'],
                'attr' => ['class' => 'toggle toggle-accent'],
                'required' => false
            ])
            ->add('niveau', EntityType::class, [
                'class' => Niveau::class,
                'label_attr' => ['class' => 'form-control font-semibold'],
                'attr' => ['class' => 'select select-accent w-full'],
                'choice_label' => 'name',
                'choice_value' => 'id'
            ])
            ->add('matiere', EntityType::class, [
                'label' => 'Matière',
                'class' => Matiere::class,
                'label_attr' => ['class' => 'form-control font-semibold'],
                'attr' => ['class' => 'select select-accent w-full'],
                'choice_label' => 'name',
                'choice_value'=> 'id'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
