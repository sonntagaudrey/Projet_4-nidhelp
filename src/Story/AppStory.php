<?php

namespace App\Story;

use App\Factory\CategoryFactory;
use App\Factory\ServiceFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        // Génère 20 utilisateurs aléatoires via faker
        UserFactory::createMany(20);

        // Génère tous les Catégories
        CategoryFactory::createSequence([
            ['name' => "Salle de bain",         'picture' => "bain.png"],
            ['name' => "Cuisine",               'picture' => "cuisine.png"],
            ['name' => "Sols et Murs",          'picture' => "sol.png"],
            ['name' => "Electricité",           'picture' => "electricite.png"],
            ['name' => "Chauffage et Plomberie",'picture' => "chauffagePlomberie.png"],
            ['name' => "Informatique",          'picture' => "informatique.png"],
            ['name' => "Jardinage",             'picture' => "jardinage.png"],
        ]);

        
        $catBain       = CategoryFactory::find(['name' => 'Salle de bain']);
        $catCuisine    = CategoryFactory::find(['name' => 'Cuisine']);
        $catSols       = CategoryFactory::find(['name' => 'Sols et Murs']);
        $catElec       = CategoryFactory::find(['name' => 'Electricité']);
        $catPlomberie  = CategoryFactory::find(['name' => 'Chauffage et Plomberie']);
        $catInfo       = CategoryFactory::find(['name' => 'Informatique']);
        $catJardin     = CategoryFactory::find(['name' => 'Jardinage']);

        ServiceFactory::createSequence([
            [
                'name' => "Réparation de fuite sous évier", 
                'category' => $catPlomberie, 
                'author' => UserFactory::random(),
                'description' => "Intervention rapide pour colmater une fuite ou changer un siphon défectueux."
            ],
            [
                'name' => "Taille de rosiers et arbustes", 
                'category' => $catJardin, 
                'author' => UserFactory::random(),
                'description' => "Entretien saisonnier de votre jardin pour une floraison optimale."
            ],
            [
                'name' => "Installation Windows et Drivers", 
                'category' => $catInfo, 
                'author' => UserFactory::random(),
                'description' => "Réinstallation propre du système pour retrouver un ordinateur rapide."
            ],
            [
                'name' => "Travaux de salle de bain", 
                'category' => $catBain, 
                'author' => UserFactory::random(),
                'description' => "Installation de robinetterie, pose de carrelage et rénovation complète de votre espace bien-être."
            ],
            [
                'name' => "Circuit ELectrique", 
                'category' => $catElec, 
                'author' => UserFactory::random(),
                'description' => "Mise aux normes, changement de tableaux électriques et installation de luminaires en toute sécurité."
            ],
            [
                'name' => "Montage de meuble de cuisine", 
                'category' => $catCuisine, 
                'author' => UserFactory::random(),
                'description' => "Aménagement de meubles, branchement d'électroménager et solutions sur mesure pour votre cuisine."
            ],
            [
                'name' => "Pose de parquet flottant et plinthes", 
                'category' => $catSols, 
                'author' => UserFactory::random(),
                'description' => "Services pour la pose de votre parquet flottant, stratifié ou vinyle clipsable dans toutes les pièces de votre maison (salon, chambres, couloirs).Travail soigné et minutieux. Vous fournissez les matériaux (parquet, plinthes, sous-couche), j'apporte tout mon outillage professionnel (scie à onglet radiale, tire-lame, cales de frappe)."
            ],
            [
                'name' => "Préparation des murs, peinture ou pose de papier peint", 
                'category' => $catSols, 
                'author' => UserFactory::random(),
                'description' => "De l'enduisage de vos murs aux finitions décoratives, je vous accompagne dans tous vos projets de rénovation pour un résultat durable, esthétique et aux finitions soignées."
            ],
            [
                'name' => "Aide pack Microsoft Office", 
                'category' => $catInfo, 
                'author' => UserFactory::random(),
                'description' => "Optimisez votre temps de travail en apprenant à maîtriser les fonctionnalités avancées de Microsoft Office, de la gestion de vos emails Outlook à l'analyse de données complexe sous Excel"
            ],
            [
                'name' => "Redonnez vie à votre pelouse", 
                'category' => $catJardin, 
                'author' => UserFactory::random(),
                'description' => "Transformez votre extérieur en un véritable écrin de verdure grâce à un entretien sur mesure conçu pour traiter les zones dégarnies et redonner toute sa couleur à votre terrain."
            ],
            
        ]);
    }
}
