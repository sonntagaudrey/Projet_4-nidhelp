<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserInfoFormType;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Audrey SONNTAG GOLINSKI
 * Contrôleur gérant les opérations CRUD et les droits des utilisateurs.
 * @todo  faire le Delete
 */

final class UserController extends AbstractController
{ 
    /**
     * Affiche le formulaire de création d'un compte utilisateur et traite la soumission.
     * @return Response La vue du formulaire de création d'un compte utilisateur
     */
    #[Route('/user/create', name: 'app_user_create')]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $objUser = new User();

        $userForm = $this->createForm(UserInfoFormType::class, $objUser);

        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid()) {

            /** @var string $plainPassword */
            $plainPassword = $userForm->get('plainPassword')->getData();

            // Dans le cas où le mot de passe n'est pas renseigné
            if(!$plainPassword) {

                $plainPassword = "default_password";
            }

            // encode the plain password
            $objUser->setPassword($userPasswordHasher->hashPassword($objUser, $plainPassword));

            // set the registration datetime
            $objUser->setCreateDate(new DateTimeImmutable('now'));
            
            $entityManager->persist($objUser);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été créé");

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm
        ]);
    }
    
    /**
     * Affiche un utilisateur en fonction de son id
     * @return Response La vue d'un user
     */
    #[Route('/{id<\d+>}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Affiche le formulaire de modification d'un compte utilisateur et traite la soumission.
     * @return Response La vue du formulaire de modification d'un compte utilisateur
     */
    #[Route('/user/{id<\d+>}', name: 'app_user_update')]
    public function update(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $userForm = $this->createForm(UserInfoFormType::class, $user);

        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid()) { 

            $user->setUpdateDate(new DateTime('now'));
            $entityManager->flush();
            
            $this->addFlash('success', "L'utilisateur a été modifié");

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm
        ]);
    }

    /**
     * Affiche le formulaire de modification du rôle d'un utilisateur et traite la soumission.
     * @return Response La vue du formulaire de modification du rôle utilisateur
     */

    #[Route('/user/{id<\d+>}/roles', name: 'app_user_roles')]
    public function updateRoles(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $strFormError = ""; //< Pas d'erreur par défaut (chaine vide)

        if($request->isMethod('POST')) {

            // Récupération du jeton CSRF dans la requête
            $submittedToken = $request->getPayload()->get('_csrf_token');

            // Vérifie si le jeton est valide : attention au nom qui doit être le mêmee que dans le dans le gabarit TWIG
            if ($this->isCsrfTokenValid('user_role', $submittedToken)) {

                $arrRoles = []; //< On défini un tableau de rôles vide avant la gestion des affectations

                // Vérifie si la case du rôle prof est coché
                if($request->request->get('user-role-admin')) {
                    $arrRoles[] = 'ROLE_ADMIN';
                }

                // Met à jour les rôles de l'utilisateur
                $user->setRoles($arrRoles);
                $entityManager->flush();

                $this->addFlash('success', "Les rôles de l'utilisateur ont été modifiés");

                return $this->redirectToRoute('app_dashboard');
            }

            // En cas d'erreur de jeton CSRF, on pourra transmettre un message d'erreur à la vue TWIG
            $strFormError = "Le jeton de sécurité n'est pas valide. Réessayez ou actualisez la page";
        }

        return $this->render('user/roles.html.twig', [
            'user'      => $user,
            'formError' => $strFormError
        ]);
    }

    /**
     * Supprime un utilisateur
     * @return Response Redirection vers la liste des utilisateurs
     */
    #[Route('/user/{id<\d+>}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        // On récupère le jeton CSRF envoyé dans le formulaire de suppression
        $submittedToken = $request->getPayload()->get('_token');

        // On vérifie si le jeton est valide (ID du jeton : 'delete' + ID de l'user)
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $submittedToken)) {
            
            // Sécurité supplémentaire : empêcher de se supprimer soi-même ?
            if ($this->getUser() === $user) {
                $this->addFlash('danger', "Vous ne pouvez pas supprimer votre propre compte.");
                return $this->redirectToRoute('app_dashboard');
            }

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été supprimé avec succès.");
        } else {
            $this->addFlash('danger', "Le jeton de sécurité est invalide.");
        }

        return $this->redirectToRoute('app_dashboard');
    }

}