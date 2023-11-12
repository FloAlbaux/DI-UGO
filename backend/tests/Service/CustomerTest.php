<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{
    public function testGetCustomers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/customers');

        $responseData = json_decode($client->getResponse()->getContent(), true);

        foreach ($responseData as $customer) {
            $this->assertArrayHasKey('id', $customer);
            $this->assertArrayHasKey('title', $customer);
            $this->assertArrayHasKey('lastname', $customer);
            $this->assertArrayHasKey('firstname', $customer);
            $this->assertArrayHasKey('postal_code', $customer);
            $this->assertArrayHasKey('city', $customer);
            $this->assertArrayHasKey('email', $customer);
            $this->assertArrayHasKey('orders', $customer);
        }

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetCustomerOrders(): void
    {
        $client = static::createClient();

        $customerId = 1;
        $client->request('GET', '/customers/' . $customerId . '/orders');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('last_name', $responseData);
        $this->assertArrayHasKey('orders', $responseData);

        foreach ($responseData['orders'] as $order) {
            $this->assertArrayHasKey('purchase_identifier', $order);
            $this->assertArrayHasKey('product_id', $order);
            $this->assertArrayHasKey('quantity', $order);
            $this->assertArrayHasKey('price', $order);
            $this->assertArrayHasKey('currency', $order);
            $this->assertArrayHasKey('date', $order);
        }

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('last_name', $responseData);
        $this->assertEquals(
            'Norris',
            $responseData['last_name'],
            'The last name of the customer with ID ' . $customerId . ' should be "Norris".'
        );

        // If the assertion fails, simply display a message to trigger the import
        if ($responseData['last_name'] !== 'Norris') {
            echo "Please run the database import with the command: php bin/console ugo:orders:import\n";
        }
    }

    public function testGetCustomerOrdersWithInvalidId(): void
    {
        $client = static::createClient();

        // Supposons que vous ayez un customer avec un ID invalide (non existant)
        $invalidCustomerId = 999;
        $client->request('GET', '/customers/' . $invalidCustomerId . '/orders');

        $this->assertResponseStatusCodeSame(404);
        $this->assertJson($client->getResponse()->getContent());
    }
}
