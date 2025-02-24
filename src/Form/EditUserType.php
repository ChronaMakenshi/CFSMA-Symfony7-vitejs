<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Sections;
use App\Repository\SectionsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username')
        ->add('plainPassword', RepeatedType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'type' => PasswordType::class,
            'mapped' => false,
            'required' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'first_options'  => ['label' => 'Mot de passe :'],
            'second_options' => ['label' => 'Confirmer :'],
            'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
            'options' => ['attr' => ['class' => 'password-field']],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe devrait être au moins entrer un mot de passe {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 12,
                ]),
            ],
        ])
        ->add('roles', ChoiceType::class, [
                'label' => false,
                'expanded' => false,
                'multiple' => true,
                'choices' => [
                    'SuperAdmin' => 'ROLE_SUPERADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Chef de Section' => 'ROLE_CHEF',
                ],
        ])
        ->add('section', EntityType::class, [
            'class' => Sections::class,
            'choice_label' => fn(Sections $sect) => $sect->getCompagnie()->getName() . '-' . $sect->getName(),
            'choice_value' => 'id',
            'query_builder' => fn(SectionsRepository $sectionRepo) =>  $sectionRepo->createQueryBuilder('s')->orderBy('s.name', 'ASC'),
            'label' => false,
          ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
