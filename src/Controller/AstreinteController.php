<?php

namespace App\Controller;

use App\Entity\Astreinte;
use App\Form\AstreinteType;
use App\Service\ManagedUsersByLogged;
use App\Repository\AstreinteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/astreinte')]
class AstreinteController extends AbstractController
{
    #[Route('/', name: 'app_astreinte_index', methods: ['GET'])]
    public function index(
        AstreinteRepository $astreinteRepository,
        ManagedUsersByLogged $ManagedUsersByLogged,
        UserInterface $userInterface
        ): Response
    {
        $managedUsers = $ManagedUsersByLogged->getUsers($userInterface->getUserIdentifier());
        return $this->render('astreinte/index.html.twig', [
            'astreintes' => $astreinteRepository->findAll(),
            'managedUsers'=> $managedUsers,
        ]);
    }

    #[Route('/new', name: 'app_astreinte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AstreinteRepository $astreinteRepository): Response
    {
        $astreinte = new Astreinte();
        $form = $this->createForm(AstreinteType::class, $astreinte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $astreinteRepository->save($astreinte, true);

            return $this->redirectToRoute('app_astreinte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('astreinte/new.html.twig', [
            'astreinte' => $astreinte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_astreinte_show', methods: ['GET'])]
    public function show(Astreinte $astreinte): Response
    {
        return $this->render('astreinte/show.html.twig', [
            'astreinte' => $astreinte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_astreinte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Astreinte $astreinte, AstreinteRepository $astreinteRepository): Response
    {
        $form = $this->createForm(AstreinteType::class, $astreinte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $astreinteRepository->save($astreinte, true);

            return $this->redirectToRoute('app_astreinte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('astreinte/edit.html.twig', [
            'astreinte' => $astreinte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_astreinte_delete', methods: ['POST'])]
    public function delete(Request $request, Astreinte $astreinte, AstreinteRepository $astreinteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$astreinte->getId(), $request->request->get('_token'))) {
            $astreinteRepository->remove($astreinte, true);
        }

        return $this->redirectToRoute('app_astreinte_index', [], Response::HTTP_SEE_OTHER);
    }
}
