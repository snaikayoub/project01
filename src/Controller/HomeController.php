<?php

namespace App\Controller;

use App\Repository\CarouselRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CarouselRepository $carouselRepository): Response
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            //'carousels'=>$carouselRepository->findAll(),
        ]);
    }
}
