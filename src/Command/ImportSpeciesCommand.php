<?php

namespace App\Command;

use App\Entity\LeafType;
use App\Entity\Species;
use App\Entity\StatusUicn;
use App\Manager\AcidityManager;
use App\Manager\LeafTypeManager;
use App\Manager\SpeciesManager;
use App\Manager\StatusUicnManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportSpeciesCommand extends Command
{
    protected static $defaultName = 'app:import-species';
    private $em;
    private $speciesManager;
    private $acidityManager;
    private $statusUicnManager;
    private $leafTypeManager;

    public function __construct(EntityManagerInterface $entityManager, SpeciesManager $speciesManager, AcidityManager $acidityManager,
                                StatusUicnManager $statusUicnManager, LeafTypeManager $leafTypeManager)
    {
        $this->em = $entityManager;
        $this->speciesManager = $speciesManager;
        $this->acidityManager = $acidityManager;
        $this->statusUicnManager = $statusUicnManager;
        $this->leafTypeManager = $leafTypeManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import des espèces.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Import des espèces',
            '============',
            '',
        ]);

        $file = 'public/export/species.csv';

        if (file_exists($file) || is_readable($file) ) {
            $header = null;
            $data = array();
            if (($handle = fopen($file, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 10000, ',')) !== FALSE) {
//                    $row = array_map("utf8_encode", $row);

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

            foreach ($data as $key => $row)
            {
                $species = $this->speciesManager->getSpecie([
                    'name' => $row['Nom Commun']
                ]);

                if (!$species)
                {
                    $latinName = $row['Genre'] . ' ' . $row['Espèce'];

                    $species = $this->speciesManager->getSpecie([
                        'latinName' =>$latinName
                    ]);

                    if (!$species)
                    {
                        $species = new Species();
                        $species->setName($row['Nom Commun']);
                        $species->setLatinName($latinName);
                    }
                }

                $species->setRecommendedAcidityMin($this->acidityManager->getAcidity($row['pH mini']));
                $species->setRecommendedAcidityMax($this->acidityManager->getAcidity($row['pH maxi']));

                if (!empty($row['feuille']))
                {
                    $statusUICN = $this->statusUicnManager->getUicn(['name' => $row['statut UICN']]);

                    if (!$statusUICN)
                    {
                        $statusUICN = new StatusUicn();
                        $statusUICN->setName($row['statut UICN']);
                        $this->em->persist($statusUICN);
                    }

                    $species->setStatusUicn($statusUICN);
                }

                if (!empty($row['feuille']))
                {
                    $leafType = $this->leafTypeManager->getLeafType([
                        'name' => $row['feuille']
                    ]);

                    if (!$leafType)
                    {
                        $leafType = new LeafType();
                        $leafType->setName($row['feuille']);
                        $this->em->persist($leafType);
                    }

                    $species->setLeafType($leafType);
                }


                $this->em->persist($species);

                $this->em->flush();
                $io->progressAdvance(1);
            }

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
