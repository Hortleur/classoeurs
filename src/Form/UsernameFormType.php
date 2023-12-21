<?php

namespace App\Form;

use App\Entity\Niveau;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UsernameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'label_attr' => ['class' => 'flex flex-col'],
                'attr' => ['class' => 'input input-bordered '],
                'required' => false
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Bio',
                'label_attr' => ['class' => 'flex flex-col'],
                'attr' => ['class' => 'textarea textarea-bordered textarea-primary'],
                'required' => false
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de profil',
                'label_attr' => ['class' => 'flex flex-col'],
                'attr' => ['class' => 'file-input w-full max-w-xs'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Téléversez un document jpeg, jpg, ou png valide'
                    ])
                ]
            ])
            ->add('niveau', EntityType::class, [
                'label' => 'Niveau',
                'label_attr' => ['flex flex-col'],
                'attr' => ['class' => 'select'],
                'class' => Niveau::class,
                'choice_label' => 'name',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
