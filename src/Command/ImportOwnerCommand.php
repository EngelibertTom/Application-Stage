<?php

namespace App\Command;

use App\Entity\Adoption;
use App\Entity\Owner;
use App\Manager\AdoptionManager;
use App\Manager\OutputTypeManager;
use App\Manager\OwnerManager;
use App\Manager\TreeManager;
use App\Manager\TreeStatusManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportOwnerCommand extends Command
{
    protected static $defaultName = 'app:import-owners';
    private $em;
    private $ownerManager;
    private $passwordEncoder;
    private $treeManager;
    private $adoptionManager;
    private $outputTypeManager;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,
                                TreeManager $treeManager, OwnerManager $ownerManager, AdoptionManager $adoptionManager,
                                OutputTypeManager $outputTypeManager)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->treeManager = $treeManager;
        $this->ownerManager = $ownerManager;
        $this->adoptionManager = $adoptionManager;
        $this->outputTypeManager = $outputTypeManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import des adoptants.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Import des adoptants',
            '============',
            '',
        ]);

        $file = 'public/export/owners.csv';

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

            $outputType = $this->outputTypeManager->getOutputType(1);

            foreach ($data as $key => $row)
            {
                $owner = $this->ownerManager->getOwner([
                    'email' => $row['email']
                ]);

                if (!$owner) {
                    $owner = new Owner();
                    $owner->setPassword($this->passwordEncoder->encodePassword($owner, strtolower($row['nom_adoptant'])));
                    $owner->setRoles(['ROLE_USER']);
                    $owner->setEmail($row['email']);
                    $owner->setName($row['prenom_adoptant'] . ' ' . $row['nom_adoptant']);
                }

                $owner->setPostalCode($row['code_postal']);
                $owner->setPhone($row['telephone']);

                $idTree = (int)str_replace('#', '', $row['qrcode']);
                $tree = $this->treeManager->getTree($idTree);

                if ($tree) {
                    $adoption = $this->adoptionManager->getAdoption([
                        'owner' => $owner,
                        'tree' => $tree
                    ]);

                    if (!$adoption)
                    {
                        $adoption = new Adoption();

                        $dateAdoption = $row["date_adoption"];
                        $dateAdoption = \DateTime::createFromFormat('d/m/Y', $dateAdoption);
                        $adoption->setDate($dateAdoption);

                        $adoption->setTree($tree);
                        $adoption->setOwner($owner);

                        $this->em->persist($adoption);
                    }

                    $tree->setStatus(TreeStatusManager::ADOPT);
                    $tree->setOutputType($outputType);
                    $tree->setOutput(true);

                    $this->em->persist($tree);
                }

                $this->em->persist($owner);

                $this->em->flush();
                $io->progressAdvance(1);
            }

            return Command::SUCCESS;
        }

        return Command::FAILURE;
    }
}
