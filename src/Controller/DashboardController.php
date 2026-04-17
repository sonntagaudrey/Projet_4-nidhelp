<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Controller gérant l'affichage de la page dashboard.
 * @return Response La vue du dashboard
 */

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
        'users' => $userRepository->findBy([], ['id' => 'ASC'])
    ]);
    }
}
