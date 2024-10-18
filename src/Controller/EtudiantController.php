<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiants', name: 'etudiant_list')]
    public function list(EtudiantRepository $etudiantRepository): Response
    {
        $etudiants = $etudiantRepository->findAll();
        
        return $this->render('etudiant/list.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }

    #[Route('/etudiants/new', name: 'etudiant_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etudiant);
            $em->flush();

            return $this->redirectToRoute('etudiant_list');
        }

        return $this->render('etudiant/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/etudiants/{id}/edit', name: 'etudiant_edit')]
    public function edit(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('etudiant_list');
        }

        return $this->render('etudiant/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/etudiants/{id}/delete', name: 'etudiant_delete')]
    public function delete(Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        $em->remove($etudiant);
        $em->flush();

        return $this->redirectToRoute('etudiant_list');
    }
}
