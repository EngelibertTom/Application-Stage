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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportPositionCommand extends Command
{
    protected static $defaultName = 'app:import-positions';
    private $em;
    private $greenhouseManager;
    private $nurseryManager;
    private $cultureTableManager;
    private $segmentManager;

    public function __construct(EntityManagerInterface $entityManager, NurseryManager $nurseryManager,
                                GreenhouseManager $greenhouseManager, CultureTableManager $cultureTableManager,
                                SegmentManager $segmentManager)
    {
        $this->em = $entityManager;
        $this->greenhouseManager = $greenhouseManager;
        $this->nurseryManager = $nurseryManager;
        $this->cultureTableManager = $cultureTableManager;
        $this->segmentManager = $segmentManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import des positions (serres, tables, segments...).')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Import des positions',
            '============',
            '',
        ]);

        $file = 'public/export/positions.csv';

        if (file_exists($file) || is_readable($file) ) {
            $header = null;
            $data = array();
            if (($handle = fopen($file, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 10000)) !== FALSE) {
                    if (!$header) {
                        $header = $row;
                    } else {
                        $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }

            $io = new SymfonyStyle($input, $output);
            $io->progressStart(count($data));

            $nursery = $this->nurseryManager->getNursery(1);

            foreach ($data as $key => $row)
            {
                $greenhouseName = sprintf("%03d", $row['Serre']);

                $greenhouse = $this->greenhouseManager->find(['name' => $greenhouseName, 'nursery' => $nursery]);

                if (!$greenhouse)
                {
                    $greenhouse = new Greenhouse();
                    $greenhouse->setName($greenhouseName);
                    $greenhouse->setNursery($nursery);

                    $this->em->persist($greenhouse);
                }

                $cultureTable = $this->cultureTableManager->find(['name' => $row['Table'], 'greenhouse' => $greenhouse]);

                if (!$cultureTable)
                {
                    $cultureTable = new CultureTable();
                    $cultureTable->setName($row['Table']);
                    $cultureTable->setGreenhouse($greenhouse);

                    $this->em->persist($cultureTable);
                }

                $segment = $this->segmentManager->find(['name' => $row['Segment'], 'cultureTable' => $cultureTable]);

                if (!$segment)
                {
                    $segment = new Segment();
                    $segment->setName($row['Segment']);
                    $segment->setCultureTable($cultureTable);

                    for ($i = 1; $i < 9; $i++)
                    {
                        $tableColumn = new TableColumn();
                        $tableColumn->setName($i);
                        $tableColumn->setSegment($segment);

                        for ($j = 1; $j < 6; $j++)
                        {
                            $columnRow = new ColumnRow();
                            $columnRow->setName($j);
                            $columnRow->setTableColumn($tableColumn);

                            $this->em->persist($columnRow);
                        }

                        $this->em->persist($tableColumn);
                    }

                    $this->em->persist($segment);
                }

                $this->em->flush();
                $io->progressAdvance(1);
            }

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
