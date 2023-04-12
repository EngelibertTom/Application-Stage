<?php

namespace App\Command;

use App\Entity\ColumnRow;
use App\Entity\CultureTable;
use App\Entity\Greenhouse;
use App\Entity\Segment;
use App\Entity\TableColumn;
use App\Manager\CultureTableManager;
use App\Manager\GreenhouseManager;
use App\Manager\NurseryManager;
use App\Manager\SegmentManager;
use App\Manager\TableColumnManager;
use App\Repository\TableColumnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateColumnRowCommand extends Command
{
    protected static $defaultName = 'app:create-column-row';
    private $em;
    private $tableColumnManager;

    public function __construct(EntityManagerInterface $entityManager, TableColumnManager $tableColumnManager)
    {
        $this->em = $entityManager;
        $this->tableColumnManager = $tableColumnManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Crée les rangs manquants des colonnes de tables (il doit y en avoir 20 par colonnes).')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Création des rangs',
            '============',
            '',
        ]);

        $tableColumns = $this->tableColumnManager->getTableColumns();

        $io = new SymfonyStyle($input, $output);
        $io->progressStart(count($tableColumns));

        /** @var TableColumn $tableColumn*/
        foreach ($tableColumns as $tableColumn)
        {
            for ($i = 1; $i < 21; $i++)
            {
                $exist = $tableColumn->getColumnRows()->filter(function (ColumnRow $columnRow) use ($i) {
                    return (int)$columnRow->getName() === $i;
                })->first();

                if (!$exist) {
                    $columnRow = new ColumnRow();
                    $columnRow->setName($i);
                    $columnRow->setTableColumn($tableColumn);

                    $this->em->persist($columnRow);
                }
            }

            $this->em->flush();
            $io->progressAdvance(1);
        }

        return Command::SUCCESS;
    }
}
