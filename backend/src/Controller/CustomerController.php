<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CustomerController.php',
        ]);
    }

    #[Route('/customers', name: 'app_customers')]
    public function getCustomers(EntityManagerInterface $entityManager): JsonResponse
    {
        $customers = $entityManager->getRepository(Customer::class)->findAll();

        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'title' => $customer->getTitle(),
                'lastname' => $customer->getLastname(),
                'firstname' => $customer->getFirstname(),
                'postal_code' => $customer->getPostalcode(),
                'city' => $customer->getCity(),
                'email' => $customer->getEmail(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/customers/{customerId}/orders', name: 'app_customer_orders')]
    public function getCustomerOrders(int $customerId, EntityManagerInterface $entityManager): JsonResponse
    {
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], 404);
        }

        $orders = $customer->getPurchases();

        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'purchaseIdentifier' => $order->getPurchaseIdentifier(),
                'quantity' => $order->getQuantity(),
                'price' => $order->getPrice(),
                'currency' => $order->getCurrency(),
                'date' => $order->getDate()->format('Y-m-d'),
            ];
        }

        return $this->json([
            'lastname' => $customer->getLastname(),
            'orders' => $data,
        ]);
    }
}
