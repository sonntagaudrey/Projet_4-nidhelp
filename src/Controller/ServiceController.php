<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $serviceRepository): Response    
    {
        $arrServices = $serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'serviceList' => $arrServices,
        ]);
    }

     #[Route('/pokemon/create', name: 'app_pokemon_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objNewService = new Service();

        $createForm = $this->createForm(ServiceCreateFormType::class, $objNewService);
        
        return $this->render('service/create.html.twig', [
            'createForm'    => $createForm
        ]);
    }

    #[Route('/pokemon/{id}', name: 'app_pokemon_show')]
    public function show(Int $id): Response
    {
        return $this->render('service/show.html.twig', [
            'srv_id' => $id
        ]);
    }
}
