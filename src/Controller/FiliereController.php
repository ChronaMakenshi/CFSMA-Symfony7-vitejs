<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Filieres;
use App\Entity\Sections;
use App\Entity\Compagnies;
use App\Form\FiliereType;
use App\Repository\SectionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FiliereController extends AbstractController
{
    #[Route('/addfiliere', name: 'app_filiere')]
    #[Route('/addfiliere/{id}', name: 'app_filiere_edit', requirements: ['id' => '\d+'])]
    public function ajoutefiliere(?Filieres $filiere = null, Request $request,ManagerRegistry $fil, EntityManagerInterface $manager): Response
    {
        $filieres = $fil->getRepository(filieres::class)->findAll();
        $sections = $fil->getRepository(Sections::class)->findAll();
        $compagnies = $fil->getRepository(Compagnies::class)->findAll();

        if (!$filiere) {
            $filiere = new Filieres();
        }

        $form = $this->createForm(FiliereType::class, $filiere, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($filiere);
            $manager->flush();

            $this->addFlash('success', 'Votre message a été bien ajouté ou modifié');

            return $this->redirectToRoute('app_filiere');
        }

        return $this->render('filiere/addfiliere.html.twig', [
            'filieres' =>  $filieres,
            'sections' =>  $sections,
            'compagnies' =>  $compagnies,
            'formFiliere' => $form->createView(),
            'EditMode' => $filiere->getId() !== null,
        ]);
    }
    #[Route('/addfiliere/delete/{id}', name: 'deletefiliere')]
    public function deletefiliere(int $id, ManagerRegistry $fil, EntityManagerInterface $manager): Response
    {
        $filiere = $fil->getRepository(Filieres::class)->find($id);
        if (!$filiere) {
            throw $this->createNotFoundException('La filière n\'existe pas');
        }

        $manager->remove($filiere);
        $manager->flush();

        $this->addFlash('success', 'La filière ' . $filiere->getName() . ' a été supprimée');

        return $this->redirectToRoute('app_filiere');
    }
}
