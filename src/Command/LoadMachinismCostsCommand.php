<?php

namespace App\Command;

use App\Entity\MachinismCost;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadMachinismCostsCommand extends Command
{
    protected static $defaultName = 'app:load-machinism-costs';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }


    protected function configure()
    {
        $this
            ->setDescription('Load machinism costs from "Coûts machinisme" of api-agro.fr (https://plateforme.api-agro.fr/explore/dataset/extrait-couts-machinisme/information/)')
            ->addArgument('file', InputArgument::OPTIONAL, 'Path to CSV file', 'data/couts-machinisme.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Load machinism costs");

        $csvFilePath = $input->getArgument('file');
        if (!$csvFilePath) {
            throw new \LogicException('You need to download CSV file from https://plateforme.api-agro.fr/explore/dataset/extrait-couts-machinisme/information/');
        }

        $qb = $this->entityManager->createQuery('delete from App\Entity\MachinismCost mc');
        $qb->execute();

        $csv = Reader::createFromPath($csvFilePath, 'r');
        $csv->setDelimiter(';');
        //$csv->setHeaderOffset(0); headers are not unique :(

        $stmt = (new Statement())->offset(1);
        $records = $stmt->process($csv);

        $io->progressStart(count($records));
        foreach ($records as $record) {
            $name = $record[4] . ' ' . $record[5];
            $hourlyCost = number_format((float) $record[46], 2);

            $io->progressAdvance();

            $machanismCost = new MachinismCost();
            $machanismCost->setName($name);
            $machanismCost->setHourlyCost($hourlyCost);

            $this->entityManager->persist($machanismCost);
        }
        $io->progressFinish();

        $this->entityManager->flush();

        $io->success('Import ok');
    }
}
