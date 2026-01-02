<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeController extends AbstractController
{
    #[Route('/employes', name: 'app_employes')]
    public function index(EmployeRepository $repo): Response
    {
        $employes = $repo->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/{id}/modifier', name: 'app_employe_modifier')]
    public function modifier(Employe $employe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($employe);
            $em->flush();
            return $this->redirectToRoute('app_employes');
        }

        return $this->render('employe/form.html.twig', [
            'form' => $form,
            'employe' => $employe
        ]);
    }

    #[Route('/employe/{id}/supprimer', name: 'app_employe_supprimer')]
    public function supprimer(int $id, EmployeRepository $repo, EntityManagerInterface $em): Response
    {
        $employe = $repo->find($id);
        if (!$employe) {
            return $this->redirectToRoute('app_employes');
        }

        $em->remove($employe);
        $em->flush();

        return $this->redirectToRoute('app_employes');
    }
}
