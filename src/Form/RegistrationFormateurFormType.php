<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Users;
use App\Entity\Filieres;
use App\Repository\FilieresRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormateurFormType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('username')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
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
                        'minMessage' => 'Votre mot de passe devrait être au moins {{ limit }} caractères',
                        'max' => 12,
                    ]),
                ],
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'label' => false,
                    'allow_extra_fields' => true,
                    'choices' => [
                        'Formateur' => 'ROLE_FORMATEUR',
                    ],
                ],
            ])
            ->add('filiere', EntityType::class, [
                'class' => Filieres::class,
                'choice_label' => function (Filieres $fil) {
                    return $fil->getSection()->getName() . '-' . $fil->getName();
                },
                'query_builder' => function (FilieresRepository $filiereRepo) use ($user) {
                    $qb = $filiereRepo->createQueryBuilder('f')
                        ->join('f.section', 's')
                        ->orderBy('s.name', 'ASC');

                    // Si l'utilisateur n'est ni admin ni super admin, on filtre les filières
                    if (!in_array('ROLE_ADMIN', $user->getRoles(), true) && !in_array('ROLE_SUPERADMIN', $user->getRoles(), true)) {
                        $qb->where('s.id = :sectionId')
                            ->setParameter('sectionId', $user->getSection()->getId());
                    }

                    return $qb;
                },
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}