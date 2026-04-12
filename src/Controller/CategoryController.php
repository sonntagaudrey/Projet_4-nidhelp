<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryTypeCreateFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $arrcategories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categoriesList' => $arrcategories,
        ]);
    }
    
    #[Route('/category/create', name: 'app_category_create')]
    #[IsGranted('ROLE_ADMIN')]  
    public function create(Request $request, EntityManagerInterface $entityManagerInterface, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryTypeCreateFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $pictureFile = $form->get('picture')->getData();

            
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                
                try {
                    $pictureFile->move(
                        $this->getParameter('category_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                
                    $this->addFlash('danger', 'Erreur lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('app_category_create');
                }

                
                $category->setPicture($newFilename);
            }

            $entityManagerInterface->persist($category); 
            $entityManagerInterface->flush();           

            $this->addFlash('success', 'La catégorie a bien été ajoutée !');
            return $this->redirectToRoute('app_category'); 
        }

        return $this->render('category/form.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }
}
