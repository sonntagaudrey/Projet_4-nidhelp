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
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Controller gérant l'affichage de la page catégorie + l'ajout d'un catégorie (uniquement si le rôle est administrateur).
 */

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
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/categories')] string $pictureDirectory): Response 
    {
        $category = new Category();

        $form = $this->createForm(CategoryTypeCreateFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move($pictureDirectory, $newFilename);
                } catch (FileException $exc) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement.');
                    return $this->redirectToRoute('app_category_create');
                }
            
                $category->setPicture($newFilename);
            }            

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été créée !');

            return $this->redirectToRoute('app_category'); 
        }

        return $this->render('category/form.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    #[Route('/category/update/{id}', name: 'app_category_update')]
    #[IsGranted('ROLE_ADMIN')]
    public function update(
        Category $category, 
        Request $request, 
        EntityManagerInterface $entityManager, 
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/categories')] string $pictureDirectory
    ): Response {
        // On utilise le même formulaire que pour la création
        $form = $this->createForm(CategoryTypeCreateFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                // Optionnel : Tu peux supprimer l'ancienne image ici si tu veux garder le dossier propre
                
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move($pictureDirectory, $newFilename);
                    $category->setPicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors du téléchargement de la nouvelle image.');
                }
            }

            $entityManager->flush(); // Pas besoin de persist() car l'objet vient de la BDD
            $this->addFlash('success', 'La catégorie a été mise à jour !');
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/form.html.twig', [
            'categoryForm' => $form->createView(),
            'editMode' => true, // Utile pour changer le titre dans ton Twig
            'category' => $category
        ]);
    }
   
}
