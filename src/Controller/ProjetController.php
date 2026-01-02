<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projet')]
final class ProjetController extends AbstractController
{
    #[Route('/{id}', name: 'app_projet', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function index(?Projet $projet): Response
    {
        if (!$projet) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('projet/index.html.twig', [
            'projet' => $projet
        ]);
    }

    #[Route('/ajouter', name: 'app_projet_ajouter', methods: ['GET', 'POST'])]
    #[Route('/{id}/modifier', name: 'app_projet_modifier', methods: ['GET', 'POST'])]
    public function ajouter(?Projet $projet, Request $request, EntityManagerInterface $em): Response
    {
        $projet ??= new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projet', [
                'id' => $projet->getId()
            ]);
        }

        return $this->render('projet/form.html.twig', [
            'form' => $form,
            'projet' => $projet
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_projet_supprimer')]
    public function supprimer(int $id, ProjetRepository $repo, EntityManagerInterface $em): Response
    {
        $projet = $repo->find($id);
        if (!$projet) {
            return $this->redirectToRoute('app_home');
        }

        $em->remove($projet);
        $em->flush();


        return $this->redirectToRoute('app_home');
    }
}
