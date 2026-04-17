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
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Controller gérant l'affichage de la page service + CRUD des services.
 */

#[Route('/service', name: 'app_service_')]
final class ServiceController extends AbstractController
{
    /**
     * @return Response La vue avec tous les services 
     */
    #[Route('/index', name: 'index')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $arrServices = $serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'serviceList' => $arrServices,
        ]);
    }

    /**
     * @return Response La vue du formulaire de crétion d'un service
     */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objNewService = new Service();

        $createForm = $this->createForm(ServiceCreateFormType::class, $objNewService);

        // J'envoie les données de la requête au formulaire
        $createForm->handleRequest($request);

        // Vérifie si le formulaire est soumis et que les données sont valides
        if ($createForm->isSubmitted() && $createForm->isValid()) {

            // récupère l'utilisateur connecté et on l'affecte au service
            $objNewService->setAuthor($this->getUser());
            // ----------------------------------

            $entityManager->persist($objNewService);
            $entityManager->flush();

            // Affiche un message de succès
            $this->addFlash('success', "La prestation a bien été créée en base");

            // Redirige vers la page de détails de la prestation créée
            return $this->redirectToRoute('app_service_show', [
                'id' => $objNewService->getId()
            ]);
        }

        return $this->render('service/form.html.twig', [
            'createForm' => $createForm->createView()
        ]);
    }

    /**
     * @return Response La vue d'un service
     */
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
        //on sécurise seul un admin ou l'auteur du service peut faire la modification
       if ($service->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) { 
           $this->addFlash('danger', "Vous n'avez pas le droit de modifier cette prestation.");
           return $this->redirectToRoute('app_service_index');
        }           
        
        // On construit le formulaire à partir des données de l'entité récupérée
        // depuis l'ID présent dans l'URL
        $updateForm = $this->createForm(ServiceCreateFormType::class, $service);
        
        $updateForm->handleRequest($request);

        if($updateForm->isSubmitted() && $updateForm->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', "La prestation a bien été modifiée en base");

            return $this->redirectToRoute('app_service_show', [
                'id' => $service->getId()
            ]);
        }
        
        return $this->render('service/form.html.twig', [
            'createForm'    => $updateForm->createView()
        ]);
    }
    
    /**
     * Supprime un service
     * @return Response renvoie sur la vue du Dashboard
     */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        
        if ($service->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', "Vous n'avez pas le droit de supprimer ce service.");
            return $this->redirectToRoute('app_service_index');
        }

        $submittedToken = $request->getPayload()->get('_token');

        if ($this->isCsrfTokenValid('delete' . $service->getId(), $submittedToken)) {
            $entityManager->remove($service);
            $entityManager->flush();
            $this->addFlash('success', "Le service a été supprimé.");
        } else {
            $this->addFlash('danger', "Jeton de sécurité invalide.");
        }

        return $this->redirectToRoute('app_dashboard');
    }


}
