<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Controller gérant l'affichage de la page d'accueil.
 */

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ServiceRepository $serviceRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'lastServices' => $serviceRepository->findLastThree(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
