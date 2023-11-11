<?php

namespace App\Command;

use App\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ugo:orders:import',
    description: 'Import db from custom and order csv',
)]
class UgoOrdersImportCommand extends Command
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    // protected function configure(): void
    // {
    //     $this
    //         ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
    //         ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
    //     ;
    // }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $customersData = $this->readCsvFile('csv/customers.csv');
        // $purchasesData = $this->readCsvFile('csv/purchases.csv');
        $this->entityManager->getRepository(Customer::class)->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();
        $io->success('Purge Customer');

        $csvFilePath = __DIR__ . '/../csv/customers.csv';
        $rows = $this->readCsvFile($csvFilePath);

        array_shift($rows);
        foreach ($rows as $row) {
            $customer = $this->createCustomerFromCsvRow($row);
            $this->entityManager->persist($customer);
        }

        $customer = new Customer();
        $customer->setTitle(1);
        $this->entityManager->persist($customer);
        $io->success('Succecfuly imported CSV');

        $this->entityManager->flush();
        return Command::SUCCESS;
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

        $customer->setTitle((int) $row[1]);
        $customer->setLastname(isset($row[2]) ? $row[2] : null);
        $customer->setFirstname(isset($row[3]) ? $row[3] : null);
        $customer->setPostalcode(isset($row[4]) && $row[4] !== '' ? (int) $row[4] : null);
        $customer->setCity(isset($row[5]) ? $row[5] : null);
        $customer->setEmail(isset($row[6]) ? $row[6] : null);

        return $customer;
    }
}
