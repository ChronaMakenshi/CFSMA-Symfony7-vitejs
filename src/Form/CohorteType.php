<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Filieres;
use App\Repository\FilieresRepository;
use App\Repository\SectionsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\SecurityBundle\Security;

class CohorteType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('name', TextType::class, ['label' => false])
            ->add('filiere', EntityType::class, [
                'class' => Filieres::class,
                'choice_label' => function (Filieres $fil) {
                    return $fil->getSection()->getName() . ' - ' . $fil->getName();
                },
                'query_builder' => function (EntityRepository $filieresRepository) use ($user) {
                    $qb =  $filieresRepository->createQueryBuilder('f')
                        ->join('f.section', 's')
                        ->orderBy('f.name', 'ASC');

                    // Si l'utilisateur n'est ni admin ni super admin, on filtre les sections et filières
                    if (!in_array('ROLE_ADMIN', $user->getRoles(), true) && !in_array('ROLE_SUPERADMIN', $user->getRoles(), true)) {
                        if (in_array('ROLE_CHEF', $user->getRoles(), true)) {
                            // Filtrer par section pour les chefs de section
                            $qb->where('s.id = :sectionId')->setParameter('sectionId', $user->getSection()->getId());
                        } elseif (in_array('ROLE_FORMATEUR', $user->getRoles(), true)) {
                            // Filtrer par formateur pour les formateurs
                            $qb->where('f.id = :sectionId')->setParameter('sectionId', $user->getFiliere()->getId());
                        }
                    }

                    return $qb;
                },
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classes::class,
            'user' => null,
        ]);

        $resolver->setDefined(['user']);
    }
}