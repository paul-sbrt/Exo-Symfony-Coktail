<?php

namespace App\Controller;

use App\Entity\Coktail;
use App\Form\CoktailType;
use App\Repository\CoktailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class CoktailController extends AbstractController
{
    #[Route('/', name: 'app_coktail_index', methods: ['GET'])]
    public function index(CoktailRepository $coktailRepository): Response
    {
        return $this->render('coktail/index.html.twig', [
            'coktails' => $coktailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coktail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coktail = new Coktail();
        $form = $this->createForm(CoktailType::class, $coktail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coktail);
            $entityManager->flush();

            return $this->redirectToRoute('app_coktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coktail/new.html.twig', [
            'coktail' => $coktail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coktail_show', methods: ['GET'])]
    public function show(Coktail $coktail): Response
    {
        return $this->render('coktail/show.html.twig', [
            'coktail' => $coktail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coktail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coktail $coktail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoktailType::class, $coktail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coktail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coktail/edit.html.twig', [
            'coktail' => $coktail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coktail_delete', methods: ['POST'])]
    public function delete(Request $request, Coktail $coktail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coktail->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($coktail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coktail_index', [], Response::HTTP_SEE_OTHER);
    }
}
