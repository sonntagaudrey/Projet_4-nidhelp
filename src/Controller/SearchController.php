<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Controller gérant l'affichage de la page "seach" résulats de recherche de l'utilisateur dans la barre de recherche du header.
 * @return Response le résulat de la recherche
 */

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ServiceRepository $serviceRepository): Response
    {
        $query = $request->query->get('search');
        
        if (!$query) {
            return $this->redirectToRoute('app_service_index');
        }

        $results = $serviceRepository->findSearchInput($query);

        return $this->render('service/index.html.twig', [
            'serviceList'  => $results,
            'categoryList' => [], 
            'search_term'  => $query
        ]);
    }
}
