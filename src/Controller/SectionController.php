<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sections;
use App\Entity\Compagnies;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SectionController extends AbstractController
{
    #[Route('/addsection', name: 'app_section')]
    #[Route('/addsection/{id}', name: 'app_section_Edit', requirements: ['id' => '\d+'])]
    public function ajoutesection(Sections $section = null, Request $request, ManagerRegistry $sect, EntityManagerInterface $manager): Response
    {
        $sections = $sect->getRepository(Sections::class)->findAll();
        $compagnies = $sect->getRepository(Compagnies::class)->findAll();

        if (!$section) {
            $section = new Sections();
        }

        $form = $this->createFormBuilder($section)
            ->add('name', TextType::class, ['label' => false])
            ->add('compagnie', EntityType::class, [
                'class' => Compagnies::class,
                'choice_label' => 'name',
                'label' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($section);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');

            return $this->redirectToRoute('app_section');
        }

        return $this->render('section/addsection.html.twig', [
            'sections' => $sections,
            'compagnies' => $compagnies,
            'formSection' => $form->createView(),
            'EditMode' => $section->getId() !== null,
        ]);
    }

    #[Route('/addsection/delete/{id}', name: 'delete_section')]
    public function deletesection(int $id, ManagerRegistry $sect, EntityManagerInterface $manager): Response
    {
        $section = $sect->getRepository(Sections::class)->find($id);
        if (!$section) {
            throw $this->createNotFoundException('La section n\'existe pas');
        }

        $manager->remove($section);
        $manager->flush();

        $this->addFlash('success', 'La section ' . $section->getName() . ' a été supprimée');

        return $this->redirectToRoute('app_section');
    }
}
