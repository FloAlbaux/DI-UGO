<?php

namespace App\Command;

use App\Entity\Customer;
use App\Entity\Purchases;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ugo:orders:import',
    description: 'Import data from customers and purchases csv',
)]
class UgoOrdersImportCommand extends Command
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->purgeDb();
        $this->importCustomers();
        $this->importPurchases();

        $this->entityManager->flush();
        $io->success('Successfully imported data');

        return Command::SUCCESS;
    }

    private function purgeDb(): void
    {
        $this->entityManager->getRepository(Customer::class)->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();
        $this->entityManager->getRepository(Purchases::class)->createQueryBuilder('p')
            ->delete()
            ->getQuery()
            ->execute();
    }

    private function importCustomers(): void
    {
        $csvCustomersFilePath = __DIR__ . '/../csv/customers.csv';
        $rows = $this->readCsvFile($csvCustomersFilePath);
        array_shift($rows); 

        foreach ($rows as $row) {
            $customer = $this->createCustomerFromCsvRow($row);
            $this->entityManager->persist($customer);
        }
        
    }

    private function importPurchases(): void
    {
        $csvPurchasesFilePath = __DIR__ . '/../csv/purchases.csv';
        $rows = $this->readCsvFile($csvPurchasesFilePath);
        array_shift($rows); 
        
        foreach ($rows as $row) {
            $purchase = $this->createPurchaseFromCsvRow($row);
            $this->entityManager->persist($purchase);
        }
    }

    private function readCsvFile(string $filePath): array
    {
        $rows = [];
        $file = fopen($filePath, 'r');

        if ($file !== false) {
            while (($data = fgetcsv($file, 0, ';')) !== false) {
                $rows[] = $data;
            }

            fclose($file);
        }

        return $rows;
    }

    private function createCustomerFromCsvRow(array $row): Customer
    {
        $customer = new Customer();
        $customer->setTitle(isset($row[1]) ? (int) $row[1] : null);
        $customer->setLastname(isset($row[2]) ? $row[2] : null);
        $customer->setFirstname(isset($row[3]) ? $row[3] : null);
        $customer->setPostalcode(isset($row[4]) && $row[4] !== '' ? (int) $row[4] : null);
        $customer->setCity(isset($row[5]) ? $row[5] : null);
        $customer->setEmail(isset($row[6]) ? $row[6] : null);

        return $customer;
    }

    private function createPurchaseFromCsvRow(array $row): Purchases
    {
        $purchase = new Purchases();
        $purchase->setPurchaseIdentifier(isset($row[0]) ? $row[0] : null);
        $customerId = isset($row[1]) ? (int) $row[1] : null;
        if ($customerId !== null) {
            $customer = $this->entityManager->getRepository(Customer::class)->find($row[1]);
            $purchase->setCustomerId($customer);
        }

        $purchase->setId((int) $row[2]);
        $purchase->setQuantity((int) $row[3]);
        $purchase->setPrice((int) $row[4]);
        $purchase->setCurrency($row[5]);

        $dateString = isset($row[6]) ? $row[6] : null;
        $date = null;
        if ($dateString && ($date = DateTime::createFromFormat('Y-m-d', $dateString)) !== false) {
            $purchase->setDate($date);
        }


        return $purchase;
    }
}