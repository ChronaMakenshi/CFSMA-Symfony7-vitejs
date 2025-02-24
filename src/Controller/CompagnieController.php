<?php

namespace App\Controller;

use App\Entity\Compagnies;
use App\Repository\CompagniesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CompagnieController extends AbstractController
{
    #[Route('/addcompagnie/', name: 'app_compagnie', methods: ['GET', 'POST'])]
    #[Route('/addcompagnie/{id}', name: 'app_compagnie_edit', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager, CompagniesRepository $compRepository, $id = null): Response
    {
        $compagnie = $id ? $compRepository->find($id) : new Compagnies();
        $compagnies = $compRepository->findAll();

        $form = $this->createFormBuilder($compagnie)
            ->add('name', TextType::class, ['label' => false])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($compagnie);
            $manager->flush();

            $this->addFlash('success', 'Votre message est bien ajouté ou modifié');
            return $this->redirectToRoute('app_compagnie');
        }

        return $this->render('compagnie/addcompagnie.html.twig', [
            'compagnies' => $compagnies,
            'formCompagnie' => $form->createView(),
            'EditMode' => $compagnie->getId() !== null,
        ]);
    }

    #[Route('/addcompagnie/delete/{id}', name: 'app_compagnie_delete')]

    public function deletecomp(int $id, Compagnies $compagnie, ManagerRegistry $comp, EntityManagerInterface $manager): Response
    {
        $compagnie = $comp->getRepository(Compagnies::class)->find($id);
        $this->addFlash('success', 'La compagnie ' . $compagnie->getName() . ' a été supprimée');
        $manager->remove($compagnie);
        $manager->flush();
        return $this->redirectToRoute('app_compagnie');
    }

    #[Route('/about', name: 'app_about')]
    public function about()
    {
        return $this->render('about.html.twig');
    }
}
