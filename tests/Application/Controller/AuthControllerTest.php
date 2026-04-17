<?php

namespace App\Tests\Application\Controller;

use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();           //< Créer un navigateur "virutel"

        $crawler = $client->request('GET', '/');
    }
}
