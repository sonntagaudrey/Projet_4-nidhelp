<?php

namespace App\Tests\Application\Controller;

use App\Factory\CategoryFactory;
use App\Factory\ServiceFactory;
use App\Factory\UserFactory;
use App\Story\AppStory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\WithStory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;


class ServiceControllerTest extends WebTestCase
{
    use ResetDatabase; 
    use Factories;

    public function testIndexPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/service/index');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Nos Services');
    }

    public function testIndexPageShowOneService(): void
    {
        $client = static::createClient();

        ServiceFactory::createOne();    //< Génère un Servvice en base avec des données aléatoires

        $crawler = $client->request('GET', '/service/index');

        $this->assertResponseIsSuccessful();        
    }
    
    #[WithStory(AppStory::class)]
    public function testIndexPageShowWithStory(): void
    {
        // Si on utilise les Stories, s'assurer sur le Kernel n'est pas déjà démarré
        static::ensureKernelShutdown();

        $client = static::createClient();
        
        $crawler = $client->request('GET', '/service/index');

        $this->assertResponseIsSuccessful();

    }

    public function testCreatePageShowWithoutLoggin(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/service/create');

        // Sans être connecté, je devrais être redirigé vers la page de connexion
        $this->assertResponseRedirects('/login');
    }

    public function testCreatePageShowWithoutLoggedIn(): void
    {
        $client = static::createClient();

        $objUser = UserFactory::createOne();        //< Création d'un user dans la base

        $client->loginUser($objUser);               //< L'utilisateur créé se connecte 

        $crawler = $client->request('GET', '/service/create');

        $this->assertResponseIsSuccessful();
    }

    public function testCreateServiceFormSubmit(): void
    {
        $client = static::createClient();
        $objUser = UserFactory::createOne();        //< Création d'un user dans la base
        $client->loginUser($objUser);               //< L'utilisateur créé se connecte à l'application

        $category = CategoryFactory::createOne();   //< Création d'une catégorie dans la base

        $crawler = $client->request('GET', '/service/create');
        
        $client->submitForm('Enregistrer', [
            'service_create_form[name]'        => 'Mon super service',
            'service_create_form[description]' => 'On écrit n\'importe quoi, pour faire style !!!',
            'service_create_form[category]'    => $category->getId()
        ]);

        $this->assertResponseRedirects();

        $client->followRedirect();

        $this->assertRouteSame('app_service_show');
    }

}
