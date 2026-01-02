<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/projet/{projet}/tache')]
final class TacheController extends AbstractController
{
    #[Route('/ajouter', name: 'app_tache_ajouter', methods: ['GET', 'POST'])]
    #[Route('/{id}/modifier', name: 'app_tache_modifier', methods: ['GET', 'POST'])]
    public function ajouter(Projet $projet, ?Tache $tache, Request $request, EntityManagerInterface $em): Response
    {
        $tache ??= new Tache();
        $form = $this->createForm(TacheType::class, $tache, ['projet' => $projet]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tache->setProjet($projet);
            $em->persist($tache);
            $em->flush();
            return $this->redirectToRoute('app_projet', [
                'id' => $projet->getId()
            ]);
        }

        return $this->render('tache/form.html.twig', [
            'form' => $form,
            'tache' => $tache,
            'projet' => $projet
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_tache_supprimer')]
    public function supprimer(Projet $projet, int $id, TacheRepository $repo, EntityManagerInterface $em): Response
    {
        $tache = $repo->find($id);

        if (!$tache) {
            return $this->redirectToRoute('app_projet', [
                'id' => $projet->getId()
            ]);
        }

        $em->remove($tache);
        $em->flush();


        return $this->redirectToRoute('app_projet', [
            'id' => $projet->getId()
        ]);
    }
}
