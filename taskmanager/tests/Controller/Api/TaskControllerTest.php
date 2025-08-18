<?php

namespace App\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class TaskControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->em = $this->client->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/api/tasks');

        self::assertResponseIsSuccessful();
    }
    public function testCreateTask(): void
    {
        $this->client->request('POST', '/api/tasks', [
                'title' => 'Test Task',
                'description' => 'This is a test task.',
                'status' => 'pending',
        ]);

        self::assertResponseIsSuccessful();
        self::assertJson($this->client->getResponse()->getContent());
    }
}
