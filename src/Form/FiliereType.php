<?php

namespace App\Form;

use App\Entity\Filieres;
use App\Entity\Sections;
use App\Repository\SectionsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\SecurityBundle\Security;

class FiliereType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('name', TextType::class, ['label' => false])
            ->add('section', EntityType::class, [
                'class' => Sections::class,
                'choice_label' => fn(Sections $sect) => $sect->getCompagnie()->getName() . ' - ' . $sect->getName(),
                'query_builder' => function (SectionsRepository $sectionRepo) use ($user) {
                    $qb = $sectionRepo->createQueryBuilder('s')->orderBy('s.name', 'ASC');

                    // Si l'utilisateur n'est ni admin ni super admin, on filtre les sections
                    if (!in_array('ROLE_ADMIN', $user->getRoles(), true) && !in_array('ROLE_SUPERADMIN', $user->getRoles(), true)) {
                        $qb->where('s.id = :sectionId')->setParameter('sectionId', $user->getSection()->getId());
                    }

                    return $qb;
                },
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filieres::class,
            'user' => null,  // Définir l'option par défaut pour l'utilisateur
        ]);

        $resolver->setDefined(['user']);
    }
}