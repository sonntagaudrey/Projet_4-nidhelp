<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceCreateFormType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service', name: 'app_service_')]
final class ServiceController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $arrServices = $serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'serviceList' => $arrServices,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objNewService = new Service();

        $createForm = $this->createForm(ServiceCreateFormType::class, $objNewService);

        // J'envoi les données de la requête au formulaire
        $createForm->handleRequest($request);

        // Vérifie si le formulaire est soumi et que les données sont valides
        if ($createForm->isSubmitted() && $createForm->isValid()) {

            $entityManager->persist($objNewService);
            $entityManager->flush();

            // Affiche un message de succès
            $this->addFlash('success', "La prestation a bien été créé en base");

            // Redirige vers la page de détails de la prestation créé
            return $this->redirectToRoute('app_service_show', [
                'id'    => $objNewService->getId()
            ]);
        }

        return $this->render('service/form.html.twig', [
            'createForm'    => $createForm
        ]);
    }

     #[Route('/{id<\d+>}', name: 'show')]
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service
        ]);
    }

     #[Route('/{id<\d+>}/update', name: 'update')]
    public function update(Service $service, Request $request, EntityManagerInterface $entityManager): Response
    {
        // On construit le formulaire à partir des données de l'entité récupérée
        // depuis l'ID présent dans l'URL
        $updateForm = $this->createForm(ServiceCreateFormType::class, $service);

        $updateForm->handleRequest($request);

        if($updateForm->isSubmitted() && $updateForm->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', "Le pokémon a bien été modifié en base");

            // Redirige vers la page de détails de la prestation modifiée
            return $this->redirectToRoute('app_service_show', [
                'id' => $service->getId()
            ]);
        }

        return $this->render('service/form.html.twig', [
            'createForm'    => $updateForm
        ]);
    }
}
